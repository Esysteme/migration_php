<?php

use \glial\synapse\Controller;


class Partner extends Controller
{
	
	function index()
	{
		$this->title = __("Partner");
		$this->ariane = "> ".$this->title;
		
	}
}

?>