<?php
/**
 * Plugin Name:  WooCommerce Theme Integration by WebMan
 * Plugin URI:   https://github.com/webmandesign/woocommerce-theme-integration
 * Description:  Provides deeper integration for WooCommerce in themes.
 * Version:      1.0.0
 * Author:       WebMan Design, Oliver Juhas
 * Author URI:   https://www.webmandesign.eu/
 * License:      GNU General Public License v3
 * License URI:  http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:  woocommerce-theme-integration
 * Domain Path:  /languages
 *
 * GitHub Plugin URI:  https://github.com/webmandesign/woocommerce-theme-integration
 *
 * Update using GitHub Updater plugin.
 * @link  https://github.com/afragen/github-updater/releases
 *
 * @copyright  WebMan Design, Oliver Juhas
 * @license    GPL-3.0, https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @link  https://github.com/webmandesign/woocommerce-theme-integration
 * @link  https://www.webmandesign.eu
 *
 * @package  WooCommerce Theme Integration
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Constants:

	define( 'WCTI_FILE', __FILE__ );
	define( 'WCTI_VERSION', get_plugin_data( WCTI_FILE, false, false )['Version'] );
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
