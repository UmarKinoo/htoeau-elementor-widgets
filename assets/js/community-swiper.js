/**
 * HtoEAU Community Testimonials — Swiper (Elementor’s bundled Swiper 8, no extra deps).
 */
(function () {
	'use strict';

	function prefersReducedMotion() {
		return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	}

	function mount(el) {
		if (!el || typeof Swiper === 'undefined') {
			return;
		}

		var section = el.closest('.htoeau-community');
		if (!section) {
			return;
		}

		var slideCount = el.querySelectorAll('.swiper-slide').length;
		if (slideCount === 0) {
			return;
		}

		if (el.swiper) {
			el.swiper.destroy(true, true);
		}

		var next = section.querySelector('.htoeau-community__next');
		var prev = section.querySelector('.htoeau-community__prev');
		var loop = slideCount > 2;

		var options = {
			slidesPerView: 'auto',
			spaceBetween: 20,
			speed: prefersReducedMotion() ? 0 : 480,
			loop: loop,
			loopAdditionalSlides: loop ? Math.min(2, slideCount) : 0,
			watchOverflow: true,
			grabCursor: true,
			watchSlidesProgress: true,
		};

		if (next && prev) {
			options.navigation = {
				nextEl: next,
				prevEl: prev,
			};
		}

		// eslint-disable-next-line no-undef
		new Swiper(el, options);
	}

	function mountAll(root) {
		var scope = root || document;
		var nodes = scope.querySelectorAll('.htoeau-community__swiper');
		for (var i = 0; i < nodes.length; i++) {
			mount(nodes[i]);
		}
	}

	function bindElementor() {
		if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
			return;
		}
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/htoeau_community_testimonials.default',
			function ($scope) {
				var el = $scope[0].querySelector('.htoeau-community__swiper');
				if (el) {
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
