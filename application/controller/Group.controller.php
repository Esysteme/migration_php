<?php
use \glial\synapse\Controller;


class Group extends Controller
{

	public $module_group = "Users & access management";

	function index()
	{
		
	}

	function admin_group()
	{

		//306900052085371394.png"
		$module['picture'] = "administration/group1.gif";
		$module['name'] = __("Group");
		$module['description'] = __("Manage picture importation from flickr's bot");

		return $module;
	}

}