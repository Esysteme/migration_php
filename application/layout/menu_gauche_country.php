<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */







echo "<div id=\"menu_country\">";

echo '<div class="title_box">'.__('Country').' </div>';




$family = explode("/",$_GET['url']);

\glial\synapse\FactoryController::addNode("species", "block_country", array($family[2],$family[3]));


/*
<table border="0" cellpadding="2" cellspacing="0" style="background-color:transparent;width:100%">
<tbody><tr valign="middle">
<td colspan="1" style="text-align: center;"><a href="/w/index.php?title=Fichier:Flag_of_the_Philippines.svg&amp;page=1" class="image" title="Drapeau des Philippines"><img alt="Drapeau des Philippines" src="//upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/125px-Flag_of_the_Philippines.svg.png" width="125" height="63" class="thumbborder"></a></td>
<td style="text-align: center;"><a href="/w/index.php?title=Fichier:Coat_of_Arms_of_the_Philippines.svg&amp;page=1" class="image" title="Armoiries des Philippines"><img alt="Armoiries des Philippines" src="//upload.wikimedia.org/wikipedia/commons/thumb/2/2b/Coat_of_Arms_of_the_Philippines.svg/85px-Coat_of_Arms_of_the_Philippines.svg.png" width="85" height="94"></a></td>
</tr>
<tr valign="middle">
<td colspan="1" style="text-align: center;"><small>(<a href="/wiki/Drapeau_des_Philippines" title="Drapeau des Philippines">Drapeau des Philippines</a>)</small></td>
<td style="text-align: center;"><small>(<a href="/wiki/Armoiries_des_Philippines" title="Armoiries des Philippines">Armoiries des Philippines</a>)</small></td>
</tr>
</tbody></table><br />';
echo '<img src="http://upload.wikimedia.org/wikipedia/commons/6/6d/PHL_orthographic.svg" height="240" width="240" />';

*/

echo '</div>';

