/**
 * HtoEAU Spotlight Testimonial Slider — Figma 86:81.
 * Crossfade between slides, avatar click + arrow navigation, optional auto-play.
 */
(function () {
	'use strict';

	function prefersReducedMotion() {
		return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	}

	function mount(section) {
		if (!section || section._htoeauSpotlight) {
			return;
		}

		var slides  = section.querySelectorAll('.htoeau-spotlight__slide');
		var avatars = section.querySelectorAll('.htoeau-spotlight__avatar');
		var prevBtn = section.querySelector('.htoeau-spotlight__arrow--prev');
		var nextBtn = section.querySelector('.htoeau-spotlight__arrow--next');

		if (slides.length < 2) {
			return;
		}

		var total   = slides.length;
		var current = 0;
		var timer   = null;
		var autoplay = section.getAttribute('data-autoplay') === '1';
		var interval = parseInt(section.getAttribute('data-interval'), 10) || 5000;

		function goTo(idx, direction) {
			if (idx === current) {
				return;
			}
			var prev = current;
			current = ((idx % total) + total) % total;

			var dirClass = direction === 'prev'
				? 'htoeau-spotlight__slide--exit-right'
				: 'htoeau-spotlight__slide--exit-left';

			slides[prev].classList.add(dirClass);
			slides[prev].classList.remove('htoeau-spotlight__slide--active');

			slides[current].classList.add('htoeau-spotlight__slide--active');

			for (var a = 0; a < avatars.length; a++) {
				avatars[a].classList.toggle('htoeau-spotlight__avatar--active', a === current);
			}

			var exitSlide = slides[prev];
			var exitClass = dirClass;
			setTimeout(function () {
				exitSlide.classList.remove(exitClass);
			}, prefersReducedMotion() ? 0 : 420);

			resetAutoplay();
		}

		function next() {
			goTo(current + 1, 'next');
		}

		function prev() {
			goTo(current - 1, 'prev');
		}

		function startAutoplay() {
			if (!autoplay || timer) {
				return;
			}
			timer = setInterval(next, interval);
		}

		function stopAutoplay() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function resetAutoplay() {
			stopAutoplay();
			startAutoplay();
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', next);
		}
		if (prevBtn) {
			prevBtn.addEventListener('click', prev);
		}

		for (var i = 0; i < avatars.length; i++) {
			(function (idx) {
				avatars[idx].addEventListener('click', function () {
					var dir = idx > current ? 'next' : 'prev';
					goTo(idx, dir);
				});
			})(i);
		}

		section.addEventListener('mouseenter', stopAutoplay);
		section.addEventListener('mouseleave', startAutoplay);
		section.addEventListener('focusin', stopAutoplay);
		section.addEventListener('focusout', function (e) {
			if (!section.contains(e.relatedTarget)) {
				startAutoplay();
			}
		});

		startAutoplay();
		section._htoeauSpotlight = true;
	}

	function mountAll(root) {
		var nodes = root.querySelectorAll('.htoeau-spotlight');
		for (var i = 0; i < nodes.length; i++) {
			mount(nodes[i]);
		}
	}

	function bindElementor() {
		if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
			return;
		}
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/htoeau_spotlight_testimonial.default',
			function ($scope) {
				var el = $scope[0].querySelector('.htoeau-spotlight');
				if (el) {
					el._htoeauSpotlight = false;
					mount(el);
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
