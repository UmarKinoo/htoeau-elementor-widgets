<?php

namespace HtoEAU_Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public URL for an optional file shipped under assets/images/ (for widget MEDIA defaults).
 *
 * @param string $relative File name or path relative to assets/images/ (no leading slash).
 */
function default_bundle_image_url( string $relative ): string {
	$relative = ltrim( $relative, '/' );
	if ( '' === $relative || false !== strpos( $relative, '..' ) ) {
		return '';
	}
	$path = HTOEAU_WIDGETS_PATH . 'assets/images/' . $relative;
	if ( ! is_readable( $path ) ) {
		return '';
	}
	return HTOEAU_WIDGETS_URL . 'assets/images/' . $relative;
}

/**
 * Attachment ID for card/listing UIs: ACF `shop_catalog_image` when set and valid, else featured image.
 *
 * Same field as the child theme shop archive (`group_htoeau_product`). PDP gallery is unchanged.
 *
 * @param \WC_Product $product Product.
 * @return int Attachment ID, or 0 if none.
 */
function get_product_card_image_attachment_id( \WC_Product $product ): int {
	$catalog_id = 0;
	if ( function_exists( 'get_field' ) ) {
		$field = get_field( 'shop_catalog_image', $product->get_id() );
		if ( is_numeric( $field ) ) {
			$catalog_id = (int) $field;
		} elseif ( is_array( $field ) && ! empty( $field['ID'] ) ) {
			$catalog_id = (int) $field['ID'];
		}
	}
	if ( $catalog_id > 0 && wp_attachment_is_image( $catalog_id ) ) {
		return $catalog_id;
	}
	$fid = (int) $product->get_image_id();
	return ( $fid > 0 && wp_attachment_is_image( $fid ) ) ? $fid : 0;
}

/**
 * Render a teal-circle checkmark (SVG) — default for hero, product, trust strip, etc.
 */
function render_check_icon( string $size = '30' ): string {
	$s  = intval( $size );
	$r  = $s / 2;

	return sprintf(
		'<span class="htoeau-check-icon" style="width:%1$dpx;height:%1$dpx;">
			<svg width="%1$d" height="%1$d" viewBox="0 0 %1$d %1$d" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="%2$s" cy="%2$s" r="%2$s" fill="%3$s"/>
				<path d="M%4$s %5$s L%6$s %7$s L%8$s %9$s" stroke="white" stroke-width="%10$s" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>',
		$s,
		$r,
		'#008fa3',
		round( $s * 0.3 ),   // x1
		round( $s * 0.5 ),   // y1
		round( $s * 0.467 ), // x2
		round( $s * 0.633 ), // y2
		round( $s * 0.7 ),   // x3
		round( $s * 0.367 ), // y3
		round( $s * 0.067 )  // stroke
	);
}

/**
 * Soft grey disc + white check — use only in Science Features (“science driven”) section on grey UI.
 */
function render_check_icon_soft( string $size = '30' ): string {
	$s  = intval( $size );
	$r  = $s / 2;

	$circle_fill = 'rgba(0, 44, 65, 0.144)';
	$mark_stroke = '#ffffff';

	return sprintf(
		'<span class="htoeau-check-icon htoeau-check-icon--soft" style="width:%1$dpx;height:%1$dpx;">
			<svg width="%1$d" height="%1$d" viewBox="0 0 %1$d %1$d" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="%2$s" cy="%2$s" r="%2$s" fill="%3$s"/>
				<path d="M%4$s %5$s L%6$s %7$s L%8$s %9$s" stroke="%11$s" stroke-width="%10$s" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>',
		$s,
		$r,
		$circle_fill,
		round( $s * 0.3 ),
		round( $s * 0.5 ),
		round( $s * 0.467 ),
		round( $s * 0.633 ),
		round( $s * 0.7 ),
		round( $s * 0.367 ),
		round( $s * 0.067 ),
		$mark_stroke
	);
}

/**
 * Check icon for teal/gradient backgrounds (Figma Hero B — semi-transparent disc + white mark).
 */
function render_check_icon_on_gradient( string $size = '30' ): string {
	$s  = intval( $size );
	$r  = $s / 2;

	return sprintf(
		'<span class="htoeau-check-icon htoeau-check-icon--on-gradient" style="width:%1$dpx;height:%1$dpx;">
			<svg width="%1$d" height="%1$d" viewBox="0 0 %1$d %1$d" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="%2$s" cy="%2$s" r="%2$s" fill="#E5F6FA"/>
				<path d="M%3$s %4$s L%5$s %6$s L%7$s %8$s" stroke="white" stroke-width="%9$s" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>',
		$s,
		$r,
		round( $s * 0.3 ),
		round( $s * 0.5 ),
		round( $s * 0.467 ),
		round( $s * 0.633 ),
		round( $s * 0.7 ),
		round( $s * 0.367 ),
		round( $s * 0.067 )
	);
}

/**
 * Render star rating SVG.
 */
function render_stars( int $count = 5, string $color = '#FFBF5B' ): string {
	$star = '<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">'
		. '<path d="M7 0L8.5716 4.83688H13.6574L9.5429 7.82624L11.1145 12.6631L7 9.67376L2.8855 12.6631L4.4571 7.82624L0.3426 4.83688H5.4284L7 0Z" fill="' . esc_attr( $color ) . '"/>'
		. '</svg>';

	$stars = '';
	for ( $i = 0; $i < $count; $i++ ) {
		$stars .= $star;
	}

	return '<span class="htoeau-stars">' . $stars . '</span>';
}

/**
 * Render a gradient CTA button.
 */
function render_cta_button( string $text, string $url = '#', array $attrs = [] ): string {
	$class = 'htoeau-btn htoeau-btn--primary';
	if ( ! empty( $attrs['class'] ) ) {
		$class .= ' ' . $attrs['class'];
	}

	$target = '';
	if ( ! empty( $attrs['is_external'] ) ) {
		$target = ' target="_blank"';
	}

	$rel_parts = [];
	if ( ! empty( $attrs['nofollow'] ) ) {
		$rel_parts[] = 'nofollow';
	}
	if ( ! empty( $attrs['is_external'] ) ) {
		$rel_parts[] = 'noopener';
		$rel_parts[] = 'noreferrer';
	}
	$rel = '';
	if ( ! empty( $rel_parts ) ) {
		$rel = ' rel="' . esc_attr( implode( ' ', array_unique( $rel_parts ) ) ) . '"';
	}

	return sprintf(
		'<a href="%s" class="%s"%s%s>%s</a>',
		esc_url( $url ),
		esc_attr( $class ),
		$target,
		$rel,
		esc_html( $text )
	);
}

/**
 * Hero rating line: bold the review count (e.g. "248" in "based on 248 happy reviews").
 *
 * @return string HTML fragment (may include &lt;strong&gt;); safe for wp_kses_post().
 */
function hero_rating_text_with_bold_count( string $rating_text ): string {
	$rating_text = trim( $rating_text );
	if ( '' === $rating_text ) {
		return '';
	}

	$escaped = esc_html( $rating_text );
	$marked  = preg_replace( '/(based\s+on\s+)(\d+)(\s+)/iu', '$1<strong>$2</strong>$3', $escaped, 1 );
	if ( is_string( $marked ) && $marked !== $escaped ) {
		return $marked;
	}

	if ( preg_match( '/\d+/', $escaped, $m, PREG_OFFSET_CAPTURE ) ) {
		$n = $m[0][0];
		$p = (int) $m[0][1];
		return substr_replace( $escaped, '<strong>' . $n . '</strong>', $p, strlen( $n ) );
	}

	return $escaped;
}

/**
 * Full cart icon link for HtoEAU Site Header (matches Site_Header_Widget markup).
 *
 * Used on first paint and in {@see woocommerce_add_to_cart_fragments} so the badge stays in sync after AJAX.
 *
 * @return string Safe HTML; empty if WooCommerce cart is unavailable.
 */
function render_site_header_cart_icon_link(): string {
	if ( ! function_exists( 'wc_get_cart_url' ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return '';
	}

	$cart_url = esc_url( wc_get_cart_url() );
	$cart_count = absint( WC()->cart->get_cart_contents_count() );

	$label = $cart_count > 0
		? sprintf(
			/* translators: %d: number of items in the cart */
			_n( 'Cart, %d item', 'Cart, %d items', $cart_count, 'htoeau-widgets' ),
			$cart_count
		)
		: __( 'Cart', 'htoeau-widgets' );

	$svg = '<svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 6h12l-1 12H3L2 6z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5 6V4a3 3 0 116 0v2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';

	$badge = '';
	if ( $cart_count > 0 ) {
		$badge = '<span class="htoeau-header__cart-badge">' . esc_html( (string) $cart_count ) . '</span>';
	}

	return sprintf(
		'<a href="%1$s" class="htoeau-header__icon htoeau-header__cart" aria-label="%2$s">%3$s%4$s</a>',
		$cart_url,
		esc_attr( $label ),
		$svg,
		$badge
	);
}
