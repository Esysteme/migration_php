<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class history_main extends Model
{

	// Nous donnons donc а Gliale la structure d'un enregistrement
	var $schema = "CREATE TABLE `UserMain` (
 `Id` int(11) NOT NULL auto_increment,
 `IsValid` int(11) NOT NULL,
 `Login` varchar(50) NOT NULL,
 `Email` varchar(50) NOT NULL,
 `Password` varchar(20) NOT NULL,
 `Name` varchar(40) NOT NULL,
 `Firstname` varchar(40) NOT NULL,
 `IP` varchar(15) NOT NULL,
 `CountryIP` char(2) NOT NULL,
 `Points` int(11) NOT NULL,
 `LastLogin` datetime NOT NULL,
 `LastConnected` datetime NOT NULL,
 `LastJoined` datetime NOT NULL,
 `KeyAuth` char(32) NOT NULL,
 PRIMARY KEY  (`Id`),
 UNIQUE KEY `email` (`Email`),
 UNIQUE KEY `login` (`Login`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8";
	// Rиgles de validation des donnйes

	var $field = array();
	var $validate = array(
		'id_history_table' => array(
			'reference_to' => array("Please select your country", "history_table", "id")
		),
		'id_history_action' => array(
			'reference_to' => array("Please select your city", "history_action", "id")
		),
		'id_history_etat' => array(
			'reference_to' => array("Please select your city", "history_etat", "id")
		)
	);

	function get_validate()
	{
		return $this->validate;
	}

}

?>