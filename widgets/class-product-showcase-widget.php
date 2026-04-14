<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Figma node 86:106 — "Driven by Science" product grid + trust strip.
 * Products: manual repeater or WooCommerce query (same card layout).
 */
class Product_Showcase_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_product_showcase';
	}

	public function get_title(): string {
		return 'HtoEAU Product Showcase';
	}

	public function get_icon(): string {
		return 'eicon-products';
	}

	public function get_keywords(): array {
		return [ 'products', 'cards', 'shop', 'woocommerce', 'htoeau', 'science' ];
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		$this->start_controls_section( 'section_heading', [
			'label' => 'Section Heading',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => 'Heading',
			'type'    => Controls_Manager::TEXTAREA,
			'default' => "Driven by Science. Engineered for Modern Performance.",
			'rows'    => 2,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_products', [
			'label' => 'Product Cards',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'product_source', [
			'label'   => 'Source',
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'manual'      => __( 'Manual (repeater)', 'htoeau-widgets' ),
				'woocommerce' => __( 'WooCommerce products', 'htoeau-widgets' ),
			],
			'default' => 'manual',
		] );

		/* ── WooCommerce ── */
		if ( class_exists( 'WooCommerce' ) ) {
			$this->add_control( 'wc_query', [
				'label'     => __( 'Query', 'htoeau-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'recent'   => __( 'Recent products', 'htoeau-widgets' ),
					'featured' => __( 'Featured', 'htoeau-widgets' ),
					'sale'     => __( 'On sale', 'htoeau-widgets' ),
					'category' => __( 'By category', 'htoeau-widgets' ),
					'ids'      => __( 'By product IDs', 'htoeau-widgets' ),
				],
				'default'   => 'recent',
				'condition' => [ 'product_source' => 'woocommerce' ],
			] );

			$cat_options = [ '' => __( '— Select —', 'htoeau-widgets' ) ];
			$terms       = get_terms(
				[
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
				]
			);
			if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					$cat_options[ (string) $term->term_id ] = $term->name;
				}
			}

			$this->add_control( 'wc_category', [
				'label'     => __( 'Category', 'htoeau-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $cat_options,
				'condition' => [
					'product_source' => 'woocommerce',
					'wc_query'       => 'category',
				],
			] );

			$this->add_control( 'wc_product_ids', [
				'label'       => __( 'Product IDs', 'htoeau-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Comma-separated WooCommerce product IDs (order preserved).', 'htoeau-widgets' ),
				'placeholder' => '12, 34, 56',
				'condition'   => [
					'product_source' => 'woocommerce',
					'wc_query'       => 'ids',
				],
			] );

			$this->add_control( 'wc_limit', [
				'label'     => __( 'Max products', 'htoeau-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'min'       => 1,
				'max'       => 12,
				'step'      => 1,
				'condition' => [
					'product_source' => 'woocommerce',
					'wc_query!'      => 'ids',
				],
			] );

			$this->add_control( 'wc_orderby', [
				'label'     => __( 'Order by', 'htoeau-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'date'       => __( 'Date', 'htoeau-widgets' ),
					'title'      => __( 'Title', 'htoeau-widgets' ),
					'menu_order' => __( 'Menu order', 'htoeau-widgets' ),
					'popularity' => __( 'Popularity (sales)', 'htoeau-widgets' ),
					'rating'     => __( 'Rating', 'htoeau-widgets' ),
					'price'      => __( 'Price', 'htoeau-widgets' ),
				],
				'default'   => 'date',
				'condition' => [
					'product_source' => 'woocommerce',
					'wc_query!'      => 'ids',
				],
			] );

			$this->add_control( 'wc_order', [
				'label'     => __( 'Order', 'htoeau-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'DESC' => __( 'Descending', 'htoeau-widgets' ),
					'ASC'  => __( 'Ascending', 'htoeau-widgets' ),
				],
				'default'   => 'DESC',
				'condition' => [
					'product_source' => 'woocommerce',
					'wc_query!'      => 'ids',
				],
			] );

			$this->add_control( 'wc_description', [
				'label'     => __( 'Description from', 'htoeau-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'short' => __( 'Short description', 'htoeau-widgets' ),
					'long'  => __( 'Long description (trimmed)', 'htoeau-widgets' ),
				],
				'default'   => 'short',
				'condition' => [ 'product_source' => 'woocommerce' ],
			] );

			$this->add_control( 'wc_desc_words', [
				'label'     => __( 'Max words (long description)', 'htoeau-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 40,
				'min'       => 10,
				'max'       => 120,
				'condition' => [
					'product_source' => 'woocommerce',
					'wc_description' => 'long',
				],
			] );

			$this->add_control( 'wc_button_text', [
				'label'     => __( 'Button text', 'htoeau-widgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'View product', 'htoeau-widgets' ),
				'condition' => [ 'product_source' => 'woocommerce' ],
			] );

			$this->add_control( 'wc_use_product_rating', [
				'label'        => __( 'Use WooCommerce rating on cards', 'htoeau-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'When a product has reviews, show its average and count. Otherwise the static score below is used.', 'htoeau-widgets' ),
				'condition'    => [ 'product_source' => 'woocommerce' ],
			] );
		} else {
			$this->add_control( 'wc_inactive_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<p>' . esc_html__( 'WooCommerce is not active. Install and activate WooCommerce to use dynamic products.', 'htoeau-widgets' ) . '</p>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => [ 'product_source' => 'woocommerce' ],
			] );
		}

		$this->add_control( 'show_card_rating', [
			'label'        => 'Show rating row on cards',
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'rating_score', [
			'label'     => 'Rating score',
			'type'      => Controls_Manager::TEXT,
			'default'   => '4.8',
			'condition' => [ 'show_card_rating' => 'yes' ],
		] );

		$this->add_control( 'rating_suffix', [
			'label'     => 'Rating suffix',
			'type'      => Controls_Manager::TEXT,
			'default'   => '(248 reviews)',
			'condition' => [ 'show_card_rating' => 'yes' ],
		] );

		/* ── Manual repeater ── */
		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label' => 'Product image',
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'title', [
			'label'   => 'Title',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Product name',
		] );

		$repeater->add_control( 'description', [
			'label'   => 'Description',
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => '',
		] );

		$repeater->add_control( 'price_prefix', [
			'label'   => 'Price prefix',
			'type'    => Controls_Manager::TEXT,
			'default' => 'From ',
		] );

		$repeater->add_control( 'price', [
			'label'   => 'Price',
			'type'    => Controls_Manager::TEXT,
			'default' => '£2.07 per can',
		] );

		$repeater->add_control( 'button_text', [
			'label'   => 'Button text',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Try HtoEAU Now',
		] );

		$repeater->add_control( 'button_link', [
			'label'       => 'Button link',
			'type'        => Controls_Manager::URL,
			'placeholder' => '#',
			'default'     => [ 'url' => '#' ],
		] );

		$this->add_control( 'products', [
			'label'       => 'Products',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'condition'   => [ 'product_source' => 'manual' ],
			'default'     => [
				[
					'title'       => 'HtoEAU® Hydrogen Water',
					'description' => 'HtoEAU® is infused to a minimum of 5 mg/L dissolved hydrogen at the point of filling. This level is carefully selected to ensure stability, consistency, and product integrity.',
					'price'       => '£2.07 per can',
				],
				[
					'title'       => 'HtoEAU® Deuterium-Depleted Water',
					'description' => 'Meticulously filtered and re-distilled over 50 times to ensure exceptional purity, clarity, and consistency using advanced controlled purification processes.',
					'price'       => '£2.95 per can',
				],
				[
					'title'       => 'HtoEAU® Hydrogen-Infused Deuterium-Depleted Water',
					'description' => 'Combines precision deuterium reduction with controlled molecular hydrogen infusion, delivering refined hydration through advanced purification and retention technologies.',
					'price'       => '£2.95 per can',
				],
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_trust', [
			'label' => 'Trust strip',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$trust_rep = new Repeater();
		$trust_rep->add_control( 'text', [
			'label'   => 'Text',
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$this->add_control( 'trust_items', [
			'label'       => 'Items',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $trust_rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'No sugar, no stimulants, no additives' ],
				[ 'text' => 'For athletes, biohackers and high performers' ],
				[ 'text' => 'Evaluated in controlled scientific research' ],
				[ 'text' => 'Precision-engineered hydrogen infusion' ],
				[ 'text' => 'No sugar, no stimulants, no additives' ],
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
				'top'    => '100',
				'right'  => '59',
				'bottom' => '100',
				'left'   => '59',
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
				'{{WRAPPER}} .htoeau-product-showcase' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @param array<string,mixed> $settings
	 * @return array<int,array<string,mixed>>
	 */
	private function get_card_rows_from_settings( array $settings ): array {
		$source = isset( $settings['product_source'] ) ? (string) $settings['product_source'] : 'manual';

		if ( 'woocommerce' === $source ) {
			if ( ! class_exists( 'WooCommerce' ) ) {
				return [];
			}
			return $this->query_woocommerce_cards( $settings );
		}

		return $this->normalize_manual_products( isset( $settings['products'] ) && is_array( $settings['products'] ) ? $settings['products'] : [] );
	}

	/**
	 * @param array<int,array<string,mixed>> $items
	 * @return array<int,array<string,mixed>>
	 */
	private function normalize_manual_products( array $items ): array {
		$out = [];
		foreach ( $items as $product ) {
			$title = isset( $product['title'] ) ? trim( (string) $product['title'] ) : '';
			if ( '' === $title && empty( $product['image']['url'] ?? '' ) ) {
				continue;
			}
			$link = $product['button_link'] ?? [];
			$out[] = [
				'image_url'          => ! empty( $product['image']['url'] ) ? (string) $product['image']['url'] : '',
				'title'              => $title,
				'description'        => isset( $product['description'] ) ? trim( (string) $product['description'] ) : '',
				'price_prefix'       => isset( $product['price_prefix'] ) ? (string) $product['price_prefix'] : 'From ',
				'price'              => isset( $product['price'] ) ? (string) $product['price'] : '',
				'button_text'        => isset( $product['button_text'] ) ? (string) $product['button_text'] : '',
				'button_url'         => is_array( $link ) ? (string) ( $link['url'] ?? '#' ) : '#',
				'button_is_external' => is_array( $link ) && ! empty( $link['is_external'] ),
				'button_nofollow'    => is_array( $link ) && ! empty( $link['nofollow'] ),
				'rating_score'       => null,
				'rating_suffix'      => null,
			];
		}
		return $out;
	}

	/**
	 * @param array<string,mixed> $settings
	 * @return array<int,array<string,mixed>>
	 */
	private function query_woocommerce_cards( array $settings ): array {
		$query_type = isset( $settings['wc_query'] ) ? (string) $settings['wc_query'] : 'recent';

		if ( 'ids' === $query_type ) {
			$raw = isset( $settings['wc_product_ids'] ) ? (string) $settings['wc_product_ids'] : '';
			$ids = array_values(
				array_filter(
					array_map( 'absint', array_map( 'trim', explode( ',', $raw ) ) )
				)
			);
			if ( empty( $ids ) ) {
				return [];
			}
			$rows = [];
			foreach ( $ids as $id ) {
				$p = wc_get_product( $id );
				if ( $p instanceof \WC_Product && $p->is_visible() ) {
					$row = $this->wc_product_to_card_row( $p, $settings );
					if ( null !== $row ) {
						$rows[] = $row;
					}
				}
			}
			return $rows;
		}

		$limit   = max( 1, min( 12, (int) ( $settings['wc_limit'] ?? 3 ) ) );
		$orderby = isset( $settings['wc_orderby'] ) ? (string) $settings['wc_orderby'] : 'date';
		$order   = isset( $settings['wc_order'] ) ? strtoupper( (string) $settings['wc_order'] ) : 'DESC';
		if ( ! in_array( $order, [ 'ASC', 'DESC' ], true ) ) {
			$order = 'DESC';
		}

		$args = [
			'status'  => 'publish',
			'limit'   => $limit,
			'orderby' => $orderby,
			'order'   => $order,
			'return'  => 'objects',
		];

		if ( 'featured' === $query_type ) {
			$args['featured'] = true;
		} elseif ( 'sale' === $query_type ) {
			$args['on_sale'] = true;
		} elseif ( 'category' === $query_type ) {
			if ( empty( $settings['wc_category'] ) ) {
				return [];
			}
			$term_id = absint( $settings['wc_category'] );
			$term    = get_term( $term_id, 'product_cat' );
			if ( ! $term || is_wp_error( $term ) ) {
				return [];
			}
			$args['category'] = [ $term->slug ];
		}

		$products = function_exists( 'wc_get_products' ) ? wc_get_products( $args ) : [];

		$rows = [];
		foreach ( $products as $p ) {
			if ( ! $p instanceof \WC_Product || ! $p->is_visible() ) {
				continue;
			}
			$row = $this->wc_product_to_card_row( $p, $settings );
			if ( null !== $row ) {
				$rows[] = $row;
			}
		}

		return $rows;
	}

	/**
	 * @param array<string,mixed> $settings
	 * @return array<string,mixed>|null
	 */
	private function wc_product_to_card_row( \WC_Product $product, array $settings ): ?array {
		$title = $product->get_name();
		if ( '' === $title ) {
			return null;
		}

		$img_id  = $product->get_image_id();
		$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : '';
		if ( ! $img_url && function_exists( 'wc_placeholder_img_src' ) ) {
			$img_url = wc_placeholder_img_src( 'large' );
		}

		$desc_source = isset( $settings['wc_description'] ) ? (string) $settings['wc_description'] : 'short';
		if ( 'long' === $desc_source ) {
			$words = max( 10, (int) ( $settings['wc_desc_words'] ?? 40 ) );
			$long  = $product->get_description();
			$desc  = $long ? wp_trim_words( wp_strip_all_tags( $long ), $words, '…' ) : '';
			if ( '' === $desc ) {
				$desc = wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), $words, '…' );
			}
		} else {
			$desc = wp_strip_all_tags( $product->get_short_description() );
		}

		$price_prefix = '';
		$price_text   = '';
		if ( $product->is_type( 'variable' ) ) {
			$price_prefix = __( 'From ', 'htoeau-widgets' );
			$min          = $product->get_variation_price( 'min', true );
			$price_text   = $min ? wp_strip_all_tags( wc_price( $min ) ) : '';
		} else {
			$price_text = wp_strip_all_tags( wc_price( $product->get_price() ) );
		}

		$btn = isset( $settings['wc_button_text'] ) && '' !== trim( (string) $settings['wc_button_text'] )
			? trim( (string) $settings['wc_button_text'] )
			: __( 'View product', 'htoeau-widgets' );

		$rating_score  = null;
		$rating_suffix = null;
		if ( 'yes' === ( $settings['wc_use_product_rating'] ?? 'yes' ) ) {
			$count = (int) $product->get_review_count();
			if ( $count > 0 ) {
				$rating_score  = (string) number_format_i18n( (float) $product->get_average_rating(), 1 );
				$rating_suffix = sprintf(
					/* translators: %d: review count */
					_n( '(%d review)', '(%d reviews)', $count, 'htoeau-widgets' ),
					$count
				);
			}
		}

		return [
			'image_url'          => $img_url ? (string) $img_url : '',
			'title'              => $title,
			'description'        => $desc,
			'price_prefix'       => $price_prefix,
			'price'              => $price_text,
			'button_text'        => $btn,
			'button_url'         => $product->get_permalink(),
			'button_is_external' => false,
			'button_nofollow'    => false,
			'rating_score'       => $rating_score,
			'rating_suffix'      => $rating_suffix,
		];
	}

	/**
	 * @param array<int,array<string,mixed>> $items
	 */
	private function render_trust_items_list( array $items ): void {
		foreach ( $items as $item ) {
			$text = isset( $item['text'] ) ? trim( (string) $item['text'] ) : '';
			if ( '' === $text ) {
				continue;
			}
			?>
			<div class="htoeau-product-showcase__trust-item">
				<?php echo \HtoEAU_Widgets\render_check_icon( '30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="htoeau-product-showcase__trust-text"><?php echo esc_html( $text ); ?></span>
			</div>
			<?php
		}
	}

	protected function render(): void {
		$s            = $this->get_settings_for_display();
		$product_rows = $this->get_card_rows_from_settings( $s );
		?>
		<section class="htoeau-product-showcase">
			<div class="htoeau-product-showcase__inner">
				<?php if ( ! empty( $s['heading'] ) ) : ?>
				<h2 class="htoeau-product-showcase__heading"><?php echo esc_html( $s['heading'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $product_rows ) ) : ?>
				<div class="htoeau-product-showcase__grid">
					<?php foreach ( $product_rows as $product ) : ?>
					<article class="htoeau-product-showcase__card">
						<?php if ( ! empty( $product['image_url'] ) ) : ?>
						<div class="htoeau-product-showcase__card-image">
							<img src="<?php echo esc_url( $product['image_url'] ); ?>" alt="<?php echo esc_attr( $product['title'] ?? '' ); ?>" loading="lazy" />
						</div>
						<?php endif; ?>

						<div class="htoeau-product-showcase__card-body">
							<?php if ( 'yes' === $s['show_card_rating'] ) : ?>
							<div class="htoeau-product-showcase__card-rating">
								<?php echo \HtoEAU_Widgets\render_stars( 1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="htoeau-product-showcase__card-rating-text">
									<strong><?php echo esc_html( null !== ( $product['rating_score'] ?? null ) ? $product['rating_score'] : ( $s['rating_score'] ?? '4.8' ) ); ?></strong>
									<?php
									$suffix = null !== ( $product['rating_suffix'] ?? null ) ? $product['rating_suffix'] : ( $s['rating_suffix'] ?? '(248 reviews)' );
									echo ' ' . esc_html( $suffix );
									?>
								</span>
							</div>
							<?php endif; ?>

							<?php if ( ! empty( $product['title'] ) ) : ?>
							<h3 class="htoeau-product-showcase__card-title"><?php echo esc_html( $product['title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $product['description'] ) ) : ?>
							<p class="htoeau-product-showcase__card-desc"><?php echo esc_html( $product['description'] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $product['price'] ) || '' !== ( $product['price_prefix'] ?? '' ) ) : ?>
							<p class="htoeau-product-showcase__card-price">
								<span class="htoeau-product-showcase__card-price-prefix"><?php echo esc_html( $product['price_prefix'] ?? 'From ' ); ?></span><strong><?php echo esc_html( $product['price'] ?? '' ); ?></strong>
							</p>
							<?php endif; ?>
						</div>

						<?php
						if ( ! empty( $product['button_text'] ) ) {
							echo \HtoEAU_Widgets\render_cta_button(
								$product['button_text'],
								$product['button_url'] ?? '#',
								[
									'class'       => 'htoeau-product-showcase__card-btn',
									'is_external' => ! empty( $product['button_is_external'] ),
									'nofollow'    => ! empty( $product['button_nofollow'] ),
								]
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</article>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $s['trust_items'] ) ) : ?>
				<div class="htoeau-product-showcase__trust htoeau-product-showcase__trust--marquee">
					<div class="htoeau-product-showcase__trust-viewport">
						<div class="htoeau-product-showcase__trust-track">
							<div class="htoeau-product-showcase__trust-group">
								<?php $this->render_trust_items_list( $s['trust_items'] ); ?>
							</div>
							<div class="htoeau-product-showcase__trust-group" aria-hidden="true">
								<?php $this->render_trust_items_list( $s['trust_items'] ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
