<?php

use glial\synapse\singleton;
use glial\parser\freelance_info\freelance_info;
use \glial\synapse\Controller;


class FreelanceInfo extends Controller
{

	function index()
	{
		

		$this->layout_name = false;
		$this->view = false;

		include_once(LIBRARY . "Glial/parser/freelance_info/freelance_info.php");

		//freelance_info::get_item(165831);

		$nbpage = freelance_info::get_nb_page();

		for ( $i = 1; $i <= $nbpage; $i++ )
		{
			$tab_ref = freelance_info::get_page($i);

			foreach ( $tab_ref as $ref )
			{
				$table['freelance_info'] = freelance_info::get_item($ref);
				$table['freelance_info']['inserted'] = date('c');

				$res = $this->db['mysql_write']->sql_save($table);

				if ( !$res )
				{
					debug($table);

					debug($this->db['mysql_write']->sql_error());

					die();
				}
			}
		}
	}

	function send_email()
	{
		

		$this->layout_name = false;
		$this->view = false;

		$message = "Bonjour,<br/>\n
Je suis à la recherche d'une nouvelle mission.<br/>\n
A la suite de l'annonce parue sur http://www.freelance-info.fr/, je me permet de vous transmettre mon CV ci-joint en pièce jointe.<br/>\n
<br/>\n
Voici un résumé de mes compétances :<br/>\n
<ul>

<li><b>Management :</b> Chef de projet, Directeur techniques, Team leader, Planification et mise en place de plan d'action d'envergures, Coordinations d'équipes
</li>
<li><b>Base de données :</b> (PostGresSQL, MySQL, Sybase, Oracle, SQL Server) <br/>\n
- Installation d'un serveur de A à Z, Backup / Restore, Création / modification / suppression d'utilisateurs (administration de base pour tous les SGBD cités)<br/>\n
- Optimisation et des traitements & performances sur des très fortes volumétries (analyse des slow query, best pratices, partitionnement vertical et horizontale, ...)<br/>\n
- Création de data-modèle performant et évolutif<br/>\n
- Migrations : PostgreSQL, Sybase, Oracle & SQL Server vers MySQL, MySQL vers Oracle, MySQL vers SQL Server & Sybase. Monté de version : Sybase 12 vers Sybase 15, Sybase 11 vers Sybase 12, MySQL X.X vers MySQL X.X<br/>\n
- MySQL : Maitre / Escalve, Maitre / Maitre, MySQL Cluster, Haute disponibilité 99.999%<br/>\n
- SQL Server : Log shipping<br/>\n
- Sybyse : replication server
</li>
<li><b>PHP :</b> installation & configuration, PECL, Suhosin<br/>\n
- Programmation POO, namespaces, multithreading, AJAX, résolution de problématiques complexes.<br/>\n
- J'ai développé mon propre framework : Glial, pour répondre à des problématiques de performance (temps d'accès très court) et de fortes volumétries, tout en économisant au maximum la mémoire.
</li>
<li><b>Distribution Debian & Ubuntu :</b> Installation d'un serveur de A à Z<br/>\n
- Serveur Apache, PHP, Samba, postfix, imap, SSL, nfs, TLS, SMTP, serveur Bind, serveur Big blue button, iptable.
</li>
<li><b>Javascript :</b> Jquery, ExtJS
</li>
</li>
<li><b>Autres :</b> C, HTML 5.0, CSS 3.0, Réseaux
</li>
</ul>

<br/>\n<br/>\n
Ce message vous a été envoyé automatiquement, et permet de vous montrez une partie de mes compétences. Ce script à été réalisé en PHP / C & MySQL en une petite matinée à l'aide du framework Glial réalisé par moi-même disponible en open source à l'adresse suivante : <a href=\"https://github.com/Esysteme/glial\">https://github.com/Esysteme/glial</a> sous une ditribution Linux (Debian) le tout sur un Raspberry Pi.<br/>\n<br/>\n
Best regards ~<br/>\n
Aurélien LEQUOY<br/>\n
Tél : 06.27.63.89.78";


		$fichier = "Aurelien_LEQUOY.doc";
		//$to = "aurelien.lequoy@gmail.com";
		$sujet = "CV Aurélien LEQUOY";
		$reply = "aurelien.lequoy@esysteme.com";
		$nomprenom ="Aurélien LEQUOY";
		$from = "aurelien.lequoy@esysteme.com";
		$typemime = "text/html";
		$nom = "Aurelien_LEQUOY.doc";

		$limite = "_parties_" . md5(uniqid(rand()));

		$mail_mime = "Date: " . date("l j F Y, G:i") . "\n";
		$mail_mime .= "MIME-Version: 1.0\n";
		$mail_mime .= "Content-Type: multipart/mixed;\n";
		$mail_mime .= " boundary=\"----=$limite\"\n\n";

		//Le message en texte simple pour les navigateurs qui n'acceptent pas le HTML
		$texte = "This is a multi-part message in MIME format.\n";
		$texte .= "Ceci est un message est au format MIME.\n";
		$texte .= "------=$limite\n";
		$texte .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$texte .= "Content-Transfer-Encoding: 8bit\n\n";
		$texte .= $message;
		$texte .= "\n\n";

		//le fichier
		$attachement = "------=$limite\n";
		$attachement .= "Content-Type: $typemime; name=\"$nom\"\n";
		$attachement .= "Content-Transfer-Encoding: base64\n";
		$attachement .= "Content-Disposition: attachment; filename=\"$nom\"\n\n";

		$fd = fopen($fichier, "r");
		$contenu = fread($fd, filesize(strip_tags($fichier)));
		fclose($fd);
		$attachement .= chunk_split(base64_encode($contenu));

		$attachement .= "\n\n\n------=$limite\n";
		
		
		
		$sql = "SELECT distinct email from freelance_info where email!= '' order by email";
		
		$res = $this->db['mysql_write']->sql_query($sql);
		
		while($ob = $this->db['mysql_write']->sql_fetch_object($res))
		{
			echo $ob->email."\n";
			mail($ob->email, $sujet, $texte . $attachement, "Reply-to: $reply\nFrom:	" . $nomprenom . "<" . $from . ">\n" . $mail_mime);
			sleep(1);
		}
	}

}