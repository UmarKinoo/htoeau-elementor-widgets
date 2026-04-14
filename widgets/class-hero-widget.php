<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hero_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_hero';
	}

	public function get_title(): string {
		return 'HtoEAU Hero';
	}

	public function get_icon(): string {
		return 'eicon-banner';
	}

	public function get_keywords(): array {
		return [ 'hero', 'banner', 'header', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		// --- Rating ---
		$this->start_controls_section( 'section_rating', [
			'label' => 'Star Rating',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_rating', [
			'label'        => 'Show Rating',
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'rating_score', [
			'label'     => 'Rating Score',
			'type'      => Controls_Manager::TEXT,
			'default'   => '4.8',
			'condition' => [ 'show_rating' => 'yes' ],
		] );

		$this->add_control( 'rating_text', [
			'label'     => 'Rating Text',
			'type'      => Controls_Manager::TEXT,
			'default'   => 'based on 248 happy reviews',
			'condition' => [ 'show_rating' => 'yes' ],
		] );

		$this->end_controls_section();

		// --- Heading & Body ---
		$this->start_controls_section( 'section_content', [
			'label' => 'Content',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'       => 'Heading',
			'type'        => Controls_Manager::TEXTAREA,
			'description' => 'Use line breaks to match Figma (e.g. four lines).',
			'default'     => "Hydration,\nEngineered\nBeyond Simple\nWater",
			'rows'        => 5,
		] );

		$this->add_control( 'description', [
			'label'   => 'Description',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Hydrogen-infused and deuterium-depleted water developed using precision infusion and purification technologies. Clean, measured and engineered for those who expect more from hydration.',
			'rows'    => 4,
		] );

		$this->end_controls_section();

		// --- CTA ---
		$this->start_controls_section( 'section_cta', [
			'label' => 'Call to Action',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_cta_controls( 'cta', [
			'text' => 'Try HtoEAU Now',
			'url'  => '#',
		] );

		$this->end_controls_section();

		// --- Feature Bullets ---
		$this->start_controls_section( 'section_features', [
			'label' => 'Feature Bullets',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'text', [
			'label'   => 'Feature Text',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Feature description',
			'rows'    => 2,
		] );

		$this->add_control( 'features', [
			'label'   => 'Features',
			'type'    => Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => [
				[ 'text' => 'Hydrogen-infused for advanced hydration' ],
				[ 'text' => 'Clean, stimulant-free formulation' ],
				[ 'text' => 'Developed for modern lifestyles' ],
			],
			'title_field' => '{{{ text }}}',
		] );

		$this->end_controls_section();

		// --- Hero Image ---
		$this->start_controls_section( 'section_images', [
			'label' => 'Hero Image',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$hero_default = \HtoEAU_Widgets\default_bundle_image_url( 'hero-default.png' );
		if ( '' === $hero_default ) {
			$hero_default = \Elementor\Utils::get_placeholder_image_src();
		}

		$this->add_control( 'hero_image', [
			'label'       => 'Hero Image',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Figma (86:40): export one PNG of the right-side composition. Target artboard ~818×635px @1× (group 86:74), or ~1636×1270px @2×. Optional: add assets/images/hero-default.png in the plugin for a built-in default.',
			'default'     => [ 'url' => $hero_default ],
		] );

		$this->end_controls_section();
	}

	private function register_style_controls(): void {

		// --- Section Style ---
		$this->start_controls_section( 'section_style_section', [
			'label' => 'Section',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'bg_color', [
			'label'     => 'Background Color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .htoeau-hero' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'section_padding', [
			'label'      => 'Section Padding',
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [
				'top'    => '30',
				'right'  => '60',
				'bottom' => '104',
				'left'   => '60',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '30',
				'right'  => '40',
				'bottom' => '72',
				'left'   => '40',
				'unit'   => 'px',
			],
			'mobile_default' => [
				'top'    => '30',
				'right'  => '20',
				'bottom' => '50',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors' => [
				'{{WRAPPER}} .htoeau-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// --- Typography Overrides ---
		$this->start_controls_section( 'section_style_typography', [
			'label' => 'Typography',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'heading_color', [
			'label'     => 'Heading Color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '#002c41',
			'selectors' => [
				'{{WRAPPER}} .htoeau-hero__heading' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'desc_color', [
			'label'     => 'Description Color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '#002c41',
			'selectors' => [
				'{{WRAPPER}} .htoeau-hero__desc' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();
		?>
		<section class="htoeau-hero htoeau-hero--bleed-image-right">
			<div class="htoeau-hero__container">
				<div class="htoeau-hero__content">

					<?php if ( 'yes' === $s['show_rating'] ) : ?>
					<div class="htoeau-hero__rating">
						<?php echo \HtoEAU_Widgets\render_stars( 5 ); ?>
						<span class="htoeau-hero__rating-text">
							<strong><?php echo esc_html( $s['rating_score'] ); ?></strong><?php echo wp_kses_post( ' ' . \HtoEAU_Widgets\hero_rating_text_with_bold_count( $s['rating_text'] ?? '' ) ); ?>
						</span>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h1 class="htoeau-hero__heading"><?php echo esc_html( $s['heading'] ); ?></h1>
					<?php endif; ?>

					<?php if ( ! empty( $s['description'] ) ) : ?>
					<p class="htoeau-hero__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>

					<?php
					if ( ! empty( $s['cta_text'] ) ) {
						$link = $s['cta_link'];
						echo \HtoEAU_Widgets\render_cta_button(
							$s['cta_text'],
							$link['url'] ?? '#',
							[
								'is_external' => ! empty( $link['is_external'] ),
								'nofollow'    => ! empty( $link['nofollow'] ),
							]
						);
					}
					?>

					<?php if ( ! empty( $s['features'] ) ) : ?>
					<div class="htoeau-hero__features">
						<?php foreach ( $s['features'] as $feature ) : ?>
						<div class="htoeau-hero__feature">
							<?php echo \HtoEAU_Widgets\render_check_icon( '30' ); ?>
							<span class="htoeau-hero__feature-text"><?php echo esc_html( $feature['text'] ); ?></span>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

				</div>

				<?php if ( ! empty( $s['hero_image']['url'] ) ) : ?>
				<div class="htoeau-hero__media">
					<img class="htoeau-hero__image" src="<?php echo esc_url( $s['hero_image']['url'] ); ?>" alt="" loading="eager" />
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
