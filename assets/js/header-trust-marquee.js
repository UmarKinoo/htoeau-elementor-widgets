/**
 * Site header trust strip — continuous horizontal marquee + swipe (≤1024px).
 * Uses scrollLeft (not CSS transform) so touch/trackpad scrolling stays native.
 */
(function () {
	'use strict';

	var MQ = '(max-width: 1024px)';
	var GAP_PX = 32;
	var PX_PER_SEC = 38;
	var USER_IDLE_MS = 2800;

	function prefersReducedMotion() {
		return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	}

	function stopRaf(trustRoot) {
		if (trustRoot._htoeauTrustRafId) {
			cancelAnimationFrame(trustRoot._htoeauTrustRafId);
			trustRoot._htoeauTrustRafId = null;
		}
	}

	function clearMarqueeState(trustRoot, scroll, marquee) {
		stopRaf(trustRoot);
		if (trustRoot._htoeauTrustResumeTimer) {
			clearTimeout(trustRoot._htoeauTrustResumeTimer);
			trustRoot._htoeauTrustResumeTimer = null;
		}
		trustRoot._htoeauTrustUserPause = false;
		trustRoot._htoeauTrustAutoTick = false;
		trustRoot._htoeauTrustAutoOn = false;
		trustRoot._htoeauTrustLastAutoScrollTs = 0;

		if (trustRoot._htoeauTrustScrollEl && trustRoot._htoeauTrustOnScroll) {
			trustRoot._htoeauTrustScrollEl.removeEventListener('scroll', trustRoot._htoeauTrustOnScroll);
			trustRoot._htoeauTrustScrollEl = null;
			trustRoot._htoeauTrustOnScroll = null;
		}

		var clones = marquee.querySelectorAll('.htoeau-header__trust-track--clone');
		for (var i = 0; i < clones.length; i++) {
			clones[i].remove();
		}
		scroll.classList.remove('htoeau-header__trust-scroll--auto');
		trustRoot.classList.remove('htoeau-header__trust--auto-on');
	}

	function unbind(trustRoot) {
		stopRaf(trustRoot);
		if (trustRoot._htoeauTrustMarqueeRO) {
			trustRoot._htoeauTrustMarqueeRO.disconnect();
			trustRoot._htoeauTrustMarqueeRO = null;
		}
		if (trustRoot._htoeauTrustMarqueeMQHandler) {
			window.matchMedia(MQ).removeEventListener('change', trustRoot._htoeauTrustMarqueeMQHandler);
			trustRoot._htoeauTrustMarqueeMQHandler = null;
		}
		if (trustRoot._htoeauTrustMarqueeRMHandler) {
			window.matchMedia('(prefers-reduced-motion: reduce)').removeEventListener(
				'change',
				trustRoot._htoeauTrustMarqueeRMHandler
			);
			trustRoot._htoeauTrustMarqueeRMHandler = null;
		}
		if (trustRoot._htoeauTrustScrollEl && trustRoot._htoeauTrustOnScroll) {
			trustRoot._htoeauTrustScrollEl.removeEventListener('scroll', trustRoot._htoeauTrustOnScroll);
			trustRoot._htoeauTrustScrollEl = null;
			trustRoot._htoeauTrustOnScroll = null;
		}
		if (trustRoot._htoeauTrustResumeTimer) {
			clearTimeout(trustRoot._htoeauTrustResumeTimer);
			trustRoot._htoeauTrustResumeTimer = null;
		}
	}

	function startAutoScroll(trustRoot, scroll, track) {
		stopRaf(trustRoot);
		trustRoot._htoeauTrustAutoOn = true;

		var lastTs = 0;

		function frame(ts) {
			if (!trustRoot._htoeauTrustAutoOn) {
				return;
			}

			var marquee = trustRoot.querySelector('.htoeau-header__trust-marquee');
			if (!marquee) {
				return;
			}

			var segTrack = marquee.querySelector(
				'.htoeau-header__trust-track:not(.htoeau-header__trust-track--clone)'
			);
			if (!segTrack) {
				trustRoot._htoeauTrustRafId = requestAnimationFrame(frame);
				return;
			}

			var segment = segTrack.offsetWidth + GAP_PX;
			if (segment < 8) {
				trustRoot._htoeauTrustRafId = requestAnimationFrame(frame);
				return;
			}

			if (trustRoot._htoeauTrustUserPause) {
				lastTs = 0;
				trustRoot._htoeauTrustRafId = requestAnimationFrame(frame);
				return;
			}

			if (!lastTs) {
				lastTs = ts;
			}
			var dt = Math.min(0.064, (ts - lastTs) / 1000);
			lastTs = ts;

			var step = PX_PER_SEC * dt;
			trustRoot._htoeauTrustAutoTick = true;
			trustRoot._htoeauTrustLastAutoScrollTs = Date.now();
			scroll.scrollLeft += step;
			while (scroll.scrollLeft >= segment - 0.5) {
				scroll.scrollLeft -= segment;
			}
			trustRoot._htoeauTrustAutoTick = false;

			trustRoot._htoeauTrustRafId = requestAnimationFrame(frame);
		}

		trustRoot._htoeauTrustRafId = requestAnimationFrame(frame);
	}

	function ensureLoopTracks(scroll, marquee, track) {
		var clones = marquee.querySelectorAll('.htoeau-header__trust-track--clone');
		for (var i = 0; i < clones.length; i++) {
			clones[i].remove();
		}

		var segment = track.offsetWidth + GAP_PX;
		if (segment < 8) {
			return false;
		}

		/*
		 * Keep adding segments until the marquee has enough width for a seamless
		 * wrap even when the original content is narrow.
		 */
		var target = Math.max(scroll.clientWidth * 2, segment * 2);
		var total = track.offsetWidth;
		var safety = 0;
		while (total < target && safety < 12) {
			var c = track.cloneNode(true);
			c.classList.add('htoeau-header__trust-track--clone');
			c.setAttribute('aria-hidden', 'true');
			marquee.appendChild(c);
			total += track.offsetWidth + GAP_PX;
			safety++;
		}

		return true;
	}

	function apply(trustRoot, scroll, marquee, track) {
		if (!window.matchMedia(MQ).matches || prefersReducedMotion()) {
			clearMarqueeState(trustRoot, scroll, marquee);
			return;
		}

		if (!ensureLoopTracks(scroll, marquee, track)) {
			clearMarqueeState(trustRoot, scroll, marquee);
			return;
		}

		scroll.classList.add('htoeau-header__trust-scroll--auto');
		trustRoot.classList.add('htoeau-header__trust--auto-on');

		if (!trustRoot._htoeauTrustOnScroll) {
			trustRoot._htoeauTrustScrollEl = scroll;
			trustRoot._htoeauTrustOnScroll = function () {
				if (trustRoot._htoeauTrustAutoTick) {
					return;
				}
				/*
				 * Some browsers dispatch scroll events slightly after scrollLeft mutation.
				 * Ignore those events so auto-scroll does not pause itself.
				 */
				if (
					trustRoot._htoeauTrustLastAutoScrollTs &&
					Date.now() - trustRoot._htoeauTrustLastAutoScrollTs < 120
				) {
					return;
				}
				trustRoot._htoeauTrustUserPause = true;
				if (trustRoot._htoeauTrustResumeTimer) {
					clearTimeout(trustRoot._htoeauTrustResumeTimer);
				}
				trustRoot._htoeauTrustResumeTimer = setTimeout(function () {
					trustRoot._htoeauTrustUserPause = false;
					trustRoot._htoeauTrustResumeTimer = null;
				}, USER_IDLE_MS);
			};
			scroll.addEventListener('scroll', trustRoot._htoeauTrustOnScroll, { passive: true });
		}

		if (!trustRoot._htoeauTrustAutoOn) {
			startAutoScroll(trustRoot, scroll, track);
		}
	}

	function setup(trustRoot) {
		var scroll = trustRoot.querySelector('.htoeau-header__trust-scroll');
		var marquee = trustRoot.querySelector('.htoeau-header__trust-marquee');
		var track =
			marquee &&
			marquee.querySelector('.htoeau-header__trust-track:not(.htoeau-header__trust-track--clone)');
		if (!scroll || !marquee || !track) {
			return;
		}

		unbind(trustRoot);
		clearMarqueeState(trustRoot, scroll, marquee);

		function run() {
			window.requestAnimationFrame(function () {
				apply(trustRoot, scroll, marquee, track);
			});
		}

		trustRoot._htoeauTrustMarqueeMQHandler = run;
		window.matchMedia(MQ).addEventListener('change', trustRoot._htoeauTrustMarqueeMQHandler);

		trustRoot._htoeauTrustMarqueeRMHandler = run;
		window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener(
			'change',
			trustRoot._htoeauTrustMarqueeRMHandler
		);

		var ro = new ResizeObserver(run);
		ro.observe(scroll);
		ro.observe(track);
		trustRoot._htoeauTrustMarqueeRO = ro;

		run();
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(run);
		}
	}

	function mountAll(root) {
		var nodes = root.querySelectorAll('.htoeau-header__trust');
		for (var i = 0; i < nodes.length; i++) {
			setup(nodes[i]);
		}
	}

	function bindElementor() {
		if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
			return;
		}
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/htoeau_site_header.default',
			function ($scope) {
				var trust = $scope[0].querySelector('.htoeau-header__trust');
				if (trust) {
					setup(trust);
				}
			}
		);
	}

	function init() {
		mountAll(document);
		bindElementor();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
