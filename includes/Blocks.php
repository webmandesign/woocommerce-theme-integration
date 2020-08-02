<?php
/**
 * Blocks.
 *
 * @package  WooCommerce Theme Integration
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Blocks {

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Filters

				add_filter( 'woocommerce_blocks_product_grid_item_html', __CLASS__ . '::product_grid_item_html', 10, 3 );

	} // /init

	/**
	 * Matching products block HTML to products list in archives.
	 *
	 * @since  1.0.0
	 *
	 * @param  string     $html
	 * @param  object     $data
	 * @param  WC_Product $product
	 *
	 * @return  string
	 */
	public static function product_grid_item_html( $html, $data, $product ): string {

		// Variables

			$has_title   = strpos( $html, 'wc-block-grid__product-title' );
			$key_title   = 0;
			$description = array();
			$html        = explode( PHP_EOL, $html );


		// Processing

			foreach (	$html as $key => $line ) {
				if ( $has_title && strpos( $line, 'wc-block-grid__product-title' ) ) {
					$key_title = $key;
				} else if ( ! $has_title && strpos( $line, 'wc-block-grid__product-image' ) ) {
					$key_title = $key + 1;
				} else if ( strpos( $line, 'wc-block-grid__product-rating' ) ) {
					$description[10] = $html[ $key ];
					unset( $html[ $key ] );
				} else if ( strpos( $line, 'wc-block-grid__product-onsale' ) ) {
					$description[20] = $html[ $key ] . $html[ $key + 1 ] . $html[ $key + 2 ] . $html[ $key + 3 ];
					unset( $html[ $key ] );
					unset( $html[ $key + 1 ] );
					unset( $html[ $key + 2 ] );
					unset( $html[ $key + 3 ] );
				} else if ( strpos( $line, 'wc-block-grid__product-price' ) ) {
					$description[30] = $html[ $key ];
					unset( $html[ $key ] );
				}
			}

			$description = array_filter( $description );

			if ( ! empty( $description ) ) {
				ksort( $description );

				$html[ $key_title ] =
					'<div class="wc-block-grid__product-description">'
					. PHP_EOL
					. $html[ $key_title ]
					. PHP_EOL
					. implode( PHP_EOL, $description )
					. PHP_EOL
					. '</div>';
			}


		// Output

			return implode( PHP_EOL, $html );

	} // /product_grid_item_html

}
