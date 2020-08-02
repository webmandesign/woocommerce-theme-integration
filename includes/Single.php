<?php
/**
 * Single product page.
 *
 * @package  WooCommerce Theme Integration
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\WCTI;

use WebManDesign\WCTI\Hook;
use WP_Theme;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Single {

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Removing

				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

				remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' );

				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );

				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

			// Actions

				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_output_all_notices', -5 );

				add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 0 );
				add_action( 'woocommerce_single_product_summary', __CLASS__ . '::rating_and_sale', 15 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 18 );

			// Filters

				add_filter( Hook::get_name( 'content/show_primary_title' ), __CLASS__ . '::show_primary_title', 20 );

				add_filter( Hook::get_name( 'sidebar/is_disabled' ), __CLASS__ . '::sidebar_disable' );

				add_filter( 'template_include', __CLASS__ . '::product_page_template_load', 20 );

				add_filter( 'woocommerce_format_price_range', __CLASS__ . '::price_separator' );

				add_filter( 'woocommerce_short_description', __CLASS__ . '::read_more_link', 5 );

				add_filter( 'woocommerce_comment_pagination_args', __CLASS__ . '::comment_pagination_args' );

				add_filter( 'woocommerce_upsell_display_args',          __CLASS__ . '::products_list_args' );
				add_filter( 'woocommerce_output_related_products_args', __CLASS__ . '::products_list_args' );

				add_filter( 'theme_templates', __CLASS__ . '::page_templates', 99, 4 );

	} // /init

	/**
	 * Product comments pagination setup.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $args
	 *
	 * @return  array
	 */
	public static function comment_pagination_args( array $args = array() ): array {

		// Processing

			$args['prev_text'] = esc_html_x( '&laquo;', 'Pagination text (visible): previous.', 'woocommerce-theme-integration' ) . '<span class="screen-reader-text"> ' . esc_html_x( 'Previous page', 'Pagination text (hidden): previous.', 'woocommerce-theme-integration' ) . '</span>';
			$args['next_text'] = '<span class="screen-reader-text">' . esc_html_x( 'Next page', 'Pagination text (hidden): next.', 'woocommerce-theme-integration' ) . ' </span>' . esc_html_x( '&raquo;', 'Pagination text (visible): next.', 'woocommerce-theme-integration' );
			$args['type']      = 'plain';


		// Output

			return $args;

	} // /comment_pagination_args

	/**
	 * Up-sells and related products setup.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $args
	 *
	 * @return  array
	 */
	public static function products_list_args( array $args ): array {

		// Processing

			$args['posts_per_page'] = $args['columns'] = absint( get_option( 'woocommerce_catalog_columns', wc_get_theme_support( 'product_grid::default_columns', 3 ) ) );


		// Output

			return $args;

	} // /products_list_args

	/**
	 * Fix for page template assigned onto a product.
	 *
	 * This will make sure we are actually loading the product post
	 * content, and not the content defined within the page template.
	 * Basically, we make sure the page template is used to provide
	 * HTML body class and post class, but we still want to display
	 * the actual product post content.
	 *
	 * This is also a fix for WooCommerce 3.2+ version.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $template
	 *
	 * @return  string
	 */
	public static function product_page_template_load( string $template ): string {

		// Processing

			if (
				'product' === get_post_type()
				&& is_page_template()
				&& function_exists( 'wc_locate_template' )
			) {
				$template = wc_locate_template( 'single-product.php' );
			}


		// Output

			return $template;

	} // /product_page_template_load

	/**
	 * Price "from-to" separator.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $html
	 *
	 * @return  string
	 */
	public static function price_separator( string $html ): string {

		// Output

			return '<span class="price-range">' . str_replace(
				array(
					'&ndash;',
					'&mdash;',
				),
				array(
					'<span class="amount-separator">&ndash;</span>',
					'<span class="amount-separator">&mdash;</span>',
				),
				$html
			) . '</span>';

	} // /price_separator

	/**
	 * Excerpt read more link.
	 *
	 * IMPORTANT:
	 * With `$excerpt == $post->post_excerpt` below we make sure
	 * we are targeting product excerpt only! This is important
	 * as `woocommerce_short_description` filter this is hooked
	 * onto, is applied on other places too. Also, due to this
	 * check we need to hook the method before `wptexturize()`
	 * is applied.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $excerpt
	 *
	 * @return  string
	 */
	public static function read_more_link( string $excerpt ): string {

		// Requirements check

			if (
				! is_product()
				|| empty( $excerpt )
			) {
				return $excerpt;
			}


		// Variables

			global $product, $post;


		// Processing

			if (
				$excerpt === $post->post_excerpt
				&& ( $post->post_content || $product->has_attributes() )
			) {
				$excerpt .= '<div class="product-description-link-container"><a class="product-description-link" href="#product-more-info">' . esc_html__( 'More details&hellip;', 'woocommerce-theme-integration' ) . '</a></div>';

				add_action( 'woocommerce_after_single_product_summary', function() {
					echo PHP_EOL . '<a name="product-more-info"></a>' . PHP_EOL;
				}, 9 );
			}


		// Output

			return $excerpt;

	} // /read_more_link

	/**
	 * Hide theme primary title on single product page.
	 *
	 * @since  1.0.0
	 *
	 * @param  bool $show
	 *
	 * @return  bool
	 */
	public static function show_primary_title( bool $show ): bool {

		// Output

			if ( is_product() ) {
				return false;
			} else {
				return $show;
			}

	} // /show_primary_title

	/**
	 * Removing page templates for products.
	 *
	 * @since  1.0.0
	 *
	 * @param  array        $post_templates
	 * @param  WP_Theme     $wp_theme
	 * @param  WP_Post|null $post
	 * @param  string       $post_type
	 *
	 * @return  array
	 */
	public static function page_templates( array $post_templates, WP_Theme $wp_theme, $post, string $post_type ): array {

		// Processing

			if ( 'product' === $post_type ) {
				unset( $post_templates['templates/content-only.php'] );
				unset( $post_templates['templates/no-intro.php'] );
			}


		// Output

			return $post_templates;

	} // /page_templates

	/**
	 * Disable sidebar on single product pages.
	 *
	 * @since  1.0.0
	 *
	 * @param  bool $bool
	 *
	 * @return  bool
	 */
	public static function sidebar_disable( bool $bool = false ): bool {

			// Processing

				if ( is_product() ) {
					$bool = true;
				}


			// Output

				return $bool;

	} // /sidebar_disable

	/**
	 * Wrap star rating and sale badge.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function rating_and_sale() {

			// Output

				echo '<div class="product-rating-sale-container">';
				woocommerce_show_product_sale_flash();
				woocommerce_template_single_rating();
				echo '</div>';

	} // /rating_and_sale

}
