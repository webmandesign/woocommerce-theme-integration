<?php
/**
 * Blocks.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.5.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Blocks {

	/**
	 * Initialization.
	 *
	 * @since  1.5.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'enqueue_block_editor_assets', __CLASS__ . '::enqueue_editor_mods' );

	} // /init

	/**
	 * Enqueues block editor assets for block modifications.
	 *
	 * @since  1.5.0
	 *
	 * @return  void
	 */
	public static function enqueue_editor_mods() {

		// Processing

			wp_enqueue_script(
				'wc-theme-integration-block-mods',
				WCTI_URL . 'assets/js/block-mods.min.js',
				array( 'wp-blocks', 'wp-hooks', 'lodash' ),
				'v' . WCTI_VERSION,
				true
			);

	} // /enqueue_editor_mods

}
