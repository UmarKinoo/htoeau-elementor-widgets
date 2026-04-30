<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 3×2 benefits grid — preset SVG icon or custom upload, bold title, description.
 * Layout: white background, centered columns, responsive (3 → 2 → 1).
 */
class Benefits_Grid_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_benefits_grid';
	}

	public function get_title(): string {
		return 'HtoEAU Benefits Grid';
	}

	public function get_icon(): string {
		return 'eicon-gallery-grid';
	}

	public function get_keywords(): array {
		return [ 'benefits', 'grid', 'features', 'icons', 'htoeau' ];
	}

	/** @return array<string,string> Preset icon key → label. */
	private function preset_options(): array {
		return [
			'dumbbell'   => 'Dumbbell (Muscle Recovery)',
			'chart-line' => 'Chart Line (Performance)',
			'leaf'       => 'Leaf (Skin)',
			'brain'      => 'Brain (Brain Function)',
			'tint'       => 'Drop (Energy)',
			'bolt'       => 'Bolt (Antioxidants)',
			'none'       => '— None —',
		];
	}

	/** Inline SVGs matching the screenshot style — thin stroke, currentColor. */
	private function preset_svg( string $key ): string {
		$svgs = [
			'dumbbell'   => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 6.5h1v11h-1z"/><path d="M17.5 6.5h1v11h-1z"/><rect x="3" y="8" width="3.5" height="8" rx="1"/><rect x="17.5" y="8" width="3.5" height="8" rx="1"/><line x1="7.5" y1="12" x2="16.5" y2="12"/></svg>',
			'chart-line' => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 17 8 11 13 14 19 7"/><polyline points="16 7 19 7 19 10"/></svg>',
			'leaf'       => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
			'brain'      => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/><path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/><path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"/><path d="M17.599 6.5a3 3 0 0 0 .399-1.375"/><path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"/><path d="M3.477 10.896a4 4 0 0 1 .585-.396"/><path d="M19.938 10.5a4 4 0 0 1 .585.396"/><path d="M6 18a4 4 0 0 1-1.967-.516"/><path d="M19.967 17.484A4 4 0 0 1 18 18"/></svg>',
			'tint'       => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/><path d="M12 15 C11 14 9.5 13 9 11.5" stroke-width="1.2"/></svg>',
			'bolt'       => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
		];

		return $svgs[ $key ] ?? '';
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
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => '',
			'rows'    => 2,
		] );

		$this->add_control( 'subheading', [
			'label'   => 'Subheading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => '',
			'rows'    => 2,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_items', [
			'label' => 'Benefits',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();

		$rep->add_control( 'icon_preset', [
			'label'   => 'Icon',
			'type'    => Controls_Manager::SELECT,
			'options' => $this->preset_options(),
			'default' => 'none',
		] );

		$rep->add_control( 'icon_image', [
			'label'       => 'Custom icon (SVG / PNG)',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Overrides the preset icon above when set.',
		] );

		$rep->add_control( 'title', [
			'label'       => 'Title',
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'default'     => '',
		] );

		$rep->add_control( 'description', [
			'label'       => 'Description',
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'rows'        => 4,
			'label_block' => true,
			'description' => 'Wrap stat numbers in &lt;strong&gt; for bold, e.g. &lt;strong&gt;20%&lt;/strong&gt;.',
		] );

		$this->add_control( 'items', [
			'label'       => 'Items',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'icon_preset' => 'dumbbell',
					'title'       => 'Speeds Up Muscle Recovery',
					'description' => 'Reduces inflammation and soreness, cutting recovery time by up to <strong>20%</strong> after workouts.',
				],
				[
					'icon_preset' => 'chart-line',
					'title'       => 'Enhances Performance',
					'description' => 'Athletes experience a <strong>10%</strong> boost in endurance and efficiency, thanks to its stress-reducing capabilities.',
				],
				[
					'icon_preset' => 'leaf',
					'title'       => 'Promotes Healthier Skin',
					'description' => 'Recognized for boosting skin health, reducing wrinkles, and improving elasticity by <strong>30%</strong>.',
				],
				[
					'icon_preset' => 'brain',
					'title'       => 'Improves Brain Function',
					'description' => 'Offers a <strong>20%</strong> improvement in cognitive performance and focus, with potential protective effects against neurodegeneration.',
				],
				[
					'icon_preset' => 'bolt',
					'title'       => 'Elevates Daily Energy',
					'description' => "It's known to increase energy levels by up to <strong>15%</strong> by enhancing mitochondrial function, helping your cells produce energy more efficiently for longer-lasting vitality.",
				],
				[
					'icon_preset' => 'tint',
					'title'       => 'Rich in Antioxidants',
					'description' => 'Acts as a selective antioxidant, <strong>60%</strong> more effective at neutralizing free radicals, reducing cellular damage and disease risk.',
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
				'top'    => '80',
				'right'  => '185',
				'bottom' => '80',
				'left'   => '185',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '60',
				'right'  => '40',
				'bottom' => '60',
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
				'{{WRAPPER}} .htoeau-benefits' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'columns', [
			'label'          => 'Columns',
			'type'           => Controls_Manager::SELECT,
			'options'        => [
				'1' => '1',
				'2' => '2',
				'3' => '3',
			],
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'selectors'      => [
				'{{WRAPPER}} .htoeau-benefits__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
			],
		] );

		$this->add_control( 'heading_color', [
			'label'     => 'Heading color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .htoeau-benefits__heading' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_color', [
			'label'     => 'Item title color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .htoeau-benefits__item-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'desc_color', [
			'label'     => 'Description color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .htoeau-benefits__desc' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'icon_color', [
			'label'     => 'Icon color',
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .htoeau-benefits__icon' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$heading    = isset( $s['heading'] ) ? trim( (string) $s['heading'] ) : '';
		$subheading = isset( $s['subheading'] ) ? trim( (string) $s['subheading'] ) : '';
		$items      = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : [];
		?>
		<section class="htoeau-benefits">
			<div class="htoeau-benefits__inner">

				<?php if ( $heading || $subheading ) : ?>
				<header class="htoeau-benefits__header">
					<?php if ( $heading ) : ?>
					<h2 class="htoeau-benefits__heading"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $subheading ) : ?>
					<p class="htoeau-benefits__subheading"><?php echo esc_html( $subheading ); ?></p>
					<?php endif; ?>
				</header>
				<?php endif; ?>

				<?php if ( ! empty( $items ) ) : ?>
				<div class="htoeau-benefits__grid">
					<?php foreach ( $items as $item ) :
						$title      = isset( $item['title'] ) ? trim( (string) $item['title'] ) : '';
						$desc       = isset( $item['description'] ) ? trim( (string) $item['description'] ) : '';
						$preset     = isset( $item['icon_preset'] ) ? (string) $item['icon_preset'] : 'none';
						$icon_img   = $item['icon_image'] ?? [];
						$icon_img_u = is_array( $icon_img ) && ! empty( $icon_img['url'] ) ? (string) $icon_img['url'] : '';
						$preset_svg = ( 'none' !== $preset && '' === $icon_img_u ) ? $this->preset_svg( $preset ) : '';
						$has_icon   = '' !== $icon_img_u || '' !== $preset_svg;

						if ( '' === $title && '' === $desc && ! $has_icon ) {
							continue;
						}
					?>
					<article class="htoeau-benefits__item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ?? '' ); ?>">

						<?php if ( $has_icon ) : ?>
						<div class="htoeau-benefits__icon" aria-hidden="true">
							<?php if ( $icon_img_u ) : ?>
							<img
								src="<?php echo esc_url( $icon_img_u ); ?>"
								alt=""
								loading="lazy"
								decoding="async"
								width="36"
								height="36"
							/>
							<?php else : ?>
							<?php echo $preset_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — static hardcoded SVG ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<?php if ( '' !== $title ) : ?>
						<h3 class="htoeau-benefits__item-title"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>

						<?php if ( '' !== $desc ) : ?>
						<p class="htoeau-benefits__desc"><?php echo wp_kses_post( $desc ); ?></p>
						<?php endif; ?>

					</article>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
