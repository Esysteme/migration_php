<?php

use \glial\synapse\Controller;


class Faq extends Controller
{
	
	
	
	public $module_group = "Communication Templates";
	public $method_administration = array("user","roles");
	
	
	function index()
	{
		$this->title = __("FAQ");
		$this->ariane = "> ".$this->title;
		




	}
	
	

}

?>