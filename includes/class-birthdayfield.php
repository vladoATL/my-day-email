<?php
namespace MYDAYEMAIL;

// don't call the file directly
if( !defined( 'ABSPATH' ) ) exit();

/** Add a user birthday field. */
class BirthdayField
{
	static function register()
	{
		$registration = 'on'; //\get_option('lws_woorewards_registration_birthday_field');

		if( $registration )
		{
			$me = new self();

			\add_filter('woocommerce_checkout_fields', array($me, 'checkout'));

			\add_action('woocommerce_edit_account_form', array($me, 'myaccountDetailForm'));
			\add_action('woocommerce_save_account_details', array($me, 'myaccountDetailSave'));

			\add_action('woocommerce_register_form', array($me, 'myaccountRegisterForm'));
			\add_filter('woocommerce_process_registration_errors', array($me, 'myaccountRegisterValidation'), 10, 4);
			\add_action('woocommerce_created_customer', array($me, 'myaccountRegisterSave'), 10, 1);

			\add_action('show_user_profile', array($me, 'showProfileBirthday'));
			\add_action('edit_user_profile', array($me, 'showProfileBirthday'));
			\add_action('personal_options_update', array($me, 'saveProfileBirthday'));
			\add_action('edit_user_profile_update', array($me, 'saveProfileBirthday'));
		}
	}

	
	protected function getDefaultBirthdayMetaKey()
	{
		return 'billing_birth_date';
	}

	function checkout($fields)
	{
		$fields['billing'][$this->getDefaultBirthdayMetaKey()] = array(
			'type'        => 'date',
			'label'       => _x("Date of birth - Year is not important but helps","Check out", "my-day-email"),
			'required'    => false
		);
		return $fields;
	}

	function myaccountRegisterForm()
	{
		$field = $this->getDefaultBirthdayMetaKey();
		$label = _x("Date of birth","Registration", "my-day-email");
		$legend = _x("Year is not important but helps","DOB note", "my-day-email");

		echo "<p class='woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide'>";
		echo "<label for='{$field}'>$label</label>";
		echo "<input type='date' class='woocommerce-Input woocommerce-Input--text input-text' name='{$field}' id='{$field}' />";
		echo "<div>{$legend}</div>";
		echo "</p>";
	}

	function myaccountRegisterValidation($validation_error, $username, $password, $email)
	{
		$birthday = $this->grabBirthdayFromPost();
		if( false === $birthday ){
			$field = $this->getDefaultBirthdayMetaKey();
			$validation_error->add($field, __("Invalid date format for date of birth", "my-day-email"), 'birthday');
		}
		return $validation_error;
	}

	function myaccountRegisterSave($userId)
	{
		$birthday = $this->grabBirthdayFromPost();
		\update_user_meta($userId, $this->getDefaultBirthdayMetaKey(), $birthday);
	}

	function myaccountDetailForm()
	{
		$userId = \get_current_user_id();
		$field = $this->getDefaultBirthdayMetaKey();
		$label = _x("Date of birth","My account", "my-day-email");
		$legend = _x("Year is not important but helps","DOB note", "my-day-email");
		$value = \esc_attr(\get_user_meta($userId, $field, true));

		echo "<fieldset>";
		echo "<legend>" . $label . "</legend>";
		echo "<p class='woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide'>";
		echo "<label for='{$field}'>{$legend}</label>";
		echo "<input type='date' class='woocommerce-Input woocommerce-Input--text input-text' name='{$field}' id='{$field}' value='{$value}' />";
		echo "</fieldset>";
		echo "</p><div class='clear'></div>";
	}

	function myaccountDetailSave($userId)
	{
		$birthday = $this->grabBirthdayFromPost();
		if( $birthday !== false )
			\update_user_meta($userId, $this->getDefaultBirthdayMetaKey(), $birthday);
		else
			\wc_add_notice(__("Invalid date format for date of birth", "my-day-email"), 'error');
	}

	function grabBirthdayFromPost()
	{
		$field = $this->getDefaultBirthdayMetaKey();
		$birthday = !empty($_POST[$field]) ? \wc_clean($_POST[$field]): '';
		if( !empty($birthday) )
		{
			$date = \date_create($birthday);
			if (empty($date)) {
				\wc_add_notice(__("Invalid date format for date of birth", "my-day-email"), 'error');
				$birthday = false;
			}
/*			$today = \date_create();
			if ($date > $today) {
				\wc_add_notice(__("Enter your date of birth, not your next birthday", "my-day-email"), 'error');
				$birthday = false;
			}*/
		}
		return $birthday;
	}

	function showProfileBirthday($user)
	{
		$field = $this->getDefaultBirthdayMetaKey();
		$label = _x("Date of birth", "Profile", "my-day-email");
		$value = \esc_attr(\get_user_meta($user->ID, $field, true));
		echo <<<EOT
<table class="form-table">
	<tr>
		<th><label for='{$field}'>{$label}</label></th>
		<td><input type='date' name='{$field}' id='{$field}' value='{$value}' /></td>
	</tr>
</table>
EOT;

	}

	function saveProfileBirthday($userId)
	{
		if ( !current_user_can( 'edit_user', $userId ) ) {
			return false;
		}
		$field = $this->getDefaultBirthdayMetaKey();
		$date = \sanitize_text_field($_POST[$field]);
		\update_user_meta( $userId, $field, $date);
	}

}
