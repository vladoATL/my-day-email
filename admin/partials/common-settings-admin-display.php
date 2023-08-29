<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://perties.sk
* @since      1.0.0
*
* @package    My_Day_Email
* @subpackage My_Day_Email/admin/partials
*/

?>

<div class="wrap woocommerce">
	<div id="namedaysemail-setting"  class="myday-setting">

<div class="icon32" id="icon-options-general"><br></div>
<h2><?php echo _x('Common Settings','Setting', 'my-day-email'); ?> </h2>
<form method="post" id="form1" name="form1" action="options.php">
<?php
settings_fields('mydayemail_plugin_options');
$options = get_option('mydayemail_options');

?>

<table class="form-table">
<tr>
	<th class="titledesc"><?php echo __( 'Delete unused coupons in days after expiration', 'my-day-email' ); ?>:</th>
	<td>
		<input type="number" id="mydayemail_options[days_delete]" name="mydayemail_options[days_delete]"  style="width: 60px;" value="<?php echo $options['days_delete'] ?? ''; ?>"</input>
		<?php  echo wc_help_tip(__( 'If you leave this blank, the coupons will not be deleted. To automatically delete coupons, enter the number of days after the expiration date and unused coupons will be deleted.', 'my-day-email' ), false); ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><?php echo __( 'Enable logs', 'my-day-email' ); ?>:</th>
	<td><input type="checkbox" name="mydayemail_options[enable_logs]" id="mydayemail_options[enable_logs]"  value="1" <?php echo checked( 1, $options['enable_logs'] ?? '', false ) ?? '' ; ?>></td>
</tr>
</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>	
		<form method="post" id="form_log" name="form_log">
			<?php 
				settings_fields('mydayemail_plugin_log_options'); 
				$options = get_option('mydayemail_logs'); 
			?>	
			<h3><?php echo _x('Logs','Setting section', 'my-day-email'); ?> </h3>
			<table id="log-table" class="form-table">	
				<tr>
					<td colspan="2" class="textarea_">						
						<textarea class="textarea_" id="mydayemail_logs[logs]" name="mydayemail_logs[logs]" rows="25" type='textarea'><?php echo $options['logs'] ?? ''; ?></textarea>
					</td>
				</tr>			
			</table>
			<p class="submit">
			<input type="button" value="<?php echo  __( 'Clear Log', 'my-day-email' ); ?>" class="button button-primary" 
			attr-nonce="<?php echo esc_attr( wp_create_nonce( '_mydayemail_nonce_log' ) ); ?>" 
			id="clear_log_btn" />						
			</p>
			
		</form>
	</div>
	</div>
