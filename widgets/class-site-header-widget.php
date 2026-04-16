<?php
/**
 * Site Header — Figma 238:15 (desktop); mobile specs: 86:622
 * https://www.figma.com/design/FcOeKFswrs0fJXLmrEvev6/Untitled?node-id=86-622
 *
 * Three rows: announcement bar | main nav (logo + links + icons) | trust strip.
 * Drag-and-drop Elementor widget — place at the top of your page template.
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

class Site_Header_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_site_header';
	}

	public function get_title(): string {
		return __( 'HtoEAU Site Header', 'htoeau-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-header';
	}

	public function get_keywords(): array {
		return [ 'header', 'nav', 'menu', 'navigation', 'htoeau', 'announcement', 'trust' ];
	}

	public function get_script_depends(): array {
		return array_merge(
			parent::get_script_depends(),
			[ 'htoeau-widgets-frontend', 'htoeau-header-trust-marquee' ]
		);
	}

	protected function register_controls(): void {

		/* ── Announcement bar ── */
		$this->start_controls_section( 'section_announce', [
			'label' => __( 'Announcement Bar', 'htoeau-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'announce_text', [
			'label'   => __( 'Text', 'htoeau-widgets' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Bulk buy and save up to 15%',
		] );

		$this->add_control( 'announce_link_text', [
			'label'   => __( 'Link label', 'htoeau-widgets' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Learn more',
		] );

		$this->add_control( 'announce_link', [
			'label'   => __( 'Link URL', 'htoeau-widgets' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '/shop/' ],
		] );

		$this->end_controls_section();

		/* ── Navigation links ── */
		$this->start_controls_section( 'section_nav', [
			'label' => __( 'Navigation', 'htoeau-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$menu_choices = [ '' => __( '— Use custom links below —', 'htoeau-widgets' ) ];
		foreach ( wp_get_nav_menus() as $menu_obj ) {
			$menu_choices[ (string) $menu_obj->term_id ] = $menu_obj->name;
		}

		$this->add_control( 'wp_nav_menu', [
			'label'       => __( 'WordPress menu', 'htoeau-widgets' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => $menu_choices,
			'default'     => '',
			'description' => __( 'Pick a menu from Appearance → Menus (same as Elementor’s WordPress Menu widget). Leave on “custom links” to use the repeater.', 'htoeau-widgets' ),
		] );

		$this->add_control( 'logo', [
			'label'       => __( 'Logo image', 'htoeau-widgets' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Upload your logo (Figma: 136 × 32). Leave empty for text fallback.', 'htoeau-widgets' ),
		] );

		$nav_rep = new Repeater();
		$nav_rep->add_control( 'label', [
			'label' => __( 'Label', 'htoeau-widgets' ),
			'type'  => Controls_Manager::TEXT,
		] );
		$nav_rep->add_control( 'url', [
			'label' => __( 'URL', 'htoeau-widgets' ),
			'type'  => Controls_Manager::URL,
		] );

		$this->add_control( 'nav_items', [
			'label'       => __( 'Menu links', 'htoeau-widgets' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $nav_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Shop',       'url' => [ 'url' => '/shop/' ] ],
				[ 'label' => 'Benefits',   'url' => [ 'url' => '/benefits/' ] ],
				[ 'label' => 'Science',    'url' => [ 'url' => '/science/' ] ],
				[ 'label' => 'About Us',   'url' => [ 'url' => '/about/' ] ],
				[ 'label' => 'FAQs',       'url' => [ 'url' => '/faqs/' ] ],
				[ 'label' => 'Contact Us', 'url' => [ 'url' => '/contact/' ] ],
			],
		] );

		$this->add_control( 'show_account_icon', [
			'label'        => __( 'Account icon', 'htoeau-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'show_search_icon', [
			'label'        => __( 'Search icon', 'htoeau-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->add_control( 'show_cart_icon', [
			'label'        => __( 'Cart icon', 'htoeau-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		] );

		$this->end_controls_section();

		/* ── Trust strip ── */
		$this->start_controls_section( 'section_trust', [
			'label' => __( 'Trust Strip', 'htoeau-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$trust_rep = new Repeater();
		$trust_rep->add_control( 'text', [
			'label' => __( 'Item', 'htoeau-widgets' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->add_control( 'trust_items', [
			'label'       => __( 'Trust items', 'htoeau-widgets' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $trust_rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Minimum 5mg/l Dissolved Hydrogen Gas' ],
				[ 'text' => 'Only Water + Molecular Hydrogen' ],
				[ 'text' => 'No Stimulants' ],
				[ 'text' => 'No Additives' ],
				[ 'text' => 'Free Shipping' ],
				[ 'text' => 'Money Back Guarantee' ],
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$announce      = isset( $s['announce_text'] ) ? trim( (string) $s['announce_text'] ) : '';
		$announce_link = isset( $s['announce_link_text'] ) ? trim( (string) $s['announce_link_text'] ) : '';
		$announce_url  = ! empty( $s['announce_link']['url'] ) ? (string) $s['announce_link']['url'] : '';

		$logo_url  = ! empty( $s['logo']['url'] ) ? (string) $s['logo']['url'] : '';
		$wp_menu_id = isset( $s['wp_nav_menu'] ) ? absint( $s['wp_nav_menu'] ) : 0;
		$nav_items = isset( $s['nav_items'] ) && is_array( $s['nav_items'] ) ? $s['nav_items'] : [];

		$show_account = 'yes' === ( $s['show_account_icon'] ?? 'yes' );
		$show_search  = 'yes' === ( $s['show_search_icon'] ?? 'yes' );
		$show_cart    = 'yes' === ( $s['show_cart_icon'] ?? 'yes' );

		$trust_items = isset( $s['trust_items'] ) && is_array( $s['trust_items'] ) ? $s['trust_items'] : [];

		$home_url    = esc_url( home_url( '/' ) );
		$account_url = function_exists( 'wc_get_page_permalink' ) ? esc_url( wc_get_page_permalink( 'myaccount' ) ) : esc_url( home_url( '/my-account/' ) );
		$cart_url    = function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : esc_url( home_url( '/cart/' ) );
		$cart_count  = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

		$uid = 'htoeau-header-' . $this->get_id();
		?>
		<header class="htoeau-header" data-htoeau-header>

			<?php if ( '' !== $announce ) : ?>
			<div class="htoeau-header__announce">
				<p class="htoeau-header__announce-text">
					<?php echo esc_html( $announce ); ?>
					<?php if ( '' !== $announce_link && '' !== $announce_url ) : ?>
						<a href="<?php echo esc_url( $announce_url ); ?>"><?php echo esc_html( $announce_link ); ?></a>
					<?php endif; ?>
				</p>
			</div>
			<?php endif; ?>

			<div class="htoeau-header__main">
				<div class="htoeau-header__inner">

					<a href="<?php echo $home_url; ?>" class="htoeau-header__logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<?php if ( '' !== $logo_url ) : ?>
							<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" height="32" />
						<?php else : ?>
							<span class="htoeau-header__logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
						<?php endif; ?>
					</a>

					<button class="htoeau-header__burger" aria-label="<?php esc_attr_e( 'Menu', 'htoeau-widgets' ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $uid ); ?>">
						<span></span><span></span><span></span>
					</button>

					<?php
					$has_wp_menu = $wp_menu_id > 0 && wp_get_nav_menu_object( $wp_menu_id );
					$custom_links = array_filter(
						$nav_items,
						static function ( $item ) {
							$label = isset( $item['label'] ) ? trim( (string) $item['label'] ) : '';
							return '' !== $label;
						}
					);
					$show_nav = $has_wp_menu || ! empty( $custom_links );
					?>
					<?php if ( $show_nav ) : ?>
					<nav id="<?php echo esc_attr( $uid ); ?>" class="htoeau-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'htoeau-widgets' ); ?>">
						<?php if ( $has_wp_menu ) : ?>
							<?php
							wp_nav_menu(
								[
									'menu'           => $wp_menu_id,
									'container'      => false,
									'menu_class'     => 'htoeau-header__links',
									'menu_id'        => 'menu-' . $uid,
									'fallback_cb'    => false,
									'depth'          => 3,
									'item_spacing'   => 'discard',
									'echo'           => true,
								]
							);
							?>
						<?php else : ?>
						<ul class="htoeau-header__links">
							<?php foreach ( $custom_links as $item ) :
								$label = isset( $item['label'] ) ? trim( (string) $item['label'] ) : '';
								$href  = ! empty( $item['url']['url'] ) ? (string) $item['url']['url'] : '#';
								?>
							<li><a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
					</nav>
					<?php endif; ?>

					<div class="htoeau-header__icons">
						<?php if ( $show_account ) : ?>
						<a href="<?php echo $account_url; ?>" class="htoeau-header__icon" aria-label="<?php esc_attr_e( 'Account', 'htoeau-widgets' ); ?>">
							<svg width="16" height="18" viewBox="0 0 16 18" fill="none"><circle cx="8" cy="4.5" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M1 16.5c0-3.59 2.91-6.5 7-6.5s7 2.91 7 6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
						</a>
						<?php endif; ?>
						<?php if ( $show_search ) : ?>
						<button class="htoeau-header__icon htoeau-header__search-btn" aria-label="<?php esc_attr_e( 'Search', 'htoeau-widgets' ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="6.5" cy="6.5" r="5.5" stroke="currentColor" stroke-width="1.4"/><line x1="10.8" y1="10.8" x2="15" y2="15" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
						</button>
						<?php endif; ?>
						<?php if ( $show_cart ) : ?>
						<a href="<?php echo $cart_url; ?>" class="htoeau-header__icon htoeau-header__cart" aria-label="<?php esc_attr_e( 'Cart', 'htoeau-widgets' ); ?>">
							<svg width="16" height="20" viewBox="0 0 16 20" fill="none"><path d="M2 6h12l-1 12H3L2 6z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5 6V4a3 3 0 116 0v2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
							<?php if ( $cart_count > 0 ) : ?>
							<span class="htoeau-header__cart-badge"><?php echo esc_html( $cart_count ); ?></span>
							<?php endif; ?>
						</a>
						<?php endif; ?>
					</div>

				</div>
			</div>

			<?php if ( ! empty( $trust_items ) ) : ?>
			<div class="htoeau-header__trust">
				<div class="htoeau-header__trust-scroll" tabindex="0" aria-label="<?php echo esc_attr__( 'Highlights', 'htoeau-widgets' ); ?>">
					<div class="htoeau-header__trust-marquee">
						<div class="htoeau-header__trust-track">
							<?php
							$trust_i = 0;
							foreach ( $trust_items as $row ) :
								$txt = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
								if ( '' === $txt ) {
									continue;
								}
								if ( $trust_i > 0 ) :
									?>
							<span class="htoeau-header__trust-dot" aria-hidden="true"></span>
									<?php
								endif;
								?>
							<span class="htoeau-header__trust-item"><?php echo esc_html( $txt ); ?></span>
								<?php
								++$trust_i;
							endforeach;
							?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</header>
		<?php
	}
}
