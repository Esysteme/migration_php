<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class pays extends Model
{

	var $schema = "CREATE TABLE `pays` (
  `id_pays` int(11) NOT NULL AUTO_INCREMENT,
  `code_pays` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `fr` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `en` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_pays`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id_pays", "code_pays", "fr", "en");
	var $validate = array(
		'id_pays' => array(
			'reference_to' => array('The constraint to pays.id isn\'t respected.', 'pays', 'id')
		),
		'code_pays' => array(
			'not_empty' => array('This field is requiered.')
		),
		'fr' => array(
			'not_empty' => array('This field is requiered.')
		),
		'en' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
