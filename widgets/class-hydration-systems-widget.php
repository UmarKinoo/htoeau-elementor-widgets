<?php

namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * “Explore the HtoEAU Hydration Systems” — two product cards on off-white (no price row).
 * Figma node 1:2260 — WooCommerce: related products, cross-sells, upsells, or manual/recent/IDs.
 */
class Hydration_Systems_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_hydration_systems';
	}

	public function get_title(): string {
		return __( 'HtoEAU Hydration Systems', 'htoeau-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-products';
	}

	public function get_keywords(): array {
		return [ 'hydration', 'products', 'related', 'woocommerce', 'cards', 'htoeau' ];
	}

	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls(): void {

		$this->start_controls_section( 'section_heading', [
			'label' => __( 'Heading', 'htoeau-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'   => __( 'Title', 'htoeau-widgets' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Explore the HtoEAU Hydration Systems',
			'rows'    => 2,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_products', [
			'label' => __( 'Products', 'htoeau-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'product_source', [
			'label'   => __( 'Source', 'htoeau-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'manual'      => __( 'Manual (repeater)', 'htoeau-widgets' ),
				'woocommerce' => __( 'WooCommerce', 'htoeau-widgets' ),
			],
			'default' => 'woocommerce',
		] );

		if ( class_exists( 'WooCommerce' ) ) {
			$this->add_control( 'wc_query', [
				'label'     => __( 'Query', 'htoeau-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'related'     => __( 'Related products (current product)', 'htoeau-widgets' ),
					'cross_sells' => __( 'Cross-sells (current product)', 'htoeau-widgets' ),
					'upsells'     => __( 'Upsells (current product)', 'htoeau-widgets' ),
					'recent'      => __( 'Recent products', 'htoeau-widgets' ),
					'featured'    => __( 'Featured', 'htoeau-widgets' ),
					'sale'        => __( 'On sale', 'htoeau-widgets' ),
					'category'    => __( 'By category', 'htoeau-widgets' ),
					'ids'         => __( 'By product IDs', 'htoeau-widgets' ),
				],
				'default'   => 'related',
				'condition' => [ 'product_source' => 'woocommerce' ],
			] );

			$this->add_control( 'wc_context_product_id', [
				'label'       => __( 'Context product ID (optional)', 'htoeau-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Only if Related / Upsells / Cross-sells stay empty: set the current product’s numeric ID, or use Dynamic Tags → Post ID on a Single Product template. Leave blank to auto-detect.', 'htoeau-widgets' ),
				'placeholder' => '',
				'condition'   => [ 'product_source' => 'woocommerce' ],
			] );

			$this->add_control( 'wc_related_fallback_ids', [
				'label'       => __( 'Fallback product IDs', 'htoeau-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Comma-separated. Used when Related / Cross-sells / Upsells still return no products after context detection.', 'htoeau-widgets' ),
				'placeholder' => '12, 34',
				'condition'   => [ 'product_source' => 'woocommerce' ],
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
				'description' => __( 'Comma-separated (max 2 shown). Order preserved.', 'htoeau-widgets' ),
				'placeholder' => '12, 34',
				'condition'   => [
					'product_source' => 'woocommerce',
					'wc_query'       => 'ids',
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
				'default'   => 'Try HtoEAU Now',
				'condition' => [ 'product_source' => 'woocommerce' ],
			] );

			$this->add_control( 'wc_use_product_rating', [
				'label'        => __( 'Use WooCommerce rating on cards', 'htoeau-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'When a product has reviews, show average and count. Otherwise use the static values below.', 'htoeau-widgets' ),
				'condition'    => [ 'product_source' => 'woocommerce' ],
			] );
		} else {
			$this->add_control( 'wc_inactive_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<p>' . esc_html__( 'WooCommerce is not active. Use manual repeater or activate WooCommerce.', 'htoeau-widgets' ) . '</p>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => [ 'product_source' => 'woocommerce' ],
			] );
		}

		$this->add_control( 'show_card_rating', [
			'label'        => __( 'Show rating row', 'htoeau-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'rating_score', [
			'label'     => __( 'Rating score (fallback)', 'htoeau-widgets' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '4.8',
			'condition' => [ 'show_card_rating' => 'yes' ],
		] );

		$this->add_control( 'rating_suffix', [
			'label'     => __( 'Rating suffix (fallback)', 'htoeau-widgets' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '(248 reviews)',
			'condition' => [ 'show_card_rating' => 'yes' ],
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label' => __( 'Image', 'htoeau-widgets' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'title', [
			'label'   => __( 'Title', 'htoeau-widgets' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$repeater->add_control( 'description', [
			'label'   => __( 'Description', 'htoeau-widgets' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => '',
		] );

		$repeater->add_control( 'button_text', [
			'label'   => __( 'Button text', 'htoeau-widgets' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Try HtoEAU Now',
		] );

		$repeater->add_control( 'button_link', [
			'label'       => __( 'Button link', 'htoeau-widgets' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => '#',
			'default'     => [ 'url' => '#' ],
		] );

		$this->add_control( 'products', [
			'label'       => __( 'Cards', 'htoeau-widgets' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'condition'   => [ 'product_source' => 'manual' ],
			'default'     => [
				[
					'image'       => [ 'url' => 'https://www.figma.com/api/mcp/asset/44f07ff0-49bf-4082-b93e-ac8cf0c41f53' ],
					'title'       => 'HtoEAU® Deuterium-Depleted Water',
					'description' => 'Ultra-purified water re-distilled over 50 times to reduce naturally occurring deuterium and deliver exceptional purity.',
					'button_text' => 'Try HtoEAU Now',
				],
				[
					'image'       => [ 'url' => 'https://www.figma.com/api/mcp/asset/2904c46b-7c60-4195-a2c6-fcdfbe866b72' ],
					'title'       => 'HtoEAU® Hydrogen-Infused Deuterium-Depleted Water',
					'description' => 'Combines deuterium-depleted water with precision hydrogen infusion to deliver both technologies in one advanced hydration system.',
					'button_text' => 'Try HtoEAU Now',
				],
			],
		] );

		$this->end_controls_section();
	}

	private function register_style_controls(): void {
		$this->start_controls_section( 'section_style', [
			'label' => __( 'Section', 'htoeau-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'section_padding', [
			'label'      => __( 'Padding', 'htoeau-widgets' ),
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
				'{{WRAPPER}} .htoeau-hydration-systems' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @param array<string,mixed> $settings
	 * @return array<int,array<string,mixed>>
	 */
	private function get_card_rows( array $settings ): array {
		$source = isset( $settings['product_source'] ) ? (string) $settings['product_source'] : 'woocommerce';

		if ( 'woocommerce' === $source && class_exists( 'WooCommerce' ) ) {
			return $this->query_woocommerce_rows( $settings );
		}

		return $this->normalize_manual_rows( isset( $settings['products'] ) && is_array( $settings['products'] ) ? $settings['products'] : [] );
	}

	/**
	 * @param array<int,array<string,mixed>> $items
	 * @return array<int,array<string,mixed>>
	 */
	private function normalize_manual_rows( array $items ): array {
		$out = [];
		foreach ( array_slice( $items, 0, 2 ) as $row ) {
			$title = isset( $row['title'] ) ? trim( (string) $row['title'] ) : '';
			if ( '' === $title && empty( $row['image']['url'] ?? '' ) ) {
				continue;
			}
			$link = $row['button_link'] ?? [];
			$out[] = [
				'image_url'          => ! empty( $row['image']['url'] ) ? (string) $row['image']['url'] : '',
				'title'              => $title,
				'description'        => isset( $row['description'] ) ? trim( (string) $row['description'] ) : '',
				'button_text'        => isset( $row['button_text'] ) ? (string) $row['button_text'] : 'Try HtoEAU Now',
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
	 * @return array<int,int>
	 */
	private function parse_id_list( string $raw ): array {
		$raw = trim( $raw );
		if ( '' === $raw ) {
			return [];
		}
		return array_values(
			array_filter(
				array_map( 'absint', array_map( 'trim', explode( ',', $raw ) ) )
			)
		);
	}

	/**
	 * Product ID for related / upsells / cross-sells.
	 * Elementor Theme Builder and some themes do not leave is_product() true or queried object as the product — prefer global $product and fallbacks.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 */
	private function get_context_product_id( array $settings ): int {
		$raw = isset( $settings['wc_context_product_id'] ) ? trim( (string) $settings['wc_context_product_id'] ) : '';
		if ( '' !== $raw ) {
			if ( is_numeric( $raw ) ) {
				$oid = absint( $raw );
			} else {
				// Dynamic tags or pasted text: take first contiguous number.
				$oid = absint( preg_replace( '/\D/', '', $raw ) );
			}
			if ( $oid && wc_get_product( $oid ) instanceof \WC_Product ) {
				return $oid;
			}
		}

		$resolved = 0;

		global $product;
		if ( $product instanceof \WC_Product ) {
			$resolved = (int) $product->get_id();
		}

		if ( ! $resolved ) {
			global $wp_query;
			if ( $wp_query instanceof \WP_Query && $wp_query->is_singular( 'product' ) ) {
				$qid = (int) $wp_query->get_queried_object_id();
				if ( $qid && 'product' === get_post_type( $qid ) ) {
					$resolved = $qid;
				}
			}
		}

		if ( ! $resolved && function_exists( 'is_singular' ) && is_singular( 'product' ) ) {
			$qid = (int) get_queried_object_id();
			if ( $qid && 'product' === get_post_type( $qid ) ) {
				$resolved = $qid;
			}
		}

		if ( ! $resolved && function_exists( 'is_product' ) && is_product() ) {
			$resolved = (int) get_queried_object_id();
		}

		if ( ! $resolved ) {
			$post = get_post();
			if ( $post && 'product' === $post->post_type ) {
				$resolved = (int) $post->ID;
			}
		}

		if ( ! $resolved ) {
			$tid = (int) get_the_ID();
			if ( $tid && 'product' === get_post_type( $tid ) ) {
				$resolved = $tid;
			}
		}

		if ( $resolved && ! wc_get_product( $resolved ) instanceof \WC_Product ) {
			$resolved = 0;
		}

		/**
		 * Filter the product ID used as context for related / upsells / cross-sells.
		 *
		 * @param int                  $resolved Resolved ID or 0.
		 * @param array<string,mixed> $settings  Widget settings.
		 */
		return (int) apply_filters( 'htoeau_hydration_systems_context_product_id', $resolved, $settings );
	}

	/**
	 * @param array<int,int>      $ids
	 * @param array<string,mixed> $settings
	 * @return array<int,array<string,mixed>>
	 */
	private function rows_from_product_ids( array $ids, array $settings ): array {
		$ids = array_slice( array_values( array_unique( $ids ) ), 0, 2 );
		$rows = [];
		foreach ( $ids as $id ) {
			$p = wc_get_product( $id );
			if ( $p instanceof \WC_Product && $p->is_visible() ) {
				$row = $this->wc_product_to_row( $p, $settings );
				if ( null !== $row ) {
					$rows[] = $row;
				}
			}
		}
		return $rows;
	}

	/**
	 * @param array<string,mixed> $settings
	 * @return array<int,array<string,mixed>>
	 */
	private function query_woocommerce_rows( array $settings ): array {
		$query_type = isset( $settings['wc_query'] ) ? (string) $settings['wc_query'] : 'related';
		$limit      = 2;

		if ( 'ids' === $query_type ) {
			$ids = $this->parse_id_list( isset( $settings['wc_product_ids'] ) ? (string) $settings['wc_product_ids'] : '' );
			return $this->rows_from_product_ids( $ids, $settings );
		}

		$current_id = $this->get_context_product_id( $settings );

		if ( 'related' === $query_type ) {
			$ids = [];
			if ( $current_id && function_exists( 'wc_get_related_products' ) ) {
				$ids = wc_get_related_products( $current_id, $limit, [], [] );
			}
			if ( empty( $ids ) ) {
				$ids = $this->parse_id_list( isset( $settings['wc_related_fallback_ids'] ) ? (string) $settings['wc_related_fallback_ids'] : '' );
				$ids = array_slice( $ids, 0, $limit );
			}
			return $this->rows_from_product_ids( $ids, $settings );
		}

		if ( 'cross_sells' === $query_type ) {
			$ids = [];
			if ( $current_id ) {
				$p = wc_get_product( $current_id );
				if ( $p instanceof \WC_Product ) {
					$ids = array_map( 'absint', $p->get_cross_sell_ids() );
				}
			}
			if ( empty( $ids ) ) {
				$ids = $this->parse_id_list( isset( $settings['wc_related_fallback_ids'] ) ? (string) $settings['wc_related_fallback_ids'] : '' );
				$ids = array_slice( $ids, 0, $limit );
			} else {
				$ids = array_slice( $ids, 0, $limit );
			}
			return $this->rows_from_product_ids( $ids, $settings );
		}

		if ( 'upsells' === $query_type ) {
			$ids = [];
			if ( $current_id ) {
				$p = wc_get_product( $current_id );
				if ( $p instanceof \WC_Product ) {
					$ids = array_map( 'absint', $p->get_upsell_ids() );
				}
			}
			if ( empty( $ids ) ) {
				$ids = $this->parse_id_list( isset( $settings['wc_related_fallback_ids'] ) ? (string) $settings['wc_related_fallback_ids'] : '' );
				$ids = array_slice( $ids, 0, $limit );
			} else {
				$ids = array_slice( $ids, 0, $limit );
			}
			return $this->rows_from_product_ids( $ids, $settings );
		}

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
		$rows     = [];
		foreach ( $products as $p ) {
			if ( ! $p instanceof \WC_Product || ! $p->is_visible() ) {
				continue;
			}
			$row = $this->wc_product_to_row( $p, $settings );
			if ( null !== $row ) {
				$rows[] = $row;
			}
			if ( count( $rows ) >= $limit ) {
				break;
			}
		}

		return $rows;
	}

	/**
	 * @param array<string,mixed> $settings
	 * @return array<string,mixed>|null
	 */
	private function wc_product_to_row( \WC_Product $product, array $settings ): ?array {
		$title = $product->get_name();
		if ( '' === $title ) {
			return null;
		}

		$img_id  = \HtoEAU_Widgets\get_product_card_image_attachment_id( $product );
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

		$btn = isset( $settings['wc_button_text'] ) && '' !== trim( (string) $settings['wc_button_text'] )
			? trim( (string) $settings['wc_button_text'] )
			: __( 'Try HtoEAU Now', 'htoeau-widgets' );

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
			'button_text'        => $btn,
			'button_url'         => $product->get_permalink(),
			'button_is_external' => false,
			'button_nofollow'    => false,
			'rating_score'       => $rating_score,
			'rating_suffix'      => $rating_suffix,
		];
	}

	protected function render(): void {
		$s    = $this->get_settings_for_display();
		$rows = $this->get_card_rows( $s );
		?>
		<section class="htoeau-hydration-systems">
			<div class="htoeau-hydration-systems__inner">
				<?php if ( ! empty( $s['heading'] ) ) : ?>
				<h2 class="htoeau-hydration-systems__heading"><?php echo esc_html( $s['heading'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $rows ) ) : ?>
				<div class="htoeau-hydration-systems__grid">
					<?php foreach ( $rows as $product ) : ?>
					<article class="htoeau-hydration-systems__card">
						<?php if ( ! empty( $product['image_url'] ) ) : ?>
						<div class="htoeau-hydration-systems__media">
							<img
								class="htoeau-hydration-systems__img"
								src="<?php echo esc_url( $product['image_url'] ); ?>"
								alt="<?php echo esc_attr( $product['title'] ?? '' ); ?>"
								loading="lazy"
								decoding="async"
							/>
						</div>
						<?php endif; ?>

						<div class="htoeau-hydration-systems__body">
							<?php if ( 'yes' === ( $s['show_card_rating'] ?? 'yes' ) ) : ?>
							<div class="htoeau-hydration-systems__rating">
								<?php echo \HtoEAU_Widgets\render_stars( 1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<p class="htoeau-hydration-systems__rating-text">
									<strong><?php echo esc_html( null !== ( $product['rating_score'] ?? null ) ? $product['rating_score'] : ( $s['rating_score'] ?? '4.8' ) ); ?></strong>
									<?php
									$suffix = null !== ( $product['rating_suffix'] ?? null ) ? $product['rating_suffix'] : ( $s['rating_suffix'] ?? '(248 reviews)' );
									echo ' ' . esc_html( $suffix );
									?>
								</p>
							</div>
							<?php endif; ?>

							<?php if ( ! empty( $product['title'] ) ) : ?>
							<h3 class="htoeau-hydration-systems__title"><?php echo esc_html( $product['title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $product['description'] ) ) : ?>
							<p class="htoeau-hydration-systems__excerpt"><?php echo esc_html( $product['description'] ); ?></p>
							<?php endif; ?>

							<?php
							if ( ! empty( $product['button_text'] ) ) {
								echo \HtoEAU_Widgets\render_cta_button(
									$product['button_text'],
									$product['button_url'] ?? '#',
									[
										'class'       => 'htoeau-hydration-systems__cta',
										'is_external' => ! empty( $product['button_is_external'] ),
										'nofollow'    => ! empty( $product['button_nofollow'] ),
									]
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</div>
					</article>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
