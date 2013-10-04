<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class statistique_main extends Model
{

	// Nous donnons donc � Gliale la structure d'un enregistrement
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
	// R�gles de validation des donn�es

	var $field = array();
	var $validate = array(
		'ip' => array(
			'ip' => array("your IP is not valid")
		)
	);

	function get_validate()
	{
		return $this->validate;
	}

}

?>