<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_cta_button;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meet Prof. Sergej Ostojic / study section.
 * Figma: 86:379 desktop; mobile 86:982 — https://www.figma.com/design/FcOeKFswrs0fJXLmrEvev6/Untitled?node-id=86-982
 */
class Professor_Study_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_professor_study';
	}

	public function get_title(): string {
		return 'HtoEAU Professor Study';
	}

	public function get_icon(): string {
		return 'eicon-person';
	}

	public function get_keywords(): array {
		return [ 'professor', 'study', 'science', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'section_visual', [
			'label' => 'Background & portrait',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'portrait_back', [
			'label'       => 'Portrait layer (back)',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Optional second layer — Figma stacks two slightly offset portraits.',
		] );

		$this->add_control( 'portrait_front', [
			'label'       => 'Portrait layer (front)',
			'type'        => Controls_Manager::MEDIA,
			'description' => 'Main photo on the right. Or export one composite PNG and use only this field.',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_content', [
			'label' => 'Content',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Meet Prof. Sergej Ostojic',
		] );

		$this->add_control( 'intro', [
			'label'   => 'Intro paragraph',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'HtoEAU® Hydrogen-Infused Water was evaluated in a 4-week randomized, double-blind, placebo-controlled study led by Professor Sergej Ostojic (MD, PhD), a leading researcher in human physiology and exercise science.',
			'rows'    => 4,
		] );

		$this->add_control( 'desc', [
			'label'   => 'Second paragraph',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'The study examined hydrogen-infused water in trained collegiate athletes under controlled conditions, measuring key physiological and wellbeing indicators, including:',
			'rows'    => 3,
		] );

		$rep = new Repeater();
		$rep->add_control( 'text', [
			'label'   => 'Item',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$this->add_control( 'items', [
			'label'       => 'List items',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Maximal running speed (vmax)' ],
				[ 'text' => 'Anaerobic threshold performance (vANT)' ],
				[ 'text' => 'Hydration and physiological biomarkers' ],
				[ 'text' => 'Self-reported wellbeing and physical readiness' ],
			],
		] );

		$this->add_control( 'footnote', [
			'label'   => 'Closing paragraph',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'These findings contribute to the growing scientific interest in molecular hydrogen and its potential role in advanced hydration and human physiology.',
			'rows'    => 3,
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
				'bottom' => '220',
				'left'   => '48',
				'unit'   => 'px',
			],
			'mobile_default' => [
				'top'    => '50',
				'right'  => '20',
				'bottom' => '360',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .htoeau-professor-study' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Bullet icon for the study list — 28×24 cyan molecule per Figma node 86:392.
	 */
	private function render_molecule_icon(): string {
		return '<span class="htoeau-professor-study__molecule" aria-hidden="true"><svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.0719 4.07784C17.0836 3.32502 17.2702 1.90337 16.4877 0.90338C15.7068 -0.096615 14.2531 -0.296937 13.2414 0.455886C12.2298 1.20871 12.0431 2.63035 12.8256 3.63034C13.6065 4.63034 15.0602 4.83066 16.0719 4.07784ZM0.655804 15.9663C1.94089 17.8661 4.56017 18.2942 6.35929 16.9534C7.26949 16.2765 7.7868 15.2813 7.87847 14.2426C7.96851 13.2216 8.62988 12.3411 9.56954 11.9033L17.4961 8.2119C18.331 7.82257 19.3149 7.83711 20.1367 8.27329C20.1465 8.27814 20.1563 8.2846 20.1678 8.28945C20.9863 8.71432 21.5609 9.48492 21.7181 10.3767L22.4499 14.5285C22.6299 15.5495 22.2289 16.5705 21.4234 17.2296C21.2826 17.3459 21.1484 17.4703 21.0322 17.6028C19.8486 18.9437 19.77 20.976 20.929 22.459C22.3189 24.2377 24.9562 24.5333 26.6849 23.0487C28.1452 21.795 28.4267 19.6303 27.3414 18.0277C26.8699 17.333 26.22 16.8354 25.498 16.5495C24.5371 16.1699 23.825 15.3508 23.6498 14.3524L22.923 10.2329C22.7642 9.32983 23.0588 8.41869 23.6989 7.76118C23.7775 7.68041 23.8381 7.61094 23.8757 7.5657C24.7859 6.46555 24.7859 4.82097 23.7399 3.6885C22.7773 2.64651 21.1779 2.39449 19.9534 3.09885C19.161 3.55442 18.6732 4.28947 18.5193 5.08753C18.3441 5.99059 17.7581 6.76119 16.9134 7.15537L8.98512 10.8532C8.04709 11.291 6.93226 11.2103 6.06626 10.6287C6.03351 10.6061 6.00241 10.5867 5.97294 10.5689C4.57163 9.73209 2.72667 9.79348 1.39084 10.8839C-0.128344 12.1246 -0.449206 14.333 0.657441 15.9663H0.655804Z" fill="#7CC9D3"/></svg></span>';
	}

	protected function render(): void {
		$s    = $this->get_settings_for_display();
		$link = $s['cta_link'] ?? [];
		$back  = ! empty( $s['portrait_back']['url'] ) ? (string) $s['portrait_back']['url'] : '';
		$front = ! empty( $s['portrait_front']['url'] ) ? (string) $s['portrait_front']['url'] : '';
		// Back-compat: older widget versions used a single `bg_image` control.
		if ( '' === $front && ! empty( $s['bg_image']['url'] ) ) {
			$front = (string) $s['bg_image']['url'];
		}
		?>
		<section class="htoeau-professor-study">
			<div class="htoeau-professor-study__visual" aria-hidden="true">
				<?php if ( $back ) : ?>
				<img class="htoeau-professor-study__img htoeau-professor-study__img--back" src="<?php echo esc_url( $back ); ?>" alt="" loading="lazy" />
				<?php endif; ?>
				<div class="htoeau-professor-study__visual-grad"></div>
				<?php if ( $front ) : ?>
				<img class="htoeau-professor-study__img htoeau-professor-study__img--front" src="<?php echo esc_url( $front ); ?>" alt="" loading="lazy" />
				<?php endif; ?>
			</div>

			<div class="htoeau-professor-study__inner">
				<div class="htoeau-professor-study__lead">
					<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h2 class="htoeau-professor-study__title"><?php echo esc_html( (string) $s['heading'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $s['intro'] ) ) : ?>
					<p class="htoeau-professor-study__intro"><?php echo esc_html( (string) $s['intro'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $s['desc'] ) ) : ?>
					<p class="htoeau-professor-study__desc"><?php echo esc_html( (string) $s['desc'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $s['items'] ) ) : ?>
				<ul class="htoeau-professor-study__list">
					<?php foreach ( $s['items'] as $item ) : ?>
						<?php
						$t = isset( $item['text'] ) ? trim( (string) $item['text'] ) : '';
						if ( '' === $t ) {
							continue;
						}
						?>
					<li class="htoeau-professor-study__list-item">
						<?php echo $this->render_molecule_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php echo esc_html( $t ); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<?php if ( ! empty( $s['footnote'] ) ) : ?>
				<p class="htoeau-professor-study__footnote"><?php echo esc_html( (string) $s['footnote'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $s['cta_text'] ) ) : ?>
				<div class="htoeau-professor-study__cta-wrap">
					<?php
					echo render_cta_button(
						(string) $s['cta_text'],
						! empty( $link['url'] ) ? (string) $link['url'] : '#',
						[
							'class'        => 'htoeau-professor-study__cta',
							'is_external'  => ! empty( $link['is_external'] ),
							'nofollow'     => ! empty( $link['nofollow'] ),
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
