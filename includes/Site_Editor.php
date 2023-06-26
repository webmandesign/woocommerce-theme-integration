<?php
/**
 * Site editor functionality.
 *
 * Checks whether the theme is FSE enabled.
 *
 * We need to duplicate this functionality from the theme here
 * for early access by WooCommerce. It solves the issue where
 * WooCommerce checks for `wp_is_block_theme()` before the
 * `after_setup_theme` hook is fired.
 * @link  https://github.com/woocommerce/woocommerce/issues/37934
 *
 * We can not use `WebManDesign\Theme_Slug\Setup\Site_Editor()`
 * class here as it is not callable early enough for WooCommerce plugin.
 * So we need to recreate the functionality.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.4.0
 * @version  1.4.2
 */

namespace WebManDesign\WCTI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Site_Editor {

	/**
	 * Theme mod ID.
	 *
	 * @since   1.4.0
	 * @access  public
	 * @var     string
	 */
	public static $theme_mod_id = 'layout_site_editing';

	/**
	 * Is Site Editor enabled?
	 *
	 * @since   1.4.0
	 * @access  public
	 * @var     bool
	 */
	public static $is_enabled = false;

	/**
	 * Initialization.
	 *
	 * @since    1.4.0
	 * @version  1.4.2
	 *
	 * @return  void
	 */
	public static function init() {

		// Variables

			// If the theme contains the method, the theme mod is definitely TRUE by default.
			self::$is_enabled = (bool) get_theme_mod( self::$theme_mod_id, is_callable( WCTI_THEME_NAMESPACE . '\Setup\Site_Editor::is_enabled' ) );


		// Processing

			// Actions

				// Using late priority here, after the theme modifications.
				add_filter( 'theme_file_path', __CLASS__ . '::bypass_is_block_theme', 99 );

				add_filter( Hook::get_name( 'index/content' ), __CLASS__ . '::content_index', 10, 2 );

				add_filter( Hook::get_name( 'content/block_template_part/get_content' ), __CLASS__ . '::content_block_template_part', 10, 2 );

	} // /init

	/**
	 * Disabling block theme functionality (FSE) when needed.
	 *
	 * @see  WebManDesign\Theme_Slug\Setup\Site_Editor() theme class for more info.
	 *
	 * @since  1.4.0
	 *
	 * @param  string $path
	 *
	 * @return  string
	 */
	public static function bypass_is_block_theme( string $path ): string {

		// Output

			if (
				stripos( $path, 'templates/index.html' )
				&& ! self::$is_enabled
			) {
				return '';
			} else {
				return $path;
			}

	} // /bypass_is_block_theme

	/**
	 * Render index page content in classic mode of a hybrid theme.
	 *
	 * Block/hybrid theme can define these template parts:
	 * - 'woocommerce-archive-product'
	 * - 'woocommerce-taxonomy-product-cat'
	 * - 'woocommerce-taxonomy-product-tag'
	 * - 'woocommerce-taxonomy-product-attribute'
	 *
	 * Otherwise fallback content is used -> @see self::content_block_template_part().
	 *
	 * @since  1.4.2
	 *
	 * @param  array $block_template_parts
	 *
	 * @return  array
	 */
	public static function content_index( array $block_template_parts ): array {

		// Variables

			$query = '';


		// Processing

			if ( is_shop() ) {
				$query = ( is_search() ) ? ( 'woocommerce-product-search-results' ) : ( 'woocommerce-archive-product' );
			} elseif ( is_product_category() ) {
				$query = 'woocommerce-taxonomy-product-cat';
			} elseif ( is_product_tag() ) {
				$query = 'woocommerce-taxonomy-product-tag';
			} elseif ( is_product_taxonomy() ) {
				$query = 'woocommerce-taxonomy-product-attribute';
			}

			if ( ! empty( $query ) ) {
				$block_template_parts = array(
					'intro' => '',
					'query' => $query,
					'query-args' => array(
						'tag'   => 'div',
						'class' => 'wcti-block-template-part-content',
					),
				);
			}


		// Output

			return $block_template_parts;

	} // /content_index

	/**
	 * Render WooCommerce block content in classic mode of a hybrid theme.
	 *
	 * This is only a fallback if the theme does not contain such block
	 * template part.
	 *
	 * Using `woocommerce-` slug prefix here and expecting no such template
	 * part exists in the hybrid theme.
	 *
	 * @since  1.4.2
	 *
	 * @param  string $content
	 * @param  string $slug
	 *
	 * @return  string
	 */
	public static function content_block_template_part( string $content, string $slug ): string {

		// Processing

			if (
				empty( $content )
				&& 0 === strpos( $slug, 'woocommerce-' )
			) {

				// Get content from theme template parts first.
				$template_part = get_block_template( get_stylesheet() . '//' . $slug, 'wp_template_part' );
				if ( ! empty( $template_part->content ) ) {
					$content = do_blocks( $template_part->content );

				} else {
				// Then fall back to custom content.

					$template = str_replace(
						array(
							'woocommerce-',
							'product-cat',
							'product-tag',
							'product-attribute',
						),
						array(
							'',
							'product_cat',
							'product_tag',
							'product_attribute',
						),
						$slug
					);

					$content = do_blocks(

						/**
						 * Filters the WooCommerce block template part content in classic mode of hybrid theme.
						 *
						 * @since  1.4.2
						 *
						 * @param  string $content
						 * @param  string $slug
						 */
						(string) apply_filters(
							'WCTI/Site_Editor/block_template_part/content',
							'<!-- wp:group {"className":"has-content-padding","layout":{"type":"constrained"}} --><div class="wp-block-group has-content-padding">'
							. '<!-- wp:group {"align":"wide"} --><div class="wp-block-group alignwide">'
							. '<!-- wp:woocommerce/legacy-template {"template":"' . $template . '"} /-->'
							. '</div><!-- /wp:group -->'
							. '</div><!-- /wp:group -->',
							$slug
						)
					);
				}
			}


		// Output

			return $content;

	} // /content_block_template_part

}
