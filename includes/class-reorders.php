<?php
namespace MYDAYEMAIL;

class Reorders
{
	
	public function get_users_reorders($as_objects = false)
	{
		$sql = new PrepareSQL('reorderemail');
		return $sql->get_users_filtered();
	}
	
	function reorderemail_event_setup()
	{
		$options = get_option('reorderemail_options');
		$success = false;
		if ( !empty($options['enabled']) && '1' == $options['enabled'] ) {
			$funcs = new EmailFunctions('reorderemail');
			$users = $this->get_users_reorders(true);
			
			foreach ($users as $user) {
				$success = $funcs->mydayemail_create($user);
			}
			$funcs->mydayemail_delete_expired();
		}
	}	
}
?>