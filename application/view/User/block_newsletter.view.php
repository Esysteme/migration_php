<?php



echo "<div class=\"title_box\"><a href=\"\">".__("Newsletter")."</a></div>";
echo "<div id=\"newsletter\">";


if (!empty($_MSG)) echo "<b>".$_MSG."</b><br />";


echo __("Stay informed : subscribe to our newsletter.")."<br />";
echo "<form action=\"\" method=\"post\">";
echo "<input class=\"text\" type=\"text\" name=\"newsletter\" /> <input class=\"boutton\" type=\"submit\" value=\"".__("Subscribe")."\" />";
echo "</form>";
echo "</div>";








?>