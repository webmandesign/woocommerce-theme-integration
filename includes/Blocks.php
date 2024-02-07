<?php
/**
 * Blocks.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.5.0
 * @version  1.6.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Blocks {

	/**
	 * Initialization.
	 *
	 * @since    1.5.0
	 * @version  1.6.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'enqueue_block_editor_assets', __CLASS__ . '::enqueue_editor_mods' );

				add_action( 'init', __CLASS__ . '::styles' );

			// Filters

				add_filter( 'block_type_metadata_settings', __CLASS__ . '::block_settings', 10, 2 );

				add_filter( 'render_block_core/post-excerpt',           __CLASS__ . '::render__single_product_more_details_link', 10, 2 );
				add_filter( 'render_block_woocommerce/product-details', __CLASS__ . '::render__single_product_more_details_link', 10, 2 );

				add_action( 'woocommerce_blocks_hook_compatibility_additional_data', __CLASS__ . '::hooks_in_blocks' );

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

	/**
	 * Registers individual block styles.
	 *
	 * @since  1.6.0
	 *
	 * @return  void
	 */
	public static function styles() {

		// Processing

			foreach ( array_filter( (array) glob( WCTI_PATH . 'assets/css/block/*.css' ) ) as $file ) {

				$block = basename( $file, '.css' );

				wp_enqueue_block_style(
					'woocommerce/' . $block,
					array(
						'handle' => 'wc-theme-integration-block-' . $block,
						'src'    => WCTI_URL . 'assets/css/block/' . $block . '.css',
						'path'   => $file,
						'ver'    => 'v' . WCTI_VERSION,
					)
				);
			}

	} // /styles

	/**
	 * Block (block type) settings modification.
	 *
	 * No need to enable specific options,
	 * simply enabling whole groups of options.
	 *
	 * @since  1.6.0
	 *
	 * @param  array $settings  Array of determined settings for registering a block type.
	 * @param  array $metadata  Metadata provided for registering a block type.
	 *
	 * @return  array
	 */
	public static function block_settings( array $settings, array $metadata ): array {

		// Processing

			switch ( $metadata['name'] ) {

				/**
				 * Margin.
				 */
				case 'woocommerce/breadcrumbs':
					$settings['supports']['spacing']['margin'] = true;
					break;
			}


		// Output

			return $settings;

	} // /block_settings

	/**
	 * Block output modification: Add single product "More details" link functionality.
	 *
	 * @since  1.6.0
	 *
	 * @param  string $block_content  The rendered content. Default null.
	 * @param  array  $block          The block being rendered.
	 *
	 * @return  string
	 */
	public static function render__single_product_more_details_link( string $block_content, array $block ): string {

		// Processing

			if (
				'core/post-excerpt' === $block['blockName']
				&& ! empty( $block['attrs']['__woocommerceNamespace'] )
				&& stripos( $block_content, '</div>' )
			) {

				$block_content =
					substr( $block_content, 0, -6 )
					. '<div class="product-description-link-container"><a class="product-description-link" href="#product-more-info">' . esc_html__( 'More details&hellip;', 'wc-theme-integration' ) . '</a></div>'
					. '</div>';

			} elseif ( 'woocommerce/product-details' === $block['blockName'] ) {

				$block_content = str_replace(
					'<div class="woocommerce-tabs',
					trim( Single::anchor_more_info( false ) ) . PHP_EOL . '<div class="woocommerce-tabs',
					$block_content
				);
			}


		// Output

			return $block_content;

	} // /render__single_product_more_details_link

	/**
	 * Removes custom functionality in WooCommerce FSE blocks.
	 *
	 * Accepts an array of hooked data. The array should be in the following format:
	 * [
	 *   [
	 *     hook     => <hook-name>,
	 *     function => <function-name>,
	 *     priority => <priority>,
	 *  ],
	 *  ...
	 * ]
	 *
	 * @link  https://github.com/woocommerce/woocommerce/blob/8.5.2/plugins/woocommerce/src/Blocks/Templates/AbstractTemplateCompatibility.php#L165
	 *
	 * @since  1.6.0
	 *
	 * @param  array $data  Additional hooked data. Default to empty
	 *
	 * @return  array
	 */
	public static function hooks_in_blocks( array $data ): array {

		// Output

			return array_merge( $data, array(

				// From `Loop::init()`:

					'WebManDesign\WCTI\Loop::pagination' => array(
						'hook'     => 'woocommerce_after_shop_loop',
						'function' => __NAMESPACE__ . '\Loop::pagination',
						'priority' => 10,
					),

					'WebManDesign\WCTI\Loop::loop_product_title' => array(
						'hook'     => 'woocommerce_shop_loop_item_title',
						'function' => __NAMESPACE__ . '\Loop::loop_product_title',
						'priority' => 10,
					),

					'WebManDesign\WCTI\Loop::loop_category_title' => array(
						'hook'     => 'woocommerce_shop_loop_subcategory_title',
						'function' => __NAMESPACE__ . '\Loop::loop_category_title',
						'priority' => 10,
					),

					'WebManDesign\WCTI\Loop::products_sorting/before' => array(
						'hook'     => 'woocommerce_before_shop_loop',
						'function' => __NAMESPACE__ . '\Loop::products_sorting',
						'priority' => 10,
					),
					'WebManDesign\WCTI\Loop::products_sorting/after' => array(
						'hook'     => 'woocommerce_after_shop_loop',
						'function' => __NAMESPACE__ . '\Loop::products_sorting',
						'priority' => 5,
					),

					'WebManDesign\WCTI\Loop::shop_loop_title' => array(
						'hook'     => 'woocommerce_before_shop_loop',
						'function' => __NAMESPACE__ . '\Loop::shop_loop_title',
						'priority' => 100,
					),

				// From `Pages::init()`:

					'WebManDesign\WCTI\Pages::page_header' => array(
						'hook'     => 'woocommerce_archive_description',
						'function' => __NAMESPACE__ . '\Pages::page_header',
						'priority' => 999,
					),

				// From `Single::init()`:

					'WebManDesign\WCTI\Single::anchor_more_info' => array(
						'hook'     => 'woocommerce_after_single_product_summary',
						'function' => __NAMESPACE__ . '\Single::anchor_more_info',
						'priority' => 9,
					),

					'WebManDesign\WCTI\Single::woocommerce_breadcrumb' => array(
						'hook'     => 'woocommerce_single_product_summary',
						'function' => 'woocommerce_breadcrumb',
						'priority' => 0,
					),

					'WebManDesign\WCTI\Single::woocommerce_output_all_notices' => array(
						'hook'     => 'woocommerce_before_single_product_summary',
						'function' => 'woocommerce_output_all_notices',
						'priority' => -5,
					),

					'WebManDesign\WCTI\Single::woocommerce_template_single_sharing' => array(
						'hook'     => 'woocommerce_single_product_summary',
						'function' => 'woocommerce_template_single_sharing',
						'priority' => 18,
					),

				// From `Wrappers::init()`:

					'WebManDesign\WCTI\Wrappers::product_summary\before' => array(
						'hook'     => 'woocommerce_before_single_product_summary',
						'function' => __NAMESPACE__ . '\Wrappers::product_summary',
						'priority' => 0,
					),
					'WebManDesign\WCTI\Wrappers::product_summary\after' => array(
						'hook'     => 'woocommerce_after_single_product_summary',
						'function' => __NAMESPACE__ . '\Wrappers::product_summary',
						'priority' => 0,
					),
			) );

	} // /hooks_in_blocks

}
