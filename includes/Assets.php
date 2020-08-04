<?php
/**
 * Assets.
 *
 * @package  WooCommerce Theme Integration
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Assets {

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueue' );

				add_action( 'woocommerce_product_after_tabs', __CLASS__ . '::print', 0 );

			// Filters

				add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	} // /init

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function enqueue() {

		// Processing

			// Styles:

				wp_enqueue_style(
					'woocommerce-theme-integration',
					WCTI_URL . 'assets/css/woocommerce.css',
					array(),
					'v' . WCTI_VERSION
				);
				wp_style_add_data(
					'woocommerce-theme-integration',
					'rtl',
					'replace'
				);

			// Scripts:

				wp_add_inline_script(
					'wc-single-product',
					"( function( $ ) {
						'use strict';

						// Add class of tabs count on tab selector wrapper.
						$( '.woocommerce-tabs' )
							.addClass( function() {
								return 'tabs-count-' + $( this ).find( '.tabs li' ).length;
							} );

					} )( jQuery );"
				);

	} // /enqueue

	/**
	 * Print styles in page content.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function print() {

		// Variables

			$print_styles_callable = WCTI_THEME_NAMESPACE . '\Assets\Factory::print_styles';


		// Requirements check

			if ( ! is_callable( $print_styles_callable ) ) {
				return;
			}


		// Processing

			if ( doing_action( 'woocommerce_product_after_tabs' ) ) {
				call_user_func(
					$print_styles_callable,
					WCTI_THEME_SLUG . '-comments'
				);
			}

	} // /print

}
