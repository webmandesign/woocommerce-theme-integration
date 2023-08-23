<?php
/**
 * Loader.
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

class Loader {

	/**
	 * Initialization.
	 *
	 * @since    1.0.0
	 * @version  1.4.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// FSE check.
			Site_Editor::init();

			// Actions

				add_action( 'after_setup_theme', __CLASS__ . '::after_setup_theme', 20 );

	} // /init

	/**
	 * After setup theme.
	 *
	 * @since    1.0.0
	 * @version  1.5.0
	 *
	 * @return  void
	 */
	public static function after_setup_theme() {

		// Requirements check

			// Why bother if WooCommerce is not active.
			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}

			// Should we load this plugin functionality?
			$can_load = true;

				// Is the theme already compatible with WooCommerce?
				if ( current_theme_supports( 'woocommerce' ) ) {
					add_action( 'admin_notices', function() {
						printf(
							'<div class="error"><p>%s</p></div>',
							esc_html__( 'Your theme seems to claim WooCommerce integration already. The Integration for WooCommerce plugin is not needed.', 'wc-theme-integration' )
						);
					} );
					$can_load = false;
				}

				// Is the theme by WebMan Design?
				if ( false === strpos( wp_get_theme( WCTI_THEME_SLUG )->get( 'AuthorURI' ), 'webmandesign' ) ) {
					add_action( 'admin_notices', function() {
						printf(
							'<div class="error"><p>%s</p></div>',
							esc_html__( 'Sorry, the Integration for WooCommerce plugin will not work with your theme. Please check WordPress themes by WebManDesign.eu.', 'wc-theme-integration' )
						);
					} );
					$can_load = false;
				}

			if ( ! $can_load ) {
				return;
			}


		// Processing

			Setup::init();

			Assets::init();
			Blocks::init();
			Loop::init();
			Options::init();
			Pages::init();
			Single::init();
			Widgets::init();
			Wrappers::init();

	} // /after_setup_theme

}
