<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class mailbox_main extends Model
{

	var $schema = "CREATE TABLE `mailbox_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_main__box` int(11) NOT NULL,
  `id_user_main__from` int(11) NOT NULL,
  `id_user_main__to` int(11) NOT NULL,
  `id_history_etat` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `id_mailbox_etat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_main__box", "id_user_main__from", "id_user_main__to", "id_history_etat", "date", "title", "text", "id_mailbox_etat");
	var $validate = array(
		'id_user_main__box' => array(
			'reference_to' => array('The constraint to user_main__box.id isn\'t respected.', 'user_main', 'id')
		),
		'id_user_main__from' => array(
			'reference_to' => array('The constraint to user_main__from.id isn\'t respected.', 'user_main', 'id')
		),
		'id_user_main__to' => array(
			'reference_to' => array('The constraint to user_main__to.id isn\'t respected.', 'user_main', 'id')
		),
		'id_history_etat' => array(
			'reference_to' => array('The constraint to history_etat.id isn\'t respected.', 'history_etat', 'id')
		),
		'date' => array(
			'not_empty' => array('This must be a datetime.')
		),
		'title' => array(
			'not_empty' => array('This field is requiered.')
		),
		'text' => array(
			'not_empty' => array('This field is requiered.')
		),
		'id_mailbox_etat' => array(
			'reference_to' => array('The constraint to mailbox_etat.id isn\'t respected.', 'mailbox_etat', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
