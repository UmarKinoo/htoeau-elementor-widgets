<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_check_icon_on_gradient;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * “Engineered to Perform.” — white section, left hero image + gradient, right-aligned spec list (desktop).
 * Figma: desktop 1:2066 — https://www.figma.com/design/ghyTkZS0JtoxOqEAsXuy7r/Untitled?node-id=1-2066
 * Mobile 1:1493 — https://www.figma.com/design/ghyTkZS0JtoxOqEAsXuy7r/Untitled?node-id=1-1493
 */
class Engineered_Perform_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_engineered_perform';
	}

	public function get_title(): string {
		return 'HtoEAU Engineered to Perform';
	}

	public function get_icon(): string {
		return 'eicon-bullet-list';
	}

	public function get_keywords(): array {
		return [ 'engineered', 'perform', 'specs', 'features', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		$this->start_controls_section( 'section_content', [
			'label' => 'Content',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Engineered to Perform.',
		] );

		$this->add_control( 'hero_image', [
			'label'       => 'Left image (water / glass)',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Large photo; positioned per Figma (left, ~47% width). Re-upload from Media before Figma asset URLs expire.',
			'default'     => [
				'url' => 'https://www.figma.com/api/mcp/asset/46d01f10-8590-457c-a033-cf88a027da97',
			],
		] );

		$this->add_control( 'hero_image_alt', [
			'label'   => 'Image alt text (optional)',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$this->add_control( 'icon_image', [
			'label'       => 'Row icon',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Figma: 56×56 check circle; used for each row.',
			'default'     => [
				'url' => 'https://www.figma.com/api/mcp/asset/2c71a25e-1110-420c-96b2-fb8917f5b57d',
			],
		] );

		$rep = new Repeater();
		$rep->add_control( 'title', [
			'label'   => 'Label',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );
		$rep->add_control( 'description', [
			'label'   => 'Description',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => '',
		] );

		$this->add_control( 'rows', [
			'label'       => 'Specifications',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'title'       => 'Hydrogen Concentration',
					'description' => 'Minimum 5 mg/L dissolved hydrogen gas.',
				],
				[
					'title'       => 'Water Base',
					'description' => 'Three-stage filtered and UV-treated water.',
				],
				[
					'title'       => 'Packaging',
					'description' => 'Recyclable aluminium cans designed to preserve product quality.',
				],
				[
					'title'       => 'Purity',
					'description' => 'Zero sugar. Zero calories. No additives.',
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
				'top'    => '96',
				'right'  => '185',
				'bottom' => '96',
				'left'   => '185',
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
				'{{WRAPPER}} .htoeau-engineered' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$hero   = $s['hero_image'] ?? [];
		$hero_u = is_array( $hero ) && ! empty( $hero['url'] ) ? (string) $hero['url'] : '';
		$alt    = isset( $s['hero_image_alt'] ) ? trim( (string) $s['hero_image_alt'] ) : '';

		$icon   = $s['icon_image'] ?? [];
		$icon_u = is_array( $icon ) && ! empty( $icon['url'] ) ? (string) $icon['url'] : '';

		$heading = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$rows    = isset( $s['rows'] ) && is_array( $s['rows'] ) ? $s['rows'] : [];
		$widget_id = $this->get_id();
		?>
		<style>
		@media(max-width:1024px){.elementor-element-<?php echo esc_attr( $widget_id ); ?> .htoeau-engineered{padding:72px 48px!important}}
		@media(max-width:767px){.elementor-element-<?php echo esc_attr( $widget_id ); ?> .htoeau-engineered{padding:50px 20px 338px!important}}
		</style>
		<section class="htoeau-engineered">
			<div class="htoeau-engineered__layers" aria-hidden="true">
				<div class="htoeau-engineered__bg"></div>
				<?php if ( $hero_u ) : ?>
				<div class="htoeau-engineered__photo-wrap">
					<img
						class="htoeau-engineered__photo"
						src="<?php echo esc_url( $hero_u ); ?>"
						alt="<?php echo esc_attr( $alt ); ?>"
						loading="lazy"
						decoding="async"
					/>
				</div>
				<?php endif; ?>
				<div class="htoeau-engineered__fade"></div>
			</div>

			<div class="htoeau-engineered__inner">
				<?php if ( '' !== trim( $heading ) ) : ?>
				<h2 class="htoeau-engineered__heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $rows ) ) : ?>
				<div class="htoeau-engineered__column">
					<?php
					foreach ( $rows as $row ) {
						$t = isset( $row['title'] ) ? (string) $row['title'] : '';
						$d = isset( $row['description'] ) ? (string) $row['description'] : '';
						if ( '' === trim( $t ) && '' === trim( $d ) ) {
							continue;
						}
						?>
					<article class="htoeau-engineered__row">
						<div class="htoeau-engineered__icon">
							<?php if ( $icon_u ) : ?>
							<img
								src="<?php echo esc_url( $icon_u ); ?>"
								alt=""
								loading="lazy"
								decoding="async"
								onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
							/>
							<?php endif; ?>
							<span class="htoeau-engineered__icon-fallback"<?php echo $icon_u ? ' style="display:none"' : ''; ?>>
								<?php echo render_check_icon_on_gradient( '44' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</div>
						<div class="htoeau-engineered__body">
							<?php if ( '' !== trim( $t ) ) : ?>
							<h3 class="htoeau-engineered__label"><?php echo esc_html( $t ); ?></h3>
							<?php endif; ?>
							<?php if ( '' !== trim( $d ) ) : ?>
							<p class="htoeau-engineered__desc"><?php echo esc_html( $d ); ?></p>
							<?php endif; ?>
						</div>
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
