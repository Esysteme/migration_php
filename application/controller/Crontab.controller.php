<?php
use \glial\synapse\Controller;

define("_FILE_CRONTAB", "/tmp/crontab_php");

class Crontab extends Controller
{
	
	public $module_group = "Administration";
	
	var $debut = '#Les lignes suivantes sont gerees automatiquement via un script PHP. - Merci de ne pas editer manuellement';
	var $fin = '#Les lignes suivantes ne sont plus gerees automatiquement';

	
	
	function index()
	{
	
	}
	
	
	
	function admin_crontab()
	{
	
		$module['picture'] = "administration/iconAttendance.gif";
		$module['name'] = __("Crontab");
		$module['description'] = __("Manage all yours jobs");
	
		if (from() !== "administration.controller.php")
		{
			
			
			$this->javascript = array("jquery.1.3.2.js");
			
			if ($_SERVER['REQUEST_METHOD'] == "POST")
			{ 
				if (!empty($_POST['crontab']['command']) )
				{	
					
					$regexp= $this->buildRegexp();
					
					$ligne = $_POST['crontab']['minute']." ".$_POST['crontab']['hour']." ".$_POST['crontab']['dayofmonth']." ".$_POST['crontab']['month']." ".$_POST['crontab']['dayofweek']." ".$_POST['crontab']['command'];
					
					if (preg_match("/$regexp/", $ligne))
					{
						set_flash("success","Added","This tasks has beend added in the crontab");
						
						$this->add($_POST['crontab']['minute'],$_POST['crontab']['hour'],$_POST['crontab']['dayofmonth'],$_POST['crontab']['month'],$_POST['crontab']['dayofweek'],$_POST['crontab']['command'],"commentaire =)");
						
						header("location: ".$_SERVER['REQUEST_URI']);
						die();
					}
					else
					{
						set_flash("error","Error","This crontab is not valid : ".$ligne);
						
						
						$ret = array();
						foreach($_POST['crontab'] as $var => $val)
						{
							$ret[] = "crontab:".$var.":".$val;
						}
						
						$param = implode("/", $ret);
						
						
						header("location: ".LINK.__CLASS__."/".__FUNCTION__."/".$param);

						die();
					}
				}
				
				if (!empty($_POST['crontab']['delete']) )
				{	
					set_flash("success","Removed","This task has been removed");
					$this->delete($_POST['crontab']['delete']);
				}
			}
			
			$this->layout_name = "admin";
			
			
			$this->title = __("Crontab");
			$this->ariane = "> <a href=\"".LINK."administration/\">".__("Administration")."</a> > ".$this->title;
			
			$data = $this->view();

			$this->set("data",$data);
		}
		
		return $module;

	}
	
	
	
	
	private function view()
	{
	
		
		$isSection = false;
		
		exec('crontab -l', $oldCrontab);		/* on récupère l'ancienne crontab dans $oldCrontab */
		
		$tab = array();
		
		foreach($oldCrontab as $index => $ligne)	/* copie $oldCrontab dans $newCrontab et ajoute le nouveau script */
		{
			if ($ligne == $this->debut)
			{
				$isSection = true;
				continue;
			}
			
			if ($ligne == $this->fin)
			{
				$isSection = false;
				break;
			}
			
			if ($isSection)
			{
				$elem = explode (" ",$ligne);
				
				if ($elem[0] === "#")
				{
					$id = $elem[1];
					continue;
				}
								
				$tab[$id] = $ligne;
			}
		}
		
		
	
		return ($tab);

	
	}
	
	
	private function add($chpMinute,$chpHeure, $chpJourMois,  $chpMois, $chpJourSemaine, $chpCommande, $chpCommentaire)
	{
		$maxNb = 0;					/* le plus grand numéro de script trouvé */
		$oldCrontab = Array();				/* pour chaque cellule une ligne du crontab actuel */
		$newCrontab = Array();				/* pour chaque cellule une ligne du nouveau crontab */
		$isSection = false;
		
		
		//$oldCrontab = $this->view();
		exec('crontab -l', $oldCrontab);
		
		
		foreach($oldCrontab as $index => $ligne)	/* copie $oldCrontab dans $newCrontab et ajoute le nouveau script */
		{
			if ($isSection == true)			/* on est dans la section gérée automatiquement */
			{
				$motsLigne = explode(' ', $ligne);
				
				if ($motsLigne[0] == '#' && $motsLigne[1] > $maxNb)	/* si on trouve un numéro plus grand */
				{
					$maxNb = $motsLigne[1];
				}
			}
			
			if ($ligne == $this->debut)
			{
				$isSection = true;
			}
			
			if ($ligne == $this->fin)			/* on est arrivé à la fin, on rajoute le nouveau script */
			{
				$id = $maxNb + 1;
				$newCrontab[] = '# '.$id.' : '.$chpCommentaire;

				$newCrontab[] = $chpMinute.' '.$chpHeure.' '.$chpJourMois.' '.$chpMois.' '.$chpJourSemaine.' '.$chpCommande;
			}
			
			$newCrontab[] = $ligne;			/* copie $oldCrontab, ligne après ligne */
		}
		
		
		
		if ($isSection == false) 			/* s'il n'y a pas de section gérée par le script */
		{						/*  on l'ajoute maintenant */
			$id = 1;
			$newCrontab[] = $this->debut;
			$newCrontab[] = '# 1 : '.$chpCommentaire;

			$newCrontab[] = $chpMinute.' '.$chpHeure.' '.$chpJourMois.' '.$chpMois.' '.$chpJourSemaine.' '.$chpCommande;
			$newCrontab[] = $this->fin;
		}
		
		$f = fopen(_FILE_CRONTAB, 'w');			/* on crée le fichier temporaire */
		
		foreach($newCrontab as $line)
		{
			fwrite($f, $line."\n");
		}
		fclose($f);
		
		exec('crontab '._FILE_CRONTAB);				/* on le soumet comme crontab */
		
		return 	$id;
	}
	
	private function delete($id)
	{
		$oldCrontab = Array();				/* pour chaque cellule une ligne du crontab actuel */
		$newCrontab = Array();				/* pour chaque cellule une ligne du nouveau crontab */
		$isSection = false;
		
		$delete_next = false;
		
		
		exec('crontab -l', $oldCrontab);		/* on récupère l'ancienne crontab dans $oldCrontab */
		
		foreach($oldCrontab as $ligne)			/* copie $oldCrontab dans $newCrontab sans le script à effacer */
		{
			if ($delete_next)
			{
				$delete_next = false;
				continue;
			}
			
			
			if ($isSection == true)			/* on est dans la section gérée automatiquement */
			{
				$motsLigne = explode(' ', $ligne);
				
				if ($motsLigne[0] != '#' || $motsLigne[1] != $id)	/* ce n est pas le script à effacer */
				{
					$newCrontab[] = $ligne;			/* copie $oldCrontab, ligne après ligne */
					
				}
				else
				{
					$delete_next = true;
				}
			}
			else
			{
				$newCrontab[] = $ligne;		/* copie $oldCrontab, ligne après ligne */
			}
			
			if ($ligne == $this->debut)
			{
				$isSection = true;
			}
			
			if ($ligne == $this->fin)
			{
				$isSection = false;
			}
		}
		
		$f = fopen(_FILE_CRONTAB, 'w');			/* on crée le fichier temporaire */
		
		foreach($newCrontab as $line)
		{
			fwrite($f, $line."\n");
		}
		fclose($f);
		
		exec('crontab '._FILE_CRONTAB);			/* on le soumet comme crontab */
		
		return 	$id;
	}		



    protected function assertFileIsValidUserCrontab($file)
	{
        $f= @fopen($file, 'r', 1);
        $this->assertTrue($f !== false, 'Crontab file must exist');
        while (($line= fgets($f)) !== false)
		{
            $this->assertLineIsValid($line);
        }
    }

    protected function assertLineIsValid($line)
	{
        $regexp= $this->buildRegexp();
        $this->assertTrue(preg_match("/$regexp/", $line) !== 0);
    }

    private function buildRegexp()
	{
        $numbers= array(
            'min'=>'[0-5]?\d',
            'hour'=>'[01]?\d|2[0-3]',
            'day'=>'0?[1-9]|[12]\d|3[01]',
            'month'=>'[1-9]|1[012]',
            'dow'=>'[0-7]'
        );

        foreach($numbers as $field=>$number)
		{
            $range= "($number)(-($number)(\/\d+)?)?";
            $field_re[$field]= "\*(\/\d+)?|$range(,$range)*";
        }

        $field_re['month'].='|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec';
        $field_re['dow'].='|mon|tue|wed|thu|fri|sat|sun';

        $fields_re= '('.join(')\s+(', $field_re).')';

        $replacements= '@reboot|@yearly|@annually|@monthly|@weekly|@daily|@midnight|@hourly';

        return '^\s*('.
                '$'.
                '|#'.
                '|\w+\s*='.
                "|$fields_re\s+\S".
                "|($replacements)\s+\S".
            ')';
    }






}


?>