<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_stars;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Figma node 86:81 — Spotlight testimonial slider.
 *
 * Centered quote + author, avatar strip below with active one rotated,
 * left/right chevron arrows, auto-play, fully interactive slider.
 */
class Spotlight_Testimonial_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_spotlight_testimonial';
	}

	public function get_title(): string {
		return 'HtoEAU Spotlight Testimonial';
	}

	public function get_icon(): string {
		return 'eicon-testimonial';
	}

	public function get_keywords(): array {
		return [ 'testimonial', 'quote', 'review', 'spotlight', 'slider', 'htoeau' ];
	}

	public function get_script_depends(): array {
		return array_merge(
			parent::get_script_depends(),
			[ 'htoeau-widgets-frontend', 'htoeau-spotlight-slider' ]
		);
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		/* ── Stars ── */
		$this->start_controls_section( 'section_header', [
			'label' => 'Header',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_stars', [
			'label'        => 'Show stars',
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'stars_count', [
			'label'     => 'Stars',
			'type'      => Controls_Manager::NUMBER,
			'default'   => 5,
			'min'       => 1,
			'max'       => 5,
			'step'      => 1,
			'condition' => [ 'show_stars' => 'yes' ],
		] );

		$this->end_controls_section();

		/* ── Slides (quote + author + avatar) ── */
		$this->start_controls_section( 'section_slides', [
			'label' => 'Testimonials',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();

		$rep->add_control( 'quote', [
			'label'   => 'Quote',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => 'Enter testimonial quote here.',
		] );

		$rep->add_control( 'author', [
			'label'   => 'Author name',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Name',
		] );

		$rep->add_control( 'image', [
			'label' => 'Avatar image',
			'type'  => Controls_Manager::MEDIA,
		] );

		$this->add_control( 'slides', [
			'label'       => 'Slides',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ author }}}',
			'default'     => [
				[
					'quote'  => "\u{201C}HtoEau is something I drink before every workout now, and I can push through with seemingly an extra gear and end up having a great session.\u{201D}",
					'author' => 'Daniel Ventura',
				],
				[
					'quote'  => "\u{201C}I started using HtoEau after a friend recommended it. The difference in my recovery time has been remarkable.\u{201D}",
					'author' => 'Sarah Mitchell',
				],
				[
					'quote'  => "\u{201C}As a nutritionist I'm selective about what I recommend. HtoEau has solid science and my clients love the results.\u{201D}",
					'author' => 'Dr. Elena Torres',
				],
			],
		] );

		$this->add_control( 'autoplay', [
			'label'        => 'Auto-play',
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'autoplay_speed', [
			'label'     => 'Interval (ms)',
			'type'      => Controls_Manager::NUMBER,
			'default'   => 5000,
			'min'       => 2000,
			'max'       => 15000,
			'step'      => 500,
			'condition' => [ 'autoplay' => 'yes' ],
		] );

		$this->end_controls_section();
	}

	private function register_style_controls(): void {
		$this->start_controls_section( 'section_style', [
			'label' => 'Section',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'section_padding', [
			'label'      => 'Padding',
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'default'    => [
				'top'    => '96',
				'right'  => '60',
				'bottom' => '96',
				'left'   => '60',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '72',
				'right'  => '40',
				'bottom' => '72',
				'left'   => '40',
				'unit'   => 'px',
			],
			'mobile_default' => [
				'top'    => '48',
				'right'  => '20',
				'bottom' => '48',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors' => [
				'{{WRAPPER}} .htoeau-spotlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$slides = array_values( array_filter(
			(array) ( $s['slides'] ?? [] ),
			static function ( $row ) {
				return ! empty( $row['quote'] );
			}
		) );

		if ( empty( $slides ) ) {
			return;
		}

		$autoplay     = 'yes' === ( $s['autoplay'] ?? 'yes' );
		$autoplay_ms  = max( 2000, (int) ( $s['autoplay_speed'] ?? 5000 ) );
		$slide_count  = count( $slides );
		$chevron_left = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		$chevron_right = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

		?>
		<section class="htoeau-spotlight" data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>" data-interval="<?php echo esc_attr( $autoplay_ms ); ?>">
			<div class="htoeau-spotlight__inner">

				<?php if ( $slide_count > 1 ) : ?>
				<button type="button" class="htoeau-spotlight__arrow htoeau-spotlight__arrow--prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'htoeau-widgets' ); ?>">
					<?php echo $chevron_left; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<?php endif; ?>

				<div class="htoeau-spotlight__content">
					<?php if ( 'yes' === ( $s['show_stars'] ?? 'yes' ) ) : ?>
					<div class="htoeau-spotlight__stars">
						<?php echo render_stars( (int) ( $s['stars_count'] ?? 5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<?php endif; ?>

					<div class="htoeau-spotlight__slides">
						<?php foreach ( $slides as $i => $slide ) : ?>
						<div class="htoeau-spotlight__slide<?php echo 0 === $i ? ' htoeau-spotlight__slide--active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>">
							<p class="htoeau-spotlight__quote"><?php echo esc_html( (string) $slide['quote'] ); ?></p>
							<p class="htoeau-spotlight__author"><?php echo esc_html( (string) ( $slide['author'] ?? '' ) ); ?></p>
						</div>
						<?php endforeach; ?>
					</div>

					<div class="htoeau-spotlight__avatars">
						<?php foreach ( $slides as $i => $slide ) :
							$img_url = $slide['image']['url'] ?? '';
						?>
						<button type="button" class="htoeau-spotlight__avatar<?php echo 0 === $i ? ' htoeau-spotlight__avatar--active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( $slide['author'] ?? '' ); ?>">
							<?php if ( $img_url ) : ?>
							<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy" decoding="async" />
							<?php else : ?>
							<span class="htoeau-spotlight__avatar-placeholder"></span>
							<?php endif; ?>
						</button>
						<?php endforeach; ?>
					</div>
				</div>

				<?php if ( $slide_count > 1 ) : ?>
				<button type="button" class="htoeau-spotlight__arrow htoeau-spotlight__arrow--next" aria-label="<?php esc_attr_e( 'Next testimonial', 'htoeau-widgets' ); ?>">
					<?php echo $chevron_right; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
