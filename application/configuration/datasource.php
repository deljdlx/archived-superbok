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
        static::$sources['default']=new \PMD\Datasource(new \MySQLi('192.168.1.64', 'root', '', 'cap'));

        static::$sources['bourse']=&static::$sources['default'];
        static::$sources['tag']=&static::$sources['default'];


        static::$sources['new']=new \PMD\Datasource(new \MySQLi('192.168.1.64', 'root', '', 'newcap'));


    }






    protected function __construct() {

    }



}
