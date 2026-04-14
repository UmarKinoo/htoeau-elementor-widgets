<?php

namespace HtoEAU_Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {

	private static ?Plugin $instance = null;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], 100 );
	}

	public function on_plugins_loaded(): void {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
			return;
		}

		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'admin_init', [ $this, 'stabilize_elementor_pro_free_trial_data' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	public function register_category( $elements_manager ): void {
		$elements_manager->add_category( 'htoeau', [
			'title' => 'HtoEAU',
			'icon'  => 'eicon-water-drop',
		] );
	}

	public function register_widgets( $widgets_manager ): void {
		require_once HTOEAU_WIDGETS_PATH . 'includes/class-widget-base.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-site-header-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-hero-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-product-showcase-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-transformation-steps-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-science-features-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-research-showcase-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-about-physics-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-professor-study-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-trust-strip-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-community-testimonials-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-spotlight-testimonial-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-sample-kit-cta-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-faq-accordion-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-engineered-perform-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-hydration-systems-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-hydrogen-apart-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-hydrogenate-hero-widget.php';
		require_once HTOEAU_WIDGETS_PATH . 'widgets/class-newsletter-footer-widget.php';

		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Site_Header_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Hero_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Product_Showcase_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Transformation_Steps_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Science_Features_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Research_Showcase_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\About_Physics_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Professor_Study_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Trust_Strip_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Community_Testimonials_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Spotlight_Testimonial_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Sample_Kit_CTA_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\FAQ_Accordion_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Engineered_Perform_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Hydration_Systems_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Hydrogen_Apart_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Hydrogenate_Hero_Widget() );
		$widgets_manager->register( new \HtoEAU_Widgets\Widgets\Newsletter_Footer_Widget() );
	}

	public function enqueue_frontend_styles(): void {
		wp_enqueue_style(
			'htoeau-font-figtree',
			'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap',
			[],
			null
		);
		wp_enqueue_style(
			'htoeau-widgets-frontend',
			HTOEAU_WIDGETS_URL . 'assets/css/frontend.css',
			[ 'htoeau-font-figtree', 'elementor-frontend' ],
			HTOEAU_WIDGETS_VERSION
		);
	}

	public function enqueue_editor_styles(): void {
		wp_enqueue_style(
			'htoeau-font-figtree',
			'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap',
			[],
			null
		);
		wp_enqueue_style(
			'htoeau-widgets-editor',
			HTOEAU_WIDGETS_URL . 'assets/css/editor.css',
			[ 'htoeau-font-figtree', 'elementor-editor' ],
			HTOEAU_WIDGETS_VERSION
		);
	}

	public function register_frontend_scripts(): void {
		wp_register_script(
			'htoeau-widgets-frontend',
			HTOEAU_WIDGETS_URL . 'assets/js/frontend.js',
			[],
			HTOEAU_WIDGETS_VERSION,
			true
		);
		wp_register_script(
			'htoeau-community-swiper',
			HTOEAU_WIDGETS_URL . 'assets/js/community-swiper.js',
			[ 'swiper', 'elementor-frontend' ],
			HTOEAU_WIDGETS_VERSION,
			true
		);
		wp_register_script(
			'htoeau-header-trust-marquee',
			HTOEAU_WIDGETS_URL . 'assets/js/header-trust-marquee.js',
			[ 'elementor-frontend' ],
			HTOEAU_WIDGETS_VERSION,
			true
		);
		wp_register_script(
			'htoeau-spotlight-slider',
			HTOEAU_WIDGETS_URL . 'assets/js/spotlight-slider.js',
			[ 'elementor-frontend' ],
			HTOEAU_WIDGETS_VERSION,
			true
		);
	}

	public function enqueue_frontend_scripts(): void {
		wp_enqueue_script( 'htoeau-widgets-frontend' );
		wp_localize_script(
			'htoeau-widgets-frontend',
			'htoeauWidgets',
			[
				'homeUrl' => esc_url( home_url( '/' ) ),
			]
		);
	}

	/**
	 * Elementor 3.32+ pro-free-trial module can throw PHP warnings when
	 * external JSON is valid but missing expected key shape.
	 * We normalize the transient to a safe fallback structure.
	 */
	public function stabilize_elementor_pro_free_trial_data(): void {
		$data = get_transient( 'elementor_pro_free_trial_data' );

		if ( ! is_array( $data ) ) {
			return;
		}

		$is_valid = isset( $data['pro-free-trial-popup'][0]['status'] ) && is_string( $data['pro-free-trial-popup'][0]['status'] );
		if ( $is_valid ) {
			return;
		}

		set_transient(
			'elementor_pro_free_trial_data',
			[
				'pro-free-trial-popup' => [
					[
						'status' => 'inactive',
					],
				],
			],
			HOUR_IN_SECONDS
		);
	}

	public function admin_notice_missing_elementor(): void {
		$message = sprintf(
			'<strong>%s</strong> requires <strong>Elementor</strong> to be installed and activated.',
			'HtoEAU Elementor Widgets'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}
}
