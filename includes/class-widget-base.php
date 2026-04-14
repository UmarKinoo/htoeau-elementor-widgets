<?php

namespace HtoEAU_Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Widget_Base extends \Elementor\Widget_Base {

	public function get_categories(): array {
		return [ 'htoeau' ];
	}

	public function get_style_depends(): array {
		return [ 'htoeau-widgets-frontend' ];
	}

	protected function add_section_heading_controls( string $prefix = 'section', array $defaults = [] ): void {
		$this->add_control(
			$prefix . '_heading',
			[
				'label'   => 'Heading',
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => $defaults['heading'] ?? '',
				'rows'    => 3,
			]
		);

		$this->add_control(
			$prefix . '_subheading',
			[
				'label'   => 'Subheading',
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => $defaults['subheading'] ?? '',
				'rows'    => 2,
			]
		);
	}

	protected function add_cta_controls( string $prefix = 'cta', array $defaults = [] ): void {
		$this->add_control(
			$prefix . '_text',
			[
				'label'   => 'Button Text',
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $defaults['text'] ?? 'Learn More',
			]
		);

		$this->add_control(
			$prefix . '_link',
			[
				'label'       => 'Button Link',
				'type'        => \Elementor\Controls_Manager::URL,
				'default'     => [
					'url' => $defaults['url'] ?? '#',
				],
				'placeholder' => 'https://example.com',
			]
		);
	}
}
