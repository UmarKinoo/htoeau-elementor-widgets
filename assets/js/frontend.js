/**
 * HtoEAU Elementor Widgets — Frontend JS
 *
 * Loaded only when widgets require interactive behavior.
 * Currently a placeholder for future widget interactions
 * (carousels, accordions, animations).
 */
(function () {
	'use strict';

	var WIDGET_SELECTOR = '[class*="elementor-widget-htoeau_"]';

	function setZeroVar(el, name) {
		if (!el) {
			return;
		}
		el.style.setProperty(name, '0px', 'important');
	}

	function forceFlushOnContainer(container) {
		if (!container) {
			return;
		}
		setZeroVar(container, '--margin-top');
		setZeroVar(container, '--margin-right');
		setZeroVar(container, '--margin-bottom');
		setZeroVar(container, '--margin-left');
		setZeroVar(container, '--margin-block-start');
		setZeroVar(container, '--margin-block-end');
		setZeroVar(container, '--gap');
		setZeroVar(container, '--row-gap');
		setZeroVar(container, '--widgets-spacing');
		setZeroVar(container, '--widgets-spacing-row');
		setZeroVar(container, '--padding-top');
		setZeroVar(container, '--padding-bottom');
		setZeroVar(container, '--padding-block-start');
		setZeroVar(container, '--padding-block-end');
		setZeroVar(container, '--padding-inline-start');
		setZeroVar(container, '--padding-inline-end');
		container.style.setProperty('margin-block-start', '0px', 'important');
		container.style.setProperty('margin-block-end', '0px', 'important');
		container.style.setProperty('margin-inline-start', '0px', 'important');
		container.style.setProperty('margin-inline-end', '0px', 'important');
		container.style.setProperty('padding-block-start', '0px', 'important');
		container.style.setProperty('padding-block-end', '0px', 'important');
		container.style.setProperty('padding-inline-start', '0px', 'important');
		container.style.setProperty('padding-inline-end', '0px', 'important');

		var inner = container.querySelector(':scope > .e-con-inner');
		if (!inner) {
			return;
		}
		setZeroVar(inner, '--gap');
		setZeroVar(inner, '--row-gap');
		setZeroVar(inner, '--widgets-spacing');
		setZeroVar(inner, '--widgets-spacing-row');
		setZeroVar(inner, '--padding-top');
		setZeroVar(inner, '--padding-bottom');
		setZeroVar(inner, '--padding-block-start');
		setZeroVar(inner, '--padding-block-end');
		setZeroVar(inner, '--padding-inline-start');
		setZeroVar(inner, '--padding-inline-end');
		inner.style.setProperty('row-gap', '0px', 'important');
		inner.style.setProperty('gap', '0px 0px', 'important');
		inner.style.setProperty('padding-block-start', '0px', 'important');
		inner.style.setProperty('padding-block-end', '0px', 'important');
		inner.style.setProperty('padding-inline-start', '0px', 'important');
		inner.style.setProperty('padding-inline-end', '0px', 'important');
	}

	function forceFlushOnWidget(widget) {
		if (!widget) {
			return;
		}
		widget.style.setProperty('--kit-widget-spacing', '0px', 'important');
		widget.style.setProperty('margin-block-end', '0px', 'important');
		widget.style.setProperty('margin-bottom', '0px', 'important');

		var container = widget.querySelector(':scope > .elementor-widget-container');
		if (container) {
			container.style.setProperty('margin-block-end', '0px', 'important');
			container.style.setProperty('margin-bottom', '0px', 'important');
		}

		var parent = widget.closest('.e-con');
		while (parent) {
			forceFlushOnContainer(parent);
			parent = parent.parentElement ? parent.parentElement.closest('.e-con') : null;
		}
	}

	function runFlushPass(root) {
		var scope = root || document;
		var widgets = scope.querySelectorAll(WIDGET_SELECTOR);
		widgets.forEach(forceFlushOnWidget);
	}

	function initTransformSections(root) {
		var scope = root || document;
		var sections = scope.querySelectorAll('[data-htoeau-transform]');
		if (!sections.length) {
			return;
		}
		sections.forEach(function (section) {
			if (section.__htoeauTransformBound) {
				return;
			}
			section.__htoeauTransformBound = true;
			section.addEventListener('click', function (evt) {
				var target = evt.target;
				while (target && target !== section && !(target instanceof HTMLElement)) {
					target = target.parentNode;
				}
				while (target && target !== section && target instanceof HTMLElement && !target.hasAttribute('data-transform-panel')) {
					target = target.parentElement;
				}
				if (!target || !target.hasAttribute('data-transform-panel')) {
					return;
				}
				var panels = section.querySelectorAll('[data-transform-panel]');
				panels.forEach(function (btn) {
					btn.classList.remove('is-active');
					btn.setAttribute('aria-expanded', 'false');
				});
				target.classList.add('is-active');
				target.setAttribute('aria-expanded', 'true');
			});
		});
	}

	function installObserver() {
		var observer = new MutationObserver(function (mutations) {
			for (var i = 0; i < mutations.length; i++) {
				var m = mutations[i];
				if (m.type !== 'childList') {
					continue;
				}
				m.addedNodes.forEach(function (node) {
					if (!(node instanceof Element)) {
						return;
					}
					if (node.matches && node.matches(WIDGET_SELECTOR)) {
						forceFlushOnWidget(node);
						initTransformSections(node);
						return;
					}
					if (node.querySelector) {
						runFlushPass(node);
						initTransformSections(node);
					}
				});
			}
		});
		observer.observe(document.body, { childList: true, subtree: true });
	}

	function initHeaders(root) {
		var scope = root || document;
		var headers = scope.querySelectorAll('[data-htoeau-header]');
		headers.forEach(function (header) {
			if (header.__htoeauHeaderBound) return;
			header.__htoeauHeaderBound = true;

			var burger = header.querySelector('.htoeau-header__burger');
			var navId = burger ? burger.getAttribute('aria-controls') : null;
			var nav = navId ? document.getElementById(navId) : header.querySelector('.htoeau-header__nav');

			if (burger && nav) {
				burger.addEventListener('click', function () {
					var open = burger.getAttribute('aria-expanded') === 'true';
					burger.setAttribute('aria-expanded', String(!open));
					nav.classList.toggle('is-open', !open);
					document.body.style.overflow = open ? '' : 'hidden';
				});

				nav.addEventListener('click', function (e) {
					if (e.target.tagName === 'A') {
						burger.setAttribute('aria-expanded', 'false');
						nav.classList.remove('is-open');
						document.body.style.overflow = '';
					}
				});

				document.addEventListener('keydown', function (e) {
					if (e.key === 'Escape' && nav.classList.contains('is-open')) {
						burger.setAttribute('aria-expanded', 'false');
						nav.classList.remove('is-open');
						document.body.style.overflow = '';
						burger.focus();
					}
				});
			}

			var searchBtn = header.querySelector('.htoeau-header__search-btn');
			if (searchBtn) {
				searchBtn.addEventListener('click', function () {
					var overlay = header.querySelector('.htoeau-header__search-overlay');
					if (!overlay) {
						var searchAction =
							typeof htoeauWidgets !== 'undefined' && htoeauWidgets.homeUrl
								? htoeauWidgets.homeUrl
								: (window.location.origin || '') + '/';
						overlay = document.createElement('div');
						overlay.className = 'htoeau-header__search-overlay';
						var form = document.createElement('form');
						form.setAttribute('role', 'search');
						form.method = 'get';
						form.action = searchAction;
						var input = document.createElement('input');
						input.className = 'htoeau-header__search-field';
						input.type = 'search';
						input.name = 's';
						input.placeholder = 'Search\u2026';
						input.setAttribute('autocomplete', 'off');
						form.appendChild(input);
						overlay.appendChild(form);
						header.querySelector('.htoeau-header__main').appendChild(overlay);
					}
					overlay.classList.toggle('is-open');
					if (overlay.classList.contains('is-open')) {
						overlay.querySelector('input').focus();
					}
				});
			}
		});
	}

	function initBackToTop() {
		var btn = document.createElement('button');
		btn.className = 'htoeau-back-to-top';
		btn.setAttribute('aria-label', 'Back to top');
		btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 16V4M10 4L4 10M10 4L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		document.body.appendChild(btn);
		btn.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
		var visible = false;
		function toggle() {
			var show = window.scrollY > 600;
			if (show !== visible) {
				visible = show;
				btn.classList.toggle('htoeau-back-to-top--show', show);
			}
		}
		window.addEventListener('scroll', toggle, { passive: true });
		toggle();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			runFlushPass(document);
			initTransformSections(document);
			initHeaders(document);
			installObserver();
			initBackToTop();
		});
	} else {
		runFlushPass(document);
		initTransformSections(document);
		initHeaders(document);
		installObserver();
		initBackToTop();
	}
})();
