<?php
/**
 * Plugin Name:  Integration for WooCommerce
 * Plugin URI:   https://www.webmandesign.eu/portfolio/woocommerce-theme-integration-wordpress-plugin/
 * Description:  Provides deeper integration for WooCommerce in WebMan Design accessibility ready themes.
 * Version:      1.3.5
 * Author:       WebMan Design, Oliver Juhas
 * Author URI:   https://www.webmandesign.eu/
 * License:      GNU General Public License v3
 * License URI:  http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:  wc-theme-integration
 * Domain Path:  /languages
 *
 * GitHub Plugin URI:  https://github.com/webmandesign/woocommerce-theme-integration
 *
 * @copyright  WebMan Design, Oliver Juhas
 * @license    GPL-3.0, https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @link  https://github.com/webmandesign/woocommerce-theme-integration
 * @link  https://www.webmandesign.eu
 *
 * @package  Integration for WooCommerce
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Constants:

	define( 'WCTI_VERSION', '1.3.5' );
	define( 'WCTI_FILE', __FILE__ );
	define( 'WCTI_PATH', plugin_dir_path( WCTI_FILE ) ); // Trailing slashed.
	define( 'WCTI_URL', plugin_dir_url( WCTI_FILE ) ); // Trailing slashed.
	define( 'WCTI_THEME_SLUG', get_template() );
	define( 'WCTI_THEME_NAMESPACE', 'WebManDesign\\' . str_replace(
		' ',
		'_',
		ucwords( str_replace(
			array( '-', '_' ),
			' ',
			WCTI_THEME_SLUG
		) )
	) );

	define( 'WCTI_VERSION_WP', '5.2' );
	define( 'WCTI_VERSION_PHP', '7.0' );

// Check that the site meets the minimum WP and PHP requirements.
if (
	version_compare( $GLOBALS['wp_version'], WCTI_VERSION_WP, '<' )
	|| version_compare( PHP_VERSION, WCTI_VERSION_PHP, '<' )
) {
	require_once WCTI_PATH . 'includes/Compatibility.php';
	return;
}

// Load the functionality.
require_once WCTI_PATH . 'includes/Autoload.php';
WebManDesign\WCTI\Loader::init();
