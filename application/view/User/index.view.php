<?php


//http://www.estrildidae.net/fr/user/confirmation/claude.festor@free.fr/498c2742abf4e0f396188cdfe779ca91b4531782





echo "<div>";



echo "<table class=\"alternance\" width=\"100%\">";

	echo "<tr>";
	echo "<th>".__("Top")."</th>";
	echo "<th>".__("Location")."</th>";
	echo "<th>".__("Name")."</th>";
	echo "<th>".__("Rank")."</th>";
	
	echo "<th>".__("Points")."</th>";
	echo "<th>".__("Last online")."</th>";
	echo "<tr>";

$i=0;
foreach($data as $line)
{
	$i++;
	if ($i %2 ==0)
	{
		echo "<tr class=\"couleur2\">";
	}
	else
	{
		echo "<tr class=\"couleur1\">";
	}
	echo "<td>#$i</td>";
	echo "<td><img class=\"country\" src=\"".IMG."country/type2/".strtolower($line['id_country']).".png\" width=\"18\" height=\"12\" /> ".$line['libelle']."</td>";
	echo "<td><a href=\"".LINK."user/profil/".$line['id']."\">".$line['firstname']." ".$line['name']."</a></td>";
	echo "<td>".$line['rank']."</td>";
	echo "<td>".$line['points']."</td>";
	echo "<td>".$line['date_last_connected']."</td>";

	echo "<tr>";
}

echo "</table>";



echo "</div>";




?>