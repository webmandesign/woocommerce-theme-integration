<?php
/**
 * Widgets.
 *
 * @package    Theme Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Widgets {

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Removing hooks

				remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );

			// Filters

				add_filter( 'get_product_search_form', __CLASS__ . '::product_search_form' );

				if ( is_callable( WCTI_THEME_NAMESPACE . '\Sidebar\Component::widget_tag_cloud_args' ) ) {
					add_filter( 'woocommerce_product_tag_cloud_widget_args', WCTI_THEME_NAMESPACE . '\Sidebar\Component::widget_tag_cloud_args' );
				}

	} // /init

	/**
	 * Fixing search field ID for multiple display.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $html
	 *
	 * @return  string
	 */
	public static function product_search_form( string $html ): string {

		// Output

			return str_replace( 'woocommerce-product-search-field', 'woocommerce-product-search-field-' . esc_attr( uniqid() ), $html );

	} // /product_search_form

}
