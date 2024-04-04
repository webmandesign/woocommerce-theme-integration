/**
 * Block modifications.
 *
 * @package    Integration for WooCommerce
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.5.0
 * @version  1.6.4
 */

( () => {
	'use strict';

	// Modify block supports.
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'wc-theme-integration/block-mods',
		( settings, name ) => {

			// Processing

				switch( name ) {

					case 'woocommerce/breadcrumbs':
						settings = lodash.merge( settings, {
							supports: {
								spacing: {
									margin: [
										'top',
										'bottom',
									],
								},
							},
						} );
						break;

					case 'woocommerce/mini-cart':
						settings.supports.color = lodash.merge( settings.supports.color, {
							text: true,
							background: true,
							gradients: true,
						} );
						// Allow multiple instances of Mini Cart for multiple headers (mobile, desktop).
						// @link  https://github.com/woocommerce/woocommerce/issues/46166
						settings.supports.multiple = true;
						break;

					case 'woocommerce/product-image':
						settings = lodash.merge( settings, {
							supports: {
								__experimentalBorder: {
									color: true,
									style: true,
									width: true,
									radius: true,
								},
							},
						} );
						break;
				}


			// Output

				return settings;

		},
		// Need to use low priority here so WordPress can hook with default
		// priority of 10 to add required `attributes` for us.
		5
	);

} )();
