<?php
namespace HtoEAU_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use HtoEAU_Widgets\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Newsletter_Footer_Widget extends Widget_Base {

	public function get_name(): string {
		return 'htoeau_newsletter_footer';
	}

	public function get_title(): string {
		return 'HtoEAU Newsletter Footer';
	}

	public function get_icon(): string {
		return 'eicon-mail';
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'newsletter',
			[
				'label' => __( 'Newsletter', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'title',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Title', 'htoeau-widgets' ),
				'default' => 'Stay Informed',
			]
		);
		$this->add_control(
			'subtitle',
			[
				'type'        => Controls_Manager::TEXTAREA,
				'label'       => __( 'Subtitle', 'htoeau-widgets' ),
				'default'     => 'Performance insights, hydration research, and product updates from HtoEAU.',
				'rows'        => 3,
			]
		);
		$this->add_control(
			'email_placeholder',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Email placeholder', 'htoeau-widgets' ),
				'default' => 'Enter email address',
			]
		);
		$this->add_control(
			'button_text',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Button text', 'htoeau-widgets' ),
				'default' => 'Sign up now',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'brand',
			[
				'label' => __( 'Brand & company', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'logo',
			[
				'type' => Controls_Manager::MEDIA,
				'label' => __( 'Logo', 'htoeau-widgets' ),
			]
		);
		$this->add_control(
			'logo_link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => __( 'Logo link', 'htoeau-widgets' ),
				'default'     => [ 'url' => '/' ],
				'placeholder' => home_url( '/' ),
			]
		);
		$this->add_control(
			'nav_column_heading',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Quick links column title', 'htoeau-widgets' ),
				/* Figma 1:598 — link row has no heading; leave empty to match. */
				'default' => '',
			]
		);
		$this->add_control(
			'company_column_heading',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Company column title', 'htoeau-widgets' ),
				'default' => '',
			]
		);
		$this->add_control(
			'address',
			[
				'type'        => Controls_Manager::TEXTAREA,
				'label'       => __( 'Address', 'htoeau-widgets' ),
				'default'     => "The Hydrogen Innovation Company B.V.,\nKeizersgracht 62, 1015 CS Amsterdam,\nThe Netherlands",
				'rows'        => 4,
			]
		);
		$this->add_control(
			'email',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Email (display)', 'htoeau-widgets' ),
				'default'     => 'hello@HtoEAU.com',
				'placeholder' => 'hello@example.com',
			]
		);
		$this->add_control(
			'email_link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => __( 'Email link', 'htoeau-widgets' ),
				'default'     => [ 'url' => 'mailto:hello@HtoEAU.com' ],
				'placeholder' => 'mailto:hello@example.com',
			]
		);
		$this->add_control(
			'kvk',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'KvK', 'htoeau-widgets' ),
				'default' => 'KvK: 92794076',
			]
		);
		$this->add_control(
			'vat',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'VAT', 'htoeau-widgets' ),
				'default' => 'VAT: NL862936330B01',
			]
		);
		$this->end_controls_section();

		$nav_rep = new Repeater();
		$nav_rep->add_control(
			'label',
			[
				'type'  => Controls_Manager::TEXT,
				'label' => __( 'Label', 'htoeau-widgets' ),
			]
		);
		$nav_rep->add_control(
			'link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => __( 'Link', 'htoeau-widgets' ),
				'placeholder' => home_url( '/' ),
			]
		);

		$this->start_controls_section(
			'nav',
			[
				'label' => __( 'Footer menu', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'nav_items',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $nav_rep->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => [
					[ 'label' => 'Shop', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'My Account', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'About', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Science', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Interview', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Contact', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Become an Affiliate', 'link' => [ 'url' => '#' ] ],
				],
			]
		);
		$this->end_controls_section();

		$legal_rep = new Repeater();
		$legal_rep->add_control(
			'label',
			[
				'type'  => Controls_Manager::TEXT,
				'label' => __( 'Label', 'htoeau-widgets' ),
			]
		);
		$legal_rep->add_control(
			'link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => __( 'Link', 'htoeau-widgets' ),
				'placeholder' => home_url( '/' ),
			]
		);

		$this->start_controls_section(
			'legal',
			[
				'label' => __( 'Copyright & legal', 'htoeau-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'copyright',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Copyright', 'htoeau-widgets' ),
				'default' => '© 2026 HtoEAU. All rights reserved',
			]
		);
		$this->add_control(
			'legal_items',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $legal_rep->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => [
					[ 'label' => 'Refund Policy', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Privacy Policy', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Terms of Service', 'link' => [ 'url' => '#' ] ],
					[ 'label' => 'Accessibility', 'link' => [ 'url' => '#' ] ],
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * @param array<string, mixed> $link
	 */
	private function render_link_attrs( array $link ): string {
		$url = isset( $link['url'] ) ? $link['url'] : '';
		if ( '' === $url ) {
			return '';
		}
		$attrs = ' href="' . esc_url( $url ) . '"';
		if ( ! empty( $link['is_external'] ) ) {
			$attrs .= ' target="_blank"';
		}
		$rel = [];
		if ( ! empty( $link['nofollow'] ) ) {
			$rel[] = 'nofollow';
		}
		if ( ! empty( $link['is_external'] ) ) {
			$rel[] = 'noopener';
		}
		if ( $rel ) {
			$attrs .= ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"';
		}
		return $attrs;
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		$logo_id  = ! empty( $s['logo']['id'] ) ? (int) $s['logo']['id'] : 0;
		$logo_url = ! empty( $s['logo']['url'] ) ? (string) $s['logo']['url'] : '';
		if ( $logo_id ) {
			$logo_url = wp_get_attachment_image_url( $logo_id, 'full' ) ?: $logo_url;
		}

		$logo_link_arr = isset( $s['logo_link'] ) && is_array( $s['logo_link'] ) ? $s['logo_link'] : [];
		if ( empty( $logo_link_arr['url'] ) ) {
			$logo_link_arr['url'] = home_url( '/' );
		}
		$logo_anchor_attrs = $this->render_link_attrs( $logo_link_arr );

		$email_display  = isset( $s['email'] ) ? trim( (string) $s['email'] ) : '';
		$email_link_arr = isset( $s['email_link'] ) && is_array( $s['email_link'] ) ? $s['email_link'] : [];
		if ( $email_display && empty( $email_link_arr['url'] ) ) {
			$safe_mail = sanitize_email( $email_display );
			if ( $safe_mail ) {
				$email_link_arr['url'] = 'mailto:' . $safe_mail;
			}
		}

		$placeholder = isset( $s['email_placeholder'] ) ? (string) $s['email_placeholder'] : 'Enter email address';
		?>
		<footer class="htoeau-news-footer">
			<div class="n1">
				<div class="htoeau-news-footer__intro">
					<h3><?php echo esc_html( $s['title'] ?? '' ); ?></h3>
					<p><?php echo esc_html( $s['subtitle'] ?? '' ); ?></p>
				</div>
				<form class="f" action="#" method="post" aria-label="<?php echo esc_attr__( 'Newsletter signup', 'htoeau-widgets' ); ?>">
					<input type="email" name="htoeau_newsletter_email" autocomplete="email" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
					<button type="button"><?php echo esc_html( $s['button_text'] ?? '' ); ?></button>
				</form>
			</div>
			<div class="line" role="presentation"></div>
			<div class="n2">
				<?php /* Figma 1:598 — left: logo + company; right: horizontal quick links (16px / 32px gap). */ ?>
				<div class="htoeau-news-footer__mid">
					<div class="htoeau-news-footer__brand-col">
						<?php if ( $logo_url ) : ?>
							<div class="htoeau-news-footer__logo-row">
								<a class="htoeau-news-footer__logo"<?php echo $logo_anchor_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<?php
									if ( $logo_id ) {
										echo wp_get_attachment_image( $logo_id, 'full', false, [ 'alt' => esc_attr( get_bloginfo( 'name' ) ) ] );
									} else {
										printf(
											'<img src="%1$s" alt="%2$s" loading="lazy" decoding="async" />',
											esc_url( $logo_url ),
											esc_attr( get_bloginfo( 'name' ) )
										);
									}
									?>
								</a>
							</div>
						<?php endif; ?>
						<div class="htoeau-news-footer__company-wrap">
							<?php
							$co_heading = isset( $s['company_column_heading'] ) ? trim( (string) $s['company_column_heading'] ) : '';
							if ( '' !== $co_heading ) :
								?>
							<p class="htoeau-news-footer__column-heading"><?php echo esc_html( $co_heading ); ?></p>
							<?php endif; ?>
							<div class="htoeau-news-footer__company">
								<?php if ( ! empty( $s['address'] ) ) : ?>
									<div class="htoeau-news-footer__address"><?php echo nl2br( esc_html( $s['address'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<?php endif; ?>
								<?php if ( $email_display ) : ?>
									<p class="htoeau-news-footer__email">
										<?php if ( ! empty( $email_link_arr['url'] ) ) : ?>
											<a<?php echo $this->render_link_attrs( $email_link_arr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $email_display ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $email_display ); ?>
										<?php endif; ?>
									</p>
								<?php endif; ?>
								<?php if ( ! empty( $s['kvk'] ) ) : ?>
									<p class="htoeau-news-footer__reg"><?php echo esc_html( $s['kvk'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $s['vat'] ) ) : ?>
									<p class="htoeau-news-footer__reg"><?php echo esc_html( $s['vat'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php if ( ! empty( $s['nav_items'] ) && is_array( $s['nav_items'] ) ) : ?>
						<div class="htoeau-news-footer__links-col">
							<?php
							$nav_heading = isset( $s['nav_column_heading'] ) ? trim( (string) $s['nav_column_heading'] ) : '';
							if ( '' !== $nav_heading ) :
								?>
							<p class="htoeau-news-footer__column-heading"><?php echo esc_html( $nav_heading ); ?></p>
							<?php endif; ?>
							<nav class="htoeau-news-footer__nav" aria-label="<?php echo esc_attr__( 'Footer', 'htoeau-widgets' ); ?>">
								<?php foreach ( $s['nav_items'] as $item ) : ?>
									<?php
									$label = $item['label'] ?? '';
									$lnk   = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : [];
									if ( ! $label ) {
										continue;
									}
									$url = $lnk['url'] ?? '#';
									?>
									<a<?php echo $this->render_link_attrs( array_merge( $lnk, [ 'url' => $url ?: '#' ] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
								<?php endforeach; ?>
							</nav>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="htoeau-news-footer__bottom">
				<div class="line" role="presentation"></div>
				<div class="n3">
					<span class="htoeau-news-footer__copyright"><?php echo esc_html( $s['copyright'] ?? '' ); ?></span>
					<?php if ( ! empty( $s['legal_items'] ) && is_array( $s['legal_items'] ) ) : ?>
						<div class="htoeau-news-footer__legal">
							<?php foreach ( $s['legal_items'] as $item ) : ?>
								<?php
								$label = $item['label'] ?? '';
								$lnk   = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : [];
								if ( ! $label ) {
									continue;
								}
								$url = $lnk['url'] ?? '#';
								?>
								<a<?php echo $this->render_link_attrs( array_merge( $lnk, [ 'url' => $url ?: '#' ] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</footer>
		<?php
	}
}
