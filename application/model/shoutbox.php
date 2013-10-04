<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class shoutbox extends Model
{

	var $schema = "CREATE TABLE `shoutbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_main` int(11) NOT NULL,
  `id_user_main__box` int(11) NOT NULL,
  `id_history_etat` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `text` varchar(140) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_main", "id_user_main__box", "id_history_etat", "date", "text");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'id_user_main__box' => array(
			'reference_to' => array('The constraint to user_main__box.id isn\'t respected.', 'user_main', 'id')
		),
		'id_history_etat' => array(
			'reference_to' => array('The constraint to history_etat.id isn\'t respected.', 'history_etat', 'id')
		),
		'date' => array(
			'not_empty' => array('This must be a time.')
		),
		'text' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
