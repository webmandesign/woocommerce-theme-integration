<?php
/**
 * Customization options.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.3.0
 */

namespace WebManDesign\WCTI;
use WP_Customize_Manager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Options {

	/**
	 * Plugin option IDs.
	 *
	 * @var array
	 */
	public static $id = array(
		'replace_theme_search' => 'wcti_replace_theme_search',
	);

	/**
	 * Initialization.
	 *
	 * @since  1.3.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'customize_register', __CLASS__ . '::customize_register', 20 );

	} // /init

	/**
	 * Customizer options.
	 *
	 * @since  1.3.0
	 *
	 * @param  WP_Customize_Manager $wp_customize
	 *
	 * @return  void
	 */
	public static function customize_register( WP_Customize_Manager $wp_customize ) {

		// Processing

			$wp_customize->add_setting(
				self::$id['replace_theme_search'],
				array(
					'capability'        => 'manage_woocommerce',
					'default'           => true,
					'sanitize_callback' => function( $value ): bool {
						return (bool) $value;
					},
				)
			);

			$wp_customize->add_control(
				self::$id['replace_theme_search'],
				array(
					'type'        => 'checkbox',
					'section'     => 'woocommerce_product_catalog',
					'priority'    => 0,
					'label'       => esc_html__( 'Replace theme search', 'wc-theme-integration' ),
					'description' => esc_html__( 'Replaces default search form in the theme with WooCommerce product search form.', 'wc-theme-integration' ),
				)
			);

	} // /customize_register

}
