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
 * Community testimonials / horizontal carousel.
 * Figma: 86:432 desktop; mobile 86:1035 — https://www.figma.com/design/FcOeKFswrs0fJXLmrEvev6/Untitled?node-id=86-1035
 */
class Community_Testimonials_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_community_testimonials';
	}

	public function get_title(): string {
		return 'HtoEAU Community Testimonials';
	}

	public function get_icon(): string {
		return 'eicon-testimonial-carousel';
	}

	public function get_keywords(): array {
		return [ 'testimonials', 'reviews', 'community', 'carousel', 'htoeau' ];
	}

	public function get_script_depends(): array {
		return array_merge( parent::get_script_depends(), [ 'swiper', 'htoeau-community-swiper' ] );
	}

	public function get_style_depends(): array {
		return array_merge( parent::get_style_depends(), [ 'swiper' ] );
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'section_header', [
			'label' => 'Header',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'rating_score', [
			'label'   => 'Rating score',
			'type'    => Controls_Manager::TEXT,
			'default' => '4.8',
		] );

		$this->add_control( 'rating_reviews', [
			'label'   => 'Review count',
			'type'    => Controls_Manager::TEXT,
			'default' => '248',
		] );

		$this->add_control( 'rating_suffix', [
			'label'   => 'Rating line suffix',
			'type'    => Controls_Manager::TEXT,
			'default' => 'happy reviews',
		] );

		$this->add_control( 'heading', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXT,
			'default' => 'The Community of High Performers',
		] );

		$this->add_control( 'subheading', [
			'label'   => 'Subheading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Trusted by athletes, innovators, and performance-driven individuals who prioritise precision hydration.',
			'rows'    => 3,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_cards', [
			'label' => 'Testimonials',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label' => 'Image',
			'type'  => Controls_Manager::MEDIA,
		] );
		$rep->add_control( 'quote', [
			'label'   => 'Quote headline',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );
		$rep->add_control( 'text', [
			'label'   => 'Body',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => '',
			'rows'    => 6,
		] );
		$rep->add_control( 'author', [
			'label'   => 'Author / line 1',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );
		$rep->add_control( 'meta', [
			'label'   => 'Role / line 2 (semibold)',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );
		$this->add_control( 'items', [
			'label'       => 'Cards',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ quote }}}',
			'default'     => self::default_testimonials(),
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style', [
			'label' => 'Section',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'section_padding', [
			'label'      => 'Padding',
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'default'    => [
				'top'    => '120',
				'right'  => '172',
				'bottom' => '120',
				'left'   => '172',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '72',
				'right'  => '48',
				'bottom' => '72',
				'left'   => '48',
				'unit'   => 'px',
			],
			'mobile_default' => [
				'top'    => '50',
				'right'  => '20',
				'bottom' => '50',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .htoeau-community' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @return array<int,array<string,string>>
	 */
	private static function default_testimonials(): array {
		return [
			[
				'quote'  => '“I drink before every workout”',
				'text'   => '“I’ve reached a level of physical fitness where I can ascertain if any supplement I take can give me an extra edge. HtoEau is something I drink before every workout now, and no matter how tired I may feel before training I can now push through with seemingly an extra gear and end up having a great session. If you’re serious about fitness and recovery it’s a must have pre-workout!”',
				'author' => 'Daniel Ventura',
				'meta'   => 'WBFF Pro Fitness Model, Actor, Advanced Personal Trainer',
			],
			[
				'quote'  => '“My sleep scores were better”',
				'text'   => '“Last summer, I kept track of three weeks of training and recovery, during which I consumed one can of HtoEAU Hydrogen Water every morning and evening. What helped me the most and also surprised me was that my sleep scores were better than usual. I have noticed that the right nutrition and the use of HtoEAU Hydrogen Water have contributed to improving my values, so even at my more advanced age, it is possible to improve.”',
				'author' => 'Endurance Cyclist, Age 62',
				'meta'   => 'Garmin VENU X1 User',
			],
			[
				'quote'  => '“The results speak for themselves!”',
				'text'   => '“HtoEau® DDW water had a positive effect on my chronic fatigue, hydration and recovery from exercise. Hence, I would highly recommend this water to anyone suffering with a chronic illness. As I work in professional sport, I would also recommend this water to any competitive athlete.”',
				'author' => 'Heather Pearson MSc',
				'meta'   => 'Sports Therapist & Strength Coach',
			],
			[
				'quote'  => 'I couldn’t recommend it enough!',
				'text'   => '"I’ve reached a level of physical fitness where I can ascertain if any supplement I take can give me an extra edge. HtoEau is something I drink before every workout now, and no matter how tired I may feel before training I can now push through with seemingly an extra gear and end up having a great session. If you’re serious about fitness and recovery it’s a must have pre-workout! I couldn’t recommend it enough!"',
				'author' => 'Daniel Ventura',
				'meta'   => 'WBFF Pro Fitness Model, Actor, Advanced Personal Trainer',
			],
		];
	}

	/**
	 * Swiper navigation requires prev + next elements; prev is visually hidden.
	 */
	private function render_prev_button(): void {
		?>
		<button type="button" class="htoeau-community__prev" tabindex="-1" aria-hidden="true">
			<?php echo esc_html__( 'Previous', 'htoeau-widgets' ); ?>
		</button>
		<?php
	}

	private function render_next_button(): void {
		?>
		<button type="button" class="htoeau-community__next" aria-label="<?php echo esc_attr__( 'Next testimonials', 'htoeau-widgets' ); ?>">
			<span class="htoeau-community__next-inner" aria-hidden="true">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
					<path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</button>
		<?php
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$score = isset( $s['rating_score'] ) ? trim( (string) $s['rating_score'] ) : '4.8';
		$count = isset( $s['rating_reviews'] ) ? trim( (string) $s['rating_reviews'] ) : '248';
		$suff  = isset( $s['rating_suffix'] ) ? trim( (string) $s['rating_suffix'] ) : 'happy reviews';

		$items = array_filter(
			(array) ( $s['items'] ?? [] ),
			static function ( $row ) {
				$img = ! empty( $row['image']['url'] );
				$q   = isset( $row['quote'] ) ? trim( (string) $row['quote'] ) : '';
				$t   = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
				$a   = isset( $row['author'] ) ? trim( (string) $row['author'] ) : '';
				$m   = isset( $row['meta'] ) ? trim( (string) $row['meta'] ) : '';
				return $img || '' !== $q || '' !== $t || '' !== $a || '' !== $m;
			}
		);

		if ( empty( $items ) ) {
			return;
		}

		// Fallback media: ensure no slide renders as a plain gray block
		// when an item is missing its image in Elementor.
		$fallback_media = '';
		foreach ( $items as $row ) {
			if ( ! empty( $row['image']['url'] ) ) {
				$fallback_media = (string) $row['image']['url'];
				break;
			}
		}
		?>
		<section class="htoeau-community">
			<header class="htoeau-community__head">
				<div class="htoeau-community__rating">
					<?php echo render_stars( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<p class="htoeau-community__rating-text">
						<?php if ( '' !== $score ) : ?>
						<strong><?php echo esc_html( $score ); ?></strong>
						<?php endif; ?>
						<?php echo ' ' . esc_html__( 'based on', 'htoeau-widgets' ) . ' '; ?>
						<?php if ( '' !== $count ) : ?>
						<strong><?php echo esc_html( $count ); ?></strong>
						<?php endif; ?>
						<?php
						if ( '' !== $suff ) {
							echo ' ' . esc_html( $suff );
						}
						?>
					</p>
				</div>
				<?php if ( ! empty( $s['heading'] ) ) : ?>
				<h2 class="htoeau-community__title"><?php echo esc_html( (string) $s['heading'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $s['subheading'] ) ) : ?>
				<p class="htoeau-community__intro"><?php echo esc_html( (string) $s['subheading'] ); ?></p>
				<?php endif; ?>
			</header>

			<div class="htoeau-community__carousel">
				<div class="htoeau-community__viewport-wrap" role="region" aria-label="<?php echo esc_attr__( 'Testimonials', 'htoeau-widgets' ); ?>">
					<?php $this->render_prev_button(); ?>
					<div class="swiper htoeau-community__swiper">
						<div class="swiper-wrapper">
							<?php foreach ( $items as $row ) : ?>
								<?php
								$cls = 'htoeau-community__card';
								$media  = ! empty( $row['image']['url'] ) ? (string) $row['image']['url'] : $fallback_media;
								?>
							<div class="swiper-slide">
								<article class="<?php echo esc_attr( $cls ); ?>">
									<div class="htoeau-community__media">
										<?php if ( ! empty( $media ) ) : ?>
										<img
											src="<?php echo esc_url( $media ); ?>"
											alt=""
											width="315"
											height="490"
											loading="lazy"
											decoding="async"
										/>
										<?php endif; ?>
									</div>
									<div class="htoeau-community__body">
										<?php echo render_stars( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<?php if ( ! empty( $row['quote'] ) ) : ?>
										<h3 class="htoeau-community__quote"><?php echo esc_html( (string) $row['quote'] ); ?></h3>
										<?php endif; ?>
										<?php if ( ! empty( $row['text'] ) ) : ?>
										<p class="htoeau-community__text"><?php echo esc_html( (string) $row['text'] ); ?></p>
										<?php endif; ?>
										<div class="htoeau-community__meta">
											<?php if ( ! empty( $row['author'] ) ) : ?>
											<span class="htoeau-community__author"><?php echo esc_html( (string) $row['author'] ); ?></span>
											<?php endif; ?>
											<?php if ( ! empty( $row['meta'] ) ) : ?>
											<small class="htoeau-community__role"><?php echo esc_html( (string) $row['meta'] ); ?></small>
											<?php endif; ?>
										</div>
									</div>
								</article>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="htoeau-community__fade" aria-hidden="true"></div>
					<?php $this->render_next_button(); ?>
				</div>
			</div>
		</section>
		<?php
	}
}
