<?php





echo "<div>";



echo "<table class=\"alternance\" width=\"100%\">";

	echo "<tr>";
	echo "<th>".__("Top")."</th>";
	
	echo "<th>".__("Name")."</th>";
	echo "<th>".__("Valided")."</th>";
	
	echo "<th>".__("Pending")."</th>";
	echo "<th>".__("Refused")."</th>";
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
	echo "<td><a href=\"".LINK."author/image/".$line['id']."\">".$line['surname']."</a></td>";
	echo "<td>".$line['valid']."</td>";
	echo "<td>".$line['in_wait']."</td>";
	echo "<td>".$line['refused']."</td>";

	echo "<tr>";
}

echo "</table>";



echo "</div>";
