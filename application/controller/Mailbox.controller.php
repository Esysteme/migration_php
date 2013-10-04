<?php
use \glial\synapse\Controller;

class Mailbox extends Controller {

	public $module_group = "Users & access management";
	

	function inbox() {
		
		
		
		$sql = "SELECT * FROM user_main a
		INNER JOIN geolocalisation_country b ON a.id_geolocalisation_country = b.id
		INNER JOIN geolocalisation_city c ON a.id_geolocalisation_city = c.id
		
where a.id ='" . $this->db['mysql_write']->sql_real_escape_string($GLOBALS['_SITE']['IdUser']) . "'";
		$res = $this->db['mysql_write']->sql_query($sql);

		$user = $this->db['mysql_write']->sql_to_array($res);
		$this->data['user'] = $user[0];

		$this->title = __('Inbox');
		$this->ariane = "> <a href=\"" . LINK . "user/\">" . __("Members") . "</a> > " 
			. '<a href="' . LINK . 'user/'.$GLOBALS['_SITE']['IdUser'].'">'.$this->data['user']['firstname'] . ' ' . $this->data['user']['name'].'</a>'
			. ' > ' . '<a href="' . LINK . 'user/'.$GLOBALS['_SITE']['IdUser'].'">'.__('Mailbox').'</a>'
			. ' > ' .$this->title;

		

		$this->set("data", $this->data);
	}
	
	function sent_mail()
	{
		
	}
	
	function compose()
	{
		
		
	}
	
	function trash()
	{
		
	}


}

