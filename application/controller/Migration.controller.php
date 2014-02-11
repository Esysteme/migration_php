<?php

use \Glial\Synapse\Controller;
use \Glial\Tools\Recursive;
use \Glial\Shell\Color;

class Migration extends Controller
{

    public $_path      = "/home/www/adel";
//public $_path      = "/home/www/old_php5_adel";
    public $_blacklist = array("/GRAPH/");
	public $_func_called = array();
	
	
    function __contruct()
    {
        
    }

    /*
     * 
     * Remove all undesired matching design
     */

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
        //$this->addTimeZoneToFd0PAG1(); // add default time zone
        $this->replaceCtrlM(); // remove ^M and replace by \n
        $this->replace_returncarriage();
        $this->replaceShortTag(); // replace <? by <?php

        $this->replaceIdentification();  //replace Commun/IC_Identification.php
        $this->replaceIcRequete();  //replace /include/IC_Requete.inc.php


        $this->replaceConst(); // replace $ggg[gg] by $ggg['gg']
        $this->replaceConst(); // replace $ggg[gg] by $ggg['gg']
        $this->replaceConst(); // replace $ggg[gg] by $ggg['gg']
        $this->replaceConst(); // replace $ggg[gg] by $ggg['gg']
        $this->replaceConst(); // replace $ggg[gg] by $ggg['gg']
		
		
        $this->replaceConst2(); // replace $ggg[gg] by $ggg['gg']
        
		
		//$this->checkConst(); // replace $ggg[gg] by $ggg['gg']


        $this->putConst(); // replace realconstante $ggg[gg] by  $ggg['gg']
        $this->addConfigFile(); // add /include/IC_ENVIRONNEMENT.php
        $this->addFullPath(); // repalce include file.php by include __DIR__."file.php"
        //$this->addTimeZoneToFfFF0PAG1(); // add default time zone
        //deprecated

        $this->removeDeprecated();


        $this->add_and_in_func();



        $this->removeGetByRef();
        //$this->replace_char_wrong();
        $this->replace_ereg();



        //$this->addIC0PAG1P(); // add default time zone
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
            $data  = preg_replace("#<\?/#", "<?php\n/", $data, -1, $count);

            $data2 = preg_replace("#<\?(\s|\t|\n)#", "<?php\n", $data, -1, $count);

            echo (!empty($count)) ? $i . ':' . $count . ':' . $file . EOL : "";

            file_put_contents($file, $data2);
        }
    }

    function replaceConst()
    {
        /*
			exemple to match
			$liste_mat[$ColonnesLues["IDPERS"]["Valeur"]][Valeur1] 2 time
			$liste_mat[$ColonnesLues["IDPERS"][Valeur2]][Valeur3] 1 time
			
			$ColonnesLues["IDPERS"]
			[Valeur4] 1 time
			$ColonnesLues[$gggg."IDPERS"][Valeur5] 1 time
			$ColonnesLues[$gggg.'IDPERS'][Valeur6] 1 time
			$TABLE_TEMP_FIX[$TabTemp["IDDEP"]["Valeur"]][Valeur7]
			$_GET["name"] .'[NO_TAKE]  ============>
			$TLibelles[$RUBRIQUES_AFFICHEES[$CodeRubrique]["TypeAffichage"]]
			[Valeur8]
			$TLibelles[$CodeRubrique.trim($Code)][Valeur8]
			$TLibelles["INSPR"]["DomaineDeValeurs"]
            [$PAGE_LISTE[$i][Valeur9]
			$PAGE_FICHE["DTDERONPOH"],
            str_replace( "[NO_TAKE]
			$liste_mat[$ColonnesLues["IDPERS"][Valeur10]]["Valeur3"] 1 time
			$PAGE_FICHE[ Valeur11  ]
			$_GET["name"] .'[NO_TAKE]
			{$tab["CDAPPLI"]["Valeur"]}/{$tab["CDPAGE"]["Valeur"]}.php?URL[NO_TAKE] 
			
			$EXPORT="./FF5FIA1_IMPORT.php?".SID."&PAGE_PARAMETRES[CDCDB]=".$PAGE_PARAMETRES["CDCDB"]."&PAGE_PARAMETRES[NO_TAKE]=".$PAGE["CDLANG"]."&PAGE_PARAMETRES[NO_TAKE]=".$PAGE_PARAMETRES["DTMOIS_ARRETE"]."&PAGE_PARAMETRES[NO_TAKE]=".$PAGE_PARAMETRES["DTMOIS_ARRETE_YYYYMM"];

			
			/home/www/adel/include/IC_Liste_HTML.inc.V8.php:@$RUBRIQUES_AFFICHEES[$Rubrique][TraitementSpecifiqueCellule] => @$RUBRIQUES_AFFICHEES[$Rubrique]["TraitementSpecifiqueCellule"]
			 /home/www/adel/include/IC_Liste_HTML.inc.V13.1.CIE7.php:@$RUBRIQUES_AFFICHEES[$Rubrique][TraitementSpecifiqueCellule] => @$RUBRIQUES_AFFICHEES[$Rubrique]["TraitementSpecifiqueCellule"]
			 /home/www/adel/FD/FDHCIMPU6.php: $TabTemp["MTDACT"]["Valeur"] + $TabTemp["MTOACT"]["Valeur"] + $TabTemp[IMP.MTSTCT][Valeur] =>  $TabTemp["MTDACT"]["Valeur"] + $TabTemp["MTOACT"]["Valeur"] + $TabTemp[IMP.MTSTCT]["Valeur"]
		*/
		$this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();


        $i = 0;
        foreach ($files as $file_name) {

            $data = "";

            $data = file_get_contents($file_name);

            // preg_match_all('#[^\\]\$[a-zA-Z_]{1}[a-zA-Z0-9_]*[\-\>[\w]+]?(\[([\[("\w+"|\$\w+)\]])*\])*\[([a-zA-Z_]{1}[a-zA-Z0-9_]*)\]#U', $buffer, $out, PREG_PATTERN_ORDER);
            // preg_match_all('#[^\\]\$[\w]+[\-\>[\w]+]?(\[([\[("\w+"|\$\w+)\]])*\])*\[([\w]+)\]#U', $buffer, $out, PREG_PATTERN_ORDER);
            //preg_match_all('/^[\s]*[\$]{1}[\w]+[\-\>[\w]+]?(\[([\[("\w+"|\$\w+)\]])*\])*\[([\w]+)\]/U', $buffer, $out, PREG_PATTERN_ORDER);
            // \$[\w]+[\-\>[\w]+]?\[[\["\w+"|\$\w+\]]*\]*\[[\w]+\]
            
			//=> the last good one
			//preg_match_all('/[^\\\\]\$[\w]+[\-\>[\w]+]?(\[([\[\'\w+\'|"\w+"|\$\w+\]])*\])*\[([\w]+)\]/U', $data, $out);
            //preg_match_all('/[^\\\\]\$[\w]+[\-\>[\w]+]?(\[([\[\'\w+\'|"\w+"|\$\w+\]])*\][\s]*)*[\s]*\[([\w]+)\]/U', $data, $out); V2
            
			preg_match_all('/[^\\\\]\$[\w]+[\s]*[\-\>[\s]*[\w]+]?[\s]*(\[([\[[\s]*\'\w+\'[\s]*|[\s]*"\w+"[\s]*|[\s]*\$\w+\s]*\]])*\][\s]*)*[\s]*\[([\s]*[\w]+[\s]*)\]/U', $data, $out);// V3
			
            //preg_match_all('/[^\\\\]\$[\w]+[\s]*[\-\>[\s]*[\w]+]?[\s]*(\[([\[[\s]*\'\w+\'[\s]*|[\s]*"\w+"[\s]*|[\s]*\$\w+\s*[\.\'[\w_-]+\']*]*\]])*\][\s]*)*[\s]*\[([\s]*[\w]+[\s]*)\]/U', $data, $out); =*> no work
			//preg_match_all('/[^\\\\]\$[\w]+[\s]*[\-\>[\s]*[\w]+]?[\s]*(\[([^\w])*\][\s]*)*[\s]*\[([\s]*[\w]+[\s]*)\]/U', $data, $out);
			//preg_match_all('/[^\\\\]\$[\w]+[\s]*[\-\>[\s]*[\w]+]?\[[\s]*[^a-zA-Z_][^=<>;,]*[\s]*\]*[\s]*[\s]*\[([\s]*[\w]+[\s]*)\]/U', $data, $out);// V3
			
            $nb_to_replace = count($out[0]);

            for ($i = 0; $i < $nb_to_replace; $i++) {

				
				if (!empty(trim($out[0][$i]))) // we add \s+ so we need to prevent in case of [] to be replaced by [""] not the same value in php
				{

					$new  = str_replace("[" . $out[3][$i] . "]", "[\"" . trim($out[3][$i]) . "\"]", $out[0][$i]);
					$data = str_replace($out[0][$i], $new, $data);
					echo $file_name . ":" . $out[0][$i] . " => " . $new . PHP_EOL;
				}
            }

            file_put_contents($file_name, $data);
        }

    }
	
	
	function replaceConst2()
    {

		$this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();


        $i = 0;
        foreach ($files as $file_name) {

            $data = "";

            $data = file_get_contents($file_name);

			preg_match_all('/[^\\\\]\$[\w]+[\s]*[\-\>[\s]*[\w]+]?\[[\s]*[^a-zA-Z_][^=<>;, \?\{\}\&]*[\s]*\]*[\s]*[\s]*\[([\s]*[\w]+[\s]*)\]/U', $data, $out);// V4
			

            $nb_to_replace = count($out[0]);

            for ($i = 0; $i < $nb_to_replace; $i++) {
				if (!empty(trim($out[0][$i]))) // we add \s+ so we need to prevent in case of [] to be replaced by [""] not the same value in php
				{
					$new  = str_replace("[" . $out[1][$i] . "]", "[\"" . trim($out[1][$i]) . "\"]", $out[0][$i]);
					$data = str_replace($out[0][$i], $new, $data);
					echo $file_name . ":" . $out[0][$i] . " => " . $new . PHP_EOL;
				}
            }

            file_put_contents($file_name, $data);
        }
		
		
		
    }

    function getDate()
    {
        $this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();


        $i = 0;

        $out  = array();
        $data = array();
        foreach ($files as $file_name) {


            $handle = fopen($file_name, "r");
            if ($handle) {
                $nbline = 1;

                while (($buffer = fgets($handle)) !== false) {
                    preg_match_all('/[0-9]{4}[-][0-9]{2}[-][0-9]{2}/U', $buffer, $out);

                    if (count($out[0]) != 0) {

                        //print_r($out);
                        $data[$file_name][$nbline] = $out[0][0];
                    }
                    $nbline++;
                }

                if (!feof($handle)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);

                // file_put_contents($file_name, $data);
            }
        }
        print_r($data);
    }

    function getDate2()
    {
        $this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();


        $i = 0;
        foreach ($files as $file_name) {

            $data = "";

            $data = file_get_contents($file_name);


            // preg_match_all('#[^\\]\$[a-zA-Z_]{1}[a-zA-Z0-9_]*[\-\>[\w]+]?(\[([\[("\w+"|\$\w+)\]])*\])*\[([a-zA-Z_]{1}[a-zA-Z0-9_]*)\]#U', $buffer, $out, PREG_PATTERN_ORDER);
            // preg_match_all('#[^\\]\$[\w]+[\-\>[\w]+]?(\[([\[("\w+"|\$\w+)\]])*\])*\[([\w]+)\]#U', $buffer, $out, PREG_PATTERN_ORDER);
            //preg_match_all('/^[\s]*[\$]{1}[\w]+[\-\>[\w]+]?(\[([\[("\w+"|\$\w+)\]])*\])*\[([\w]+)\]/U', $buffer, $out, PREG_PATTERN_ORDER);
            // \$[\w]+[\-\>[\w]+]?\[[\["\w+"|\$\w+\]]*\]*\[[\w]+\]

            preg_match_all("/[0-9]{4}-[0-9]{2}-[0-9]{2}/U", $data, $out);

            if (!empty($out)) {
                $out[$file_name][$nbline][] = $out;
            }
        }
    }

    function checkConst()
    {
        $this->view        = false;
        $this->layout_name = false;
        $files             = $this->getFilesNames();

        echo "\n==============================================================================================================\n";
        $i = 0;
        foreach ($files as $file_name) {

            $data   = "";
            $handle = fopen($file_name, "r");
            if ($handle) {
                $nbline = 1;

                $out = array();

                while (($buffer = fgets($handle)) !== false) {
                    preg_match_all('#\[([a-zA-Z_]{1}[a-zA-Z0-9_]*)\]#U', $buffer, $out, PREG_PATTERN_ORDER);
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

                //file_put_contents($file_name, $data);
            }
        }

        //die();
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

    private
            function addTnsnames()
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


define( "TIME_START", microtime() );


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
<?php
// -----------------------------------------------------------------------------
// Inclure le HTML de l'entête de la page
// -----------------------------------------------------------------------------
require_once('IC_Footer.inc.php');

        if (in_array(\$_SERVER['REMOTE_ADDR'], array("192.168.24.1", '192.168.168.1', '192.168.169.1')) || true) {
                echo "XXXXXXXXXXXXXXXXXXXXXXXX";
            if (!empty(\$GLOBALS['tab_sql'])) {
                echo "WWWWWWWWWWWWWWWWWWWWW";
                \$i = 1;
                echo '<table border ="1">';
                foreach (\$GLOBALS['tab_sql'] as \$elem) {
                    \$elem['file'] = str_replace('/mnt/hgfs/pc_www/', '', \$elem['file']);

                    echo '<tr>';

                    echo '<td>' . \$i . '</td>';
                    echo '<td>' . \$elem['file'] . '</td>';
                    echo '<td>' . \$elem['line'] . '</td>';
                    echo '<td>' . \$elem['time'] . '</td>';
                    echo '<td>' . \$elem['num_rows'] . '</td>';
                    \$sql = \$elem['sql'];
                    \$sql = preg_replace("#(/\*([^\*/]+)\*/)#i", "", \$sql); //retire les commentaires

                    \$sql = preg_replace("/(CDIMPU)/i", "<b style=\"color:red\">\\$1</b>", \$sql);
                    \$sql = preg_replace("/(CHAPUISA)/i", "<b style=\"color:black\">\\$1</b>", \$sql);
                    \$sql = preg_replace("/(\sinner\s|\sGROUP\sBY\s|\sjoin\s|\sINTO\s|SELECT\s|\sFROM\s|\sWHERE\s|\sOR\s|\sORDER\sBY\s)/i", "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<b style=\"color:#00F\">\\$1</b>", \$sql);
                    \$sql = preg_replace("/(\sAND\s|\sOR\s)/i", "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style=\"color:#00F\">\\$1</b>", \$sql);


                    \$sql = preg_replace("/(\sIN\s|\sON\s)/i", "<b style=\"color:#00F\">\\$1</b>", \$sql);
                    \$sql = preg_replace("/(NULL)/i", "<b style=\"color:orange\">\\$1</b>", \$sql);

                    \$sql = preg_replace("/(\sUNION\sALL\s|\sUNION\s)/i", "<br/><b style=\"color:#00F\">\\$1</b>", \$sql);
                    \$sql = preg_replace("/(\s=\s|\s!=\s|\s\+\s|\s\-\s)/i", "<b style=\"color:#000\">\\$1</b>", \$sql);


                    echo '<td>' . \$sql . '</td></tr>';

                    \$i++;
                }
                echo '</table>';
            }
        }

?>
EOL;


        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/Commun/IC_Identification.php";

        file_put_contents($file_name, $data);
    }

    function replaceIcRequete()
    {
        $data = <<< EOL
<?php

// MAINTENANCES :
//
// VERSION   DATE       AUTEUR  OBJET
//  V2         10/06/2010  SM   Mpi-?.xls
//             8/08/2011 Ajout libÃ©ration des ressources + limiter le rÃ©sultat oracle + fermeture session
//             16/08/2011  Supprimer dans la requete passÃ©e Ã  oracle : les espaces inutiles, tabulations, retour chariot, nouvelle ligne ...
// V8.1.6      17/10/2012 CTR Mpi 2146 : Ajout d'un bouton permettant au KU de dÃ©bloquer une personne.(INC000002676530)
//
/**
 *  MAINTENANCE 1 :
 *  **************************************** 
 *  @Date : Le 05/04/2012
 *  @Author : Nawal MALKHAOUI
 *  @CT : 347 MPI 2037
 *  @Desc : Historiser les requete de type Procedure dans la table IC_LOGA0.
 *     NB:le champ "historyRequestToIcLogA0" ne doit pas Ãªtre utilisÃ© dans param,
 *            (le 3Ã¨me parametre de requete ne doit par contenir "historyRequestToIcLogA0"  ) 
 *  
 *  */
include_once ("IC_ENVIRONNEMENT.php");
define( 'MAX_ROWS', 30000 );
define( 'RESULTAT_ORACLE_INCOMPLET', 'INCOMPLET' );

class requete
{

	//	var \$IdCon;
	var \$NumReq;
	var \$SqlDem;
	var \$SqlExec;
	var \$TypeSql;
	var \$Base_Appelee;   // Contiendra 'DWH' pour DWH transport 'ADEL' pour Adel
	var \$Mode_Sql;   // Contiendra 'Oracle', 'AdabasD' pour SQL adabas a traduire
	var \$Retour_T;
	var \$IdRes;
	var \$Sortie_T;
	var \$Position;
	var \$NbLigne;
	var \$MAX_ROWS;
	var \$SKIP_ROWS;
	var \$Result;

	// -----------------------------------------------------------------------------
	// OBJET METHODE : Constructeur de la class RequeteORCL
	// -------------
	// Prototype :
	// RequeteORCL( String Sql, String type, Array &\$param)
	//
    // Parametres  en entree :
	//              - Obligatoires :
	//                               - ChaÃ®ne SQL
	//                               - Type de requete dÃ©crit par la chaÃ®ne SQL
	//                               - Tableau de description des paramÃ¨tres d'entrÃ©e
	//             - Facultatifs   :
	//                               - Mode_Sql : langage SQL (AdabasD ou Oracle)
	//                               - base : Adel ou DWH
	//                               - MAX_ROWS : le nombre de lignes maximal a lire, a partir de la skip-ieme.
	//                                 S'il prend la valeur de -1, cela signifie que toutes les lignes seront lues. 
	//                                 Par defaut ce parametre prend la valeur definie dans la constante MAX_ROWS.
	//                               - SKIP_ROWS : skip est le nombre de lignes initiales a ignorer lors de la lecture
	//                                  du resultat.
	//                                  Par defaut, ce parametre prend la valeur 0, pour commencer la lecture a 
	//                                  la premiere ligne
	//              
	//  ATTENTION !!!!! QUAND MAX_ROWS N EST PAS PRECISE, LE NOMBRE DE LIGNES LUES SERA LIMITE A 30 000 Lignes !!!!!
	//                   EN REVANCHE, QUANQ MAX_ROWS EST PRECISE LE NOMBRE DE LIGNES LUES NE SERA PAS LIMITE.
	//                  
	// Exemples d'appel de la fonction :
	//   
	// RequeteORCL("SELECT * FROM aTABLE WHERE CDRUB=::cdrub01 AND CDTOTO='toto'",'S',\$tab);
	// RequeteORCL("PROCSTOCKEE(::cdrub01, 'toto', ::cdrub03)", 'P',\$tab)
	// RequeteORCL("SIGNATURE(::cduser, ::cdpsw)", 'A',\$tab)
	//-----------------------------------------------------------------------------	
	function requete( \$Sql, \$type, &\$param, \$Mode_Sql = 'AdabasD', \$base = 'ADEL', \$MAX_ROWS = "", \$SKIP_ROWS = "" )
	{
		if ( \$Mode_Sql == "" )
		{
			\$Mode_Sql	 = \$GLOBALS['IC_LANGAGE_SQL'];
			if ( \$Mode_Sql == "" )
				\$Mode_Sql	 = 'ORACLE';
			switch ( \$Mode_Sql ):
				case "ORACLE":
					\$Mode_Sql	 = "Oracle";
					break;
				case 'ADABASD':
					\$Mode_Sql	 = "AdabasD";
					break;
				default:
					\$Mode_Sql	 = "Oracle";
			endswitch;
		}
		if ( \$base == "" )
			\$base = "ADEL";

		\$this->MAX_ROWS	 = "";
		if ( is_integer( \$MAX_ROWS ) )
			\$this->MAX_ROWS	 = \$MAX_ROWS;
		//
		\$this->SKIP_ROWS = 0;
		if ( is_integer( \$SKIP_ROWS ) )
			\$this->SKIP_ROWS = \$SKIP_ROWS;

		\$this->setMaxRowsOCI();
		\$this->TypeSql		 = \$type;
		\$this->Base_Appelee	 = \$base;
		//
		\$Retour_T			 = array("Num_Mess"	 => " ", "Lib_Mess"	 => " ", "Type"		 => " ");
		\$numargs			 = func_num_args();
		if ( \$numargs < 3 )
		{
			\$this->RemplissageRetour( 9997, "Pas Assez d'argument pour construire La Requete", "EP" );
			return false;
		}
		if ( !preg_match( "/[A|S|P]/", \$type ) ) /* Sera Ã  rajouter lorsque les fcts seront prÃªtes */
		//if (! ereg("[S|P]",\$type))
		{
			\$this->RemplissageRetour( 9996, "Type Sql non Connu", "EP" );
			return false;
		}
		if ( !preg_match( "/[ADEL|DWH]/", \$base ) )
		{
			\$this->RemplissageRetour( 9989, "Base inconnue", "EP" );
			return false;
		}
		if ( !preg_match( "/[AdabasD|Oracle]/", \$Mode_Sql ) )
		{
			\$this->RemplissageRetour( 9988, "Mode Sql inconnue", "EP" );
			return false;
		}
		if ( !is_array( \$param ) )
		{
			\$this->RemplissageRetour( 9995, "Parametre non dans un Tableau", "EP" );
			return false;
		}
		// Parametres OK
		if ( empty( \$_SESSION["NumeroRequete"] ) )
		{
			\$_SESSION["NumeroRequete"] = 0;
		}

		\$this->NumReq	 = ++\$_SESSION["NumeroRequete"];
		\$this->SqlDem	 = \$Sql;
		\$this->Mode_Sql	 = \$Mode_Sql;
		\$this->Sortie_T	 = &\$param;
		\$this->Position	 = 0;
		\$this->Result	 = array();
		//
		\$this->PrepareRequete();
		//
		\$this->ExecuteRequete();
		// echo '<pre>';print_r(\$_SESSION);
		//NMA = MAINTENANCE 1 :  lancer la sauvgarde de la requete demandÃ©e


		if ( empty( \$this->Sortie_T['CDMESSAGE'] ) )
		{
			\$this->Sortie_T['CDMESSAGE']['Valeur'] = 0;
		}

		if ( \$this->Sortie_T['CDMESSAGE']['Valeur'] == 0 && \$this->TypeSql == 'P' && (!isset( \$param['historyRequestToIcLogA0']['Valeur'] ) || \$param['historyRequestToIcLogA0']['Valeur'] == false) && isset( \$_SESSION['APPLIS']['FF']['CDPROF'] ) && \$_SESSION['APPLIS']['FF']['CDPROF'] == 'A0' )
		{
			saveHistory( str_replace("'", "''", \$this->SqlExec  ) );
		}
	}

	// ----------------------------------------------------------------------------
	// OBJET FONCTION : Postionne le nombre de lignes Ã  retourner
	// --------------   par les requetes ORACLE
	//         - MAX_ROWS : le nombre de lignes maximal a lire (donnÃ© en paramÃ¨tre de l'objet requete) par les requetes ORACLE, a partir de la skip-ieme.
	//                      S'il prend la valeur de -1, cela signifie que toutes les lignes seront lues. 
	//                      Par dÃ©faut ce paramÃ¨tre est null
	//         - SKIP_ROWS : skip est le nombre de lignes initiales a ignorer lors de la lecture
	//                       du resultat.
	//                       Par defaut, ce parametre prend la valeur 0, pour commencer la lecture a 
	//                       la premiere ligne
	//         - MAX_ROWS_OCI : Nombre de lignes Ã  retourner par l'instruction OCI_FETCH_ALL.
	//                          Quand MAX_ROWS est null alors MAX_ROWS_OCI est limitÃ© pour des raisons 
	//                              de performances Ã   30 000 + 1 
	//                          (+1 afin de pouvoir afficher une erreur quand le rÃ©sultat est incomplet)
	//                          Quand MAX_ROWS > 0 alors MAX_ROWS_OCI = MAX_ROWS
	//                          Quand MAX_ROWS < 0 alors MAX_ROWS_OCI = MAX_ROWS
	// Exemples d'appel de la fonction :
	// \$this->RemplissageRetour('001','Le paramÃ¨tre aParam_T n'est pas un tableau.','EP');
	//-----------------------------------------------------------------------------
	function setMaxRowsOCI()
	{

		\$this->MAX_ROWS_OCI	 = \$this->MAX_ROWS;
		if ( \$this->MAX_ROWS_OCI == "" )
			\$this->MAX_ROWS_OCI	 = MAX_ROWS; // Permet de limiter le nombre




			
// de lignes retournÃ©es par ORACLE
	}

	// ----------------------------------------------------------------------------
	// OBJET FONCTION : Permet de mettre Ã  jour le tableau du retour des erreurs ou informations
	// --------------
	// Prototype :
	// RemplissageRetour( String NumMess, String LibMess, String Type)
	//
    // ParamÃ¨tres  en entrÃ©e :
	//              - Obligatoires :
	//                               - NumÃ©ro de l'erreur
	//                               - LibellÃ© de l'erreur
	//                               - Type de l'erreur
	//
    // Exemples d'appel de la fonction :
	// \$this->RemplissageRetour('001','Le paramÃ¨tre aParam_T n'est pas un tableau.','EP');
	//-----------------------------------------------------------------------------
	function RemplissageRetour( \$NumMess, \$LibMess, \$Type, \$ForceDisplayError = "" )
	{
		\$numargs = func_num_args();
		if ( \$numargs < 3 )
		{
			\$this->Retour_T['Num_Mess']	 = 9994;
			\$this->Retour_T['Lib_Mess']	 = "Pas assez d'arguments Retour_T";
			\$this->Retour_T['Typ_Mess']	 = "EP";
		}
		\$this->Retour_T['Num_Mess']	 = \$NumMess;
		\$this->Retour_T['Lib_Mess']	 = \$LibMess;
		\$this->Retour_T['Typ_Mess']	 = \$Type;
		if ( trim( \$ForceDisplayError ) != "" )
		{
			\$GLOBALS["MESSAGE"]["NumMess"]			 = \$Req1->Retour_T["Num_Mess"];
			\$GLOBALS["TRANSACTION"]["ErreurLecture"] = true;
			//  if ( \$this->Retour_T[Typ_Mess] == "EP" )
			\$GLOBALS["MESSAGE"]["Type"]				 = "P";
		}
	}

	// ----------------------------------------------------------------------------
	// OBJET FONCTION : Permet de remplacer dans la requÃªte demandÃ© les variables 
	// --------------  de la forme "::<nom variable>" par leur valeur issue du tableau \$Param_T
	// Prototype :
	// PrepareRequete()
	//
    // ParamÃ¨tres en sortie :
	//              
	//
    // Exemples d'appel de la fonction :
	// \$this->PrepareRequete();
	//-----------------------------------------------------------------------------
	function PrepareRequete()
	{
		//        global \$_SESSION;
		\$temp			 = \$this->SqlDem;
		\$V_Sortie_T		 = \$this->Sortie_T;
		krsort( \$V_Sortie_T, SORT_STRING );
		\$Tmp_session_id	 = session_id();
		if ( trim( \$Tmp_session_id ) == "" )
			\$Tmp_session_id	 = \$_SERVER["REMOTE_ADDR"] . \$_SERVER["REMOTE_PORT"];
		//
		//      Traduction de la requete en Oracle si Mode_Sql = 'AdabasD'
		//            
		if ( \$this->Mode_Sql == 'AdabasD' )
		{
			if ( \$this->TypeSql == 'S' )
				\$temp = TrancodificationRequeteADABASD( \$temp );
		}
		//
		//      Fin de la Transco
		//
        
        // JC 16/8/2011 : supprimer les caractÃ¨res inutiles dans la requete
		\$patterns[0] = "/\s+/";
		\$patterns[1] = "/\t+/";
		\$patterns[2] = "/\n+/";
		\$patterns[3] = "/\r+/";

		\$replacements[0] = " ";
		\$replacements[1] = "";
		\$replacements[2] = "";
		\$replacements[3] = "";


		if ( empty( \$_SESSION["CDUSER"] ) )
		{
			\$_SESSION["CDUSER"] = "";
		}


		\$temp = preg_replace( \$patterns, \$replacements, \$temp );

		\$temp	 = str_replace( "::CDSESSION", \$Tmp_session_id, \$temp );
		\$temp	 = str_replace( "::IDREQUETE", \$this->NumReq, \$temp );
		\$temp	 = str_replace( "::CDUTIL", \$_SESSION["CDUSER"], \$temp );
		foreach ( \$V_Sortie_T as \$key => \$value )
		{
			if ( \$this->TypeSql == 'S' )
				\$V_Sortie_T[\$key]['Valeur'] = TrancodificationValeurADABASD( \$key, \$V_Sortie_T[\$key]['Valeur'] );
			if ( \$V_Sortie_T[\$key]['Recherchee'] )
			{
				\$temp = str_replace( "::\$key", \$V_Sortie_T[\$key]['Valeur'], \$temp );
			}
		}
		\$this->SqlExec = \$temp;
	}

	// ----------------------------------------------------------------------------
	// OBJET FONCTION : ExÃ©cute la requete SQL et crÃ©e un rÃ©sultat
	// --------------
	// Prototype :
	// ExecuteRequete()
	//
    // ParamÃ¨tres en sortie :
	// 
	//
    // Exemples d'appel de la fonction :
	// \$this->ExecuteRequete();
	//-----------------------------------------------------------------------------
	function ExecuteRequete()
	{


		if ( empty( \$GLOBALS[\$this->Base_Appelee]["ID_CON"] ) )
		{
			\$GLOBALS[\$this->Base_Appelee]["ID_CON"] = "";
		}


		if ( !IsConnectId( \$GLOBALS[\$this->Base_Appelee]["ID_CON"] ) )
		{
			ConnectBase( \$GLOBALS[\$this->Base_Appelee]["ID_CON"], \$this->Base_Appelee );
			//  echo "<br> ***".\$GLOBALS[\$this->Base_Appelee]["ID_CON"];
			// A supprimer
			//       if (! IsConnectId(\$GLOBALS[\$this->Base_Appelee]["ID_CON"]))
			//          echo "<br> **** Non Connecte";
			// FIN A supprimer
		}
		if ( \$this->TypeSql == "S" )
		{
			//echo \$this->SqlExec;
            list (\$msec, \$sec) = explode( ' ', microtime() );    
			\$microtime_sql = ( float ) \$msec + ( float ) \$sec;
			\$this->IdRes = ociparse( \$GLOBALS[\$this->Base_Appelee]["ID_CON"], \$this->SqlExec );
			if ( !IsResultId( \$this->IdRes ) )
			{
				\$this->RemplissageRetour( 9990, "La Requete a echouee", "EP" );
				return false;
			}
			\$ok = ociexecute( \$this->IdRes );
			if ( !\$ok )
			{
				 if ( in_array( \$_SERVER['REMOTE_ADDR'], array("192.168.24.1", '192.168.168.1') ) )
                 {
                       echo "" . \$this->SqlExec . "<br />";
                 }
   
                \$this->RemplissageRetour( 9990, "La Requete a echouee", "EP" );
                
				return false;
			}
            list (\$msec, \$sec) = explode( ' ', microtime() );    
            \$microtime_sql_end = ( float ) \$msec + ( float ) \$sec;
                        \$calledFrom = debug_backtrace();
                        if ( empty( \$GLOBALS['tab_sql'] ) )
                        {
                                \$GLOBALS['tab_sql']      = array();
                                \$GLOBALS['time_sql'] = 0;
                        }
                        \$gg = array();
                        \$gg['sql']               = \$this->SqlExec;
                        \$gg['file']              = \$calledFrom["1"]['file'];
                        \$gg['line']              = \$calledFrom["1"]['line'];
                        \$gg['time']              = round( \$microtime_sql_end - \$microtime_sql, 4 );
                        \$gg['num_rows']  = oci_num_rows( \$this->IdRes );
                        \$GLOBALS['tab_sql'][] = \$gg;
                        \$GLOBALS['time_sql'] += \$microtime_sql_end - \$microtime_sql;    
                
			\$res			 = array();
			//  
			\$MAX_ROWS_OCI	 = \$this->MAX_ROWS_OCI;
			if ( \$this->MAX_ROWS == "" )
				\$MAX_ROWS_OCI += 1;

			\$SKIP = \$this->SKIP_ROWS;

			\$this->NbLigne	 = ocifetchstatement( \$this->IdRes, \$res, \$SKIP, \$MAX_ROWS_OCI, OCI_FETCHSTATEMENT_BY_ROW );
			\$cr				 = ocifreestatement( \$this->IdRes );

			if ( \$this->NbLigne > 0 )
			{
				if ( \$MAX_ROWS_OCI > 0 && \$this->NbLigne > \$this->MAX_ROWS_OCI && \$this->MAX_ROWS == "" )
				{
					\$this->RemplissageRetour( 9990, RESULTAT_ORACLE_INCOMPLET, 'EP', "OUI" );
				}
				reset( \$res );
				if ( \$this->MAX_ROWS == "" && \$this->NbLigne > \$this->MAX_ROWS_OCI )
					\$this->NbLigne -= 1;
			}

			\$this->Result = \$res;
		}
		else
		{

			\$Tmp_session_id = session_id();
			if ( trim( \$Tmp_session_id ) == "" )
			{
				\$Tmp_session_id = \$_SERVER["REMOTE_ADDR"] . \$_SERVER["REMOTE_PORT"];
			}
			if ( \$this->Mode_Sql != 'Oracle' )
			{
				// --------------------------------------------------------------------------
				// Remplacer tous les '{CALL ' par begin
				// --------------------------------------------------------------------------
			//	\$Fonction_debut			 = '{[Cc][Aa][Ll][Ll] ';
				\$Fonction_debut			 = '{CALL ';
				\$Fonction_debut_Oracle	 = 'begin ';
			//	\$this->SqlExec			 = eregi_replace( \$Fonction_debut, \$Fonction_debut_Oracle, \$this->SqlExec );
				\$this->SqlExec			 = str_ireplace ( \$Fonction_debut, \$Fonction_debut_Oracle, \$this->SqlExec );
				\$Fonction_debut2		 = '}';
				\$Fonction_debut2_Oracle	 = '; end;';
			//	\$this->SqlExec			 = eregi_replace( \$Fonction_debut2, \$Fonction_debut2_Oracle, \$this->SqlExec );
				\$this->SqlExec			 = str_ireplace( \$Fonction_debut2, \$Fonction_debut2_Oracle, \$this->SqlExec );
				\$pos_nom				 = strpos( \$this->SqlExec, 'DBASIF' );
				\$pos_parenthese			 = strpos( \$this->SqlExec, '(' );
				\$Tmp_call				 = substr( \$this->SqlExec, 0, \$pos_nom )
						. substr( \$this->SqlExec, \$pos_nom + 7, \$pos_parenthese - \$pos_nom - 7 )
						. '.' . substr( \$this->SqlExec, \$pos_nom + 7, \$pos_parenthese - \$pos_nom - 7 )
						. substr( \$this->SqlExec, \$pos_parenthese );
				\$this->SqlExec			 = \$Tmp_call;
			}
			\$ID_Res_DBP = ociparse( \$GLOBALS[\$this->Base_Appelee]["ID_CON"], \$this->SqlExec );
			if ( !IsResultId( \$ID_Res_DBP ) )
			{
				\$this->RemplissageRetour( 9990, "La Requete a echouee", "EP" );
				return false;
			}
			\$ok = ociexecute( \$ID_Res_DBP );
			if ( !\$ok )
			{
				\$this->RemplissageRetour( 9990, "La Requete a echouee", "EP" );
				return false;
			}
			\$req_res	 = "SELECT CDMESSAGE,LISORTIE FROM IC_MER WHERE CDSESSION = '"
					. \$Tmp_session_id
					. "' AND IDREQUETE ="
					. \$this->NumReq;
			\$this->IdRes = ociparse( \$GLOBALS[\$this->Base_Appelee]["ID_CON"], \$req_res );
			if ( !IsResultId( \$this->IdRes ) )
			{
				\$this->RemplissageRetour( 9990, "La Requete a echouee", "EP" );
				return false;
			}
			\$ok = ociexecute( \$this->IdRes );
			if ( !\$ok )
			{
				\$this->RemplissageRetour( 9990, "La Requete a echouee", "EP" );
				return false;
			}
			\$res = array();

			\$this->NbLigne	 = ocifetchstatement( \$this->IdRes, \$res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW );
			\$this->Result	 = \$res;
			\$this->LectureSuivant( \$this->Sortie_T );
			\$cr				 = ocifreestatement( \$this->IdRes );
			//print_r(\$this->Sortie_T);
		}
	}

	// -----------------------------------------------------------------------------
	// Methode  APlusResultat
	// -----------------------------------------------------------------------------
	// OBJET METHODE : Verifie que la requete a au moins 1 ligne de rÃ©sultat de plus
	// --------------
	// Prototype :
	// bool APlusResultat()
	//
    // ParamÃ¨tres  en entrÃ©e :
	//              - Obligatoires :
	//
    //              - Facultatifs  :
	//
    // ParamÃ¨tres en sortie :
	//
    //
    // Exemples d'appel de la mÃ©thode :
	// \$MaRequete->APlusResultat();
	//------------------------------------------------------------------------------
	function APlusResultat()
	{
		/*  if ( ! IsConnectId(\$this->IdCon) )
		  {
		  \$this->RemplissageRetour(9991," Pas de Connexion ","ODBC");
		  return false;
		  }
		  if ( ! IsResultId(\$this->IdRes))
		  {
		  \$this->RemplissageRetour(9992," RÃ©sultat non valide ","ODBC");
		  return false;
		  }
		 */
		if ( \$this->Position < \$this->NbLigne )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// -----------------------------------------------------------------------------
	// MÃ©thode  LectureSuivant
	// -----------------------------------------------------------------------------
	// OBJET METHODE : Lit le rÃ©sultat suivant et deplace le curseur
	// --------------
	// Prototype :
	// bool LectureSuivant(Tableau) /* prend un tableau en parametre
	//
    // ParamÃ¨tres  en entrÃ©e :
	//              - Obligatoires :
	//                               - Tableau dans lequel seront placÃ© les rÃ©sultats
	//              - Facultatifs  :
	//
    // ParamÃ¨tres en sortie :
	//
    //
    // Exemples d'appel de la mÃ©thode :
	// \$MaRequete->LectureSuivant(MonTableau);
	//------------------------------------------------------------------------------
	function LectureSuivant( &\$TableauSortie )
	{
		\$NumArgs = func_num_args();
		if ( \$NumArgs == 0 || !is_array( \$TableauSortie ) )
		{
			\$this->RemplissageRetour( 9993, " Pas de Tableau de sortie ", "EP" );
			return false;
		}
		if ( !\$this->APlusResultat() )
		{
			if ( \$this->Retour_T['Num_Mess'] == 0 )
			{
				\$this->RemplissageRetour( 9108, " Fin de Liste ", "INFO" );
			}
			return false;
		}
		/*   if ( ! IsConnectId(\$this->IdCon) )
		  {
		  \$this->RemplissageRetour(9991," Pas de Connexion ","ODBC");
		  return false;
		  } */
		foreach ( \$this->Result[\$this->Position] as \$col => \$val )
		{
			if ( \$col == "LISORTIE" && \$val != "" )
			{
				parse_str( \$val );
				foreach ( \$elt_name as \$indice => \$valeur )
				{
					\$TableauSortie[\$elt_name[\$indice]]['Valeur']	 = \$elt_val[\$indice];
					\$TableauSortie[\$elt_name[\$indice]]['Affichee']	 = htmlentities( \$elt_val[\$indice], ENT_COMPAT );
				}
			}
			else
			{
				\$TableauSortie[\$col]['Valeur']	 = \$val;
				\$TableauSortie[\$col]['Affichee'] = htmlentities( \$val, ENT_COMPAT );
			}
		}
		++\$this->Position;
		return true;
	}


	// -----------------------------------------------------------------------------
	// MÃ©thode  LibereResultat
	// -----------------------------------------------------------------------------
	// OBJET METHODE : Libere le curseur de rÃ©sultat dans la base
	// --------------
	// Prototype :
	// bool LibereResultat()
	//
    // ParamÃ¨tres  en entrÃ©e :
	//              - Obligatoires :
	//
    //              - Facultatifs  :
	//
    // ParamÃ¨tres en sortie :
	//
    //
    // Exemples d'appel de la mÃ©thode :
	// Resultat->LibereResultat();
	//------------------------------------------------------------------------------
	function LibereResultat()
	{
		if ( !IsResultId( \$this->IdRes ) )
		{
			\$this->RemplissageRetour( 9992, " RÃ©sultat non valide ", "ODBC" );
			return false;
		}
		if ( !IsConnectId( \$GLOBALS[\$this->Base_Appelee]["ID_CON"] ) )
		{
			\$this->RemplissageRetour( 9991, " Pas de Connexion ", "ODBC" );
			return false;
		}
		ocifreestatement( \$this->IdRes );
		\$this->IdRes = false;
		return true;
	}

	//
	// ---------------------------------------------------------------------------
}

// fin class
//
//
// Fonctions GÃ©nÃ©rales
// -----------------------------------------------------------------------------
// Fonction  IsResultId
// -----------------------------------------------------------------------------
// OBJET FONCTION : VÃ©rifie que l'argument est un Identifiant de rÃ©sultat ODBC
// --------------
// Prototype :
// bool IsResultId(\$IdRes)
//
// ParamÃ¨tres  en entrÃ©e :
//              - Obligatoires :
//                      - \$IdResult
//              - Facultatifs  :
//
// ParamÃ¨tres en sortie :
//
//
// Exemples d'appel de la mÃ©thode :
// IsResultId(\$MonResult);
//------------------------------------------------------------------------------

function IsResultId( \$IdResult )
{
	\$numargs = func_num_args();
	if ( \$numargs < 1 )
		return false;
	if ( !Is_Resource( \$IdResult ) || !"oci8 statement" == get_resource_type( \$IdResult ) )
		return false;
	return true;
}

// -----------------------------------------------------------------------------
// Fonction  IsConnectId
// -----------------------------------------------------------------------------
// OBJET FONCTION : VÃ©rifie que l'argument est un Identifiant de connexion ODBC
// --------------
// Prototype :
// bool IsConnectId(\$IdConnexion)
//
// ParamÃ¨tres  en entrÃ©e :
//              - Obligatoires :
//                      - \$IdConnexion
//              - Facultatifs  :
//
// ParamÃ¨tres en sortie :
//
//
// Exemples d'appel de la mÃ©thode :
// IsConnectId(\$MaConnexion);
//------------------------------------------------------------------------------
function IsConnectId( \$IdConnexion )
{
	
   //var_dump(\$IsConnectId);
   
    \$numargs = func_num_args();
	if ( \$numargs < 1 )
		return false;

                
	if ( ! \$IdConnexion  )
	{
		return false;
	}      
                
                
	if ( !Is_Resource( \$IdConnexion ) )
	{
		return false;
	}
	if ( !"OCI8 CONNECTION" == strtoupper( get_resource_type( \$IdConnexion ) ) )
		return false;
	return true;
}

// -----------------------------------------------------------------------------
// Fonction ConnectBase
// -----------------------------------------------------------------------------
// OBJET FONCTION : crÃ©e une connection ou RecupÃ¨re l'ID de la connection active.
// --------------
// Prototype :
// bool ConnectBase(\$IdConnect)
//
// ParamÃ¨tres  en entrÃ©e :
//              - Obligatoires :
//                      - \$IdConnect : rÃ©fÃ©rence sur identifiant de connection.
//              - Facultatifs  :
//
//
// ParamÃ¨tres en sortie :
//
//
// Exemples d'appel de la mÃ©thode :
// ConnectBase(\$MaConnexionID);
//------------------------------------------------------------------------------
function ConnectBase( &\$IdConnect, \$base )
{
	global \$IC_TNSNAME; 
	
	\$numargs = func_num_args();
	if ( \$numargs < 2 )
		return false;  // aucun paramÃ¨tre.
// debut traitement

        // echo "------------\$base-----------";

	if ( \$base == 'DWH' )
	{
		\$MaCon = ocinlogon( "BOSIF", "BOSIF", "BOTISPRD.WORLD" );
	}
	if ( \$base == 'ADEL' )
	{
        //SBCCH10186.ad.sys:1523/ADELPREP
		//putenv("TNS_ADMIN=/usr/local/Zend/Core/network/admin/");
		//print_r(\$GLOBALS['IC_TNSNAME']);
		//ADELDEV <= GLOBALS['IC_TNSNAME']

                
                // =>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> this connect is used for ADEL
		\$MaCon = ocinlogon( "PHP", "JGMWEB", \$IC_TNSNAME ) or die( "pb connection" );
//           \$MaCon = ocinlogon("DBASIF","DBASIF","ADELPROD");
		/*          if (! \$MaCon = ocinlogon("DBASIF","DBASIF","ADELPROD")) {
		  \$error = ocierror();
		  print_r(\$error);
		  echo "There was an error connecting. Error was: ".\$error["message"];
		  phpinfo();
		  die();
		  } */


// echo "**** Macon". \$MaCon;
	}
	if ( !IsConnectId( \$MaCon ) )
	{
		return false;
	}
//     odbc_autocommit(\$MaCon,True);
// echo "*****CONNECT OK****";
	\$IdConnect = \$MaCon;
	return true;
}

// -----------------------------------------------------------------------------
// Fonction CloseBase
// -----------------------------------------------------------------------------
// OBJET FONCTION : ferme la connexion paseeÃ© en parametre.
// --------------
// Prototype :
// bool CloseBase(\$IdConnect)
//
// ParamÃ¨tres  en entrÃ©e :
//              - Obligatoires :
//                      - \$IdConnect : rÃ©fÃ©rence sur identifiant de connection.
//              - Facultatifs  :
//
//
// ParamÃ¨tres en sortie :
//
//
// Exemples d'appel de la mÃ©thode :
// CloseBase(\$MaConnexionID);
//------------------------------------------------------------------------------
function CloseBase( &\$IdConnect )
{
	/*   \$numargs = func_num_args();
	  if (\$numargs < 1)
	  return false;  // aucun paramÃ¨tre.
	  if ( ! IsConnectId(\$IdConnect))
	  return false;  // le parametre n'est pas un identifiant de connection.
	  ocilogoff(\$IdConnect);;
	  \$IdConnect = false; */
	return true;
}

function CloseBaseEndScript()
{
// ALE : WTF ???? \$this outide a class ...
/*
	if ( !IsConnectId( \${\$this->Base_Appelee}["ID_CON"] ) )
		return false;  // le parametre n'est pas un identifiant de connection.
	ociclose( \$GLOBALS[\$this->Base_Appelee]["ID_CON"] );
*/
	
	// sert a qq chose 
	//\$IdConnect								 = false;
	
	return true;
}

// -----------------------------------------------------------------------------
// Fonction Authentification
// -----------------------------------------------------------------------------
// OBJET FONCTION : VÃ©fie qu'un couple User-Password est valide
// --------------
// Prototype :
// int Authentification(\$User, \$Password)
//
// ParamÃ¨es  en entrÃ©:
//              - Obligatoires :
//                      - \$User : Code utilisateur
//						- \$Password : Mot de passe
//              - Facultatifs  :
//
//
// ParamÃ¨es en sortie :
// 			  	 - Code du message d'erreur ou 0 si OK.
//
// Exemples d'appel de la mÃ©ode :
// Retour = Authentification(\$MonUser, \$MonPassword);
//------------------------------------------------------------------------------
function Authentification( \$User, \$Password )
{
	\$numargs						 = func_num_args();
	if ( \$numargs < 2 )
		return 9998;
	\$param['CDUSER']['Valeur']		 = \$User;
	\$param['CDUSER']['Affichee']	 = "";
	\$param['CDUSER']['Recherchee']	 = true;
	\$param['CDPSW']['Valeur']		 = \$Password;
	\$param['CDPSW']['Affichee']		 = "";
	\$param['CDPSW']['Recherchee']	 = true;
// 	\$SqlReq1 = "{CALL DBASIF.ICYAUT1('::CDSESSION',::IDREQUETE,'::CDUSER','::CDPSW')}";
	\$SqlReq1						 = "begin  ICYAUT1.ICYAUT1('::CDSESSION',::IDREQUETE,'::CDUSER','::CDPSW'); end;";
	//
	\$Req1							 = new Requete( \$SqlReq1, "A", \$param, "Oracle", "ADEL" );
	if ( \$Req1->Retour_T['Num_Mess'] == 0 )
		return \$Req1->Sortie_T['CDMESSAGE']['Valeur'];
	else
		return \$Req1->Retour_T['Num_Mess'];
}

// -----------------------------------------------------------------------------
// Fonction NouveauPSW
// -----------------------------------------------------------------------------
// OBJET FONCTION : Change le mot de passe d'un utilisateur
// --------------
// Prototype :
// int NouveauPSW(\$User, \$Password)
//
// ParamÃ¨es  en entrÃ©:
//              - Obligatoires :
//                      - \$User : Code utilisateur
//						- \$Password : Mot de passe
//              - Facultatifs  :
//
//
// ParamÃ¨es en sortie :
// 			  	 - Code du message d'erreur ou 0 si OK.
//
// Exemples d'appel de la mÃ©ode :
// Retour = NouveauPSW(\$MonUser, \$MonPassword);
//------------------------------------------------------------------------------
function NouveauPSW( \$User, \$Password )
{
	\$numargs					 = func_num_args();
	if ( \$numargs < 2 )
		return 9999;
	\$param["CDUSER"]["Valeur"]		 = \$User;
	\$param["CDUSER"]["Affichee"]	 = '';
	\$param["CDUSER"]["Recherchee"]	 = true;
	\$param["CDPSW"]["Valeur"]		 = \$Password;
	\$param["CDPSW"]["Affichee"]		 = '';
	\$param["CDPSW"]["Recherchee"]	 = true;
//	\$SqlReq1 = "{CALL DBASIF.ICYPSW1('::CDSESSION',::IDREQUETE,'::CDUSER','::CDPSW')}";
	\$SqlReq1					 = "begin  ICYPSW1.ICYPSW1('::CDSESSION',::IDREQUETE,'::CDUSER','::CDPSW'); end;";
	//
	\$Req1						 = new Requete( \$SqlReq1, "P", \$param, "Oracle", "ADEL" );
	if ( \$Req1->Retour_T[Num_Mess] == 0 )
		return \$Req1->Sortie_T['CDMESSAGE']['Valeur'];
	else
		return \$Req1->Retour_T['Num_Mess'];
}

// -----------------------------------------------------------------------------
//  Fonction pour transcoder les requetes ADA D en Oracle
//
// -----------------------------------------------------------------------------
function TrancodificationRequeteADABASD( \$Requete )
{
	global \$IC_BASE, \$IC_LANGAGE_SQL;
	\$Requete		 = str_replace( chr( 13 ), ' ', \$Requete );
	\$Requete		 = str_replace( "\n", ' ', \$Requete );
	// \$Requete = eregi_replace ("  {1,}",' ',\$Requete);
	// if ( \$IC_BASE != 'ORACLE') return \$Requete;
	if ( \$IC_LANGAGE_SQL != 'ADABASD' )
		return \$Requete;
	\$C_SEP_F		 = '([ ,(])';
	// --------------------------------------------------------------------------
	// Remplacer tous les value( par NVL(
	// --------------------------------------------------------------------------
	\$Fonction_ADABAS = '[Vv][Aa][Ll][Uu][Ee]';
	\$Fonction_Oracle = 'NVL';
	\$Requete		 = preg_replace( '%' . \$C_SEP_F . '(' . \$Fonction_ADABAS . ')[ ]*[(]%', '\\1' . \$Fonction_Oracle . '(', \$Requete );
	// --------------------------------------------------------------------------
	// Remplacer tous les CHR( par TO_CHAR(
	// --------------------------------------------------------------------------
	\$Fonction_ADABAS = '[Cc][Hh][Rr]';
	\$Fonction_Oracle = 'TO_CHAR';
	\$Requete		 = preg_replace( '%' . \$C_SEP_F . '(' . \$Fonction_ADABAS . ')[ ]*[(]%', '\\1' . \$Fonction_Oracle . '(', \$Requete );
	// --------------------------------------------------------------------------
	// Remplacer tous les NUM( par TO_NUMBER(
	// --------------------------------------------------------------------------
	\$Fonction_ADABAS = '[Nn][Uu][Mm]';
	\$Fonction_Oracle = 'TO_NUMBER';
	\$Requete		 = preg_replace( '%' . \$C_SEP_F . '(' . \$Fonction_ADABAS . ')[ ]*[(]%', '\\1' . \$Fonction_Oracle . '(', \$Requete );
	// --------------------------------------------------------------------------
	// Remplacer tous les & par || 
	// --------------------------------------------------------------------------
	// \$Requete		 = eregi_replace( '[\&]', '||', \$Requete );
	\$Requete		 = str_replace( '&', '||', \$Requete );
	// --------------------------------------------------------------------------
	// Remplacer EXCEPT par MINUS
	// --------------------------------------------------------------------------
	// \$Requete		 = eregi_replace( ' EXCEPT ', ' MINUS ', \$Requete );
	\$Requete		 = str_ireplace( ' EXCEPT ', ' MINUS ', \$Requete );
	// --------------------------------------------------------------------------
	// Remplacer DATE par SYSDATE
	// --------------------------------------------------------------------------
	\$MotCle_ADABAS	 = '[Dd][Aa][Tt][Ee]';
	\$C_Sep_MC_Av	 = '([ ,=><(])';
	\$C_Sep_MC_Ap	 = '([ ,=><)])';
	\$Requete		 = preg_replace( '%' . \$C_Sep_MC_Av . '(' . \$MotCle_ADABAS . ')' . \$C_Sep_MC_Ap . '%', '\\1SYSDATE\\3', \$Requete );
	// --------------------------------------------------------------------------
	// Remplacer ROWNO par ROWNUM
	// --------------------------------------------------------------------------
	\$MotCle_ADABAS	 = '[Rr][Oo][Ww][Nn][Oo]';
	\$C_Sep_MC_Av	 = '([ ,=><(])';
	\$C_Sep_MC_Ap	 = '([ ,=><)])';
	\$Requete		 = preg_replace( '%' . \$C_Sep_MC_Av . '(' . \$MotCle_ADABAS . ')' . \$C_Sep_MC_Ap . '%', '\\1ROWNUM\\3', \$Requete );
	return \$Requete;
}

function TrancodificationValeurADABASD( \$CodeRubrique, \$Valeur )
{
	global \$IC_BASE, \$IC_LANGAGE_SQL, \$TRubriques;
	return \$Valeur;

	if ( \$IC_LANGAGE_SQL != 'ADABASD' )
		return \$Valeur;
	//  echo "<br> *** \$CodeRubrique :". \$TRubriques[\$CodeRubrique]['TYPE'];
	if ( \$TRubriques[\$CodeRubrique]['TYPE'] == 'DATE' )
	{
		//    echo "<br> *** date";
		\$Pattern = "/(19|20)(\d{2})-(\d{2})-(\d{2})/";
		\$Replace = "\\4/\\3/\\1\\2";
		if ( preg_match( \$Pattern, \$Valeur, \$Matches ) )
		{
			\$Valeur = preg_replace( \$Pattern, \$Replace, \$Valeur );
		}
	}
	return \$Valeur;
}

/**
 * 
 * @Author : Nawal MALKHAOUI
 * @Date : 05/04/2012
 * @CT: MAINTENANCE 1          
 * @desc : methode permet d'enregistrer les requetes executÃ© de type Procedure
 *      C'est la historisation des requetes executÃ©es.
 * @param : \$request string  valeur est addslashes(\$this-> SqlExec)
 *          \$prog string valeur est \$this-> SqlDem    
 *       
 * */
function saveHistory( \$request )
{
	\$params											 = array();
	\$params['cdutil']['Recherchee']					 = true;
	\$params['requete']['Recherchee']				 = true;
	\$params['cdprog']['Recherchee']					 = true;
	\$params['historyRequestToIcLogA0']['Recherchee'] = true;
	\$params['cdutil']['Valeur']						 = \$_SESSION["CDUSER"];
	\$params['requete']['Valeur']					 = \$request; //addslashes(\$this-> SqlExec);
	\$params['cdprog']['Valeur']						 = \$request; //\$this-> SqlDem ;
	\$params['historyRequestToIcLogA0']['Valeur']	 = true;

	\$posDebut					 = strpos( \$params['cdprog']['Valeur'], ' ' );
	\$posFin						 = strpos( \$params['cdprog']['Valeur'], '.', \$posDebut + 1 );
	\$params['cdprog']['Valeur']	 = substr( \$params['cdprog']['Valeur'], \$posDebut + 1, \$posFin - \$posDebut - 1 );

	\$SqlReq1 = "begin  DBASIF.ICYLOG('::cdutil', '::cdprog','::requete'); end;";
	\$Req1	 = new Requete( \$SqlReq1, "P", \$params );
}

// -----------------------------------------------------------------------------
// Fonction RecupererKU /* V8.1.6 */
// -----------------------------------------------------------------------------
// OBJET FONCTION : Recuperer le Key User pour l'utilisateur courrent
// --------------
// Prototype :
// string Authentification(\$User)
//
// ParamÃ¨es  en entrÃ©:
//              - Obligatoires :
//                      - \$User : Code utilisateur
//
// ParamÃ¨es en sortie :
// 			  	 - Nom du KU ou empty string si aucun trouvÃ©
//
// Exemples d'appel de la mÃ©ode :
// \$NomKU = RecupererKU(\$MonUser);
//------------------------------------------------------------------------------
function RecupererKU( \$User )
{
	\$NomKU = '';
	if ( !\$TRANSACTION["ErreurLecture"] )
	{
		\$MesParams["CDUSER"]["Valeur"]		 = \$User;
		\$MesParams["CDUSER"]["Recherchee"]	 = true;
		\$SqlReq1							 = " /* OBTERNIR LE KU DE L'ORGANIZATION SI L'UTILISATEUR A UN PROFIL FF */
					SELECT 
                      '1' AS PRIORITE,
                      USRKU.LIUSER AS NOMKU
                    FROM 
					IC_USER USR INNER JOIN IC_GRPF GRPF ON 
						USR.CDUSER = GRPF.CDUSER AND 
						GRPF.CDAPPLI = 'FF'
                    INNER JOIN FF_TPR TPR ON 
						USR.CDORG = TPR.CDORG
                    INNER JOIN IC_USER USRKU ON 
						TPR.CDUTILKU = USRKU.CDUSER
                    WHERE USR.CDUSER = UPPER('::CDUSER')
                    
                    UNION ALL                        
                    
					/* OBTERNIR LE KU DE L'APPLICATION FF SI L'UTILISATEUR A UN PROFIL FF */
                    SELECT 
                      '2' AS PRIORITE,
                      USRKU.LIUSER AS NOMKU  
                    FROM 
					IC_USER USR INNER JOIN IC_GRPF GRPF ON 
						USR.CDUSER = GRPF.CDUSER AND 
						GRPF.CDAPPLI = 'FF'
                    INNER JOIN IC_GRKU GRKU ON 
						USR.CDORG = GRKU.CDORG AND 
						GRPF.CDAPPLI = GRKU.CDAPPLI
                    INNER JOIN IC_USER USRKU ON 
						GRKU.CDUSER = USRKU.CDUSER 
                    WHERE USR.CDUSER = UPPER('::CDUSER')
                    
					UNION ALL
					
					/* OBTERNIR LE KU DE L'APPLICATION FD SI L'UTILISATEUR A UN PROFIL FD */
					SELECT 
                      '3' AS PRIORITE,
                      USRKU.LIUSER AS NOMKU  
                    FROM 
					IC_USER USR INNER JOIN IC_GRPF GRPF ON 
						USR.CDUSER = GRPF.CDUSER AND 
						GRPF.CDAPPLI = 'FD'
                    INNER JOIN IC_GRKU GRKU ON 
						USR.CDORG = GRKU.CDORG AND 
						GRPF.CDAPPLI = GRKU.CDAPPLI
                    INNER JOIN IC_USER USRKU ON 
						GRKU.CDUSER = USRKU.CDUSER 
                    WHERE USR.CDUSER = UPPER('::CDUSER')
					
					/* TRIER POUR POUVOIR EXTRAIRE LA PLUS GRANDE PRIORITE DANS PHP */
                    ORDER BY PRIORITE ASC";
		\$Req1								 = new Requete( \$SqlReq1, "S", \$MesParams, null, null, -1 );
		if ( \$Req1->Retour_T["Num_Mess"] != 0 )
		{
			\$MESSAGE["NumMess"]	 = \$Req1->Retour_T["Num_Mess"];
			\$MESSAGE["Libelle"]	 = ArretTransaction( \$MESSAGE["NumMess"] );
		}
		else
		{
			if ( \$Req1->NbLigne > 0 )
			{
				// le KU avec la plus grande priorite
				\$NomKU = \$Req1->Result[0]['NOMKU'];
			}
		} // fin lecture base de donnÃ©es
	}

	return \$NomKU;
}


EOL;

        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/include/IC_Requete.inc.php";

        file_put_contents($file_name, $data);
    }

    function addTimeZoneToFfFF0PAG1()
    {
        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/FF/FF0PAG1.php";


        $data = file_get_contents($file_name);

        $data2 = preg_replace("/<\?php(\r|\s|\t|\n)/", "<?php\ndate_default_timezone_set(\"Europe/Paris\");\n", $data, -1, $count);

        file_put_contents($file_name, $data2);
    }

    function addTimeZoneToFd0PAG1()
    {
        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/FD/FD0PAG1.php";


        $data = file_get_contents($file_name);

        $data2 = preg_replace("/<\?php(\r|\s|\t|\n)/", "<?php\ndate_default_timezone_set(\"Europe/Paris\");\n", $data, -1);
        $data3 = str_replace('error_reporting( E_ALL ^ E_NOTICE );', 'error_reporting( E_ALL ^ E_NOTICE  ^ E_DEPRECATED);', $data2);


        file_put_contents($file_name, $data3);
    }

    function addIC0PAG1P()
    {
        $this->view        = false;
        $this->layout_name = false;
        $file_name         = $this->_path . "/SY/IC0PAG1P.php";


        $data = file_get_contents($file_name);

        $data2 = str_replace('include_once($PAGE["CDPROG"].".php");', 'require_once(dirname(__DIR__).$PAGE["CDPROG"].".php");', $data);

        file_put_contents($file_name, $data2);
    }

    function removeGetByRef()
    {
        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();

        $i   = 0;
        $out = array();


        $function_list = array();

        foreach ($files as $file_name) {

            $data = file_get_contents($file_name);


			preg_match_all('/\$?[\w]+\s*[\s*\["\w+"\]\s*]*\(([^)]*\&\$[^)]*)\);/U', $data, $out);
			//preg_match_all("#\$?[\w]+\s*[\s*\["\w+\"\]\s*]*\(([^)]*\&\$[^)]*)\);#U", $data, $out, PREG_PATTERN_ORDER);
            //preg_match_all('#([a-zA-Z_]{1}[a-zA-Z0-9_]*)\["\w+"\]?[ ]*\((.+)\)[ ]*;#U', $data, $out, PREG_PATTERN_ORDER);

			echo Color::getColoredString( $file_name, "yellow") . "\n";

            $i = 0;
			
			if (! empty($out[1]))
			{
				
				foreach ($out[1] as $params) {
				   
					$vars = explode(",", $params);
					foreach ($vars as $var) {
						$var = trim($var);

						if (mb_substr($var, 0, 2) === "&$") {

							$string_to_replace = str_replace('&$', '$', $out[1][$i]);
							$data2              = str_replace($out[1][$i], $string_to_replace, $out[0][$i]);
							$data              = str_replace($out[0][$i], $data2, $data);
							
							echo Color::getColoredString( $out[0][$i], "red") . "\n";
							echo Color::getColoredString( $data2, "green") . "\n";
						}
					}

					$i++;
				}
			}
            file_put_contents($file_name, $data);
        }



   
    }

    function replaceCtrlM()
    {

        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();

        foreach ($files as $file_name) {

            $data = file_get_contents($file_name);

            $data = str_replace("\x0D", "\n", $data);
            $data = preg_replace('/\n\t*\n+/', "\n", $data);

            file_put_contents($file_name, $data);
        }
    }

    function removeDeprecated()
    {
        echo "Traitement des fonctions deprecated\n";


        $this->view        = false;
        $this->layout_name = false;

        $file      = "/include/IC_MENU_MULTI_NIVEAUX.inc.php";
        $file_name = $this->_path . $file;
        $data      = file_get_contents($file_name);


        $data = str_replace("split", "explode", $data);

        file_put_contents($file_name, $data);
    }

    function replace_char_wrong()
    {
        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();

        $i   = 0;
        $out = array();


        $function_list = array();

        foreach ($files as $file_name) {

            $data = file_get_contents($file_name);


            $data = str_replace("\xE8", "è", $data);
            $data = str_replace("\xE9", "é", $data);

            $data = str_replace('Ã©', 'é', $data);


            file_put_contents($file_name, $data);
        }
    }

    function get_function()
    {

        /*
         * 
         * function LectureDesDonnees_FF5MIR($Ma_Requete_Sql,
          $Mes_Parametres_Requete,
          $MesColonnes,
          $PAGE_LISTE ){
         */
        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();



        $out = array();

        $data = array();

        foreach ($files as $file_name) {

            //  echo $file_name."\n";
            $input_lines = file_get_contents($file_name);

            //echo $data;

            preg_match_all('/function[\s]+[\w]+[\s]*\(\$[^\)]+\)/Ux', $input_lines, $output_array);


            if (!empty($output_array[0])) {
                echo $file_name . "\n";
                print_r($output_array[0]);
                // $data[] = $output_array[0];
            }

            //file_put_contents($file_name, $data);
        }

        //print_r($data);
    }

    function replace_returncarriage()
    {

        /*
         * 
         * function LectureDesDonnees_FF5MIR($Ma_Requete_Sql,
          $Mes_Parametres_Requete,
          $MesColonnes,
          $PAGE_LISTE ){
         * 
         * 
         * AlimentationLignesSynthese ( $TamponLecture,$MesColonnes,&$PAGE_LISTE)
         */
        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();

        $out  = array();
        $data = array();

        
        //function
        foreach ($files as $file_name) {

            //  echo $file_name."\n";
            $input_lines = file_get_contents($file_name);
            //echo $data;

            preg_match_all('/function[\s]+[\w]+[\s]*\(\$[^\)]+\)/Ux', $input_lines, $output_array);

            if (!empty($output_array[0])) {
                foreach ($output_array[0] as $func) {
                    $func2 = str_replace("\n", "", $func);

                    $func2 = preg_replace("/[\s]+/", " ", $func2);

                    echo $func . " => " . $func2 . "\n";
                    $input_lines = str_replace($func, $func2, $input_lines);
                }
            }
            file_put_contents($file_name, $input_lines);
        }

        //function called
		
        foreach ($files as $file_name) {

            //  echo $file_name."\n";
            $input_lines = file_get_contents($file_name);
            //echo $data;

            preg_match_all('/\$?[\w]+\s*[\s*\["\w+"\]\s*]*\([^)]*\&\$[^)]*\);/s', $input_lines, $output_array);

            if (!empty($output_array[0])) {
                foreach ($output_array[0] as $func) {
                    $func2 = str_replace("\n", "", $func);

                    $func2 = preg_replace("/[\s]+/", " ", $func2);

                    echo Color::getColoredString( $func, "yellow", "black") . "\n" . Color::getColoredString( $func2, "white", "green")  . "\n";

                    $input_lines = str_replace($func, $func2, $input_lines);
                }
            }
            file_put_contents($file_name, $input_lines);
        }


    }

    function add_and_in_func()
    {
        echo "################################################################################################################################\n";
        echo "GET & when calling function\n";
        echo "################################################################################################################################\n";




        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();

        $i   = 0;
        $out = array();


        $function_list = array();
        $func_list     = array();

        foreach ($files as $file_name) {

            $data = file_get_contents($file_name);



			
            //[ ]*[.{1}[\w]+.{1}]*
            //preg_match_all('#([a-zA-Z_]{1}[a-zA-Z0-9_]*)[ ]*\([^\)](.+)\)[ ]*;#Um', $data, $out, PREG_PATTERN_ORDER);
            preg_match_all('/([\w]+)\(([^)]*[&]{1}[$]{1}[^)]*\));/U', $data, $out, PREG_PATTERN_ORDER);

            $i = 0;
            foreach ($out[2] as $params) {
                $vars = explode(",", $params);

                $cpt_arg = 0;
                foreach ($vars as $var) {
                    $var = trim($var);

                    if (mb_substr($var, 0, 2) === "&$") {

                        //print_r($out);
                        //echo $out[1][$i] . " ( " . $var . " )\n";
                        echo Color::getColoredString($file_name, "black", "orange") . ":" . $out[0][$i] . " VALEUR \$cpt_arg => '" . $cpt_arg . "' <=\n";

                        $func_list[$out[1][$i]][count($vars)][$cpt_arg] = 1;
                    }
                    $cpt_arg++;
                }

                $i++;
            }
        }
        print_r($func_list);



        $func_to_escape = array('FG_Afficher_Liste_V16', 'LectureDesDonnees', 'lecturedesdonnees', 'RAPPROCHEMENT_JALON');


        foreach ($func_list as $func => $tab) {

            if (!in_array($func, $func_to_escape)) {
                $function_list[] = $func;
            }
        }

        print_r($function_list);

        //die();


        $out  = array();
        $data = array();



        echo "################################################################################################################################\n";
        echo "GET declare function\n";
        echo "################################################################################################################################\n";


        //$pointer = 0;
        foreach ($files as $file_name) {

            //  echo $file_name."\n";
            $input_lines = file_get_contents($file_name);
            //echo $data;

            $output_array = array();
            preg_match_all('/function[\s]+([\w]+)[\s]*\((.+)\)[\s]*\{/Ux', $input_lines, $output_array);


            echo Color::getColoredString("file : $file_name", "dark_gray", "blue") . "\n";


            if (count($output_array[0]) !== 0) {

                //echo "xhgxfgfgh : \n";
                //print_r($output_array[1]);


                print_r($output_array);

                $pointer = 0;
                foreach ($output_array[1] as $elem) {


                    if (in_array($elem, $function_list)) {

                        echo Color::getColoredString(" => function " . $elem . " : FOUND !", "black", "green") . "\n";
                        echo Color::getColoredString("Etat du pointer : " . $pointer, "yellow", "black") . "\n";


                        $list_arg = explode(",", $output_array[2][$pointer]);


                        echo Color::getColoredString("Liste des arguments : ", "yellow", "black") . "\n";
                        print_r($list_arg);
                        $nb_arg = count($list_arg);
                        if (!empty($func_list[$output_array[1][$pointer]][$nb_arg])) {


                            echo Color::getColoredString("nombre d'arguments : " . $nb_arg, "black", "cyan") . "\n";


                            //echo "fucnc ====>";
                            //print_r($func_list[$output_array[1][$i]]);

                            foreach ($func_list[$output_array[1][$pointer]][$nb_arg] as $offset => $val) {
                                print_r($func_list[$output_array[1][$pointer]][$nb_arg]);

                                if (substr(trim($list_arg[$offset]), 0, 1) == "&") {
                                    continue;
                                }

                                $list_arg[$offset] = "&" . trim($list_arg[$offset]);
                            }
                            echo Color::getColoredString("argument(s) modifié(s) : ", "black", "cyan") . "\n";
                            print_r($list_arg);

                            $new_arg   = implode(",", $list_arg);
                            $newstring = str_replace($output_array[2][$pointer], $new_arg, $output_array[0][$pointer]);



                            echo Color::getColoredString($output_array[0][$pointer] . " ===> " . $newstring, "green", "black") . "\n";


                            $input_lines = str_replace($output_array[0][$pointer], $newstring, $input_lines);

                            echo Color::getColoredString("END OF TREATEMENT", "cyan", "black") . "\n";
                            echo " \n";

                            //preg_match("/\((.+)\)/U", $input_line, $output_array);
                        } else {
                            if (in_array($output_array[1][$pointer], $func_to_escape)) {
                                continue;
                            }


                            echo Color::getColoredString("\nfile : " . $file_name, "white", "red") . "\n";

                            print_r($list_arg);

                            echo Color::getColoredString("function with number of arg not found : " . $output_array[1][$pointer], "white", "red") . "\n";
                        }


                        //exit;
                    }




                    $pointer++;
                }
            }


            file_put_contents($file_name, $input_lines);
        }
    }

    function replace_ereg()
    {

        /*
         * 
         * function LectureDesDonnees_FF5MIR($Ma_Requete_Sql,
          $Mes_Parametres_Requete,
          $MesColonnes,
          $PAGE_LISTE ){
         * 
         * 
         * AlimentationLignesSynthese ( $TamponLecture,$MesColonnes,&$PAGE_LISTE)
         */
        $this->view        = false;
        $this->layout_name = false;

        $files = $this->getFilesNames();

        $out  = array();
        $data = array();


        echo "#####################################################################\n";
        echo "Replace ereg => \" \n";
        echo "#####################################################################\n";




        foreach ($files as $file_name) {

            //  echo $file_name."\n";
            $input_lines = file_get_contents($file_name);
            //echo $data;

            preg_match_all('/ereg[\s]*\("(.+)",.*\).*;/Us', $input_lines, $output_array);

            if (!empty($output_array[0])) {

                $i = 0;
                foreach ($output_array[1] as $args) {

                   
                    $new_arg = '/' . $args . '/';

                    $new_string = str_replace("ereg", "preg_match", $output_array[0][$i]);
                    $new_string = str_replace($args, $new_arg, $new_string);

                    echo $args . " ===> " . $new_arg . "\n";

                    $input_lines = str_replace($output_array[0][$i], $new_string, $input_lines);
                    $i++;
                }
            }
            file_put_contents($file_name, $input_lines);
        }
		

		echo "#####################################################################\n";
        echo "Replace ereg => \$ \n";
        echo "#####################################################################\n";
		foreach ($files as $file_name) {

            //  echo $file_name."\n";
            $input_lines = file_get_contents($file_name);
            //echo $data;

            preg_match_all('/ereg[\s]*\((\$[\w]+),.*\).*;/Us', $input_lines, $output_array);

            if (!empty($output_array[0])) {

                $i = 0;
                foreach ($output_array[1] as $args) {

                   
                    $new_arg = '"/".' . $args . '."/"';

                    $new_string = str_replace("ereg", "preg_match", $output_array[0][$i]);
                    $new_string = str_replace($args, $new_arg, $new_string);

                    echo $args . " ===> " . $new_arg . "\n";

                    $input_lines = str_replace($output_array[0][$i], $new_string, $input_lines);
                    $i++;
                }
            }
            file_put_contents($file_name, $input_lines);
        }
    }

}

// /home/www/adel/FF/FF5TNO3.php