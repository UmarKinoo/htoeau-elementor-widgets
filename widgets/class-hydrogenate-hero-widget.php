<?php
/**
 * Hydrogenate Hero — Figma node 1:2289 (Hero B / Desktop). Single hero image + copy/CTA.
 *
 * @package HtoEAU_Widgets
 */

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;
use function HtoEAU_Widgets\render_check_icon;
use function HtoEAU_Widgets\render_cta_button;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hydrogenate_Hero_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_hydrogenate_hero';
	}

	public function get_title(): string {
		return __( 'HtoEAU Hydrogenate Hero', 'htoeau-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-call-to-action';
	}

	public function get_keywords(): array {
		return [ 'hero', 'hydrogenate', 'gradient', 'htoeau', 'banner', 'cans' ];
	}

	/**
	 * Default bundled asset for Elementor media control.
	 *
	 * @param string $filename File under assets/images/.
	 * @return array<string, string>
	 */
	private static function default_image( string $filename ): array {
		return [
			'url' => HTOEAU_WIDGETS_URL . 'assets/images/' . $filename,
			'id'  => '',
		];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_visual',
			[
				'label' => __( 'Product visual', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hero_image',
			[
				'label'       => __( 'Image', 'htoeau-widgets' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Single hero image (e.g. composite ~652×730). Leave empty to keep using a legacy layered image until you upload one composite here.', 'htoeau-widgets' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'heading',
			[
				'label'   => __( 'Heading', 'htoeau-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Your Next Peak Starts Now.', 'htoeau-widgets' ),
			]
		);

		$this->add_control(
			'lead',
			[
				'label'   => __( 'Lead paragraph', 'htoeau-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'Precision-engineered hydrogen hydration developed for modern performance routines.', 'htoeau-widgets' ),
			]
		);

		$rep = new Repeater();
		$rep->add_control(
			'text',
			[
				'label' => __( 'Line', 'htoeau-widgets' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'features',
			[
				'label'       => __( 'Feature lines', 'htoeau-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => [
					[ 'text' => __( 'Minimum 5 mg/L dissolved hydrogen', 'htoeau-widgets' ) ],
					[ 'text' => __( '30-day money-back guarantee', 'htoeau-widgets' ) ],
					[ 'text' => __( 'Delivered directly to your door', 'htoeau-widgets' ) ],
				],
			]
		);

		$this->add_control(
			'cta_text',
			[
				'label'   => __( 'Button label', 'htoeau-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Get Mine Now', 'htoeau-widgets' ),
			]
		);

		$this->add_control(
			'cta_link',
			[
				'label'       => __( 'Button link', 'htoeau-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => '/shop/',
				'default'     => [ 'url' => '#' ],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$s        = $this->get_settings_for_display();
		$link     = isset( $s['cta_link'] ) && is_array( $s['cta_link'] ) ? $s['cta_link'] : [];
		$cta_url  = isset( $link['url'] ) ? (string) $link['url'] : '#';
		$heading  = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$lead     = isset( $s['lead'] ) ? trim( (string) $s['lead'] ) : '';
		$features = isset( $s['features'] ) && is_array( $s['features'] ) ? $s['features'] : [];

		// Pre–1.4.5 widgets used `text` + `items` + single `image`.
		if ( '' === $lead && ! empty( $s['text'] ) ) {
			$lead = trim( (string) $s['text'] );
		}
		if ( empty( $features ) && ! empty( $s['items'] ) && is_array( $s['items'] ) ) {
			$features = $s['items'];
		}

		$image_url = $this->resolve_display_image_url( $s );

		$title_id = 'htoeau-hydrogenate-title-' . $this->get_id();
		?>
		<section
			class="htoeau-hydrogenate"
			<?php if ( '' !== $heading ) : ?>
				aria-labelledby="<?php echo esc_attr( $title_id ); ?>"
			<?php else : ?>
				aria-label="<?php echo esc_attr__( 'Hydrogen water hero', 'htoeau-widgets' ); ?>"
			<?php endif; ?>
		>
			<div class="htoeau-hydrogenate__inner">
				<div class="htoeau-hydrogenate__content">
					<?php if ( '' !== $heading ) : ?>
						<h2 class="htoeau-hydrogenate__title" id="<?php echo esc_attr( $title_id ); ?>">
							<?php echo esc_html( $heading ); ?>
						</h2>
					<?php endif; ?>

					<?php if ( '' !== $lead ) : ?>
						<p class="htoeau-hydrogenate__lead"><?php echo esc_html( $lead ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $features ) ) : ?>
						<ul class="htoeau-hydrogenate__list">
							<?php foreach ( $features as $row ) : ?>
								<?php
								$line = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
								if ( '' === $line ) {
									continue;
								}
								?>
								<li class="htoeau-hydrogenate__item">
									<span class="htoeau-hydrogenate__item-icon" aria-hidden="true">
										<?php echo render_check_icon( '30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
									<span class="htoeau-hydrogenate__item-text"><?php echo esc_html( $line ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php
					$cta_label = isset( $s['cta_text'] ) ? trim( (string) $s['cta_text'] ) : '';
					if ( '' !== $cta_label ) :
						?>
					<div class="htoeau-hydrogenate__actions">
						<?php
						echo render_cta_button(
							$cta_label,
							$cta_url,
							[
								'class'       => 'htoeau-btn--outline htoeau-hydrogenate__cta',
								'is_external' => ! empty( $link['is_external'] ),
								'nofollow'    => ! empty( $link['nofollow'] ),
							]
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
						<?php
					endif;
					?>
				</div>

				<?php if ( '' !== $image_url ) : ?>
					<div class="htoeau-hydrogenate__visual">
						<img
							class="htoeau-hydrogenate__img"
							src="<?php echo esc_url( $image_url ); ?>"
							alt=""
							width="652"
							height="730"
							loading="lazy"
							decoding="async"
						/>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}

	/**
	 * @param array<string, mixed> $media Elementor media control value.
	 */
	private function media_url( array $media ): string {
		if ( ! empty( $media['url'] ) && is_string( $media['url'] ) ) {
			return $media['url'];
		}
		return '';
	}

	/**
	 * `hero_image` when set; else legacy keys; else bundled default asset.
	 *
	 * @param array<string, mixed> $s Widget settings.
	 */
	private function resolve_display_image_url( array $s ): string {
		$url = $this->media_url( isset( $s['hero_image'] ) && is_array( $s['hero_image'] ) ? $s['hero_image'] : [] );
		if ( '' !== $url ) {
			return $url;
		}
		foreach ( [ 'image_front', 'image_rear_left', 'image_rear_right', 'image_legacy', 'image' ] as $key ) {
			$url = $this->media_url( isset( $s[ $key ] ) && is_array( $s[ $key ] ) ? $s[ $key ] : [] );
			if ( '' !== $url ) {
				return $url;
			}
		}
		return HTOEAU_WIDGETS_URL . 'assets/images/hydrogenate-can-front.png';
	}
}
