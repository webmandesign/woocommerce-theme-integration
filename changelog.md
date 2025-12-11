# Integration for WooCommerce Changelog

## 1.8.0, 20251211

### Added
- Option to set position of WooCommerce store notice

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Options.php
	includes/Setup.php


## 1.7.4, 20251210

### Fixed
- CSS styles (accommodating for WC 10.3)

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss
	assets/scss/block/mini-cart.scss
	assets/scss/block/order-confirmation-totals-wrapper.scss


## 1.7.3, 20250828

### Fixed
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss


## 1.7.2, 20250418

### Fixed
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/block/checkout.scss


## 1.7.1, 20250118

### Fixed
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss

## 1.7.0, 20241231

### Added
- CSS styles for WC block layouts

### Updated
- Making page endpoint titles work with blocks
- Fixing and updating CSS styles

### Fixed
- Preventing PHP errors
- Removing obsolete page templates from Product

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss
	assets/scss/block/cart.scss
	assets/scss/block/checkout.scss
	assets/scss/block/mini-cart.scss
	assets/scss/block/order-confirmation-billing-wrapper.scss
	assets/scss/block/order-confirmation-downloads-wrapper.scss
	assets/scss/block/order-confirmation-shipping-wrapper.scss
	assets/scss/block/order-confirmation-totals-wrapper.scss
	assets/scss/block/product-button.scss
	assets/scss/block/product-categories.scss
	assets/scss/block/product-sku.scss
	assets/scss/block/product-template.scss
	includes/Blocks.php
	includes/Single.php


## 1.6.6, 20241011

### Fixed
- Index content in universal theme's classic mode

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Site_Editor.php


## 1.6.5, 20240921

### Added
- Option to simplify WooCommerce pattern categories

### Updated
- Localization

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Blocks.php
	includes/Options.php
	languages/*.*


## 1.6.4, 20240406

### Updated
- Improving styles, introducing more CSS variables
- Removing obsolete HTML comments to prevent styling issues
- Allowing multiple instances of Mini Cart block
- Allowing themes to opt out of custom add to cart button styles

### Fixed
- AJAX add to cart button status indicators

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/js/block-mods.js
	assets/scss/blocks.scss
	assets/scss/woocommerce.scss
	assets/scss/block/mini-cart.scss
	includes/Options.php
	includes/Setup.php
	includes/Wrappers.php


## 1.6.3, 20240223

### Added
- Body class if shop page has content

### Fixed
- Block styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/woocommerce.scss
	assets/scss/block/mini-cart.scss
	assets/scss/block/order-confirmation-summary.scss
	assets/scss/block/product-categories.scss
	assets/scss/block/product-details.scss
	assets/scss/block/rating-filter.scss
	includes/Pages.php


## 1.6.2, 20240210

### Added
- Mini Cart block stylesheet

### Update
- Moving specific block styles from generic blocks stylesheet to dedicated stylesheets

### Fixed
- Preventing My Account login forms width issue

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss
	assets/scss/block/customer-account.scss
	assets/scss/block/featured-category.scss
	assets/scss/block/featured-product.scss
	assets/scss/block/mini-cart.scss
	assets/scss/block/product-categories.scss


## 1.6.1, 20240208

### Fixed
- "More details..." link functionality in classic WC layouts

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Blocks.php
	includes/Single.php


## 1.6.0, 20240208

### Added
- Support for WooCommerce template blocks (FSE)

### Update
- Improving CSS styles introducing more CSS variables
- Customizer options
- Localization

### Fixed
- Reviews section CSS styles
- Demo notice CSS styles
- Products list block CSS styles
- Application of block styles in classic/hybrid themes

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/js/block-mods.js
	assets/scss/blocks.scss
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss
	assets/scss/block/add-to-cart-form.scss
	assets/scss/block/cart-cross-sells-products-block.scss
	assets/scss/block/customer-account.scss
	assets/scss/block/product-details.scss
	assets/scss/block/product-image-gallery.scss
	includes/Assets.php
	includes/Blocks.php
	includes/Loop.php
	includes/Pages.php
	includes/Single.php
	includes/Wrappers.php
	languages/*.*


## 1.5.4, 20231228

### Fixed
- Notice block styles
- Targeting heading styles more precisely
- Address columns styles in order confirmation/details page
- Form label styles for checkboxes and radio buttons

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/woocommerce.scss


## 1.5.3, 20231117

### Fixed
- Block editor CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	includes/Assets.php


## 1.5.2, 20230901

### Fixed
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss


## 1.5.1, 20230901

### Updated
- CSS styles

### Fixed
- Editor CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss
	includes/Assets.php


## 1.5.0, 20230823

### Added
- WooCommerce 8 compatibility
- Option to set up related products columns number
- Option to set up upsell products columns number

### Updated
- Improving WooCommerce blocks
- CSS styles
- Localization

### Fixed
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/js/block-mods.js
	assets/scss/blocks.scss
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss
	includes/Autoload.php
	includes/Blocks.php
	includes/Loader.php
	includes/Options.php
	includes/Single.php
	languages/*.*


## 1.4.7, 20230726

### Fixed
- PHP error

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Loop.php


## 1.4.6, 20230725

### Fixed
- Products list image custom aspect ratio

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss
	includes/Loop.php


## 1.4.5, 20230719

### Fixed
- Products list block styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss


## 1.4.4, 20230718

### Added
- Edit page body admin class for WooCommerce pages
- CSS variables to control Featured Product/Category heading size

### Updated
- Mini Cart block styles

### Fixed
- Single product badge position
- Order view styles
- Featured Product and Category block styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/woocommerce.scss
	includes/Pages.php


## 1.4.3, 20230705

### Fixed
- Improving block theme support

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Options.php
	includes/Setup.php
	includes/Site_Editor.php


## 1.4.2, 20230627

### Updated
- Responsive CSS styles
- Removing obsolete code

### Fixed
- Stylesheet enqueuing
- CSS variables
- Hybrid theme issues
- Single product template application

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/custom-properties.scss
	includes/Assets.php
	includes/Loop.php
	includes/Pages.php
	includes/Setup.php
	includes/Single.php
	includes/Site_Editor.php


## 1.4.1, 20230625

### Updated
- Loading stylesheet in block theme editor
- Splitting stylesheets

### Fixed
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/blocks.scss
	assets/scss/custom-properties.scss
	assets/scss/woocommerce.scss
	includes/Assets.php


## 1.4.0, 20230623

### Added
- Block and hybrid theme support
- Option to set up products list columns on mobile devices

### Updated
- CSS styles
- Localization
- Removing obsolete code

### Fixed
- Theme hook name functionality
- CSS styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss
	assets/scss/woocommerce/_custom-properties.scss
	includes/Autoload.php
	includes/Hook.php
	includes/Loader.php
	includes/Loop.php
	includes/Options.php
	includes/Setup.php
	includes/Single.php
	includes/Site_Editor.php


## 1.3.6, 20220225

### Fix
- Product reviews styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.3.5, 20211222

### Fix
- Checkout payment methods styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.3.4, 20211212

### Fix
- Password reset styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.3.3, 20211117

### Fix
- Preventing PHP error

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Assets.php


## 1.3.2, 20211114

### Update
- Improving styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.3.1, 20211108

### Update
- Improving and fixing styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.3.0, 20210713

### Add
- Customizer option to control theme search form replacement

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	includes/Autoload.php
	includes/Loader.php
	includes/Options.php
	includes/Setup.php
	languages/*.*


## 1.2.5, 20210311

### Update
- Plugin name for WPORG repository

### File updates
	*.*


## 1.2.4, 20210311

### Update
- Plugin name for WPORG repository

### File updates
	*.*


## 1.2.3, 20210311

### Add
- Shop page title styles
- Fallback values to CSS variables

### Fix
- WooCommerce blocks styles

### File updates
	changelog.md
	readme.txt
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.2.2, 20210126

### Update
- Preventing PHP error related to PHP7+ type hinting and plugins such as WPML

### File updates
	changelog.md
	wc-theme-integration.php
	includes/Loop.php


## 1.2.1, 20201226

### Fix
- PHP error introduced in previous version

### File updates
	changelog.md
	wc-theme-integration.php
	includes/Assets.php


## 1.2.0, 20201215

### Update
- All JavaScript is now vanilla (no jQuery)
- Calling `get_plugin_data` only when needed

### File updates
	changelog.md
	wc-theme-integration.php
	includes/Assets.php


## 1.1.0, 20201119

### Add
- Featured Product block styling
- Active Filters block styling

### Update
- Styles

### Fixed
- Password reset procedure styles

### File updates
	changelog.md
	wc-theme-integration.php
	assets/scss/woocommerce.scss


## 1.0.0, 20200804

- Initial release.
