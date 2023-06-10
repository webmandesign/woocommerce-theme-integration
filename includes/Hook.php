<?php
/**
 * Action and filter hooks.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.5.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Hook {

	/**
	 * Get theme hook name.
	 *
	 * @since    1.0.0
	 * @version  1.5.0
	 *
	 * @param  string $hook
	 *
	 * @return  string
	 */
	public static function get_name( string $hook ) {

		// Output

			/**
			 * Filters the theme hook name.
			 *
			 * @since  1.5.0
			 *
			 * @param  string $theme_hook  Actual full theme related hook name.
			 * @param  string $hook        Partial hook name.
			 */
			return apply_filters( 'WCTI/Hook/get_name', str_replace( '-', '_', WCTI_THEME_SLUG ) . '/' . $hook, $hook );

	} // /get_name

}
