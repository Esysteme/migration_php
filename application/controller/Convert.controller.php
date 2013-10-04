<?php

use \Glial\synapse\Controller;

class Convert extends Controller
{

    function index()
    {
        
    }

    function updateModel()
    {
        $this->view = false;
        $this->layout_name = false;

        $files = glob(APP_DIR . DS . "model" . DS . "*.php");

        foreach ($files as $filename) {
            echo $filename . EOL;

            $data = file_get_contents($filename);

            $data = str_replace('extends model', 'extends Model', $data);
            $data = str_replace('use glial\synapse\model;', 'use \Glial\Synapse\Model;', $data);
            $data = str_replace('namespace application\model;', 'namespace Application\Model;', $data);

            file_put_contents($filename, $data);
        }
    }

    function upgradeController()
    {
        $this->view = false;
        $this->layout_name = false;

        $files = glob(APP_DIR . DS . "controller" . DS . "*.php");

        foreach ($files as $filename) {


            if (preg_match("#Convert#i", $filename)) {
                continue;
            }

            echo $filename . EOL;

            $data = file_get_contents($filename);

            $data = str_replace('$GLOBALS[\'_SQL\']->', '$this->db[\'mysql_write\']->', $data);
            $data = str_replace('$_SQL->', '$this->db[\'mysql_write\']->', $data);
            $data = str_replace('$_SQL = Singleton::getInstance(SQL_DRIVER);', '', $data);

            file_put_contents($filename, $data);
        }
    }

    function removeWrongTranslation()
    {
        $this->view = false;
        $this->layout_name = false;

        $tables = $this->db['mysql_write']->getListTable();
        $lg_available = explode(",", LANGUAGE_AVAILABLE);


        foreach ($tables['table'] as $table) {
            if (mb_strstr($table, 'translation_')) {

                list(, $suffixe ) = explode("_", $table);

                if (in_array($suffixe, $lg_available)) {

                    $sql = "delete from `" . $table . "` where file_found like '%species/general.view.php';";
                    $this->db['mysql_write']->sql_query($sql);
                    $sql = "delete from `" . $table . "` where file_found like '%controller/species.controller.php';";
                    echo $sql . EOL;
                    $this->db['mysql_write']->sql_query($sql);


                    $sql = "truncate table `" . $table . "`;";
                    $this->db['mysql_write']->sql_query($sql);
                }
            }
        }
    }

    function replaceI18n()
    {

        $this->view = false;
        $this->layout_name = false;

        $files = glob(APP_DIR . DS . "controller" . DS . "*.php");

        foreach ($files as $filename) {


            if (preg_match("#Convert#i", $filename)) {
                continue;
            }

            echo $filename . EOL;

            $data = file_get_contents($filename);
            $data = str_replace('$GLOBALS[\'_LG\']->', 'I18n::', $data);
            file_put_contents($filename, $data);
        }
    }

}