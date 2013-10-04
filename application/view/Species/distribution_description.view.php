<?php

//debug($data);

echo '<h2>'.$data['scientific_name'].'</h2>';

echo "<h3 class=\"item\"><span>[<a href=\"\">".__('modify')."</a> - <a href=\"\">".__('view history')."</a>]</span>".__('Photos')."</h3>";


include APP_DIR.DS."element".DS."photo250".".php";

echo "<h3 class=\"item\"><span>[<a href=\"\">".__('modify')."</a> - <a href=\"\">".__('view history')."</a>]</span>".__('Distribution')."</h3>";
echo "<h3 class=\"item\"><span>[<a href=\"\">".__('modify')."</a> - <a href=\"\">".__('view history')."</a>]</span>".__('Descriptives notes')."</h3>";