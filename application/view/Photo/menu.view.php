<?php


if (!empty($_GET['Id']))
{
	$contrainte = " and a.IdSpecies = '".mysql_real_escape_string($_GET['Id'])."'";
}
else
{
	$contrainte = "";
}

$sql = "SELECT count(1) FROM `SpeciesTreeId` a
INNER JOIN SpeciesPictureInWait b ON b.IdSpeciesMain = a.IdSpecies
LEFT JOIN SpeciesAction c on c.CfLine = b.Id
where IdFamily = 438 AND c.CfLine IS NULL $contrainte";

$r = sql::sql_query($sql);  
$d = mysql_fetch_row($r);
$rand = mt_rand(1,$d[0] - 1);  

  
$sql = "SELECT a.IdSpecies, c.`ScientificName`,b.Id as IdPhoto, b.*, c.ScientificName,e.*
FROM `SpeciesTreeId` a
INNER JOIN SpeciesPictureInWait b ON b.IdSpeciesMain = a.IdSpecies
INNER JOIN SpeciesMain c ON c.Id = a.IdSpecies
INNER JOIN SpeciesTreeName e ON e.Id = a.IdSpecies
LEFT JOIN SpeciesAction f on f.CfLine = b.Id
WHERE IdFamily =438 AND f.CfLine IS NULL $contrainte 
LIMIT $rand, 1";

$res = sql::sql_query($sql);

//echo $sql;
if (mysql_num_rows($res) > 0)
{
	$ob = mysql_fetch_object($res);
}




echo "<div id=\"title_box\">".__("Menu")."</div>";


echo "<ul>";
echo "<li><a href=\"\"><b>Valider une photo (espèce aléatoire)</b></a></li>";
echo "<li><a href=\"\">Valider une photo (espèce aléatoire)</a></li>";
echo "<li><a href=\"\">Valider un hybride</a></li>";
echo "<li><a href=\"\">Confirmer une validation</a></li>";
echo "</ul>";


if (mysql_num_rows($res) > 0)
{

	echo "<div id=\"title_box\">Espèce devant correspondre à la photo</div>";

	echo "<ul>";
	echo "<li>Name : <b>".species::GetSpeciesNameById($ob->IdSpecies,$Language->Get())."</b></li>";
	echo "<li>Nom scientifique : <b>$ob->ScientificName</b></li>";
	echo "<li>Sub species : ";
	
	
	
	
	$sql = "select * from SpeciesSub where IdSpeciesMain = '".$ob->IdSpecies."'";
	$res2 = sql::sql_query($sql);
	

	echo "<input type=\"hidden\" name=\"idspecies\" value=\"{$ob->IdSpecies}\" />";
	
	if (mysql_num_rows($res2) > 1)
	{
		echo "<select name=\"ssp\">";
		echo "<option value=\"$ob2->Id\">Unknowed</option>";
		while ($ob2 = mysql_fetch_object($res2))
		{
			$tab = explode(" ",$ob2->ScientificName);
			
			echo "<option value=\"$ob2->Id\">".$tab[2]."</option>";

		}
		echo "</select>";
	}
	else if (mysql_num_rows($res2) == 1)
	{
		$ob2 = mysql_fetch_object($res2);
		$tab = explode(" ",$ob2->ScientificName);
		echo $tab[2];
		echo "<input type=\"hidden\" name=\"ssp\" value=\"{$ob2->Id}\" />";
	}
	else
	{
		die("Pas de sous espèces trouvée !!!! => utilisé le script de duplication");
	}
	
	
	echo "</li>";

	echo "</ul>";


	echo "<div id=\"title_box\">Valider / Refuser ?</div>";


	$sql2 = "select * from SpeciesPictureInfo where Type =1 order by CfOrder";
	$res2 = sql::sql_query($sql2);


	echo "<h3 class=\"item2\">Je valide</h3>";

	while ($ob2 = mysql_fetch_object($res2))
	{
		echo "<input class=\"boutonvalide\" name=\"Action$ob2->Id\" type=\"submit\" value=\"$ob2->Libelle\" />";

	}


	echo "<h3 class=\"item2\">".__("I don't know")."</h3>";

	$sql2 = "select * from SpeciesPictureInfo where Type=2 order by CfOrder";
	$res2 = sql::sql_query($sql2);

	while ($ob2 = mysql_fetch_object($res2))
	{
		echo "<input class=\"boutonnoidea\" name=\"Action$ob2->Id\" type=\"submit\" value=\"$ob2->Libelle\" />";
	}


	echo "<h3 class=\"item2\">".__("I refuse")."</h3>";



	$sql2 = "select * from SpeciesPictureInfo where Type =3 order by CfOrder";
	$res2 = sql::sql_query($sql2);

	while ($ob2 = mysql_fetch_object($res2))
	{
		echo "<input class=\"boutoninvalide\" name=\"Action$ob2->Id\" type=\"submit\" value=\"$ob2->Libelle\" />";
	}

	echo "<br /><br />";

	echo "<div id=\"title_box\">Je réaffecte la photo</div>";


	echo "<table width=\"100%\">";

	echo "<tr><td>Kingdom</td><td>{$ob->Kingdom}</td></tr>";
	echo "<tr><td>Phylum</td><td>{$ob->Phylum}</td></tr>";
	echo "<tr><td>Class</td><td>{$ob->Class}</td></tr>";
	echo "<tr><td>Order</td><td>{$ob->Order2}</td></tr>";
	echo "<tr><td>Family</td><td>{$ob->Family}</td></tr>";
	echo "<tr><td>Genus</td><td>{$ob->Genus}</td></tr>";
	echo "<tr><td>Species</td><td>{$ob->Species}</td></tr>";


	echo "</table>";

	echo "<div id=\"title_box\">Information sur la photo</div>";

	echo "<table width=\"100%\">";

	echo "<tr><td>Posted by</td><td>BOT</td></tr>";
	echo "<tr><td>Date</td><td>{$ob->DateCreated}</td></tr>";

	$link = explode("/",$ob->UrlFound);
	$link2 = explode("/",$ob->UrlContext);

	echo "<tr><td>Author</td><td><a href=\"\">{$ob->Author}</a></td></tr>";
	echo "<tr><td>URL Found</td><td><a href=\"{$ob->UrlFound}\" target=\"_BLANK\">http://".$link[2]."/</a></td></tr>";
	echo "<tr><td>URL Context</td><td><a href=\"{$ob->UrlContext}\" target=\"_BLANK\">http://".$link2[2]."/</a></td></tr>";
	echo "<tr><td>".__("Original size")."</td><td>{$ob->Width}*{$ob->Height}</td></tr>";

	echo "</table>";



	echo "<div id=\"title_box\">Historique des modifications effectuées</div>";
	/*
	echo "<ul>";
	echo "<li>Animalia</li>";
	echo "<li>Aves</li>";
	echo "<li>Passeriforme</li>";
	echo "<li>Estrildides</li>";
	echo "<li>Lonchura</li>";
	echo "<li>Atricapilla</li>";
	echo "<li>Sinensis</li>";
	echo "</ul>";
	*/
}



?>