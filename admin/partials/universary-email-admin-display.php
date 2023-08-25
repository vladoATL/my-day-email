<?php

?>

<div class="wrap woocommerce">
<div id="namedaysemail-setting">
<div class="loader_cover">
	<div class="namedays_loader"></div> </div>
<input type="button" value="<?php echo  __( 'Restore Defaults', 'my-day-email' ); ?>" class="button button-primary"
attr-nonce="<?php echo esc_attr( wp_create_nonce( '_namedayemail_nonce' ) ); ?>"
id="restore_uni_values_btn" />

<div class="icon32" id="icon-options-general"><br></div>
<h2><?php echo _x('Order anniversary Emails Settings','Setting', 'my-day-email'); ?> </h2>

<form method="post" id="form3" name="form3" action="options.php">

</form>
</div>
</div>