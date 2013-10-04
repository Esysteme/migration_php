<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class link__user_main__user_main extends Model
{

	var $schema = "CREATE TABLE `link__user_main__user_main` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `id_user_main__from` int(11) NOT NULL,
  `id_user_main__to` int(11) NOT NULL,
  `id_history_etat` int(11) NOT NULL,
  UNIQUE KEY `id_user_main__from` (`id_user_main__from`,`id_user_main__to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "date", "id_user_main__from", "id_user_main__to", "id_history_etat");
	var $validate = array(
		'date' => array(
			'time' => array('This must be a time.')
		),
		'id_user_main__from' => array(
			'reference_to' => array('The constraint to user_main__from.id isn\'t respected.', 'user_main__from', 'id')
		),
		'id_user_main__to' => array(
			'reference_to' => array('The constraint to user_main__to.id isn\'t respected.', 'user_main__to', 'id')
		),
		'id_history_etat' => array(
			'reference_to' => array('The constraint to history_etat.id isn\'t respected.', 'history_etat', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
