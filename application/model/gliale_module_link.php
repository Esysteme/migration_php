<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class gliale_module_link extends Model
{

	var $schema = "CREATE TABLE `gliale_module_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `id_gliale_module_main` int(11) NOT NULL,
  `link_php` varchar(255) NOT NULL,
  `link_rewrited` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_parent", "id_gliale_module_main", "link_php", "link_rewrited", "is_active");
	var $validate = array(
		'id_parent' => array(
			'reference_to' => array('The constraint to parent.id isn\'t respected.', 'parent', 'id')
		),
		'id_gliale_module_main' => array(
			'reference_to' => array('The constraint to gliale_module_main.id isn\'t respected.', 'gliale_module_main', 'id')
		),
		'link_php' => array(
			'not_empty' => array('This field is requiered.')
		),
		'link_rewrited' => array(
			'not_empty' => array('This field is requiered.')
		),
		'is_active' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
