<?php
/**
 * Setup.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.5.0
 */

namespace WebManDesign\WCTI;

use WebManDesign\WCTI\Hook;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Setup {

	/**
	 * Initialization.
	 *
	 * @since    1.0.0
	 * @version  1.5.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'after_setup_theme', __CLASS__ . '::after_setup_theme', 30 );

				add_action( 'wp', __CLASS__ . '::replace_theme_search' );

			// Filters

				add_filter( 'woocommerce_review_gravatar_size', __CLASS__ . '::review_gravatar_size' );

				add_filter( 'woocommerce_breadcrumb_defaults', __CLASS__ . '::breadcrumb_defaults' );

				add_filter( Hook::get_name( 'setup/post_type/get_feature' ), __CLASS__ . '::add_post_type', 10, 2 );

	} // /init

	/**
	 * After setup theme.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function after_setup_theme() {

		// Processing

			add_theme_support( 'woocommerce', array(
				'thumbnail_image_width'         => 480,
				'single_image_width'            => 800,
				'gallery_thumbnail_image_width' => 200,

				'product_grid' => array(

					'default_columns' => 3,
					'min_columns'     => 2,
					'max_columns'     => 6,

					'default_rows' => 3,
					'min_rows'     => 2,
					'max_rows'     => 8,

				),
			) );

			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'wc-product-gallery-zoom' );

	} // /after_setup_theme

	/**
	 * Breadcrumbs setup.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $args
	 *
	 * @return  array
	 */
	public static function breadcrumb_defaults( array $args ): array {

		// Processing

			$args['before']    = '<span class="woocommerce-breadcrumb-item">';
			$args['after']     = '</span>';
			$args['delimiter'] = '<span class="woocommerce-breadcrumb-delimiter">&nbsp;&#47;&nbsp;</span>';


		// Output

			return $args;

	} // /breadcrumb_defaults

	/**
	 * Allow features to work with products.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $post_types
	 * @param  string $feature
	 *
	 * @return  array
	 */
	public static function add_post_type( array $post_types, string $feature = '' ): array {

		// Processing

			if ( in_array( $feature, array( 'continue_reading', 'post_navigation' ) ) ) {
				$post_types[] = 'product';
			}


		// Output

			return $post_types;

	} // /add_post_type

	/**
	 * Review author avatar image size.
	 *
	 * @since  1.0.0
	 *
	 * @return  int
	 */
	public static function review_gravatar_size(): int {

		// Output

			return 120;

	} // /review_gravatar_size

	/**
	 * Replace theme search.
	 *
	 * @since    1.3.0
	 * @version  1.5.0
	 *
	 * @return  void
	 */
	public static function replace_theme_search() {

		// Processing

			if (
				get_theme_mod( Options::$id['replace_theme_search'], true )
				&& ! Site_Editor::$is_enabled
			) {
				remove_action( Hook::get_name( 'search_form' ), 'get_search_form' );
				add_action( Hook::get_name( 'search_form' ), 'get_product_search_form' );
			}

	} // /replace_theme_search

}
