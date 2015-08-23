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

        //$oldDriver=new \PMD\Datasource(new \MySQLi('192.168.180.142', 'root', 'root', 'cap'));
        $oldDriver=new \PMD\Datasource(new \MySQLi('192.168.1.64', 'root', '', 'cap'));
        $oldDriver->query("SET NAMES 'utf8'");

        //$newDriver=new \PMD\Datasource(new \MySQLi('192.168.180.142', 'root', 'root', 'cap'));
        $newDriver=new \PMD\Datasource(new \MySQLi('192.168.1.64', 'root', '', 'newcap'));
        $newDriver->query("SET NAMES 'utf8'");


        static::$sources['default']=$oldDriver;
        static::$sources['old']=&static::$sources['default'];
        static::$sources['bourse']=&static::$sources['default'];
        static::$sources['tag']=&static::$sources['default'];




        static::$sources['new']=$newDriver;



    }






    protected function __construct() {

    }



}
