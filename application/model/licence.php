<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class licence extends Model
{

	var $schema = "CREATE TABLE `licence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_inserted` datetime NOT NULL,
  `cf_order` int(11) NOT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `libelle` (`libelle`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "libelle", "link", "date_inserted", "cf_order", "icon");
	var $validate = array(
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
		'link' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_inserted' => array(
			'time' => array('This must be a time.')
		),
		'cf_order' => array(
			'numeric' => array('This must be an int.')
		),
		'icon' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
