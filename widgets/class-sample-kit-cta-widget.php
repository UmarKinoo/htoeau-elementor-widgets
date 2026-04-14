<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_check_icon;
use function HtoEAU_Widgets\render_cta_button;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sample kit CTA (navy block, copy + gradient CTA + features + layered product art).
 * Figma: 86:500 desktop; mobile spacing aligned with 86:622.
 */
class Sample_Kit_CTA_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_sample_kit_cta';
	}

	public function get_title(): string {
		return 'HtoEAU Sample Kit CTA';
	}

	public function get_icon(): string {
		return 'eicon-cart';
	}

	public function get_keywords(): array {
		return [ 'sample', 'kit', 'cta', 'hydration', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'section_content', [
			'label' => 'Content',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Hydrate Smarter',
		] );

		$default_copy = "Discover the HtoEAU® Try-Out Box and explore our advanced hydration systems. Choose from four sample kit options, including Hydrogen Water, Deuterium-Depleted Water, and Hydrogen-Infused Deuterium-Depleted Water.\n\nEach box includes three cans — a simple way to experience HtoEAU® and find the right fit for your routine.";

		$this->add_control( 'text', [
			'label'   => 'Body',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => $default_copy,
			'rows'    => 8,
		] );

		$this->add_control( 'cta_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Get your sample kit now',
		] );

		$this->add_control( 'cta_link', [
			'label'   => 'Button link',
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$rep = new Repeater();
		$rep->add_control( 'text', [
			'label'   => 'Label',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$this->add_control( 'features', [
			'label'       => 'Feature row',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Four try-out kit options' ],
				[ 'text' => 'Three cans in every box' ],
				[ 'text' => 'Experience HtoEAU® at home' ],
			],
		] );

		$this->add_control( 'frame_image', [
			'label'       => 'Frame / base layer',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Optional background frame (502×502 in Figma). Product layer sits on top.',
		] );

		$this->add_control( 'image', [
			'label'       => 'Product photo',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Foreground product shot. If you only use one asset, set this and leave the frame empty.',
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
				'top'    => '44',
				'right'  => '172',
				'bottom' => '44',
				'left'   => '172',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '48',
				'right'  => '40',
				'bottom' => '48',
				'left'   => '40',
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
				'{{WRAPPER}} .htoeau-sample-kit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s    = $this->get_settings_for_display();
		$link = $s['cta_link'] ?? [];

		$frame_url = ! empty( $s['frame_image']['url'] ) ? (string) $s['frame_image']['url'] : '';
		$prod_url  = ! empty( $s['image']['url'] ) ? (string) $s['image']['url'] : '';

		?>
		<section class="htoeau-sample-kit">
			<div class="htoeau-sample-kit__inner<?php echo ( $frame_url || $prod_url ) ? '' : ' htoeau-sample-kit__inner--text-only'; ?>">
				<div class="htoeau-sample-kit__content">
					<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h2 class="htoeau-sample-kit__title"><?php echo esc_html( (string) $s['heading'] ); ?></h2>
					<?php endif; ?>

					<?php
					$body_raw = isset( $s['text'] ) ? trim( str_replace( [ "\r\n", "\r" ], "\n", (string) $s['text'] ) ) : '';
					if ( '' !== $body_raw ) :
						$paragraphs = preg_split( '/\n\s*\n/', $body_raw, -1, PREG_SPLIT_NO_EMPTY );
						$paragraphs = array_values( array_filter( array_map( 'trim', (array) $paragraphs ) ) );
						?>
					<div class="htoeau-sample-kit__copy">
						<?php if ( count( $paragraphs ) > 1 ) : ?>
							<?php foreach ( $paragraphs as $para ) : ?>
						<p class="htoeau-sample-kit__copy-p"><?php echo esc_html( $para ); ?></p>
							<?php endforeach; ?>
						<?php else : ?>
							<?php
							$single = $paragraphs[0] ?? $body_raw;
							$p_class = 'htoeau-sample-kit__copy-p';
							if ( false !== strpos( $single, "\n" ) ) {
								$p_class .= ' htoeau-sample-kit__copy-p--rich';
							}
							?>
						<p class="<?php echo esc_attr( $p_class ); ?>"><?php echo esc_html( $single ); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $s['cta_text'] ) ) : ?>
					<div class="htoeau-sample-kit__cta-wrap">
						<?php
						echo render_cta_button(
							(string) $s['cta_text'],
							! empty( $link['url'] ) ? (string) $link['url'] : '#',
							[
								'class'       => 'htoeau-sample-kit__cta',
								'is_external' => ! empty( $link['is_external'] ),
								'nofollow'    => ! empty( $link['nofollow'] ),
							]
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $s['features'] ) ) : ?>
					<ul class="htoeau-sample-kit__features">
						<?php foreach ( (array) $s['features'] as $row ) : ?>
							<?php
							$t = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
							if ( '' === $t ) {
								continue;
							}
							?>
						<li class="htoeau-sample-kit__feature">
							<?php echo render_check_icon( '30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $t ); ?></span>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>

				<?php if ( $frame_url || $prod_url ) : ?>
				<div class="htoeau-sample-kit__visual<?php echo $frame_url ? '' : ' htoeau-sample-kit__visual--solo'; ?>">
					<?php if ( $frame_url ) : ?>
					<img
						class="htoeau-sample-kit__frame"
						src="<?php echo esc_url( $frame_url ); ?>"
						alt=""
						width="502"
						height="502"
						loading="lazy"
						decoding="async"
					/>
					<?php endif; ?>
					<?php if ( $prod_url ) : ?>
					<img
						class="htoeau-sample-kit__product<?php echo $frame_url ? '' : ' htoeau-sample-kit__product--solo'; ?>"
						src="<?php echo esc_url( $prod_url ); ?>"
						alt=""
						width="485"
						height="493"
						loading="lazy"
						decoding="async"
					/>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
