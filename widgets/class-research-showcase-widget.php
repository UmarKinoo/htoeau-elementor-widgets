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
 * Research showcase (controlled study + product visual).
 * Figma: 86:314 desktop; mobile stack 86:916 — https://www.figma.com/design/FcOeKFswrs0fJXLmrEvev6/Untitled?node-id=86-916
 */
class Research_Showcase_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_research_showcase';
	}

	public function get_title(): string {
		return 'HtoEAU Research Showcase';
	}

	public function get_icon(): string {
		return 'eicon-device-desktop';
	}

	public function get_keywords(): array {
		return [ 'research', 'science', 'study', 'htoeau' ];
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
			'default' => 'Evaluated in Controlled Human Research',
			'rows'    => 2,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_visual', [
			'label' => 'Center visual',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'center_image', [
			'label'       => 'Image',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Export the full collage from Figma (can, splashes, papers) as one PNG for best fidelity.',
		] );

		$this->add_control( 'center_image_alt', [
			'label'   => 'Image alt text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'HtoEAU hydrogen-infused water',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_left', [
			'label' => 'Left column (research points)',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'title', [
			'label'   => 'Title',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 3,
			'default' => '',
		] );
		$rep->add_control( 'description', [
			'label'   => 'Description',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => '',
		] );

		$this->add_control( 'points_left', [
			'label'       => 'Points',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'title'       => "Measured, verifiable results\nunder controlled conditions",
					'description' => 'HtoEAU® Hydrogen-Infused Water was evaluated in a 4-week randomized, double-blind, placebo-controlled study involving trained collegiate athletes.',
				],
				[
					'title'       => 'Improved Peak Speed and Threshold Output',
					'description' => 'The study recorded improvements in maximal running speed (vmax) and anaerobic threshold velocity (vANT).',
				],
				[
					'title'       => 'Improved Wellbeing Indicators',
					'description' => 'Participants reported improvements in general wellbeing and mental health scores during the study period.',
				],
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_right', [
			'label' => 'Right column (product points)',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep_r = new Repeater();
		$rep_r->add_control( 'title', [
			'label'   => 'Title',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 3,
			'default' => '',
		] );
		$rep_r->add_control( 'description', [
			'label'   => 'Description',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => '',
		] );

		$this->add_control( 'points_right', [
			'label'       => 'Points',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep_r->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'title'       => 'Specialised Infusion Process',
					'description' => 'Our process creates stable microbubble dispersion, helping preserve dissolved hydrogen concentration and stability for longer.',
				],
				[
					'title'       => 'Clean, Stimulant-Free Hydration',
					'description' => 'Only water and molecular hydrogen. No sugar, stimulants, or additives.',
				],
				[
					'title'       => 'Developed for Modern Lifestyles',
					'description' => 'Trusted by athletes, biohackers, and individuals who go further, consistently.',
				],
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_cta', [
			'label' => 'Call to action',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'cta_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Find out more about the science',
		] );

		$this->add_control( 'cta_link', [
			'label'   => 'Button link',
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
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
				'right'  => '71',
				'bottom' => '96',
				'left'   => '71',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '64',
				'right'  => '40',
				'bottom' => '64',
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
				'{{WRAPPER}} .htoeau-research' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @param array<int,array<string,mixed>> $points
	 * @param 'left'|'right'                 $side
	 */
	private function render_points_column( array $points, string $side ): void {
		if ( empty( $points ) ) {
			return;
		}
		$class = 'htoeau-research__col htoeau-research__col--' . $side;
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php
			foreach ( $points as $row ) {
				$title = isset( $row['title'] ) ? (string) $row['title'] : '';
				$desc  = isset( $row['description'] ) ? (string) $row['description'] : '';
				if ( '' === trim( $title ) && '' === trim( $desc ) ) {
					continue;
				}
				?>
			<div class="htoeau-research__point">
				<div class="htoeau-research__point-icon" aria-hidden="true">
					<?php echo render_check_icon( '56' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( '' !== trim( $title ) ) : ?>
				<h3 class="htoeau-research__point-title"><?php echo esc_html( str_replace( [ "\r\n", "\r" ], "\n", $title ) ); ?></h3>
				<?php endif; ?>
				<?php if ( '' !== trim( $desc ) ) : ?>
				<p class="htoeau-research__point-text"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
			</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$img   = $s['center_image'] ?? [];
		$img_u = is_array( $img ) && ! empty( $img['url'] ) ? (string) $img['url'] : '';
		$alt   = ! empty( $s['center_image_alt'] ) ? (string) $s['center_image_alt'] : '';

		$cta = $s['cta_link'] ?? [];
		?>
		<section class="htoeau-research">
			<div class="htoeau-research__inner">
				<?php if ( ! empty( $s['heading'] ) ) : ?>
				<h2 class="htoeau-research__title"><?php echo esc_html( $s['heading'] ); ?></h2>
				<?php endif; ?>

				<div class="htoeau-research__layout">
					<?php $this->render_points_column( $s['points_left'] ?? [], 'left' ); ?>

					<div class="htoeau-research__visual">
						<?php if ( $img_u ) : ?>
						<img
							class="htoeau-research__visual-img"
							src="<?php echo esc_url( $img_u ); ?>"
							alt="<?php echo esc_attr( $alt ); ?>"
							loading="lazy"
						/>
						<?php endif; ?>
					</div>

					<?php $this->render_points_column( $s['points_right'] ?? [], 'right' ); ?>
				</div>

				<?php if ( ! empty( $s['cta_text'] ) ) : ?>
				<div class="htoeau-research__cta-wrap">
					<?php
					echo render_cta_button(
						(string) $s['cta_text'],
						is_array( $cta ) && ! empty( $cta['url'] ) ? (string) $cta['url'] : '#',
						[
							'class'       => 'htoeau-research__cta',
							'is_external' => ! empty( $cta['is_external'] ),
							'nofollow'    => ! empty( $cta['nofollow'] ),
						]
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
