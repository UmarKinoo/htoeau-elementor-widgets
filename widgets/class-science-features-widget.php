<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

use function HtoEAU_Widgets\render_check_icon_soft;
use function HtoEAU_Widgets\render_cta_button;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Science-driven feature cards (Hydrogen / Deuterium / Combined).
 * Figma: 86:211 desktop; mobile 86:820 — https://www.figma.com/design/FcOeKFswrs0fJXLmrEvev6/Untitled?node-id=86-820
 */
class Science_Features_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_science_features';
	}

	public function get_title(): string {
		return 'HtoEAU Science Features';
	}

	public function get_icon(): string {
		return 'eicon-info-box';
	}

	public function get_keywords(): array {
		return [ 'science', 'features', 'cards', 'hydrogen', 'htoeau' ];
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
			'default' => "The science-driven approach \nto advanced hydration and performance",
			'rows'    => 3,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_card_1', [
			'label' => 'Card — Hydrogen (top left)',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'card1_label', [
			'label'   => 'Eyebrow',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Hydrogen',
		] );

		$this->add_control( 'card1_label_tone', [
			'label'   => 'Eyebrow color',
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'cyan' => 'Cyan (#70EDFF)',
				'gold' => 'Gold (#FFEDB1)',
				'mint' => 'Mint (#70F2C2)',
			],
			'default' => 'cyan',
		] );

		$this->add_control( 'card1_title', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => "The Smallest Molecule.\nA New Frontier in Hydration.",
			'rows'    => 2,
		] );

		$this->add_control( 'card1_intro', [
			'label'   => 'Intro',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Molecular hydrogen is increasingly studied for its potential role in human physiology. Its small size allows it to dissolve into water when infused using precision-controlled technology.',
			'rows'    => 3,
		] );

		$bullets1 = new Repeater();
		$bullets1->add_control( 'text', [
			'label'   => 'Text',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => '',
		] );

		$this->add_control( 'card1_bullets', [
			'label'       => 'Bullets',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $bullets1->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Evaluated in controlled human research' ],
				[ 'text' => 'Precision infusion' ],
				[ 'text' => 'Clean, stimulant-free hydration' ],
			],
		] );

		$this->add_control( 'card1_footer', [
			'label'   => 'Closing paragraph',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'By infusing pure molecular hydrogen into water, we give your cells immediate access to this high-performance fuel — no pills, no guesswork, just hydration that works.',
			'rows'    => 3,
		] );

		$this->add_control( 'card1_cta_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Shop Hydrogen Water',
		] );

		$this->add_control( 'card1_cta_link', [
			'label' => 'Button link',
			'type'  => Controls_Manager::URL,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_card_2', [
			'label' => 'Card — Deuterium (top right)',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'card2_label', [
			'label'   => 'Eyebrow',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Deuterium',
		] );

		$this->add_control( 'card2_label_tone', [
			'label'   => 'Eyebrow color',
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'cyan' => 'Cyan (#70EDFF)',
				'gold' => 'Gold (#FFEDB1)',
				'mint' => 'Mint (#70F2C2)',
			],
			'default' => 'gold',
		] );

		$this->add_control( 'card2_title', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => "Refined Through\nPrecision Distillation.",
			'rows'    => 2,
		] );

		$this->add_control( 'card2_intro', [
			'label'   => 'Intro',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Deuterium is a naturally occurring form of hydrogen present in all water. HtoEAU® Deuterium-Depleted Water undergoes specialised distillation to reduce deuterium concentration and deliver refined hydration.',
			'rows'    => 3,
		] );

		$bullets2 = new Repeater();
		$bullets2->add_control( 'text', [
			'label'   => 'Text',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => '',
		] );

		$this->add_control( 'card2_bullets', [
			'label'       => 'Bullets',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $bullets2->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Precision-distilled to reduce deuterium levels' ],
				[ 'text' => 'Produced under controlled quality standards' ],
				[ 'text' => 'Clean, additive-free hydration' ],
				[ 'text' => 'Exceptional purity and consistency' ],
			],
		] );

		$this->add_control( 'card2_cta_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Shop Hydrogen Water',
		] );

		$this->add_control( 'card2_cta_link', [
			'label' => 'Button link',
			'type'  => Controls_Manager::URL,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_card_3', [
			'label' => 'Card — Combined (bottom)',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'card3_label', [
			'label'   => 'Eyebrow',
			'type'    => Controls_Manager::TEXT,
			'default' => 'hydrogen infused & deuterium depleted',
		] );

		$this->add_control( 'card3_label_tone', [
			'label'   => 'Eyebrow color',
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'cyan' => 'Cyan (#70EDFF)',
				'gold' => 'Gold (#FFEDB1)',
				'mint' => 'Mint (#70F2C2)',
			],
			'default' => 'mint',
		] );

		$this->add_control( 'card3_title', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Two Advanced Technologies. One Refined Hydration System.',
			'rows'    => 2,
		] );

		$this->add_control( 'card3_intro', [
			'label'   => 'Intro',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Molecular hydrogen infusion and deuterium-depleted water represent two complementary approaches to advanced hydration. Together, they create a next-generation water developed for performance-focused lifestyles.',
			'rows'    => 3,
		] );

		$this->add_control( 'card3_cta_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Hydrogen-infused DDW',
		] );

		$this->add_control( 'card3_cta_link', [
			'label' => 'Button link',
			'type'  => Controls_Manager::URL,
		] );

		$bullets3 = new Repeater();
		$bullets3->add_control( 'text', [
			'label'   => 'Text',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => '',
		] );

		$this->add_control( 'card3_bullets', [
			'label'       => 'Bullets (right column)',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $bullets3->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => "Infused with molecular hydrogen using precision-\ncontrolled technology" ],
				[ 'text' => 'Produced using deuterium-depleted water' ],
				[ 'text' => 'Evaluated in emerging performance research' ],
				[ 'text' => 'Developed for maximum benefits' ],
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
				'top'    => '60',
				'right'  => '40',
				'bottom' => '60',
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
				'{{WRAPPER}} .htoeau-science' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @param 'cyan'|'gold'|'mint' $tone
	 */
	private function eyebrow_class( string $tone ): string {
		$map = [
			'cyan' => 'htoeau-science__eyebrow--cyan',
			'gold' => 'htoeau-science__eyebrow--gold',
			'mint' => 'htoeau-science__eyebrow--mint',
		];
		return $map[ $tone ] ?? $map['cyan'];
	}

	/**
	 * @param array<int,array<string,mixed>> $items
	 */
	private function render_bullet_list( array $items ): void {
		if ( empty( $items ) ) {
			return;
		}
		?>
		<ul class="htoeau-science__bullets">
			<?php foreach ( $items as $row ) {
				$text = isset( $row['text'] ) ? (string) $row['text'] : '';
				if ( '' === trim( $text ) ) {
					continue;
				}
				?>
			<li class="htoeau-science__bullet">
				<?php echo render_check_icon_soft( '30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="htoeau-science__bullet-text"><?php echo nl2br( esc_html( $text ) ); ?></span>
			</li>
				<?php
			} ?>
		</ul>
		<?php
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$heading = $s['heading'] ?? '';
		$h_lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $heading ) ) );

		$card1_link = $s['card1_cta_link'] ?? [];
		$card2_link = $s['card2_cta_link'] ?? [];
		$card3_link = $s['card3_cta_link'] ?? [];
		?>
		<section class="htoeau-science">
			<div class="htoeau-science__inner">
				<?php if ( ! empty( $h_lines ) ) : ?>
				<header class="htoeau-science__header">
					<h2 class="htoeau-science__title">
						<?php
						foreach ( $h_lines as $i => $line ) {
							echo '<span class="htoeau-science__title-line">' . esc_html( $line ) . '</span>';
							if ( $i < count( $h_lines ) - 1 ) {
								echo '<br />';
							}
						}
						?>
					</h2>
				</header>
				<?php endif; ?>

				<div class="htoeau-science__grid">
					<div class="htoeau-science__row htoeau-science__row--two">
						<article class="htoeau-science__card">
							<div class="htoeau-science__card-main">
								<div class="htoeau-science__card-lead">
								<?php if ( ! empty( $s['card1_label'] ) ) : ?>
								<p class="htoeau-science__eyebrow htoeau-science__eyebrow--caps <?php echo esc_attr( $this->eyebrow_class( (string) ( $s['card1_label_tone'] ?? 'cyan' ) ) ); ?>">
									<?php echo esc_html( (string) $s['card1_label'] ); ?>
								</p>
								<?php endif; ?>
								<?php if ( ! empty( $s['card1_title'] ) ) : ?>
								<h3 class="htoeau-science__card-title"><?php echo esc_html( str_replace( [ "\r\n", "\r" ], "\n", (string) $s['card1_title'] ) ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $s['card1_intro'] ) ) : ?>
								<p class="htoeau-science__card-intro"><?php echo esc_html( $s['card1_intro'] ); ?></p>
								<?php endif; ?>
								</div>
								<?php $this->render_bullet_list( $s['card1_bullets'] ?? [] ); ?>
								<?php if ( ! empty( $s['card1_footer'] ) ) : ?>
								<p class="htoeau-science__card-footer"><?php echo esc_html( $s['card1_footer'] ); ?></p>
								<?php endif; ?>
							</div>
							<?php if ( ! empty( $s['card1_cta_text'] ) ) : ?>
							<div class="htoeau-science__card-cta">
								<?php
								echo render_cta_button(
									(string) $s['card1_cta_text'],
									! empty( $card1_link['url'] ) ? (string) $card1_link['url'] : '#',
									[
										'class'        => 'htoeau-science__btn',
										'is_external'  => ! empty( $card1_link['is_external'] ),
										'nofollow'     => ! empty( $card1_link['nofollow'] ),
									]
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
							<?php endif; ?>
						</article>

						<article class="htoeau-science__card htoeau-science__card--tall">
							<div class="htoeau-science__card-main">
								<div class="htoeau-science__card-lead">
								<?php if ( ! empty( $s['card2_label'] ) ) : ?>
								<p class="htoeau-science__eyebrow htoeau-science__eyebrow--caps <?php echo esc_attr( $this->eyebrow_class( (string) ( $s['card2_label_tone'] ?? 'gold' ) ) ); ?>">
									<?php echo esc_html( (string) $s['card2_label'] ); ?>
								</p>
								<?php endif; ?>
								<?php if ( ! empty( $s['card2_title'] ) ) : ?>
								<h3 class="htoeau-science__card-title"><?php echo esc_html( str_replace( [ "\r\n", "\r" ], "\n", (string) $s['card2_title'] ) ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $s['card2_intro'] ) ) : ?>
								<p class="htoeau-science__card-intro"><?php echo esc_html( $s['card2_intro'] ); ?></p>
								<?php endif; ?>
								</div>
								<?php $this->render_bullet_list( $s['card2_bullets'] ?? [] ); ?>
							</div>
							<?php if ( ! empty( $s['card2_cta_text'] ) ) : ?>
							<div class="htoeau-science__card-cta">
								<?php
								echo render_cta_button(
									(string) $s['card2_cta_text'],
									! empty( $card2_link['url'] ) ? (string) $card2_link['url'] : '#',
									[
										'class'        => 'htoeau-science__btn',
										'is_external'  => ! empty( $card2_link['is_external'] ),
										'nofollow'     => ! empty( $card2_link['nofollow'] ),
									]
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
							<?php endif; ?>
						</article>
					</div>

					<article class="htoeau-science__card htoeau-science__card--wide">
						<div class="htoeau-science__wide-left">
							<div class="htoeau-science__card-lead">
							<?php if ( ! empty( $s['card3_label'] ) ) : ?>
							<p class="htoeau-science__eyebrow htoeau-science__eyebrow--sentence <?php echo esc_attr( $this->eyebrow_class( (string) ( $s['card3_label_tone'] ?? 'mint' ) ) ); ?>">
								<?php echo esc_html( (string) $s['card3_label'] ); ?>
							</p>
							<?php endif; ?>
							<?php if ( ! empty( $s['card3_title'] ) ) : ?>
							<h3 class="htoeau-science__card-title"><?php echo esc_html( $s['card3_title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $s['card3_intro'] ) ) : ?>
							<p class="htoeau-science__card-intro"><?php echo esc_html( $s['card3_intro'] ); ?></p>
							<?php endif; ?>
							</div>
							<?php if ( ! empty( $s['card3_cta_text'] ) ) : ?>
							<div class="htoeau-science__card-cta htoeau-science__card-cta--inline">
								<?php
								echo render_cta_button(
									(string) $s['card3_cta_text'],
									! empty( $card3_link['url'] ) ? (string) $card3_link['url'] : '#',
									[
										'class'        => 'htoeau-science__btn htoeau-science__btn--narrow',
										'is_external'  => ! empty( $card3_link['is_external'] ),
										'nofollow'     => ! empty( $card3_link['nofollow'] ),
									]
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
							<?php endif; ?>
						</div>
						<div class="htoeau-science__wide-right">
							<?php $this->render_bullet_list( $s['card3_bullets'] ?? [] ); ?>
						</div>
					</article>
				</div>
			</div>
		</section>
		<?php
	}
}
