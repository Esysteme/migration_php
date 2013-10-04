<?php

echo "<h3 class=\"item\">" . __("Main") . "</h3>";


echo "<form action=\"\" method=\"post\">";
echo "<table class=\"form\" width=\"100%\">";

echo "<tr>";
echo "<td class=\"first\">" . __("Link of context") . " :</td>";
echo "<td>" . input("user_main", "url_context", "textform") . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=\"first\">" . __("Link of photo") . " :</td>";
echo "<td>" . input("user_main", "url_found", "textform") . "</td>";
echo "</tr>";


echo "<tr>";
echo "<td class=\"first\">" . __("Author") . " :</td>";
echo "<td>" . input("user_main", "url", "textform") . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=\"first\">" . __("License") . " :</td>";
echo "<td>" . input("user_main", "url", "textform") . "</td>";
echo "</tr>";


echo "<tr>";
echo "<td class=\"first\">" . __("Species") . " :</td>";
echo "<td>" . autocomplete("species_picture_in_wait", "id_species_main", "textform") . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan=\"2\">";
echo "<h3 class=\"item\">" . __("Optional") . "</h3>";
echo "</td>";
echo "</tr>";






echo "<tr>";
echo "<td class=\"first\">" . __("Legend") . " :</td>";
echo "<td><textarea class=\"textform\"></textarea></td>";
echo "</tr>";


echo "<tr>";
echo "<td class=\"first\">" . __("Latitude") . " :</td>";
echo "<td>" . input("user_main", "url", "textform") . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=\"first\">" . __("Longitude") . " :</td>";
echo "<td>" . input("user_main", "url", "textform") . "</td>";
echo "</tr>";




echo "<tr>";
echo "<td colspan=\"2\" class=\"td_bouton\"><br/><input class=\"button btBlueTest overlayW btMedium\" type=\"submit\" value=\"" . __("Validate") . "\" /> <input class=\"button btBlueTest overlayW btMedium\" type=\"reset\" value=\"" . __("Delete") . "\" /></td>";
echo "</tr>";
echo "</table>";


echo "</form>";

