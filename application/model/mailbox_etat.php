<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class mailbox_etat extends Model
{

	var $schema = "CREATE TABLE `mailbox_etat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `libelle` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";
	var $field = array("id", "libelle");
	var $validate = array(
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
