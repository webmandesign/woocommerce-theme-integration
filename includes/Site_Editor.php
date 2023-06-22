<?php
/**
 * Site editor functionality.
 *
 * Checks whether the theme is FSE enabled.
 *
 * We need to duplicate this functionality from the theme here
 * for early access by WooCommerce. It solves the issue where
 * WooCommerce checks for `wp_is_block_theme()` before the
 * `after_setup_theme` hook is fired.
 * @link  https://github.com/woocommerce/woocommerce/issues/37934
 *
 * We can not use `WebManDesign\Theme_Slug\Setup\Site_Editor()`
 * class here as it is not callable early enough for WooCommerce plugin.
 * So we need to recreate the functionality.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.4.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Site_Editor {

	/**
	 * Theme mod ID.
	 *
	 * @since   1.4.0
	 * @access  public
	 * @var     string
	 */
	public static $theme_mod_id = 'layout_site_editing';

	/**
	 * Is Site Editor enabled?
	 *
	 * @since   1.4.0
	 * @access  public
	 * @var     bool
	 */
	public static $is_enabled = false;

	/**
	 * Initialization.
	 *
	 * @since  1.4.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Variables

			// If the theme contains the method, the theme mod is definitely TRUE by default.
			self::$is_enabled = (bool) get_theme_mod( self::$theme_mod_id, is_callable( WCTI_THEME_NAMESPACE . '\Setup\Site_Editor::is_enabled' ) );


		// Processing

			// Actions

				// Using late priority here, after the theme modifications.
				add_filter( 'theme_file_path', __CLASS__ . '::bypass_is_block_theme', 99 );

	} // /init

	/**
	 * Disabling block theme functionality (FSE) when needed.
	 *
	 * @see  WebManDesign\Theme_Slug\Setup\Site_Editor() theme class for more info.
	 *
	 * @since  1.4.0
	 *
	 * @param  string $path
	 *
	 * @return  string
	 */
	public static function bypass_is_block_theme( string $path ): string {

		// Output

			if (
				stripos( $path, 'templates/index.html' )
				&& ! self::$is_enabled
			) {
				return '';
			} else {
				return $path;
			}

	} // /bypass_is_block_theme

}
