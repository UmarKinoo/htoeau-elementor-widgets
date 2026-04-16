<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * “What Sets HtoEAU® Hydrogen Water Apart” — four glass cards on navy + one background scene.
 * Figma 1:2046 — https://www.figma.com/design/ghyTkZS0JtoxOqEAsXuy7r/Untitled?node-id=1-2046
 * Use a single composite (splash + can) asset; CSS positions it behind the heading and cards.
 */
class Hydrogen_Apart_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_hydrogen_apart';
	}

	public function get_title(): string {
		return 'HtoEAU Hydrogen Apart';
	}

	public function get_icon(): string {
		return 'eicon-info-box';
	}

	public function get_keywords(): array {
		return [ 'hydrogen', 'features', 'cards', 'glass', 'product', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		$this->start_controls_section( 'section_heading', [
			'label' => 'Heading',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Title',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'What Sets HtoEAU® Hydrogen Water Apart',
			'rows'    => 2,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_decor', [
			'label' => 'Background scene',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'scene_image', [
			'label'       => 'Scene image (splash + product)',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'One composite asset for Figma 1:2046 — water splash and can together. Replaces the old separate texture + product fields.',
			'default'     => [
				'url' => 'https://www.figma.com/api/mcp/asset/ea6e6699-cf81-4714-80f9-39116beb4511',
			],
		] );

		$this->add_control( 'scene_image_alt', [
			'label'   => 'Scene image alt text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'HtoEAU hydrogen water can with water splash',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_cards', [
			'label' => 'Feature cards',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'title', [
			'label'   => 'Title',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );
		$rep->add_control( 'description', [
			'label'   => 'Description',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => '',
		] );

		$this->add_control( 'cards', [
			'label'       => 'Cards',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'title'       => 'Consistent Hydrogen Levels',
					'description' => 'Infused to a minimum therapeutic level of 5 mg/L dissolved hydrogen gas, ensuring reliable hydrogen concentration in every can.',
				],
				[
					'title'       => 'Engineered Hydrogen Stability',
					'description' => 'Pressurised filling and controlled headspace help preserve dissolved hydrogen, maintaining stability from production to consumption.',
				],
				[
					'title'       => 'Efficient Molecular Delivery',
					'description' => 'Hydrogen’s extremely small molecular size allows it to dissolve easily in water and disperse efficiently throughout the body.',
				],
				[
					'title'       => 'Clean Hydration Formula',
					'description' => 'No sugar, stimulants, or additives. Just purified water and molecular hydrogen.',
				],
			],
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
				'top'    => '95',
				'right'  => '172',
				'bottom' => '95',
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
				'top'    => '48',
				'right'  => '20',
				'bottom' => '48',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .htoeau-hydrogen-apart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$scene   = $s['scene_image'] ?? [];
		$scene_u = is_array( $scene ) && ! empty( $scene['url'] ) ? (string) $scene['url'] : '';
		if ( '' === $scene_u ) {
			$legacy = $s['product_image'] ?? [];
			if ( is_array( $legacy ) && ! empty( $legacy['url'] ) ) {
				$scene_u = (string) $legacy['url'];
			}
		}
		if ( '' === $scene_u ) {
			$legacy = $s['bg_texture'] ?? [];
			if ( is_array( $legacy ) && ! empty( $legacy['url'] ) ) {
				$scene_u = (string) $legacy['url'];
			}
		}

		$alt = ! empty( $s['scene_image_alt'] ) ? (string) $s['scene_image_alt'] : '';
		if ( '' === $alt && ! empty( $s['product_image_alt'] ) ) {
			$alt = (string) $s['product_image_alt'];
		}

		$heading = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$cards   = isset( $s['cards'] ) && is_array( $s['cards'] ) ? $s['cards'] : [];
		?>
		<section class="htoeau-hydrogen-apart">
			<div class="htoeau-hydrogen-apart__layers" aria-hidden="true">
				<?php if ( $scene_u ) : ?>
				<img
					class="htoeau-hydrogen-apart__scene"
					src="<?php echo esc_url( $scene_u ); ?>"
					alt="<?php echo esc_attr( $alt ); ?>"
					loading="lazy"
					decoding="async"
				/>
				<?php endif; ?>
			</div>

			<div class="htoeau-hydrogen-apart__inner">
				<?php if ( '' !== trim( $heading ) ) : ?>
				<h2 class="htoeau-hydrogen-apart__heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $cards ) ) : ?>
				<div class="htoeau-hydrogen-apart__grid">
					<?php
					foreach ( $cards as $card ) {
						$t = isset( $card['title'] ) ? (string) $card['title'] : '';
						$d = isset( $card['description'] ) ? (string) $card['description'] : '';
						if ( '' === trim( $t ) && '' === trim( $d ) ) {
							continue;
						}
						?>
					<article class="htoeau-hydrogen-apart__card">
						<?php if ( '' !== trim( $t ) ) : ?>
						<h3 class="htoeau-hydrogen-apart__card-title"><?php echo esc_html( $t ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== trim( $d ) ) : ?>
						<p class="htoeau-hydrogen-apart__card-text"><?php echo esc_html( $d ); ?></p>
						<?php endif; ?>
					</article>
						<?php
					}
					?>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
