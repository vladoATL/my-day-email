<?php
// check user capabilities
if ( ! current_user_can( 'read' ) ) {
	return;
}
wp_enqueue_script( 'jquery-tiptip' );

//Get the active tab from the $_GET param
$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
<div class="wrap">
	<h1><?php _e( 'My Day Email Settings','my-day-email' ); ?></h1>
<nav class="nav-tab-wrapper">
	<a href="?page=mydayemail" class="nav-tab <?php
	if ($tab===null) : ?>nav-tab-active<?php
	endif; ?>"><?php echo  __( 'Common', 'my-day-email' ); ?></a>
	<a href="?page=mydayemail&tab=birth-day" class="nav-tab
		<?php
	if ($tab==='birth-day') : ?>nav-tab-active<?php
	endif; ?>"> <?php echo  __( 'Birthday', 'my-day-email' ); ?></a>	
	<a href="?page=mydayemail&tab=name-day" class="nav-tab 
		<?php if ($tab==='name-day') : ?>nav-tab-active<?php endif; ?>"> <?php echo  __( 'Name Day', 'my-day-email' ); ?></a>
	<a href="?page=mydayemail&tab=anniversary" class="nav-tab <?php
			if ($tab==='anniversary') : ?>nav-tab-active<?php
	endif; ?>"><?php echo  __( 'Order Anniversary', 'my-day-email' ); ?></a>
	<a href="?page=mydayemail&tab=one-time" class="nav-tab
		<?php
		if ($tab==='one-time') : ?>nav-tab-active<?php
	endif; ?>"> <?php echo  __( 'One Time', 'my-day-email' ); ?></a>	
</nav>
	
<div class="tab-content">
	<?php
switch ($tab) :
case 'one-time':
	?>
	<div class="metabox-holder">
		<?php include('onetime-email-admin-display.php'); ?>		
	</div>
	<?php
break;	
case 'birth-day':
	?>
	<div class="metabox-holder">
		<?php include('birth-day-email-admin-display.php'); ?>
	</div>
	<?php
	break;		
case 'anniversary':
	?>
	<div class="metabox-holder">
		<?php include('anniversary-email-admin-display.php'); ?>
	</div>
	<?php
	break;
case 'name-day':
	?>
	<div class="metabox-holder">
		<?php include('name-day-email-admin-display.php'); ?>
	</div>
	<?php
	break;
default:	
	?>
	<div class="metabox-holder">
		<?php include('common-settings-admin-display.php'); ?>
	</div>
	<?php
	break;
endswitch; ?>
	</div>

</div>