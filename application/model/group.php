<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class group extends Model
{

	var $schema = "CREATE TABLE `group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "id_parent", "name", "description");
	var $validate = array(
		'id_parent' => array(
			'reference_to' => array('The constraint to parent.id isn\'t respected.', 'parent', 'id')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'description' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
