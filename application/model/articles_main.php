<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class articles_main extends Model
{

	var $schema = "CREATE TABLE `articles_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etat` int(11) DEFAULT NULL,
  `id_cat` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_lang` char(5) NOT NULL,
  `date_posted` datetime NOT NULL,
  `date_validated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "etat", "id_cat", "id_module", "id_type", "id_lang", "date_posted", "date_validated");
	var $validate = array(
		'etat' => array(
			'numeric' => array('This must be an int.')
		),
		'id_cat' => array(
			'reference_to' => array('The constraint to cat.id isn\'t respected.', 'cat', 'id')
		),
		'id_module' => array(
			'reference_to' => array('The constraint to module.id isn\'t respected.', 'module', 'id')
		),
		'id_type' => array(
			'reference_to' => array('The constraint to type.id isn\'t respected.', 'type', 'id')
		),
		'id_lang' => array(
			'reference_to' => array('The constraint to lang.id isn\'t respected.', 'lang', 'id')
		),
		'date_posted' => array(
			'time' => array('This must be a time.')
		),
		'date_validated' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
