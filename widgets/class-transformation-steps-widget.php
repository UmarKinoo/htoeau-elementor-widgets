<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Figma node 86:184 — Transformation B / 3 steps.
 */
class Transformation_Steps_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_transformation_steps';
	}

	public function get_title(): string {
		return 'HtoEAU Transformation Steps';
	}

	public function get_icon(): string {
		return 'eicon-flow';
	}

	public function get_keywords(): array {
		return [ 'steps', 'process', 'transformation', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		$this->start_controls_section( 'section_header', [
			'label' => 'Heading',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Title',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'From Molecular Hydrogen to Next-Level Hydration',
			'rows'    => 2,
		] );

		$this->add_control( 'subtitle', [
			'label'   => 'Subtitle',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'What happens when advanced infusion technology meets pure hydration?',
			'rows'    => 2,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_steps', [
			'label' => 'Steps',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label' => 'Background image',
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'step_number', [
			'label'   => 'Step number',
			'type'    => Controls_Manager::TEXT,
			'default' => '1',
		] );

		$repeater->add_control( 'wide_title', [
			'label'       => 'Title (wide panel only)',
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => 'Used for step 1 (large panel).',
		] );

		$repeater->add_control( 'wide_description', [
			'label'       => 'Description (wide panel only)',
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 3,
			'default'     => '',
			'description' => 'Used for step 1.',
		] );

		$repeater->add_control( 'narrow_caption', [
			'label'       => 'Vertical caption (narrow panels)',
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => 'Steps 2–3: text shown vertically on the side strips.',
		] );

		$this->add_control( 'steps', [
			'label'       => 'Steps',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ step_number }}} — {{{ wide_title }}}{{{ narrow_caption }}}',
			'default'     => [
				[
					'step_number'      => '1',
					'wide_title'       => 'Drink HtoEAU',
					'wide_description' => 'Begin with clean, precision-engineered hydration developed using advanced purification and infusion technologies.',
					'narrow_caption'   => '',
				],
				[
					'step_number'    => '2',
					'narrow_caption' => 'Hydrogen is absorbed efficiently',
				],
				[
					'step_number'    => '3',
					'narrow_caption' => 'Supports your daily hydration needs',
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
				'right'  => '104',
				'bottom' => '96',
				'left'   => '104',
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
				'top'    => '50',
				'right'  => '20',
				'bottom' => '50',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .htoeau-transformation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s     = $this->get_settings_for_display();
		$steps = (array) ( $s['steps'] ?? [] );

		if ( empty( $steps ) ) {
			return;
		}

		$img_base = function_exists( '\\htoeau_child_get_brand_images_base_url' )
			? \htoeau_child_get_brand_images_base_url()
			: '';

		?>
		<section class="htoeau-transform" aria-labelledby="htoeau-transform-heading" data-htoeau-transform>
			<div class="htoeau-transform__inner">
				<header class="htoeau-transform__header">
					<?php if ( ! empty( $s['heading'] ) ) : ?>
						<h2 id="htoeau-transform-heading" class="htoeau-transform__title">
							<?php echo esc_html( (string) $s['heading'] ); ?>
						</h2>
					<?php endif; ?>
					<?php if ( ! empty( $s['subtitle'] ) ) : ?>
						<p class="htoeau-transform__subtitle">
							<?php echo esc_html( (string) $s['subtitle'] ); ?>
						</p>
					<?php endif; ?>
				</header>
				<div class="htoeau-transform__panels" data-transform-panels>
					<?php
					$mobile_desc_fallback = '';
					if ( isset( $steps[0]['wide_description'] ) ) {
						$mobile_desc_fallback = trim( (string) $steps[0]['wide_description'] );
					}
					?>
					<?php foreach ( $steps as $i => $step ) : ?>
						<?php
						$index    = (int) $i;
						$is_active = ( 0 === $index );
						$panel_cls = 'htoeau-transform__panel' . ( $is_active ? ' is-active' : '' );

						$image_url = ! empty( $step['image']['url'] ) ? (string) $step['image']['url'] : '';
						$num       = isset( $step['step_number'] ) ? (string) $step['step_number'] : (string) ( $index + 1 );

						// Map widget fields to theme fields.
						if ( 0 === $index ) {
							$title = (string) ( $step['wide_title'] ?? '' );
							$desc  = (string) ( $step['wide_description'] ?? '' );
						} else {
							$title = (string) ( $step['narrow_caption'] ?? '' );
							if ( '' === trim( $title ) ) {
								$title = (string) ( $step['wide_title'] ?? '' );
							}
							$desc = (string) ( $step['wide_description'] ?? '' );
							if ( '' === trim( $desc ) ) {
								$desc = $mobile_desc_fallback;
							}
						}

						$circle = $is_active ? 'step-circle-lg.svg' : 'step-circle-sm.svg';
						$circle_url = $img_base ? $img_base . $circle : '';
						?>
						<button
							type="button"
							class="<?php echo esc_attr( $panel_cls ); ?>"
							data-transform-panel="<?php echo esc_attr( (string) $index ); ?>"
							aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>"
						>
							<span class="htoeau-transform__panel-bg"<?php echo $image_url ? ' style="background-image: url(\'' . esc_url( $image_url ) . '\');"' : ''; ?>></span>
							<span class="htoeau-transform__overlay" aria-hidden="true"></span>
							<span class="htoeau-transform__panel-content">
								<span class="htoeau-transform__num-wrap">
									<?php if ( $circle_url ) : ?>
									<img
										src="<?php echo esc_url( $circle_url ); ?>"
										alt=""
										class="htoeau-transform__num-ring"
										width="<?php echo $is_active ? 72 : 56; ?>"
										height="<?php echo $is_active ? 72 : 56; ?>"
									/>
									<?php endif; ?>
									<span class="htoeau-transform__num"><?php echo esc_html( $num ); ?></span>
								</span>
								<?php if ( '' !== $title ) : ?>
								<span class="htoeau-transform__panel-title"><?php echo esc_html( $title ); ?></span>
								<?php endif; ?>
								<?php if ( '' !== $desc ) : ?>
								<span class="htoeau-transform__panel-desc"><?php echo esc_html( $desc ); ?></span>
								<?php endif; ?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
