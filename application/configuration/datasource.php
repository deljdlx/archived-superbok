<?php

namespace PMD\Capital\Configuration;


class DataSource
{
    protected static $sources=null;




    static public function get($name) {

        if(static::$sources===null) {
            static::initialize();
        }

        if(isset(static::$sources[$name])) {
            return static::$sources[$name];
        }
        else {
            return false;
        }
    }



    static protected function initialize() {
        static::$sources=array();

        $driver=new \PMD\Datasource(new \MySQLi('192.168.1.64', 'root', '', 'cap'));
        $driver->query("SET NAMES 'utf8'");
        static::$sources['default']=$driver;




        static::$sources['bourse']=&static::$sources['default'];
        static::$sources['tag']=&static::$sources['default'];


        $driver=new \PMD\Datasource(new \MySQLi('192.168.1.64', 'root', '', 'newcap'));
        $driver->query("SET NAMES 'utf8'");

        static::$sources['new']=$driver;


    }






    protected function __construct() {

    }



}
