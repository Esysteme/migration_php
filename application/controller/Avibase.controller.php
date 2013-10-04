<?php
use \glial\synapse\Controller;


class Avibase extends Controller {
	function get_pic()
	{
		$this->view = false;
		$this->layout_name = false;
 

		
		
		include_once(LIBRARY . "Glial/parser/avibase/avibase.php");
		include_once (LIB . "wlHtmlDom.php");

 
 
		//$data = glial\parser\avibase\avibase::get_regions();
		//$data = glial\parser\avibase\avibase::get_ids('auvi01');
		//$data = glial\parser\avibase\avibase::get_regions();
		$data = glial\parser\avibase\avibase::get_ids("us");
		
		
		
		debug($data); 
	}
	
	function get_all_ids()
	{
		$this->view = false;
		$this->layout_name = false;
		
		
		
		
		include_once(LIBRARY . "Glial/parser/avibase/avibase.php");
		include_once(LIB . "wlHtmlDom.php");
		
		$data = array();
		$regions = glial\parser\avibase\avibase::get_regions();

			
		foreach($regions as $var)
		{
			
			echo $var."\n";
			$data = array_merge($data, glial\parser\avibase\avibase::get_ids($var));
			
		}
		debug($data);
		
		file_put_contents("ff.txt", json_encode($data));
	}

}
