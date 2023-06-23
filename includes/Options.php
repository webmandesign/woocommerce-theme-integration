<?php
/**
 * Customization options.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.3.0
 * @version  1.4.0
 */

namespace WebManDesign\WCTI;

use WP_Customize_Manager;
use WP_Customize_Control;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Options {

	/**
	 * Plugin option IDs.
	 *
	 * @since  1.4.0
	 *
	 * @var array
	 */
	public static $id = array(
		'replace_theme_search'   => 'wcti_replace_theme_search',
		'catalog_columns_mobile' => 'wcti_catalog_columns_mobile',
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
	 * @since    1.3.0
	 * @version  1.4.0
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
						'type'            => 'checkbox',
						'section'         => 'woocommerce_product_catalog',
						'priority'        => 0,
						'label'           => esc_html__( 'Replace theme search', 'wc-theme-integration' ),
						'description'     => esc_html__( 'Replaces default search form in the theme with WooCommerce product search form.', 'wc-theme-integration' ),
						'active_callback' => function( WP_Customize_Control $control ) {

							if ( Site_Editor::$is_enabled ) {
								return ! (bool) $control->manager->get_setting( Site_Editor::$theme_mod_id )->value();
							} else {
								return true;
							}
						}
					)
				);

			$wp_customize->add_setting(
				self::$id['catalog_columns_mobile'],
				array(
					'capability'        => 'manage_woocommerce',
					'default'           => 1,
					'sanitize_callback' => 'absint',
				)
			);

				$wp_customize->add_control(
					self::$id['catalog_columns_mobile'],
					array(
						'type'        => 'number',
						'section'     => 'woocommerce_product_catalog',
						'label'       => esc_html__( 'Small screen products per row', 'wc-theme-integration' ),
						'description' => esc_html__( 'How many products should be shown per row on small screen devices?', 'wc-theme-integration' ),
						'input_attrs' => array(
							'min'  => 1,
							'max'  => 2,
							'step' => 1,
						),
					)
				);

	} // /customize_register

}
