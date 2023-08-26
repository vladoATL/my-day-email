<?php

?>

<div class="wrap woocommerce">
	<div id="anniversaryemail-setting"  class="myday-setting">
<div class="loader_cover">
	<div class="anniversary_loader"></div> </div>
<input type="button" value="<?php echo  __( 'Restore Defaults', 'my-day-email' ); ?>" class="button button-primary"
attr-nonce="<?php echo esc_attr( wp_create_nonce( '_anniversaryemail_nonce' ) ); ?>"
id="restore_anni_values_btn" />

<div class="icon32" id="icon-options-general"><br></div>
<h2><?php echo _x('Order Anniversary Emails Settings','Setting', 'my-day-email'); ?> </h2>

<form method="post" id="form4" name="form4" action="options.php">
	<?php
	settings_fields('anniversaryemail_plugin_options');
	$options = get_option('anniversaryemail_options');
	?>
	
	<h3><?php echo __('Coupon settings', 'my-day-email'); ?> </h3>
	
	<table id="coupon-table" class="form-table">
		<tr>
			<th class="titledesc"><?php echo __( 'Description', 'woocommerce' ); ?>:</th>
			<td>
				<input type="text" id="anniversaryemail_options[description]" name="anniversaryemail_options[description]"  style="width: 500px;" value="<?php echo $options['description'] ?? ''; ?>"</input>
				<?php  echo wc_help_tip(__( 'Description will be used in Coupons page.', 'my-day-email' ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Count of coupon characters', 'my-day-email' ); ?>:</th>
			<td>
				<select name='anniversaryemail_options[characters]' style="width: 200px;">
					<option value='5' <?php selected( $options['characters'] ?? '', 5 ); ?>><?php echo '5 ' . __( 'characters', 'my-day-email' ); ?>&nbsp;</option>
					<option value='6' <?php selected( $options['characters'] ?? '', 6 ); ?>><?php echo '6 ' . __( 'characters', 'my-day-email' ); ?>&nbsp;</option>
					<option value='7' <?php selected( $options['characters'] ?? '', 7 ); ?>><?php echo '7 ' . __( 'characters', 'my-day-email' ); ?>&nbsp;</option>
					<option value='8' <?php selected( $options['characters'] ?? '', 8 ); ?>><?php echo '8 ' . __( 'characters', 'my-day-email' ); ?>&nbsp;</option>
					<option value='9' <?php selected( $options['characters'] ?? '', 9 ); ?>><?php echo '9 ' . __( 'characters', 'my-day-email' ); ?>&nbsp;</option>
					<option value='10' <?php selected( $options['characters'] ?? '', 10 ); ?>><?php echo '10 ' . __( 'characters', 'my-day-email' ); ?>&nbsp;</option>
				</select>
				<?php  echo wc_help_tip(__( 'Choose how many characters will be in generated coupons.', 'my-day-email' ), false); ?>
			</td>
		</tr>

		<tr valign="top">
			<th class="titledesc"><?php echo __( 'Discount type', 'woocommerce' ); ?>:</th>
			<td>
				<select name='anniversaryemail_options[disc_type]' style="width: 200px;">
					<option value='1' <?php selected( $options['disc_type'] ?? '', 1 ); ?>><?php echo __( 'Percentage discount', 'woocommerce' ); ?>&nbsp;</option>
					<option value='2' <?php selected( $options['disc_type'] ?? '', 2 ); ?>><?php echo __( 'Fixed cart discount', 'woocommerce' ); ?>&nbsp;</option>
					<option value='3' <?php selected( $options['disc_type'] ?? '', 3 ); ?>><?php echo __( 'Fixed product discount', 'woocommerce' ); ?>&nbsp;</option>
				</select>
				<?php  echo wc_help_tip(__( 'Set the discount type.', 'my-day-email' ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Coupon amount', 'woocommerce' ); ?>:</th>
			<td>
				<input type="number" id="anniversaryemail_options[coupon_amount]" name="anniversaryemail_options[coupon_amount]"  style="width: 60px;" value="<?php echo $options['coupon_amount'] ?? ''; ?>"</input>
				<?php  echo wc_help_tip(__(  'Value of the coupon.', 'woocommerce' ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Minimum spend', 'woocommerce' ); ?>:</th>
			<td>
				<input type="number" id="anniversaryemail_options[minimum_amount]" name="anniversaryemail_options[minimum_amount]"  style="width: 60px;" value="<?php echo $options['minimum_amount'] ?? ''; ?>"</input>
				<?php  echo wc_help_tip(__( 'This field allows you to set the minimum spend (subtotal) allowed to use the coupon.', 'woocommerce'), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Maximum spend', 'woocommerce' ); ?>:</th>
			<td>
				<input type="number" id="anniversaryemail_options[maximum_amount]" name="anniversaryemail_options[maximum_amount]"  style="width: 60px;" value="<?php echo $options['maximum_amount'] ?? ''; ?>"</input>
				<?php  echo wc_help_tip(__( 'This field allows you to set the maximum spend (subtotal) allowed when using the coupon.', 'woocommerce'  ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Coupon expires in days', 'my-day-email' ); ?>:</th>
			<td>
				<input type="number" id="anniversaryemail_options[expires]" name="anniversaryemail_options[expires]"  style="width: 60px;" value="<?php echo $options['expires'] ?? ''; ?>"</input>
				<?php  echo wc_help_tip(__( 'Leave empty for coupon with no expiration date.', 'my-day-email' ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Limit usage to X items', 'woocommerce' ); ?>:</th>
			<td>
				<input type="number" id="anniversaryemail_options[max_products]" name="anniversaryemail_options[max_products]"  style="width: 60px;" value="<?php echo $options['max_products'] ?? ''; ?>"</input>
				<?php  echo wc_help_tip(__( 'The generated coupon could be used for maximum number of products. Leave empty for no limit.', 'my-day-email' ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Good only for products', 'my-day-email' ); ?>:</th>
			<td>
				<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="anniversaryemail_options[only_products]" name="anniversaryemail_options[only_products][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" >
					<?php
	if (isset($options['only_products'])) {
		$product_ids = $options['only_products'];
		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( is_object( $product ) ) {
				echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
			}
		}
	}
	?>
				</select>
				<?php  echo wc_help_tip(__( 'Products that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'woocommerce' ), false); ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Exclude products', 'woocommerce' ); ?>:</th>
			<td>
				<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="anniversaryemail_options[exclude_prods]" name="anniversaryemail_options[exclude_prods][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" >
					<?php
	if (isset($options['exclude_prods'])) {
		$ex_product_ids = $options['exclude_prods'];
		foreach ( $ex_product_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( is_object( $product ) ) {
				echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
			}
		}
	}
	?>
				</select>
				<?php  echo wc_help_tip(__( 'Products that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.', 'woocommerce' ), false) ; ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Product categories', 'woocommerce' ); ?>:</th>
			<td>
				<select id="anniversaryemail_options[only_cats]" name="anniversaryemail_options[only_cats][]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'No categories', 'woocommerce' ); ?>">
					<?php
	if (isset($options['only_cats'])) {
		$category_ids = $options['only_cats'];
		$categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		if ( $categories ) {
			foreach ( $categories as $cat ) {
				echo '<option value="' . esc_attr( $cat->term_id ) . '"' . wc_selected( $cat->term_id, $category_ids ) . '>' . esc_html( $cat->name ) . '</option>';
			}
		}
	}
	?>
				</select>
				<?php  echo wc_help_tip(__(  'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'woocommerce'), false) ; ?>
			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Exclude categories', 'woocommerce' ); ?>:</th>
			<td>
				<select id="anniversaryemail_options[exclude_cats]" name="anniversaryemail_options[exclude_cats][]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'No categories', 'woocommerce' ); ?>">
					<?php
	if (isset($options['only_cats'])) {
		$category_ids = $options['exclude_cats'];
		$categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		if ( $categories ) {
			foreach ( $categories as $cat ) {
				echo '<option value="' . esc_attr( $cat->term_id ) . '"' . wc_selected( $cat->term_id, $category_ids ) . '>' . esc_html( $cat->name ) . '</option>';
			}
		}
	}
	?>
				</select>
				<?php  echo wc_help_tip(__('Product categories that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.', 'woocommerce' ), false) ; ?>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc"><?php echo __( 'Individual use only', 'woocommerce'); ?>:</th>
			<td><input type="checkbox" name="anniversaryemail_options[individual_use]" id="anniversaryemail_options[individual_use]"  value="1"
				<?php echo checked( 1, $options['individual_use'] ?? '', false ) ?? '' ; ?>>
				<?php
	echo wc_help_tip(__( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'woocommerce' )); ?>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc"><?php echo __( 'Allow free shipping', 'woocommerce' ); ?>:</th>
			<td><input type="checkbox" name="anniversaryemail_options[free_shipping]" id="anniversaryemail_options[free_shipping]"  value="1"
				<?php echo checked( 1, $options['free_shipping'] ?? '', false ) ?? '' ; ?>>
				<?php
	echo wc_help_tip(__( 'Check this box if the coupon grants free shipping. A <a href="%s" target="_blank">free shipping method</a> must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'woocommerce' ), 'https://docs.woocommerce.com/document/free-shipping/'); ?>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc"><?php echo __( 'Exclude discounted products', 'my-day-email' ); ?>:</th>
			<td><input type="checkbox" name="anniversaryemail_options[exclude_discounted]" id="anniversaryemail_options[exclude_discounted]"  value="1" <?php echo checked( 1, $options['exclude_discounted'] ?? '', false ) ?? '' ; ?>>
				<?php  echo wc_help_tip(__('Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'woocommerce'  ), false) ; ?>

			</td>
		</tr>
		<tr>
			<th class="titledesc"><?php echo __( 'Coupon category slug', 'my-day-email' ); ?>:</th>
			<td>
				<?php
	$acfw ="";
	if ( ! is_plugin_active( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php' ) ) {
		$acfw = 'disabled';
	}
	?>
				<input type="text" id="anniversaryemail_options[category]" name="anniversaryemail_options[category]"  style="width: 200px;" value="<?php echo $options['category'] ?? ''; ?>"
				<?php echo $acfw; ?>>
				<?php  echo wc_help_tip(__( 'This could be used only by plugin Advanced Coupons for WooCommerce (free) plugin. Enter coupon category slug, which has to exist.', 'my-day-email' ), false); ?>
			</td>
		</tr>

	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>			
</form>
</div>
</div>