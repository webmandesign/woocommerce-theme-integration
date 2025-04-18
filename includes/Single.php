<?php
/**
 * Single product page.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.7.0
 */

namespace WebManDesign\WCTI;

use WP_Theme;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Single {

	/**
	 * Initialization.
	 *
	 * //* = Affects (FSE) blocks.
	 *
	 * @since    1.0.0
	 * @version  1.6.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Removing hooks

				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

				remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' );

				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

			// Actions

				// See `Blocks::hooks_in_blocks()` for removal in FSE blocks.
				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_output_all_notices', -5 ); //*

				// See `Blocks::hooks_in_blocks()` for removal in FSE blocks.
				add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 0 ); //*
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 18 ); //*

			// Filters

				add_filter( Hook::get_name( 'content/show_primary_title' ), __CLASS__ . '::show_primary_title', 20 );

				add_filter( Hook::get_name( 'sidebar/is_disabled' ), __CLASS__ . '::sidebar_disable' );

				add_filter( 'template_include', __CLASS__ . '::single_product_template', 20 );

				add_filter( 'woocommerce_format_price_range', __CLASS__ . '::price_separator' );

				add_filter( 'woocommerce_short_description', __CLASS__ . '::read_more_link', 5 );

				add_filter( 'woocommerce_comment_pagination_args', __CLASS__ . '::comment_pagination_args' );

				add_filter( 'woocommerce_upsell_display_args',          __CLASS__ . '::products_list_args' );
				add_filter( 'woocommerce_output_related_products_args', __CLASS__ . '::products_list_args' );

				add_filter( 'theme_templates', __CLASS__ . '::page_templates', 99, 4 );

				add_filter( Hook::get_name( 'entry/content_wrapper/classes' ), __CLASS__ . '::content_wrapper_classes' );

	} // /init

	/**
	 * Product comments pagination setup.
	 *
	 * @since    1.0.0
	 * @version  1.4.0
	 *
	 * @param  array $args
	 *
	 * @return  array
	 */
	public static function comment_pagination_args( array $args = array() ): array {

		// Processing

			$args['prev_text'] = esc_html_x( '&larr;', 'Pagination text (visible): previous.', 'wc-theme-integration' ) . '<span class="screen-reader-text"> ' . esc_html_x( 'Previous page', 'Pagination text (hidden): previous.', 'wc-theme-integration' ) . '</span>';
			$args['next_text'] = '<span class="screen-reader-text">' . esc_html_x( 'Next page', 'Pagination text (hidden): next.', 'wc-theme-integration' ) . ' </span>' . esc_html_x( '&rarr;', 'Pagination text (visible): next.', 'wc-theme-integration' );
			$args['type']      = 'plain';


		// Output

			return $args;

	} // /comment_pagination_args

	/**
	 * Up-sells and related products setup.
	 *
	 * @since    1.0.0
	 * @version  1.5.0
	 *
	 * @param  array $args
	 *
	 * @return  array
	 */
	public static function products_list_args( array $args ): array {

		// Variables

			$default = wc_get_theme_support( 'product_grid::default_columns', 3 );

			if ( 'woocommerce_upsell_display_args' === current_filter() ) {
				$columns = get_theme_mod( Options::$id['upsell_products_columns'], $default );
			} else {
				$columns = get_theme_mod( Options::$id['related_products_columns'], $default );
			}


		// Processing

			$args['posts_per_page'] = $args['columns'] = absint( $columns );


		// Output

			return $args;

	} // /products_list_args

	/**
	 * Fix for page template assigned onto a product.
	 *
	 * NOTE:
	 * Pre v1.4.2 the condition below contained `&& is_page_template()`
	 * and the description text below (the "OBSOLETE:" text) applied.
	 * Since 1.4.2 we are forcing the single product template on all
	 * products also to fix hybrid theme classic mode.
	 * Making it work with block themes.
	 * Testing with classic themes this works too.
	 * Also, the function was renamed in 1.4.2.
	 *
	 * OBSOLETE:
	 * This will make sure we are actually loading the product post
	 * content, and not the content defined within the page template.
	 * Basically, we make sure the page template is used to provide
	 * HTML body class and post class, but we still want to display
	 * the actual product post content.
	 *
	 * This is also a fix for WooCommerce 3.2+ version.
	 *
	 * @since    1.0.0
	 * @version  1.7.0
	 *
	 * @param  null|string $template
	 *
	 * @return  null|string
	 */
	public static function single_product_template( $template ) {

		// Processing

			if (
				is_product()
				&& ! wp_is_block_theme()
			) {
				$template = wc_locate_template( 'single-product.php' );
			}


		// Output

			return $template;

	} // /single_product_template

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
	 * //* = Affects (FSE) blocks.
	 *
	 * For blocks version check:
	 * @see  Blocks::render__single_product_more_details_link()
	 *
	 * @since    1.0.0
	 * @version  1.6.0
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
				$excerpt .= '<div class="product-description-link-container"><a class="product-description-link" href="#product-more-info">' . esc_html__( 'More details&hellip;', 'wc-theme-integration' ) . '</a></div>';

				// See `Blocks::hooks_in_blocks()` for removal in FSE blocks.
				add_action( 'woocommerce_after_single_product_summary', __CLASS__ . '::anchor_more_info', 9 ); //*
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
	 * @since    1.0.0
	 * @version  1.7.0
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

				$page_templates = array_filter(

					/**
					 * Filters the array of page/post templates to unset for Product post.
					 *
					 * @since    1.4.0
					 * @version  1.7.0
					 *
					 * @param  array $page_templates
					 */
					(array) apply_filters( 'WCTI/Single/page_templates', array(

						'templates/content-only.php',
						'templates/no-intro.php',
						'templates/overlaid-header.php',

						'templates/custom-content-only.php',
						'templates/custom-no-intro.php',
						'templates/custom-overlaid-header.php',

						'templates/custom-content-only.html',
						'templates/custom-no-intro.html',
						'templates/custom-overlaid-header.html',

						'custom-content-only',
						'custom-no-intro',
						'custom-overlaid-header',
					) )
				);

				foreach ( $page_templates as $template ) {
					unset( $post_templates[ $template ] );
				}
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
	 * Product content wrapper classes.
	 *
	 * @since  1.4.0
	 *
	 * @param  array $classes
	 *
	 * @return  array
	 */
	public static function content_wrapper_classes( array $classes ): array {

			// Processing

				if ( is_product() ) {
					$classes = array(
						'product-content',
					);
				}


			// Output

				return $classes;

	} // /content_wrapper_classes

	/**
	 * Product more info anchor.
	 *
	 * @since    1.6.0
	 * @version  1.6.1
	 *
	 * @param  mixed $method
	 *
	 * @return  void|string
	 */
	public static function anchor_more_info( $method = 'echo' ) {

			// Variables

				$output = PHP_EOL . '<a name="product-more-info"></a>' . PHP_EOL;


			// Output

				if ( 'return' === $method ) {
					return $output;
				} else {
					echo $output;
				}

	} // /anchor_more_info

}
