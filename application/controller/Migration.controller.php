<?php

use \Glial\Synapse\Controller;
use \Glial\Tools\Recursive;

class Migration extends Controller
{

    public $_path      = "/home/www/adel";
    //public $_path      = "/home/www/old_php5_adel";
    public $_blacklist = array("/GRAPH/");

    function __contruct()
    {
        
    }

    private function blacklist($filename)
    {
        foreach ($this->_blacklist as $elem) {
            if (strstr($filename, $elem)) {
                return false;
            }
        }
        return true;
    }

    private function getFilesNames()
    {
        $files  = Recursive::globRecursive($this->_path, "*.php");
        $files2 = Recursive::globRecursive($this->_path, "*.prn");
        $ret    = array();
        foreach ($files as $file) {
            if ($this->blacklist($file)) {
                $ret[] = $file;
            }
        }
        foreach ($files2 as $file) {
            if ($this->blacklist($file)) {
                $ret[] = $file;
            }
        }
        return $ret;
    }

    function all()
    {
        $this->replaceShortTag();

        for ($i = 0; $i < 4; $i++) {
            $this->replaceConst();
        }
        $this->putConst();
        $this->addConfigFile();
        $this->addFullPath();
    }

    function replaceShortTag()
    {
        $this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();


        $i = 0;
        foreach ($files as $file) {

            $i++;
            $data = file_get_contents($file);

            $count = 0;
            $data  = preg_replace("/<\?(\s|\t|\n)/", "<?php\n", $data, -1, $count);

            echo (!empty($count)) ? $i . ':' . $count . ':' . $file . EOL : "";

            file_put_contents($file, $data);
        }
    }

    function replaceConst()
    {
        $this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();


        $i = 0;
        foreach ($files as $file_name) {

            $data   = "";
            $handle = fopen($file_name, "r");
            if ($handle) {
                $nbline = 1;

                while (($buffer = fgets($handle)) !== false) {
                    preg_match_all('#\$[a-zA-Z_]{1}[a-zA-Z0-9_]*[\-\>[\w]+]?(\[([\[(.+)\]])*\])*\[([a-zA-Z_]{1}[a-zA-Z0-9_]*)\]#U', $buffer, $out, PREG_PATTERN_ORDER);
                    $nb_to_replace = count($out[0]);

                    for ($i = 0; $i < $nb_to_replace; $i++) {
                        //echo "[".$out[2][$i]."] => ['".$out[2][$i]."']".PHP_EOL;
                        $new    = str_replace("[" . $out[3][$i] . "]", "[\"" . $out[3][$i] . "\"]", $out[0][$i]);
                        $buffer = str_replace($out[0][$i], $new, $buffer);
                        echo $file_name . ":" . $nbline . " - " . $out[0][$i] . " => " . $new . PHP_EOL;
                    }
                    $data .= $buffer;

                    $nbline++;
                }
                if (!feof($handle)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);

                file_put_contents($file_name, $data);
            }
        }
    }

    function putConst()
    {
        //fix specific for this file IC_Liste_HTML.inc.V8.3.CIE7.php

        $file   = "/include/IC_Liste_HTML.inc.V8.3.CIE7.php";
        $consts = array("FIRMING_IN_VALUE", "KI", "KP");

        $file_name = $this->_path . $file;
        $data      = file_get_contents($file_name);

        foreach ($consts as $const) {
            $data = str_replace("[\"" . $const . "\"]", "[" . $const . "]", $data);
        }

        file_put_contents($file_name, $data);
    }

    function addFullPath()
    {
        $dir = $this->_path . "/include/";

        $this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();

        $requires = glob($dir . "*.{php,prn}", GLOB_BRACE);

        //with "
        foreach ($files as $file_name) {

            echo $file_name . EOL;
            $data = file_get_contents($file_name);

            foreach ($requires as $require) {

                $tab_path     = explode("/", $require);
                $name_require = end($tab_path);

                $data = str_replace("\"" . $name_require . "\"", "dirname(__DIR__).\"/include/" . $name_require . "\"", $data);
            }
            file_put_contents($file_name, $data);
        }


        //with '
        foreach ($files as $file_name) {

            echo $file_name . EOL;
            $data = file_get_contents($file_name);

            foreach ($requires as $require) {

                $tab_path     = explode("/", $require);
                $name_require = end($tab_path);

                $data = str_replace("'" . $name_require . "'", "dirname(__DIR__).\"/include/" . $name_require . "\"", $data);
            }
            file_put_contents($file_name, $data);
        }
    }

    function addConfigFile()
    {
        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/include/IC_ENVIRONNEMENT.php";

        $data = <<< EOL
<?PHP
\$IC_SERVEUR_WEB = "adelprep";
\$IC_BASE = "ORACLE";
\$IC_ADRESSE_WEB_EXTERNE = '10.239.4.33';
\$IC_TNSNAME="//SBCCH10186.ad.sys:1523/ADELPREP";
\$IC_EPM_DBNAME = 'ADEL_EPM_EXCHANGE';
\$IC_EPM_HOST = '10.18.0.43';
\$IC_EPM_PORT = '1433';
\$IC_EPM_USER = 'EPMADEL';
\$IC_EPM_PWD = 'ADEL21042010';
\$LDAP['SERVEUR']= "l-frlpta04.notes.alstom.com";
\$IC_CLASS_BANDEAU = "BandeauPREP";
\$IC_SPEUDO_SERVEUR = "PRE-PROD";
\$IC_TAB_TITLE = "PRE-PROD";
?>
EOL;
        file_put_contents($file_name, $data);
    }

    function addTnsnames(
    )
    {
        # TNSNAMES.ORA

        $data = <<< EOL
ADELDEV =
  (DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(Host = SBCCH10199.ad.sys)(Port = 1523))
    )
    (CONNECT_DATA =
      (SID = ADELDEV)
    )
  )

ADELREC =
  (DESCRIPTION =
      (ADDRESS = (PROTOCOL = TCP)(HOST = SBCCH10186.ad.sys) (PORT = 1523))
    (CONNECT_DATA =
      (SERVICE_NAME = ADELPREP)
    )
  )

ADELPREP =
  (DESCRIPTION =
      (ADDRESS = (PROTOCOL = TCP)(HOST = SBCCH10186.ad.sys ) (PORT = 1523))
    (CONNECT_DATA =
      (SERVICE_NAME = ADELPREP)
    )
  )

INST1_HTTP.DOM2.AD.SYS =
  (DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = SAOUS10041.dom2.ad.sys)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVER = SHARED)
      (SERVICE_NAME = MODOSE)
      (PRESENTATION = http://HRService)
    )
  )

ADEL9I =
  (DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.23.4.209 )(PORT = 1521))
    )
    (CONNECT_DATA =
      (SID = ADEL)
    )
  )
BOTISPRD.WORLD =
  (DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 159.217.128.212)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SID = BOSIFPRD)
    )
 )

EOL;

        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/tnsnames.ora";

        file_put_contents($file_name, $data);
    }

    function replaceIdentification()
    {
        $data = <<< EOL
<?php
//  OBJET :
//  ------
// ______   Page d'autentification de l'utilisateur au site
//     ______________________________________________________________________
// ___________________________________________________________________________
//
// AUTEUR : JBS    DATE DE CREATION : 04/01/2002
// ____________________________________________________________________________
// MAINTENANCES :
//
// VERSION DATE       AUTEUR  OBJET
// ------- ----       ------  ---
//          05/11/2007 JC     Mesurer le temps de connexion à Adel
/* ~FF7.5.*~ */// 28/12/2011 MAC : Nouvelle norme pour les mots de passe / possibilité de récuperons le mot de passe via l'adresse mail
/* ~FF7.5.*~ */// 04/04/2012 NM : Par défaut le champ Login doit être blanc et pas de message d'erreur + Ajout le test sur \$_POST
/* ~FF8.0.1~ */// 21.06.2012 PTA : Coriger le button de renvoyer le mot de passe
/* ~FF8.0.56~ */// 30.08.2012 PTA : Ajouter aide pour la probleme de login
/* ~FF8.1.6~ *///  17.10.2012 CTR Mpi 2146 : Ajout d'un bouton permettant au KU de débloquer une personne.(INC000002676530)
/* ~FF8.1.40~ */// 10.12.2012 BCO Mpi 2182: Corrige le message d'erreur (\$message_result)
// ____________________________________________________________________________

date_default_timezone_set( "Europe/Paris" );

define( "TIME_START", microtime( true ) );


error_reporting( E_ALL ^ E_NOTICE ^ E_DEPRECATED );
//error_reporting( -1 );
ini_set( 'display_errors', 1 );


//\$_POST["Login"] = "LEQUOYA";
//\$_POST["Password"] = "init";
// --------------------------------------------------------------
\$NomPagePHP		 = "IDENTIFICATION";
\$styleLogin		 = "background:white;color:black;";
\$stylePassword	 = "background:white;color:black;";
\$styleMail		 = "background:white;color:black;";
\$loginName		 = "";

// -----------------------------------------------------------------------------          
require_once("ICMessages_Erreurs_FR.inc.php");
/* ~FF7.5~ */// -----------------------------------------------------------------------------
/* ~FF7.5~ */require_once ("IC_ENVIRONNEMENT.php");
// -----------------------------------------------------------------------------
// Inclure les fonctions générales
// -----------------------------------------------------------------------------
require_once("IC_Fonctions_Generales.inc.php");
// -----------------------------------------------------------------------------
// Inclure les fonctions de la base de donnée
// -----------------------------------------------------------------------------
require_once("IC_Requete.inc.php");
// -----------------------------------------------------------------------------
// Inclure les fonctions de la base de donnée
// -----------------------------------------------------------------------------
require_once("Libelles_Commun_FR.inc.php");
// -----------------------------------------------------------------------------
// Réception des infos transmises à la page
// -----------------------------------------------------------------------------
foreach ( \$_GET as \$key => \$value )
{
	\$\$key = \$value;
}
foreach ( \$_POST as \$key => \$value )
{
	\$\$key = \$value;
}
//Start_Session();

\$NextPage		 = 'IC_ChoixAPPLI.php';
\$NouveauPSWPage	 = 'IC_New_CDPSW.php';


/*
  // ALE to be removed
  \$Login				 = (empty( \$_POST["Login"] )) ? "" : \$_POST["Login"];
  \$Password			 = (empty( \$_POST["Password"] )) ? "" : \$_POST["Password"];
  \$NouveauPSW			 = (empty( \$_POST["NouveauPSW"] )) ? "" : \$_POST["NouveauPSW"];
  \$Change				 = (empty( \$_POST["Change"] )) ? "" : \$_POST["Change"];
  \$NotChange			 = (empty( \$_POST["NotChange"] )) ? "" : \$_POST["NotChange"];
  \$rechercherLogin	 = (empty( \$_POST["rechercherLogin"] )) ? "" : \$_POST["rechercherLogin"];
  //\$sendMail			 = (empty( \$_POST["sendMail"] )) ? "" : \$_POST["sendMail"];
  \$cduser_recherche	 = (empty( \$_POST["cduser_recherche"] )) ? "" : \$_POST["cduser_recherche"];
  \$AdMail_recherche	 = (empty( \$_POST["AdMail_recherche"] )) ? "" : \$_POST["AdMail_recherche"];
 */






//NMA : var form_Search crée pour savoir le formulaire de submit pour le cas de enter
\$form_Search = isset( \$_POST["AdMail_recherche"] ) || !empty( \$_POST["rechercherLogin"] ) ? true : false;


//\$LoginHelpForm_display = \$rechercherLogin || \$AdMail_recherche || \$form_Search ? 'block' : 'none';

if ( !empty( \$_POST['rechercherLogin'] ) || !empty( \$_POST['AdMail_recherche'] ) || !empty( \$form_Search ) )
{
	\$LoginHelpForm_display = 'block';
}
else
{
	\$LoginHelpForm_display = 'none';
}

// -----------------------------------------------------------------------------
// Redirection suite à la demande de l'internaute de changer ou pas son pwd en cas d'expiration
// -----------------------------------------------------------------------------
if ( !empty( \$Change ) || !empty( \$NotChange ) )
{
	Start_Session();
	\$nbr_jour				 = isset( \$_POST['nbr_jour'] ) ? ( int ) \$_POST['nbr_jour'] : 120;
	\$_SESSION['pwd_expired'] = \$nbr_jour < 120 ? false : true;
	Redirection();
}

// -----------------------------------------------------------------------------
// Envoie de mail contenant le login et le pwd de l'utilisateur : login help
// -----------------------------------------------------------------------------
if ( !empty( \$sendMail ) )
{
	\$ColonnesLues							 = ConstruireTableauRequete( 'ADMAIL', 'LIUSER', 'CDUSER', 'CDPSW' );
	\$ColonnesLues['CDUSER']['Valeur']		 = \$cduser_recherche;
	\$ColonnesLues['CDUSER']['Recherchee']	 = true;
	\$query									 = 'SELECT ADMAIL, LIUSER, CDUSER, CDPSW FROM IC_USER WHERE CDUSER =\'::CDUSER\'';
	\$MaRequeteSQL							 = new requete( \$query, "S", \$ColonnesLues );

	if ( \$MaRequeteSQL->Retour_T['Num_Mess'] != 0 )
	{
		// la requete est interrompu
		\$MESSAGE["Libelle"] = ArretTransaction( 9400, \$MaRequeteSQL->Retour_T['Num_Mess'] );
	}
	if ( \$MaRequeteSQL->NbLigne > 0 )
	{
		\$MaRequeteSQL->LectureSuivant( \$ColonnesLues );

		\$sendMailResult = Envoyer_Mail(
				\$ColonnesLues['LIUSER']['Valeur'] . '<' . \$ColonnesLues['ADMAIL']['Valeur'] . '>', 'ADEL Login and Password', 'Login : ' . \$ColonnesLues['CDUSER']['Valeur'] . "\nPassword : " . \$ColonnesLues['CDPSW']['Valeur'], \$ADRESSE_EMAIL_ADEL
		);

		if ( \$sendMailResult )
		{
			/* FF8.1.40 */ \$message_result = 'Un mail vient d\'être envoyé à « ' . \$ColonnesLues['ADMAIL']['Valeur'] . ' » <br /> An email has been sent to « ' . \$ColonnesLues['ADMAIL']['Valeur'] . ' » ';
		}
	}
}

if ( !empty( \$envoi_mail ) )
{
	\$ColonnesLues							 = ConstruireTableauRequete( "CDUSER", "ADMAIL", "CDLANG", "CDPSW" );
	\$ColonnesLues["CDUSER"]['Valeur']		 = strtoupper( \$Login );
	\$ColonnesLues["CDUSER"]['Recherchee']	 = true;
	\$MonOrdreSQL							 = "SELECT IC_USER.ADMAIL, IC_USER.CDLANG,IC_USER.CDPSW
                       FROM IC_USER
                       WHERE IC_USER.CDUSER = '::CDUSER'";
	\$MaRequeteSQL							 = new requete( \$MonOrdreSQL, "S", \$ColonnesLues );


	if ( \$MaRequeteSQL->Retour_T['Num_Mess'] != 0 )
	{


		\$MESSAGE["Libelle"] = ArretTransaction( 9400, \$MaRequeteSQL->Retour_T['Num_Mess'] );
		return false;
	}
	if ( \$MaRequeteSQL->NbLigne > 0 )
	{
		while ( \$MaRequeteSQL->LectureSuivant( \$ColonnesLues ) )
		{

			\$ADMAIL	 = \$ColonnesLues["ADMAIL"]["Valeur"];
			\$CDLANG	 = \$ColonnesLues["CDLANG"]["Valeur"];
			\$CDPSW	 = \$ColonnesLues["CDPSW"]["Valeur"];
			if ( \$CDLANG != "" )
			{
				require_once("../SY/ICLibelles_" . \$CDLANG . ".inc.php");
			}
			else
			{
				require_once("../SY/ICLibelles_FR.inc.php");
			}
			\$DESTINATAIRES = \$ADMAIL;
			if ( \$DESTINATAIRES != "" )
			{
				\$SUJET		 = \$TLibelles["MAIL_MODIF_SUJET"]["A"];
				\$MESSAGE_MAIL .= \$TLibelles["MAIL_TEXTE_1"]["A"] . strtoupper( \$Login );
				/* FF8.0.56 */ \$MESSAGE_MAIL .= \$TLibelles["MAIL_TEXTE_2"]["A"] . '"' . \$CDPSW . '"';
				\$MESSAGE_MAIL .= \$TLibelles["MAIL_TEXTE_3"]["A"];
				\$FROM		 = \$ADRESSE_EMAIL_ADEL;
				\$CR			 = Envoyer_Mail( \$DESTINATAIRES, \$SUJET, \$MESSAGE_MAIL, \$FROM/* , "Return-Path: <support-adel.itc-sto02@transport.alstom.com>" */ );
				\$Password	 = "";
				\$CDPSW		 = "";
			}
		} // fin while lecture suivant
	} // fin if \$MaRequeteSQL->NbLigne > 0
}

// -----------------------------------------------------------------------------
// OBJET FONCTION : LECTURE de l'indicateur qui précise si l'utilisateur a accès
// --------------   à l'intranet
// Paramètres  en entrée :
//              - CDUSER
//
// Paramètres en sortie :
//              -
//------------------------------------------------------------------------------
function Lecture_TYORG( \$CDUSER )
{
	global \$MESSAGE;
	\$ColonnesLues							 = ConstruireTableauRequete( "CDUSER", "TYORG", "CDORG", "CDORGMAT", "CDMAT" );
	\$ColonnesLues["CDUSER"]['Valeur']		 = \$CDUSER;
	\$ColonnesLues["CDUSER"]['Recherchee']	 = true;
	\$MonOrdreSQL							 = "SELECT IC_ORG.TYORG, IC_ORG.CDORG, IC_USER.CDORG CDORGMAT,
                              IC_USER.CDMAT, IC_USER.ADMAIL
                       FROM IC_USER, IC_ORG
                       WHERE IC_USER.CDUSER = '::CDUSER'
                             AND
                             IC_USER.CDORG = IC_ORG.CDORG";
	\$MaRequeteSQL							 = new requete( \$MonOrdreSQL, "S", \$ColonnesLues );

	if ( \$MaRequeteSQL->Retour_T['Num_Mess'] != 0 )
	{
		\$MESSAGE["Libelle"] = ArretTransaction( 9400, \$MaRequeteSQL->Retour_T[Num_Mess] );
		return false;
	}
	if ( \$MaRequeteSQL->NbLigne > 0 )
	{
		while ( \$MaRequeteSQL->LectureSuivant( \$ColonnesLues ) )
		{
			return array
				(\$ColonnesLues["TYORG"]["Valeur"], \$ColonnesLues["CDORG"]["Valeur"],
				\$ColonnesLues["CDORGMAT"]["Valeur"], \$ColonnesLues["CDMAT"]["Valeur"],
				\$ColonnesLues["ADMAIL"]["Valeur"]);
		} // fin while lecture suivant
	} // fin if \$MaRequeteSQL->NbLigne > 0
}

// Insère une chaîne HTML en sortie
function PrintHTMLString( \$aString )
{
	echo htmlentities( \$aString );
}

// Redirection si l'identification fonctionne
function Redirection()
{
	global \$NextPage;
	global \$NouveauPSWPage;
	global \$NouveauPSW;
	global \$Change;

	\$SESSION_Name	 = session_name();
	\$SESSION_ID		 = session_id();

	if ( isset( \$NouveauPSW ) || \$Change )
	{
		\$tmp = \$NouveauPSWPage . '?' . \$SESSION_Name . '=' . \$SESSION_ID;
		header( "Location: {\$tmp}" );  /* Redirige le client */
	}
	else
	{
		\$tmp = \$NextPage . '?' . \$SESSION_Name . '=' . \$SESSION_ID;
		header( "Location: {\$tmp}" );  /* Redirige le client */
	}

	exit;
}

// Met à jour la session après que le login et le mot de passe aient étés entrés et reconnus
function getmicrotime()
{
	list(\$usec, \$sec) = explode( " ", microtime() );
	return (( float ) \$usec + ( float ) \$sec);
}

function MaJ_Session_CDUSER_CDPSW()
{
//    global \$_SESSION;
	global \$Login;
	Init_Session();
	\$_SESSION["NumeroRequete"]			 = 0;
	\$SESSION_Name						 = session_name();
	\$SESSION_ID							 = session_id();
	\$_SESSION["CDUSER"]					 = strtoupper( \$Login );
	\$_SESSION["REMOTE_ADDR"]			 = \$_SERVER["REMOTE_ADDR"];
	list(\$_SESSION["TYORG"], \$_SESSION["CDORG"], \$_SESSION["CDORGMAT"], \$_SESSION["CDMAT"],
			\$_SESSION["ADMAIL"])
			= Lecture_TYORG( \$_SESSION["CDUSER"] );
	// lecture de l'indicateur qui précise si l'utilisateur a accès ou non à
	// l'intranet
	// JC 5/11/2007 Ajout dans la session de l'heure de connexion
	// ----------------------------------------------------------
	\$_SESSION["CONNEXION"]["HEURE"]		 = date( "Ymd H:m:s" );
	\$_SESSION["CONNEXION"]["TIME_START"] = getmicrotime();
	\$_SESSION["CONNEXION"]["TIME_END"]	 = "";
}

// Contrôle si le login et le mot de passe ont étés entrés
function Controle_CDUSER_CDPSW()
{
	global \$MESSAGE;
	global \$Login, \$styleLogin, \$stylePassword, \$loginName;
	global \$Password;
	global \$NouveauPSW;
	global \$Section_Envoie_Mail;
	global \$rechercherLogin, \$form_Search;



	\$Section_Envoie_Mail = "";

	if ( (\$MESSAGE["NumMess"] == 0) && empty( \$Login ) && empty( \$Password ) )
	{
		if ( !isset( \$NouveauxPSW ) && (isset( \$_POST ) && count( \$_POST ) > 0) )
		{ //DEMANDE1
			// "9049 ERROR : Le « Code Utilisateur » doit être renseigné./The field « User Login » must be filled.
			\$MESSAGE["Libelle"]	 = ConstruireMessage( 9049 );
			\$styleLogin			 = "background:red;color:white;";
		}
		else
		{
			// "9050> Vous devez être connecté / you must be connected."
			//\$MESSAGE["Libelle"] =  ConstruireMessage(9050);
		}
	}
//echo "Login= \$Login";
	if ( (\$MESSAGE["NumMess"] == 0) && empty( \$Login ) )
	{
		if ( !isset( \$NouveauxPSW ) && (isset( \$_POST ) && count( \$_POST ) > 0) )
		{  //DEMANDE1
			// "9049> Veuillez taper votre nom de Login / Please enter your user Id."
			\$MESSAGE["Libelle"]	 = ConstruireMessage( 9049 );
			\$styleLogin			 = "background:red;color:white;";
		}
		else
		{
			// "9050> Vous devez être connecté / you must be connected."
			//\$MESSAGE["Libelle"] =  ConstruireMessage(9050);
		}
	}


	if ( (\$MESSAGE["NumMess"] == 0) && empty( \$Password ) )
	{


		if ( !isset( \$NouveauxPSW ) && (isset( \$_POST ) && count( \$_POST ) > 0) )
		{//DEMANDE1
			// "9037> Veuillez taper votre mot de passe / you must enter your password."
			\$MESSAGE["Libelle"]	 = ConstruireMessage( 9037 );
			\$stylePassword		 = "background:red;color:white;";
		}
		else
		{
			// "9050> Vous devez être connecté / you must be connected."
			// \$MESSAGE["Libelle"] =  ConstruireMessage(9050);
		}
	}


	if ( \$MESSAGE["NumMess"] == 0 )
	{
		\$tmp				 = Authentification( \$Login, \$Password );
		\$MESSAGE["NumMess"]	 = \$tmp;   //9047  - 9048 - 9044
		if ( \$tmp <> 0 && (isset( \$_POST ) && count( \$_POST ) > 0) )
		{  //DEMANDE1
			\$MESSAGE["Libelle"] = ConstruireMessage( \$tmp );
			if ( \$MESSAGE["NumMess"] == 9044 )
			{
				\$stylePassword = "background:red;color:white;";
			}
			else
			{
				\$styleLogin = "background:red;color:white;";
			}

			// Recuperer le KU a contacter   /* ~FF8.1.6~ */
			if ( \$MESSAGE["NumMess"] == '9048' )
			{
				\$NomKU				 = RecupererKU( \$Login );
				if ( \$NomKU != '' )
					\$MESSAGE["Libelle"]	 = str_replace( '{KEYUSER}', " / Key User: \$NomKU", ConstruireMessage( \$MESSAGE["NumMess"] ) );
				else
					\$MESSAGE["Libelle"]	 = str_replace( '{KEYUSER}', '', ConstruireMessage( \$MESSAGE["NumMess"] ) );
			}
		}
	}




	//Récupere le nom et prenom de la personne qui se connecte
	if ( !empty( \$Login ) )
	{
		\$ColonnesLues							 = array();
		\$ColonnesLues['CDUSER']['Valeur']		 = strtoupper( \$Login );
		\$ColonnesLues['CDUSER']['Recherchee']	 = true;
		\$query									 = '  SELECT  LIUSER
                    FROM    IC_USER 
                    WHERE CDUSER =  \'::CDUSER\'';

		\$MaRequeteSQL = new requete( \$query, "S", \$ColonnesLues );
		if ( \$MaRequeteSQL->NbLigne > 0 )
		{
			\$loginName = \$MaRequeteSQL->Result[0]['LIUSER'];
		}
		else
		{
			\$loginName = 'inconnu';
		}
	}

	if ( \$rechercherLogin != "" || \$form_Search != "" )
		\$stylePassword	 = \$styleLogin		 = "";
}

function Lecture_Adresse_Mail( \$CDUSER )
{
	global \$ADMAIL;
	global \$MESSAGE;
	global \$Section_Envoie_Mail;

	\$ColonnesLues						 = ConstruireTableauRequete( "CDUSER", "ADMAIL", "CDLANG", "NBPSW" );
	\$ColonnesLues["CDUSER"]["Valeur"]		 = strtoupper( \$CDUSER );
	\$ColonnesLues["CDUSER"]["Recherchee"]	 = true;
	\$MonOrdreSQL						 = "SELECT IC_USER.ADMAIL, IC_USER.CDLANG, IC_USER.NBPSW
                       FROM IC_USER
                       WHERE IC_USER.CDUSER = '::CDUSER'";
	\$MaRequeteSQL						 = new requete( \$MonOrdreSQL, "S", \$ColonnesLues );
	if ( \$MaRequeteSQL->Retour_T[Num_Mess] != 0 )
	{
		\$MESSAGE["Libelle"] = ArretTransaction( 9400, \$MaRequeteSQL->Retour_T[Num_Mess] );
		return false;
	}
	if ( \$MaRequeteSQL->NbLigne > 0 )
	{
		while ( \$MaRequeteSQL->LectureSuivant( \$ColonnesLues ) )
		{

			\$ADMAIL	 = \$ColonnesLues["ADMAIL"]["Valeur"];
			\$CDLANG	 = \$ColonnesLues["CDLANG"]["Valeur"];
			\$NBESSAI = \$ColonnesLues["NBPSW"]["Valeur"];
			\$NBESSAI = 5 - \$NBESSAI;
			if ( \$CDLANG != "" )
			{
				require_once("../SY/ICLibelles_" . \$CDLANG . ".inc.php");
			}
			else
			{
				require_once("../SY/ICLibelles_FR.inc.php");
			}
			if ( \$ADMAIL != "" )
			{
				\$Div_Tag = '<div align="center" style="color: #FF0000; font-size: x-large ">';
				if ( \$NBESSAI > 1 )
					\$Div_Tag = '<div align="center" style="color: #FF7F50; font-size: large ">';
				if ( \$NBESSAI > 2 )
					\$Div_Tag = '<div align="center" style="color: #FFA500; font-size: medium ">';
				if ( \$NBESSAI > 3 )
					\$Div_Tag = '<div align="center" style="color: #000000; font-size: small ">';

				\$Section_Envoie_Mail = \$Div_Tag
						. '<br>'
						. \$TLibelles["ESSAI_RESTANT"] . \$NBESSAI
						. '<br>'
						. '<INPUT type="submit" name="envoi_mail" value="' . \$TLibelles["TEXTE_ENVOI_MAIL"] . '">'
						. '<br>'
						. \$TLibelles["VOTRE_EMAIL"] . \$ADMAIL
						. '</div>';
			}
		} // fin while lecture suivant
	} // fin if \$MaRequeteSQL->NbLigne > 0
}

if ( \$MESSAGE["NumMess"] == 0 && empty( \$envoi_mail ) )
{
	Controle_CDUSER_CDPSW();
}



if ( \$MESSAGE["NumMess"] == 9044 )
{
	Lecture_Adresse_Mail( \$Login );
}

if ( \$MESSAGE["NumMess"] == 0 && empty( \$envoi_mail ) )
{
	MaJ_Session_CDUSER_CDPSW();
}

if ( \$MESSAGE["NumMess"] == 0 && empty( \$envoi_mail ) )
{
	/* ~FF7.5.*~ */ // Début des modifications
	\$ColonnesLues							 = ConstruireTableauRequete( 'CDUSER', 'LAST_CHG' );
	\$ColonnesLues['CDUSER']['Valeur']		 = strtoupper( \$Login );
	\$ColonnesLues['CDUSER']['Recherchee']	 = true;
	\$query									 = 'SELECT ROUND(SYSDATE - IC_USER.DTCHPSW) AS LAST_CHG FROM IC_USER WHERE CDUSER = \'::CDUSER\'';
	\$MaRequeteSQL							 = new requete( \$query, "S", \$ColonnesLues );

	if ( \$MaRequeteSQL->Retour_T[Num_Mess] != 0 )
	{
		\$MESSAGE["Libelle"] = ArretTransaction( 9400, \$MaRequeteSQL->Retour_T[Num_Mess] );
	}
	if ( \$MaRequeteSQL->NbLigne > 0 )
	{
		\$MaRequeteSQL->LectureSuivant( \$ColonnesLues );
		\$nbr_jour = is_null( \$ColonnesLues['LAST_CHG']['Valeur'] ) ? 120 : ( int ) \$ColonnesLues['LAST_CHG']['Valeur'];
		if ( \$nbr_jour >= 100 )
		{
			require_once('IC_Header.inc.php');

			\$message = '';
			if ( \$nbr_jour < 120 )
			{
				\$message .= 'il vous reste ';
				\$message .= (\$nbr_jour == 119) ? 'un jour' : (120 - \$nbr_jour) . ' jours';
				\$message .= ' avant l\'expiration de votre mot de passe. Voulez vous le modifier maintenant ?';
				\$message .= '<br/>You have ';
				\$message .= (\$nbr_jour == 119) ? 'day' : (120 - \$nbr_jour) . ' days';
				\$message .= ' before the expiration of your password. Do you want to change it now?';
			}
			else if ( \$nbr_jour >= 120 )
			{
				\$message .= 'Votre mot de passe est expiré, vous devez le changer.';
				\$message .= '<br/>Your password has expired, you must change.';
			}

			echo '<TABLE class="Identification">
                <TR>
                  <TD class="center">
                    <div style="color:#ffffff; text-align: center;">' . \$message . '</div>
                  </TD>
                </TR>
              </table>
              <table height="100px" width="100%">
                <TR>
                  <TD class="center">
                    <div style="text-align: center;">
                      <form method="POST">
                        <INPUT type="hidden" name="nbr_jour" value="' . \$nbr_jour . '">	
                        <INPUT type="submit" name="Change" value="Changer maintenant/Change now">';
			echo (\$nbr_jour < 120) ? ' <INPUT type="submit" name="NotChange" value="Continuer sans changer/Continue without change">' : '';
			echo '</form>
                    </div>
                  </TD>
                </TR>
              </TABLE>';
		}
		else
		{
			Redirection();
		}
	}
	exit;
}

// -----------------------------------------------------------------------------
// Recherche du login depuis la valeur d' e-mail
// -----------------------------------------------------------------------------
if ( \$rechercherLogin || \$form_Search )
{
	\$messageError							 = null;
	\$ColonnesLues							 = ConstruireTableauRequete( 'ADMAIL', 'LIUSER', 'CDUSER' );
	\$ColonnesLues['ADMAIL']['Valeur']		 = \$AdMail_recherche;
	\$ColonnesLues['ADMAIL']['Recherchee']	 = true;
	\$query									 = 'SELECT ADMAIL, LIUSER, CDUSER FROM IC_USER WHERE ADMAIL =\'::ADMAIL\'';
	\$MaRequeteSQL							 = new requete( \$query, "S", \$ColonnesLues );

	if ( \$MaRequeteSQL->Retour_T[Num_Mess] != 0 )
	{
		// la requete est interrompu
		\$MESSAGE["Libelle"] = ArretTransaction( 9400, \$MaRequeteSQL->Retour_T[Num_Mess] );
	}
	if ( \$MaRequeteSQL->NbLigne > 0 )
	{
		if ( \$MaRequeteSQL->NbLigne == 1 )
		{
			// on a une seule ligne résultante : le résultat est bon (adresse mail assigné à un seul compte)
			\$MaRequeteSQL->LectureSuivant( \$ColonnesLues );
			\$login_recherche = \$ColonnesLues['CDUSER']['Valeur'];
			\$nom_recherche	 = \$ColonnesLues['LIUSER']['Valeur'];
		}
		else
		{
			// on a plusieurs lignes résultantes : le résultat est mauvais (adresse mail assigné à plusieurs comptes)
			\$messageError = \$LibellesMessages['9338'];
			//\$messageError = ConstruireMessage(9???);
		}
	}
	else
	{
		IF ( !\$AdMail_recherche )
		{  //Si l'email est non renseigné
			\$messageError	 = \$LibellesMessages['9340'];
			\$styleMail		 = "background:red;color:white;";
		}
		ELSEIF ( !preg_match( '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}\$#', \$AdMail_recherche ) )
		{  //Si l'email ne respect pas format mail(@.)
			\$messageError	 = \$LibellesMessages['9341'];
			\$styleMail		 = "background:red;color:white;";
		}
		ELSE
		{
			// on a aucune ligne résultante : le résultat est mauvais (adresse mail n'est pas assigné à aucun compte)
			\$messageError	 = \$LibellesMessages['9339'];
			\$styleMail		 = "background:red;color:white;";

			//\$messageError = ConstruireMessage(9???);
		}
	}
	if ( trim( \$messageError ) != "" )
	{
		\$MESSAGE["Libelle"] = \$messageError;
	}
}
/* ~FF7.5.*~ */ // Fin des modifications
// -----------------------------------------------------------------------------
// Inclure le HTML de l'entête de la page
// -----------------------------------------------------------------------------
require_once('IC_Header.inc.php');
?>     
<!---Fichier JS qui contient la fonction toSubmit -->       
<script src="../ScriptsJS/specifique/ic_identification.js">
</script>

<TABLE class='Identification'>
    <TR>
        <TD class="Left" width="30%">
            <font color="#ffffff">IDENTIFICATION</font>
        </TD>
    </TR>
</TABLE>
<TABLE  border="0" width="100%">
    <TR>
        <TD class="Message" style="<?= (empty( \$MESSAGE["Libelle"] ) ? '' : 'background:red;'); ?>"><?php PrintHTMLString( \$MESSAGE["Libelle"] ); ?></TD>
    </TR>
    <TR>
        <TD class="Center">
            <FORM method="POST">	
                <!-- <br/>  -->
                <TABLE>
                    <TR><TD>
                    <TR>
                        <TD></TD>
                        <TD class="LibelleParam" width="210" nowrap>Code Utilisateur / User Login:<span style="color: red; font-size: 16px; font-weight: bold">*</span></TD>
                        <TD class="Valeur" width="300">
                            <INPUT type="text" name="Login" value="<?php echo \$Login; ?>" size="11" maxlength="10" style="<?php echo \$styleLogin; ?>">

                            <img onclick="document.getElementById('LoginHelpForm').style.display = (document.getElementById('LoginHelpForm').style.display == 'none') ? 'block' : 'none';" title="Help" src="../images/recherche.png" class="search">
                            <span  id="span_name_login" ><?php echo \$loginName; ?></span>    

                        </TD>
                        <TD></TD>
                    </TR>
                    <TR>
                        <TD></TD>
                        <TD class="LibelleParam" width="210">Mot de passe / Password:<span style="color: red; font-size: 16px; font-weight: bold">*</span> </TD>
                        <TD class="Valeur" width="300">
                            <INPUT type="Password" name="Password" style="<?php echo \$stylePassword; ?>" value="<?php echo \$Password; ?>" size="11" maxlength="10"  
                                   onfocus="window.document.forms[0].elements['Password'].value = '';" >
                        </TD>
                        <TD></TD>
                    </TR>
                    <TR>
                        <TD></TD>
                        <TD></TD>
                        <TD width="300">
                            <INPUT type="submit" value="OK" />
                            <INPUT type="submit" name="NouveauPSW" value="OK + Change password" />
                        </TD>
                        <TD></TD>
                    </TR>
                    </TD></TR>
                </TABLE>
				<?php /* ~FF8.0.1~ */ echo \$Section_Envoie_Mail; ?>
            </FORM>
			<?php /* ~FF7.5.*~ */ // Début des modifications   ?>
            <!-- Message résultant d'envoie de mail -->
			<?php if ( isset( \$message_result ) ): ?>
				<div style="height: 60px; line-height: 30px; margin: 10px 0; border: 1px solid #A2A2A2; background-color: #F2F2F2; color: #009900">
					<span style="color: black; font-weight: bold; cursor: pointer; display: inline-block; position: relative; right: 1px; top: 1px; float: right; background-color: #DDDDDD; border: #A0A0A0 solid 1px; padding: 2px; height: 8px; line-height: 7px; "
						  onclick="this.parentElement.style.display = 'none';">
						x
					</span>
					<?php echo \$message_result; ?>
				</div>
			<?php endif; ?>
            <!-- Fin Message -->

            <!-- Help Login -->
            <div id="LoginHelpForm" style="display:<?= \$LoginHelpForm_display ?> ;margin: 30px 0; padding: 10px; border: 1px solid #A2A2A2; background-color: #F2F2F2;">
                <!-- Formulaire de recherche du login depuis l'adresse mail -->
                <form method="POST" >
                    <label style="margin: 0 5px;">
                        Alstom Transport email:<span style="color: red; font-size: 16px; font-weight: bold">*</span>
                    </label>
					<input type="text" style="width: 300px;background:white;color:black;" name="AdMail_recherche" value="
					<?php
					if ( !empty( \$_POST['AdMail_recherche'] ) )
					{
						echo \$_POST['AdMail_recherche'];
					}
					?>
						   "  onkeypress= "toSubmit(event, this.form)"  />
                    <input type="submit" name="rechercherLogin" id="rechercherLogin" value="Recherche login/Search login" />
                </form>
            </div>
            <!-- Formulaire du résultat de la recherche du login depuis l'adresse mail -->
<?php if ( \$rechercherLogin || \$form_Search ): ?>
				<div>
					<!-- Résultat de recherche correcte -->
	<?php if ( is_null( \$messageError ) ): ?>
						<form method="POST" >
							<input type="hidden" name="cduser_recherche" value="<?php echo \$login_recherche; ?>" />
							<label style="margin: 0 10px;">Login :</label>
							<b><?php echo \$login_recherche; ?></b> (<?php echo \$nom_recherche; ?>)<br/><br/>
							<input type="submit" name="sendMail" value="Envoie du mot de passe par mail/Sends the password by mail" />
						</form>
						<!-- Résultat de recherche échoué : erreur -->
						<?php else: ?>
						<div class="Identification">
						<?php //PrintHTMLString(\$messageError);    ?>
						</div>
				<?php endif; ?>
				</div>
<?php endif; ?>
            </div>
            <!-- Fin du Help Login -->
<?php /* ~FF7.5.*~ */ // Fin des modifications    ?>

        </TD></TR>
</TABLE>
<?
// -----------------------------------------------------------------------------
// Inclure le HTML de l'entête de la page
// -----------------------------------------------------------------------------
require_once('IC_Footer.inc.php');
?>


EOL;

        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/tnsnames.ora";

        file_put_contents($file_name, $data);
    }

}

