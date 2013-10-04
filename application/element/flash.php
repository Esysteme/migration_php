<?php



$doublon = array();

foreach($data as $line)
{
    if (! in_array($line['msg'],$doublon))
    {
        $doublon[] = $line['msg'];
        echo "

        <div class=\"alert " . $line['type_error'] . "\">
            <div class=\"alert-inner\">
                <div class=\"alert-message\">
                    <div class=\"title\"><strong>" . $line['title'] . "</strong></div>
                    <div class=\"msg\">" . $line['msg'] . "</div>
                </div>
            </div>
            <a class=\"alert-close\" href=\"#\" onclick=\"$(this).parent().fadeOut(250, function() { $(this).css({opacity:0}).animate({height: 0}, 100, function() { $(this).remove(); }); }); return false;\">";
        echo __("Close");
        echo "</a>
        </div>";
    }
}
?>