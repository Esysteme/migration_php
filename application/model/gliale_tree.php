<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class gliale_tree extends Model
{

	var $schema = "CREATE TABLE `gliale_tree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `cf_link` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cf_order` int(11) NOT NULL,
  `module_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `script_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "id_parent", "cf_link", "cf_order", "module_name", "script_name");
	var $validate = array(
		'id_parent' => array(
			'reference_to' => array('The constraint to parent.id isn\'t respected.', 'parent', 'id')
		),
		'cf_link' => array(
			'not_empty' => array('This field is requiered.')
		),
		'cf_order' => array(
			'numeric' => array('This must be an int.')
		),
		'module_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'script_name' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
