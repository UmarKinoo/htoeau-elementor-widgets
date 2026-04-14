<?php
/**
 * Figma node 86:529 — FAQ accordion (heading, subheading, five Q&A rows, chevrons).
 *
 * @package HtoEAU_Widgets
 */

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FAQ_Accordion_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_faq_accordion';
	}

	public function get_title(): string {
		return 'HtoEAU FAQ Accordion';
	}

	public function get_icon(): string {
		return 'eicon-accordion';
	}

	public function get_keywords(): array {
		return [ 'faq', 'accordion', 'questions', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'content',
			array(
				'label' => __( 'Content', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'htoeau-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Frequently Asked Questions', 'htoeau-widgets' ),
			)
		);

		$this->add_control(
			'subheading',
			array(
				'label'   => __( 'Subheading', 'htoeau-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Everything you need to know about hydrogen-infused hydration and the science behind HtoEAU.', 'htoeau-widgets' ),
				'rows'    => 3,
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'q',
			array(
				'label' => __( 'Question', 'htoeau-widgets' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$rep->add_control(
			'a',
			array(
				'label' => __( 'Answer', 'htoeau-widgets' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 4,
			)
		);

		$this->add_control(
			'items',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ q }}}',
				'default'     => array(
					array(
						'q' => __( 'What does it taste like?', 'htoeau-widgets' ),
						'a' => __( 'HtoEAU® tastes like exceptionally pure, clean water. Molecular hydrogen is colourless, odourless, and tasteless, so there are no added flavours or aftertaste.', 'htoeau-widgets' ),
					),
					array(
						'q' => __( 'How many cans should I drink per day?', 'htoeau-widgets' ),
						'a' => '',
					),
					array(
						'q' => __( 'Is there real science behind hydrogen-infused water?', 'htoeau-widgets' ),
						'a' => '',
					),
					array(
						'q' => __( 'How is HtoEAU® different from regular water?', 'htoeau-widgets' ),
						'a' => '',
					),
					array(
						'q' => __( 'Who is HtoEAU® designed for?', 'htoeau-widgets' ),
						'a' => '',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			array(
				'label' => __( 'Settings', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'single_expand',
			array(
				'label'        => __( 'Only one panel open at a time', 'htoeau-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'htoeau-widgets' ),
				'label_off'    => __( 'No', 'htoeau-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$heading    = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$subheading = isset( $s['subheading'] ) ? (string) $s['subheading'] : '';
		$items      = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		$single     = ! empty( $s['single_expand'] ) && 'yes' === $s['single_expand'];
		$group_name = $single ? 'htoeau-faq-' . $this->get_id() : '';

		?>
		<section class="htoeau-faq" aria-label="<?php echo esc_attr( $heading ); ?>">
			<header class="htoeau-faq__head">
				<?php if ( $heading !== '' ) : ?>
					<h2 class="htoeau-faq__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $subheading !== '' ) : ?>
					<p class="htoeau-faq__sub"><?php echo esc_html( $subheading ); ?></p>
				<?php endif; ?>
			</header>
			<div class="htoeau-faq__list">
				<?php
				foreach ( $items as $k => $i ) :
					$q = isset( $i['q'] ) ? trim( (string) $i['q'] ) : '';
					$a = isset( $i['a'] ) ? trim( (string) $i['a'] ) : '';
					if ( $q === '' ) {
						continue;
					}
					$open = ( 0 === (int) $k );
					?>
					<details class="htoeau-faq__item"<?php echo $open ? ' open' : ''; ?><?php echo $group_name !== '' ? ' name="' . esc_attr( $group_name ) . '"' : ''; ?>>
						<summary class="htoeau-faq__summary">
							<span class="htoeau-faq__summary-main">
								<span class="htoeau-faq__q"><?php echo esc_html( $q ); ?></span>
								<?php if ( $a !== '' ) : ?>
									<div class="htoeau-faq__answer"><?php echo wp_kses_post( wpautop( $a ) ); ?></div>
								<?php endif; ?>
							</span>
							<span class="htoeau-faq__chev" aria-hidden="true">
								<svg class="htoeau-faq__chev-svg" width="14" height="24" viewBox="0 0 14 24" focusable="false">
									<path d="M3.5 9.5 7 14l3.5-4.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
						</summary>
					</details>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
