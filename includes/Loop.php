<?php
/**
 * WooCommerce loop (products lists).
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.4.7
 */

namespace WebManDesign\WCTI;

use WP_HTML_Tag_Processor;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Loop {

	/**
	 * Initialization.
	 *
	 * @since    1.0.0
	 * @version  1.4.6
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Removing hooks

				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

				remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );

				remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
				remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title' );

			// Actions

				add_action( 'woocommerce_before_shop_loop', __CLASS__ . '::products_sorting' );
				add_action( 'woocommerce_before_shop_loop', __CLASS__ . '::shop_loop_title', 100 );

				add_action( 'woocommerce_shop_loop_item_title', __CLASS__ . '::loop_product_title' );
				add_action( 'woocommerce_shop_loop_subcategory_title', __CLASS__ . '::loop_category_title' );

				add_action( 'woocommerce_before_subcategory_title', __CLASS__ . '::category_label', 95 );

				add_action( 'woocommerce_after_subcategory', __CLASS__ . '::category_button', 20 );

				add_action( 'woocommerce_after_shop_loop', __CLASS__ . '::products_sorting', 5 );
				add_action( 'woocommerce_after_shop_loop', __CLASS__ . '::pagination' );

			// Filters

				add_filter( 'woocommerce_pagination_args', __CLASS__ . '::pagination_args' );

				add_filter( 'the_title', __CLASS__ . '::search_results_product_title', 10, 2 );

				add_filter( Hook::get_name( 'loop/search_template_hierarchy/condition' ), __CLASS__ . '::search_template_hierarchy', 10, 2 );

				add_filter( 'woocommerce_product_get_image', __CLASS__ . '::product_image', 10, 4 );

	} // /init

	/**
	 * Category description top.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function category_label() {

		// Output

			echo '<p class="category-label">' . esc_html__( 'Shop category', 'wc-theme-integration' ) . '</p>';

	} // /category_label

	/**
	 * Category description bottom.
	 *
	 * @since  1.0.0
	 *
	 * @param  object|int|string $category
	 *
	 * @return  void
	 */
	public static function category_button( $category = null ) {

		// Variables

			$term_link = get_term_link( $category, 'product_cat' );


		// Requirements check

			if ( empty( $term_link ) || is_wp_error( $term_link ) ) {
				return;
			}


		// Output

			echo '<a href="' . esc_url( $term_link ) . '" class="button">' . esc_html__( 'Shop now &rarr;', 'wc-theme-integration' ) . '</a>';

	} // /category_button

	/**
	 * Products sorting.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function products_sorting() {

		// Output

			echo '<div class="products-sorting">';
				woocommerce_result_count();
				woocommerce_catalog_ordering();
			echo '</div>';

	} // /products_sorting

	/**
	 * Pagination.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function pagination() {

		// Requirements check

			if ( ! function_exists( 'woocommerce_pagination' ) ) {
				return;
			}


		// Processing

			ob_start();
			woocommerce_pagination();

			$pagination = str_replace(
				'<nav class="woocommerce-pagination',
				'<nav aria-label="' . esc_attr__( 'Products Navigation', 'wc-theme-integration' ) . '" data-current="' . esc_attr( wc_get_loop_prop( 'current_page' ) ) . '" data-total="' . esc_attr( wc_get_loop_prop( 'total_pages' ) ) . '" class="pagination woocommerce-pagination',
				ob_get_clean()
			);


		// Output

			echo $pagination;

	} // /pagination

	/**
	 * Pagination setup.
	 *
	 * @since    1.0.0
	 * @version  1.4.0
	 *
	 * @param  array $args
	 *
	 * @return  array
	 */
	public static function pagination_args( array $args = array() ): array {

		// Processing

			$args['type'] = 'plain';

			$args['prev_text'] =
				esc_html_x( '&larr;', 'Pagination text (visible): previous.', 'wc-theme-integration' )
				. '<span class="screen-reader-text"> '
				. esc_html_x( 'Previous page', 'Pagination text (hidden): previous.', 'wc-theme-integration' )
				. '</span>';

			$args['next_text'] =
				'<span class="screen-reader-text">'
				. esc_html_x( 'Next page', 'Pagination text (hidden): next.', 'wc-theme-integration' )
				. ' </span>'
				. esc_html_x( '&rarr;', 'Pagination text (visible): next.', 'wc-theme-integration' );


		// Output

			return $args;

	} // /pagination_args

	/**
	 * Products list title.
	 *
	 * Adding heading to non-titled lists to improve accessibility.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function shop_loop_title() {

		// Output

			echo '<h2 class="screen-reader-text">' . esc_html__( 'List of products', 'wc-theme-integration' ) . '</h2>';

	} // /shop_loop_title

	/**
	 * Show the product title in the product loop.
	 *
	 * By default this is an H2. Changing it to H3.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function loop_product_title() {

		// Output

			ob_start();
			woocommerce_template_loop_product_title();

			echo str_replace( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array( '<h2', '</h2' ),
				array( '<h3', '</h3' ),
				ob_get_clean()
			);

	} // /loop_product_title

	/**
	 * Show the subcategory title in the product loop.
	 *
	 * By default this is an H2. Changing it to H3.
	 *
	 * @since  1.0.0
	 *
	 * @param  object $category
	 *
	 * @return  void
	 */
	public static function loop_category_title( $category ) {

		// Output

			ob_start();
			woocommerce_template_loop_category_title( $category );

			echo str_replace( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array( '<h2', '</h2' ),
				array( '<h3', '</h3' ),
				ob_get_clean()
			);

	} // /loop_category_title

	/**
	 * Price in product title in global search.
	 *
	 * @since    1.0.0
	 * @version  1.2.2
	 *
	 * @param  string   $title The post title.
	 * @param  int/null $id    The post ID.
	 *
	 * @return  string
	 */
	public static function search_results_product_title( string $title, $id = 0 ): string {

		// Requirements check

			if (
				empty( $id )
				|| ! is_search()
				|| is_post_type_archive( 'product' )
				|| 'product' !== get_post_type( $id )
			) {
				return $title;
			}


		// Variables

			$product = wc_setup_product_data( $id );


		// Output

			return $title . ' <span class="price">' . $product->get_price_html() . '</span>';

	} // /search_results_product_title

	/**
	 * Search template hierarchy modification condition for hybrid theme.
	 *
	 * @since  1.4.2
	 *
	 * @param  bool   $condition
	 * @param  string $post_type
	 *
	 * @return  bool
	 */
	public static function search_template_hierarchy( bool $condition, string $post_type ): bool {

		// Processing

			if ( is_shop() ) {
				return false;
			}


		// Output

			return $condition;

	} // /search_template_hierarchy

	/**
	 * Modifying product image HTML.
	 *
	 * @since    1.4.6
	 * @version  1.4.7
	 *
	 * @param  string       $image    Image HTML.
	 * @param  WC_Product   $product  Product object.
	 * @param  string|int[] $size     Accepts any registered image size name, or an array of width and height values in pixels (in that order). Default: 'woocommerce_thumbnail'.
	 * @param  array        $attr     Image attributes.
	 *
	 * @return  string
	 */
	public static function product_image( string $image, $product, $size, array $attr ): string {

		// Processing

			if ( 'woocommerce_thumbnail' === $size ) {

				$html = new WP_HTML_Tag_Processor( $image );

				$html->next_tag();

				$width  = $html->get_attribute( 'width' );
				$height = $html->get_attribute( 'height' );

				if ( $width && $height ) {
					$html->set_attribute( 'style', 'aspect-ratio:' . absint( $width ) . '/' . absint( $height ) . ';' . (string) $html->get_attribute( 'style' ) );
				}

				$image = $html->get_updated_html();
			}


		// Output

			return $image;

	} // /product_image

}
