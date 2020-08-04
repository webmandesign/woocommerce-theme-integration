<?php
/**
 * Action and filter hooks.
 *
 * @package    WooCommerce Theme Integration
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Hook {

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $hook
	 *
	 * @return  string
	 */
	public static function get_name( string $hook ) {

		// Output

			return apply_filters( 'WCTI/Hook/get_name', WCTI_THEME_SLUG . '/' . $hook, $hook );

	} // /get_name

}
