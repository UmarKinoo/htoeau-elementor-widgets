<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_cta_button;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * About Us A / “We Started With Physics”.
 * Figma: 86:366 desktop; mobile frame 86:969 — https://www.figma.com/design/FcOeKFswrs0fJXLmrEvev6/Untitled?node-id=86-969
 */
class About_Physics_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_about_physics';
	}

	public function get_title(): string {
		return 'HtoEAU About Physics';
	}

	public function get_icon(): string {
		return 'eicon-info-circle-o';
	}

	public function get_keywords(): array {
		return [ 'about', 'story', 'physics', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'content', [
			'label' => 'Content',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'bg_image', [
			'label' => 'Background image',
			'type'  => Controls_Manager::MEDIA,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => "We Started With Physics.\nNot Marketing.",
			'rows'    => 2,
		] );

		$this->add_control( 'intro', [
			'label'       => 'Intro (two paragraphs)',
			'type'        => Controls_Manager::TEXTAREA,
			'description' => 'Separate paragraphs with a blank line.',
			'default'     => "Hydrogen water is easy to talk about. It's much harder to produce properly.\n\nHydrogen escapes easily. It destabilises quickly. It demands precision. If you're not engineering for stability, you're producing a story.",
			'rows'        => 6,
		] );

		$this->add_control( 'block1_title', [
			'label'   => 'Block 1 title',
			'type'    => Controls_Manager::TEXT,
			'default' => 'We Engineer Hydrogen. We Do Not Simply Add It.',
		] );

		$this->add_control( 'block1_text', [
			'label'   => 'Block 1 text',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Our process is designed to optimise dissolved hydrogen concentration, protect hydrogen integrity, and preserve stability from infusion to sealing. Because hydrogen performance isn\'t a label claim. It\'s an experienced reality.',
			'rows'    => 4,
		] );

		$this->add_control( 'block2_title', [
			'label'   => 'Block 2 title',
			'type'    => Controls_Manager::TEXT,
			'default' => 'A New Standard',
		] );

		$this->add_control( 'block2_text', [
			'label'   => 'Block 2 text',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Engineered. Measured. Performance-driven. Not just hydration. Hydrogen precision.',
			'rows'    => 2,
		] );

		$this->add_control( 'cta_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Explore the Science',
		] );

		$this->add_control( 'cta_link', [
			'label'   => 'Button link',
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
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
				'right'  => '104',
				'bottom' => '120',
				'left'   => '104',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '80',
				'right'  => '48',
				'bottom' => '200',
				'left'   => '48',
				'unit'   => 'px',
			],
			'mobile_default' => [
				'top'    => '50',
				'right'  => '20',
				'bottom' => '320',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .htoeau-about-physics' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @return list<string>
	 */
	private function split_intro_paragraphs( string $raw ): array {
		$parts = preg_split( '/\r\n\r\n|\r\r|\n\n/', $raw );
		if ( false === $parts || ! is_array( $parts ) ) {
			return array_filter( [ trim( $raw ) ] );
		}
		$out = [];
		foreach ( $parts as $p ) {
			$t = trim( (string) $p );
			if ( '' !== $t ) {
				$out[] = $t;
			}
		}
		return $out;
	}

	protected function render(): void {
		$s    = $this->get_settings_for_display();
		$bg   = ! empty( $s['bg_image']['url'] ) ? (string) $s['bg_image']['url'] : '';
		$link = $s['cta_link'] ?? [];
		$intro_paras = $this->split_intro_paragraphs( (string) ( $s['intro'] ?? '' ) );
		?>
		<section class="htoeau-about-physics">
			<?php if ( $bg ) : ?>
			<img class="htoeau-about-physics__bg" src="<?php echo esc_url( $bg ); ?>" alt="" loading="lazy" />
			<?php endif; ?>
			<div class="htoeau-about-physics__overlay" aria-hidden="true"></div>
			<div class="htoeau-about-physics__inner">
				<div class="htoeau-about-physics__stack">
					<div class="htoeau-about-physics__lead">
						<?php if ( ! empty( $s['heading'] ) ) : ?>
						<h2 class="htoeau-about-physics__title"><?php echo esc_html( str_replace( [ "\r\n", "\r" ], "\n", (string) $s['heading'] ) ); ?></h2>
						<?php endif; ?>
						<?php foreach ( $intro_paras as $para ) : ?>
						<p class="htoeau-about-physics__intro-p"><?php echo esc_html( $para ); ?></p>
						<?php endforeach; ?>
					</div>

					<?php if ( ! empty( $s['block1_title'] ) || ! empty( $s['block1_text'] ) ) : ?>
					<div class="htoeau-about-physics__block">
						<?php if ( ! empty( $s['block1_title'] ) ) : ?>
						<p class="htoeau-about-physics__block-title"><?php echo esc_html( (string) $s['block1_title'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $s['block1_text'] ) ) : ?>
						<p class="htoeau-about-physics__block-text"><?php echo esc_html( (string) $s['block1_text'] ); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $s['block2_title'] ) || ! empty( $s['block2_text'] ) ) : ?>
					<div class="htoeau-about-physics__block">
						<?php if ( ! empty( $s['block2_title'] ) ) : ?>
						<p class="htoeau-about-physics__block-title"><?php echo esc_html( (string) $s['block2_title'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $s['block2_text'] ) ) : ?>
						<p class="htoeau-about-physics__block-text"><?php echo esc_html( (string) $s['block2_text'] ); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $s['cta_text'] ) ) : ?>
					<div class="htoeau-about-physics__cta-wrap">
						<?php
						echo render_cta_button(
							(string) $s['cta_text'],
							! empty( $link['url'] ) ? (string) $link['url'] : '#',
							[
								'class'        => 'htoeau-about-physics__cta',
								'is_external'  => ! empty( $link['is_external'] ),
								'nofollow'     => ! empty( $link['nofollow'] ),
							]
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
