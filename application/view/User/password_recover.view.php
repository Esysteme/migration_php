<?php


echo "<h3 class=\"item\">".__("Set your new password")."</h3>";


echo "<form action=\"\" method=\"post\">";
echo "<table class=\"form\" width=\"100%\">";


/*
echo "<tr>";
echo "<td>Identifiant </td>";
echo "<td>: <input class=\"text\" type=\"text\" name=\"identifiant\" value=\"".$_GET['identifiant']."\" /></td>";
echo "</tr>";
*/
echo "<tr>";
echo "<td class=\"first\">".__("Password")." :</td>";
echo "<td>".password("user_main","password","textform")."</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=\"first\">(".__("repeat").") :</td>";
echo "<td>".password("user_main","password2","textform")."</td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan=\"2\" class=\"td_bouton\"><br/><input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"".__("Update")."\" /> <input class=\"button btBlueTest overlayW btMedium\" type=\"reset\" value=\"".__("Delete")."\" /></td>";
echo "</tr>";
echo "</table>";


echo "</form>";





