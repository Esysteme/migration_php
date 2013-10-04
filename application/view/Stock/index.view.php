<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */






if (!empty($data['list_stock'])) {

    echo '<table class="table" style="width:100%">';
    echo '<tr>';
    echo '<th></th><th>' . __("Name") . '</th><th>' . __("Male") . '</th><th>' . __("Female") . '</th><th>' . __("Unknow") . '</th><th>' . __("Total") . '</th>';
    echo '</tr>';


    $i = 0;


    $species = "-1";

    $total = array();
    $total['male'] = 0;
    $total['female'] = 0;
    $total['unknow'] = 0;
    $total['total'] = 0;

    foreach ($data['list_stock'] as $line) {
        $i++;


        echo '<tr>';
        echo '<th>' . $i . '</th>
		<td><a href="'.LINK.'/species/nominal/Lonchura_atricapilla/breeder/">' . $line['scientific_name'] . '</a></td>

		<td>' . $line['male'] . '</td>
		<td>' . $line['female'] . '</td>
		<td>' . $line['unknow'] . '</td>
		<td>' . $line['total'] . '</td>';
        echo '</tr>';

        $total['male'] += $line['male'];
        $total['female'] += $line['female'];
        $total['unknow'] += $line['unknow'];
        $total['total'] += $line['total'];
    }



    echo '<tr>';
    echo '<th></th>
		<td class="total">' . __("Total") . '</td>

		<td class="total">' . $total['male'] . '</td>
		<td class="total">' . $total['female'] . '</td>
		<td class="total">' . $total['unknow'] . '</td>
		<td class="total">' . $total['total'] . '</td>';
    echo '</tr>';

    echo '</table>';
}