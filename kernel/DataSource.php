<?php


namespace PMD;




class Datasource
{

    protected $source=null;

    public function __construct($source) {
        $this->source=$source;
    }


    public function escape($string) {
        return $this->source->escape_string($string);
    }

    public function query($query) {

        $statement=$this->source->query($query);
        return $statement;
    }


    public function queryAndFetch($query) {

        $statement=$this->query($query);
        $returnValues=array();
        if($statement) {
            while($row=$statement->fetch_assoc()) {
                $returnValues[]=$row;
            }
        }
        return $returnValues;
    }

    public function queryAndFetchOne($query) {

        $returnValues=array();
        $statement=$this->query($query);
        if($statement) {
            $returnValues=$statement->fetch_assoc();
        }


        return $returnValues;
    }



    public function getLastInsertId() {
        return $this->source->insert_id;
    }


    public function autocommit($value) {
        $this->source->autocommit($value);
        return $this;
    }

    public function commit() {
        $this->source->commit();
        return $this;
    }

}




