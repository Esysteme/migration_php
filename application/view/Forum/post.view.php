<?phpecho '<div class="post">';echo '<form action="" method="POST" />';echo '<label class="required"><strong>'.__("Category").' <span>(Required)</span></strong><br>';echo select("forum_topic","id_forum_main",$data['categories'],$data['category_default'],"textform category");echo '<br></label><table><tr><td><label class="required"><strong>Langugage <span>(Required)</span></strong><br><img id="flag" class="'.$data['default_lg'].'" src="'.IMG.'main/1x1.png" width="18" border="0" height="12">';echo select("forum_post","id_language",$data['geolocalisation_country'],$data['default_lg'],"textform lg");echo '<br></label></td><td><label class="required"><strong>Subject <span>(Required)</span></strong><br>';echo input("forum_post","title","textform");echo '<br></label></td></tr></table><label class="required"><strong>Message <span>(Required)</span></strong><br>';echo textarea("forum_post","message","textform");echo '<br></label>';/*echo '<ul class="bblinks">	<li><span><a href="help.php#bbcode" onclick="window.open(this.href); return false;">BBCode:</a> on</span></li>	<li><span><a href="help.php#img" onclick="window.open(this.href); return false;">[img] tag:</a> on</span></li>	<li><span><a href="help.php#smilies" onclick="window.open(this.href); return false;">Smilies:</a> on</span></li></ul>';*/echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="'.__("Submit").'" /> '; echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="'.__("Preview").'" /> '; echo '<input class="button btBlueTest overlayW btMedium" type="submit" value="'.__("Go back").'" /> '; echo '<form>';echo '</div>';