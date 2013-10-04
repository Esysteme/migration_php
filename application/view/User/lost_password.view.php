<?php





//echo "<h3 class=\"item\">Signalétique</h3>";


echo "<form action=\"\" method=\"post\">";
echo "<table class=\"form\" width=\"100%\">";

echo "<tr>";
echo "<td class=\"first\">".__("Email")." :</td>";
echo "<td>".input("user_main","email")."</td>";
echo "</tr>";




echo "<tr>";
echo "<td colspan=\"2\" class=\"td_bouton\"><br/><input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"Valider\" /> <input class=\"button btBlueTest overlayW btMedium\" type=\"reset\" value=\"Effacer\" /></td>";
echo "</tr>";
echo "</table>";


echo "</form>";








?>