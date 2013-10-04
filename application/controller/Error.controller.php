<?php
use \glial\synapse\Controller;

class Error extends Controller
{
	
	public $module_group = "Media";
	public $method_administration = array("user","roles");
	
	function index()
	{

	
	}
	
	
	
	function _404()
	{
		$this->title = __("Error")." 404";
		$this->ariane = "> ".$this->title;
		
		
		
		set_flash("error",__("Error"),__("The requested URL :")." ".$_SESSION['URL_404']." ".__("was not found on this server.")."<br />".__("That's all we know."));
		

	
	
	}
	

	
}
	
	
?>