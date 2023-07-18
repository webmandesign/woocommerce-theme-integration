<?php
/**
 * Pages.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.4.4
 */

namespace WebManDesign\WCTI;

use WP_Post;
use WC;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Pages {

	/**
	 * Initialization.
	 *
	 * @since    1.0.0
	 * @version  1.4.4
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'woocommerce_before_cart_table', __CLASS__ . '::cart_products_list_title' );

				add_action( 'woocommerce_before_checkout_form', __CLASS__ . '::checkout_title', 5 );

				add_action( 'woocommerce_cart_coupon', __CLASS__ . '::coupon_description' );

				add_action( 'woocommerce_proceed_to_checkout', __CLASS__ . '::button_continue_shopping', 30 );

				add_action( 'woocommerce_before_shipping_calculator', __CLASS__ . '::shipping_calculator_wrapper' );

				add_action( 'woocommerce_archive_description', __CLASS__ . '::page_header', 999 );

			// Filters

				add_filter( 'admin_body_class', __CLASS__ . '::body_class_admin' );

				add_filter( 'single_post_title', __CLASS__ . '::page_endpoint_title' );
					add_action( Hook::get_name( 'page_header/top' ), function() {
						add_filter( 'the_title', 'wc_page_endpoint_title' );
					} );

				add_filter( 'woocommerce_page_title', __CLASS__ . '::page_title' );

				add_filter( 'the_content', __CLASS__ . '::page_title_replace' );

				add_filter( 'woocommerce_cross_sells_columns', __CLASS__ . '::cross_sells_columns' );
				add_filter( 'woocommerce_cross_sells_total',   __CLASS__ . '::cross_sells_columns' );

				add_filter( 'woocommerce_show_page_title', __CLASS__ . '::page_header', -10 );

	} // /init

	/**
	 * HTML body classes in admin area.
	 *
	 * NOTE:
	 * The `post-type--` classes are hacks to fix block editor iframe body classes,
	 * when block editor is displayed in iframe and inherit only certain specific
	 * body classes from parent admin screen. This is really tragic...
	 * @link  https://github.com/WordPress/gutenberg/blob/v16.2.1/packages/block-editor/src/components/iframe/index.js#L123
	 * @link  https://github.com/WordPress/gutenberg/issues/17854#issuecomment-1638268100
	 *
	 * @since  1.4.4
	 *
	 * @param  string $classes
	 *
	 * @return  string
	 */
	public static function body_class_admin( string $classes ): string {

		// Requirements check

			global $post;

			if (
				! is_admin()
				|| ! $post instanceof WP_Post
			) {
				return $classes;
			}


		// Processing

			switch ( $post->ID ) {

				case wc_get_page_id( 'shop' ):
					$classes .= ' is-woocommerce-page--shop';
					$classes .= ' post-type--is-woocommerce-page--shop';
					$classes .= ' ';
					break;

				case wc_get_page_id( 'cart' ):
					$classes .= ' is-woocommerce-page--cart';
					$classes .= ' post-type--is-woocommerce-page--cart';
					$classes .= ' ';
					break;

				case wc_get_page_id( 'checkout' ):
					$classes .= ' is-woocommerce-page--checkout';
					$classes .= ' post-type--is-woocommerce-page--checkout';
					$classes .= ' ';
					break;

				case wc_get_page_id( 'myaccount' ):
					$classes .= ' is-woocommerce-page--myaccount';
					$classes .= ' post-type--is-woocommerce-page--myaccount';
					$classes .= ' ';
					break;

				case wc_get_page_id( 'terms' ):
					$classes .= ' is-woocommerce-page--terms';
					$classes .= ' post-type--is-woocommerce-page--terms';
					$classes .= ' ';
					break;
			}


		// Output

			return $classes;

	} // /body_class_admin

	/**
	 * Replace a page title with the endpoint title.
	 *
	 * This is a copy of `wc_page_endpoint_title()` modified for `single_post_title`
	 * hook and making it work outside the loop (removing `in_the_loop()` check).
	 *
	 * @since  1.0.0
	 *
	 * @param  string $title
	 *
	 * @return  string
	 */
	public static function page_endpoint_title( string $title ): string {

		// Variables

			global $wp_query;


		// Processing

			if (
				! is_null( $wp_query )
				&& ! is_admin()
				&& is_main_query()
				&& is_page()
				&& is_wc_endpoint_url()
			) {
				$endpoint       = WC()->query->get_current_endpoint();
				$endpoint_title = WC()->query->get_endpoint_title( $endpoint );
				$title          = ( $endpoint_title ) ? ( $endpoint_title ) : ( $title );

				// Run just once.
				remove_filter( current_filter(), __METHOD__ );
			}


		// Output

			return $title;

	} // /page_endpoint_title

	/**
	 * Page title replacing for dynamic WooCommerce titles.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $page_title
	 *
	 * @return  string
	 */
	public static function page_title( string $page_title ): string {

		// Output

			return $page_title;

	} // /page_title

	/**
	 * Page title replacing for dynamic WooCommerce titles.
	 *
	 * Useful in page builder "Checkout" or "My Account" page content.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $content  Page content
	 *
	 * @return  string
	 */
	public static function page_title_replace( string $content ): string {

		// Requirements check

			if ( ! function_exists( 'wc_page_endpoint_title' ) ) {
				return $content;
			}


		// Variables

			$title = get_the_title();
			$title = wc_page_endpoint_title( $title );


		// Output

			return str_replace( '%wc_title%', $title, $content );

	} // /page_title_replace

	/**
	 * Products list title.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function cart_products_list_title() {

		// Output

			echo
				'<h2 class="cart-table-title">'
				. esc_html__( 'Cart content', 'wc-theme-integration' )
				. '<small class="cart-table-products-count"> '
				. sprintf(
					/* translators: %d: number of shopping cart items. */
					esc_html( _nx( '(%d item)', '(%d items)', absint( wp_strip_all_tags( WC()->cart->get_cart_contents_count() ) ), 'Shopping cart items count.', 'wc-theme-integration' ) ),
					absint( wp_strip_all_tags( WC()->cart->get_cart_contents_count() ) )
				)
				. '</small>'
				. '</h2>';

	} // /cart_products_list_title

	/**
	 * Coupon description.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function coupon_description() {

		// Output

			echo '<p class="description coupon-description">' . esc_html__( 'Use your discount coupon code here', 'wc-theme-integration' ) . '</p>';

	} // /coupon_description

	/**
	 * Cross sells products columns/count.
	 *
	 * @since  1.0.0
	 *
	 * @return  int
	 */
	public static function cross_sells_columns(): int {

		// Output

			return 2;

	} // /cross_sells_columns

	/**
	 * Shipping calculator.
	 *
	 * @link  woocommerce/templates/cart/cart-shipping.php
	 * @link  woocommerce/templates/cart/shipping-calculator.php
	 *
	 * @since  1.0.0
	 *
	 * @param  int|string $index  Needed for conditional check.
	 *
	 * @return  void
	 */
	public static function shipping_calculator_wrapper( $index ) {

		// Output

			if ( is_cart() && ! $index ) {
				echo '</td></tr><tr class="shipping"><td class="shipping-calculator" colspan="2">';
			}

	} // /shipping_calculator_wrapper

	/**
	 * "Continue shopping" link.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function button_continue_shopping() {

		// Output

			echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="continue-shopping">' . esc_html__( 'Continue Shopping', 'wc-theme-integration' ) . '</a>';

	} // /button_continue_shopping

	/**
	 * Checkout page title to keep correct heading levels.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function checkout_title() {

		// Output

			echo '<h2 class="screen-reader-text">' . esc_html__( 'Checkout', 'wc-theme-integration' ) . '</h2>';

	} // /checkout_title

	/**
	 * Page header markup for theme compatibility.
	 *
	 * This is actually a hack as we are also hooking onto a filter, but works.
	 *
	 * @link  https://github.com/woocommerce/woocommerce/blob/trunk/templates/archive-product.php#L33
	 *
	 * @since  1.2.5
	 *
	 * @return  void
	 */
	public static function page_header() {

		// Output

			if ( doing_filter( 'woocommerce_show_page_title' ) ) {

				$class     = '';
				$shop_page = get_post( wc_get_page_id( 'shop' ) );

				if (
					(
						! is_search()
						&& is_post_type_archive( 'product' )
						&& in_array( absint( get_query_var( 'paged' ) ), array( 0, 1 ), true )
						&& $shop_page
						&& wc_format_content( wp_kses_post( $shop_page->post_content ) )
					)
					|| (
						is_product_taxonomy()
						&& 0 === absint( get_query_var( 'paged' ) )
						&& $term = get_queried_object()
						&& ! empty( $term->description )
					)
				) {
					$class .= ' has-page-summary';
				}


				// Page header markup opening:
				echo '<div id="page-header" class="page-header' . esc_attr( $class ) . '">';
				echo '<div class="page-header-content">';
				do_action( Hook::get_name( 'page_header/top' ) );
				echo '<div class="page-header-text' . esc_attr( $class ) . '">' . PHP_EOL;

				// This has to be here due to filter hook.
				return true;

			} elseif ( doing_action( 'woocommerce_archive_description' ) ) {

				// Page header markup closing:
				echo PHP_EOL . '</div>'; // /.page-header-text
				do_action( Hook::get_name( 'page_header/bottom' ) );
				echo '</div>'; // /.page-header-content
				echo '</div>' . PHP_EOL; // /#page-header

			}

	} // /page_header

}
