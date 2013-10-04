<?php

use \Glial\Synapse\Controller;
use \Glial\Synapse\Singleton;

class TestAction extends Controller
{

    function doEcho($data)
    {
        return $data;
    }

    function getTree($id)
    {

        $out = array();

        $arbo = explode(".", $id);
        $NbArbo = count($arbo) - 1;


        

        switch ($NbArbo) {
            case 1:

                $sql = "select * from species_kingdom WHERE id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);


                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                    $ob->scientific_name = ucwords(strtolower($ob->scientific_name));

                    array_push($out, array(
                        'id' => 'n.1.' . $ob->id,
                        'text' => $ob->scientific_name,
                        'leaf' => false
                    ));
                }
                break;

            case 2:

                $sql = "select * from species_phylum where id_species_kingdom = '" . $arbo[2] . "' AND id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);
                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                    array_push($out, array(
                        'id' => $id . "." . $ob->id,
                        'text' => $ob->scientific_name,
                        'leaf' => false
                    ));
                }


                break;
            case 3:

                $sql = "select * from species_class where id_species_phylum = '" . $arbo[3] . "' and id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);

                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                    array_push($out, array(
                        'id' => $id . "." . $ob->id,
                        'text' => $ob->scientific_name,
                        'leaf' => false
                    ));
                }
                break;
            case 4:
                $sql = "select * from species_order where id_species_class = '" . $arbo[4] . "' and id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);

                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                    array_push($out, array(
                        'id' => $id . "." . $ob->id,
                        'text' => $ob->scientific_name,
                        'leaf' => false
                    ));
                }

                break;
            case 5:

                $sql = "select * from species_family where id_species_order = '" . $arbo[5] . "' and id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);

                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                    array_push($out, array(
                        'id' => $id . "." . $ob->id,
                        'text' => $ob->scientific_name,
                        'leaf' => false
                    ));
                }
                break;


            case 6:
                $sql = "select * from species_genus where id_species_family = '" . $arbo[6] . "' and id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);

                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                    array_push($out, array(
                        'id' => $id . "." . $ob->id,
                        'text' => $ob->scientific_name,
                        'leaf' => false
                    ));
                }
                break;

            case 7:

                $sql = "select * from species_main where id_species_genus = '" . $arbo[7] . "' and id_history_etat=1 order by scientific_name";
                $res = $this->db['mysql_write']->sql_query($sql);

                while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {

                    array_push($out, array(
                        'id' => $id . "." . $ob->id,
                        'text' => "" . $ob->scientific_name . "",
                        'leaf' => true
                    ));
                }
                break;
        }

        return $out;
    }

}
