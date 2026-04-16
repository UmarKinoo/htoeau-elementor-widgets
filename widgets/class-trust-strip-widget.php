<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_check_icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Figma node 86:400 — gradient trust strip with infinite marquee.
 */
class Trust_Strip_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_trust_strip';
	}

	public function get_title(): string {
		return 'HtoEAU Trust Strip';
	}

	public function get_icon(): string {
		return 'eicon-h-align-center';
	}

	public function get_keywords(): array {
		return [ 'trust', 'marquee', 'strip', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'section_content', [
			'label' => 'Items',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'text', [
			'label'   => 'Text',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$this->add_control( 'items', [
			'label'       => 'Trust items',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Leading hydrogen infusion' ],
				[ 'text' => 'For athletes, biohackers and high performers' ],
				[ 'text' => 'Evaluated in controlled scientific research' ],
				[ 'text' => 'Precision-engineered hydrogen infusion' ],
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style', [
			'label' => 'Section',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'section_padding', [
			'label'       => 'Padding',
			'description' => 'Figma frame uses py-100 (100px top + 100px bottom). Default here is 50px each (100px total) for a slimmer strip — set 100 top/bottom to match Figma exactly.',
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px' ],
			'default'     => [
				'top'    => '50',
				'right'  => '0',
				'bottom' => '50',
				'left'   => '0',
				'unit'   => 'px',
			],
			'tablet_default' => [
				'top'    => '40',
				'right'  => '24',
				'bottom' => '40',
				'left'   => '24',
				'unit'   => 'px',
			],
			'mobile_default' => [
				'top'    => '12',
				'right'  => '20',
				'bottom' => '12',
				'left'   => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .htoeau-trust-strip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @param array<int,array<string,mixed>> $items Repeater rows.
	 */
	private function render_items_list( array $items ): void {
		foreach ( $items as $item ) {
			$text = isset( $item['text'] ) ? trim( (string) $item['text'] ) : '';
			if ( '' === $text ) {
				continue;
			}
			?>
			<div class="htoeau-trust-strip__item">
				<?php echo render_check_icon( '30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="htoeau-trust-strip__text"><?php echo esc_html( $text ); ?></span>
			</div>
			<?php
		}
	}

	protected function render(): void {
		$s     = $this->get_settings_for_display();
		$items = array_filter(
			(array) ( $s['items'] ?? [] ),
			static function ( $row ) {
				$t = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
				return '' !== $t;
			}
		);

		if ( empty( $items ) ) {
			return;
		}
		?>
		<section class="htoeau-trust-strip">
			<div class="htoeau-trust-strip__marquee htoeau-trust-strip--marquee">
				<div class="htoeau-trust-strip__viewport">
					<div class="htoeau-trust-strip__track">
						<div class="htoeau-trust-strip__group">
							<?php $this->render_items_list( $items ); ?>
						</div>
						<div class="htoeau-trust-strip__group" aria-hidden="true">
							<?php $this->render_items_list( $items ); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
