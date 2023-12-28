<?php
/**
 * Assets.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.5.3
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Assets {

	/**
	 * Initialization.
	 *
	 * @since    1.0.0
	 * @version  1.5.3
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueue' );
				add_action( 'enqueue_block_assets', __CLASS__ . '::enqueue_editor' );

				add_action( 'woocommerce_product_after_tabs', __CLASS__ . '::print', 0 );

			// Filters

				add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	} // /init

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since    1.0.0
	 * @version  1.5.3
	 *
	 * @return  void
	 */
	public static function enqueue() {

		// Processing

			wp_enqueue_style(
				'wc-theme-integration-custom-properties',
				WCTI_URL . 'assets/css/custom-properties.css',
				array(),
				'v' . WCTI_VERSION
			);

			// This has to be loaded globally due to possible shortcode styles.
			wp_enqueue_style(
				'wc-theme-integration',
				WCTI_URL . 'assets/css/woocommerce.css',
				array(),
				'v' . WCTI_VERSION
			);
			wp_style_add_data(
				'wc-theme-integration',
				'rtl',
				'replace'
			);

			// Adds class of tabs count on tabs wrapper.
			wp_add_inline_script(
				'wc-single-product',
				"( function() {
					'use strict';

					var
						wcTabs = document.getElementsByClassName( 'woocommerce-tabs' );

					if ( wcTabs.length ) {
						var
							wcTabsCount = wcTabs[0].querySelectorAll( '.tabs li' ).length;

						wcTabs[0].classList.add( 'tabs-count-' + wcTabsCount );
					}

				} )();"
			);

	} // /enqueue

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since    1.4.2
	 * @version  1.5.3
	 *
	 * @return  void
	 */
	public static function enqueue_editor() {

		// Processing

			if ( is_admin() ) {
				wp_enqueue_style(
					'wc-theme-integration-custom-properties',
					WCTI_URL . 'assets/css/custom-properties.css',
					array(),
					'v' . WCTI_VERSION
				);
			}

			wp_enqueue_style(
				'wc-theme-integration-blocks',
				WCTI_URL . 'assets/css/blocks.css',
				array(),
				'v' . WCTI_VERSION
			);
			wp_style_add_data(
				'wc-theme-integration-blocks',
				'rtl',
				'replace'
			);

	} // /enqueue_editor

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
