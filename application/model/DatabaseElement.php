<?php
namespace PMD\Capital\Model;
use vakata\database\Exception;


/**
 * Class DatabaseElement
 * @package PMD\Capital\Model
 *
 *
 * @property \PMD\Datasource $source
 *
 */
abstract class  DatabaseElement
{

    protected $source;

    protected $values=array();


    protected $skipUpdateTimestamp=false;


    abstract public static function getTableName();


    public function __construct($dataSource=null) {
        if($dataSource) {
            $this->setSource($dataSource);
        }
    }



    public static function getPrimaryKeyFieldName() {
        return 'id';
    }

    public function getCreationDateFieldName() {
        return 'datecreation';
    }

    public function getModificationDateFieldName() {
        return 'datemodification';
    }




    public function getGetPrimaryKeyValue() {
        return $this->values[static::getPrimaryKeyFieldName()];
    }






    public function setId($id) {
        $this->values[static::getPrimaryKeyFieldName()]=$id;
    }

    public function getId() {
        return $this->values[static::getPrimaryKeyFieldName()];
    }





    public function loadById($id) {
        $query="SELECT * FROM ".static::getTableName()." WHERE ".static::getPrimaryKeyFieldName()."='".$this->escape($id)."'";
        $this->values=$this->queryAndFetchOne($query);
        return $this;
    }

    public function loadBy($fieldName, $value) {
        $query="SELECT * FROM ".static::getTableName()." WHERE `".$fieldName."`='".$this->escape($value)."'";
        $this->values=$this->queryAndFetchOne($query);
        return $this;
    }




    public function updateModificationTimeStamp($enable) {
        $this->$skipUpdateTimestamp=$enable;
        return $this;
    }


    public function setValues($values) {
        $this->values=$values;
        return $this;
    }


    public function getValues() {
        return $this->values;
    }





    public function delete() {
        if($this->getId()) {
            $query="
            DELETE FROM ".static::getTableName()."
            WHERE ".static::getPrimaryKeyFieldName()."=".$this->escape($this->getId())."
        ";
            $this->query($query);

            return $this;
        }
        else {
            return false;
        }
    }




    public function insert() {


        $fields=array();
        $values=array();


        if(array_key_exists($this->getCreationDateFieldName(), $this->values) && !isset($this->values[$this->getCreationDateFieldName()])) {
            $this->values[$this->getCreationDateFieldName()]=date('Y-m-d H:i:s');
        }


        foreach ($this->values as $fieldName=>$value) {
            if($value!==null && $fieldName!=static::getPrimaryKeyFieldName()) {
                $fields[]=$fieldName;
                $values[]="'".$this->escape($value)."'";
            }

        }


        $query="
            INSERT INTO ".static::getTableName()." (
                ".implode(',', $fields)."
            ) VALUES (
                ".implode(',', $values)."
            );
        ";


        $this->query($query);
        $this->setId($this->getLastInsertId());

        return $this;
    }

    public function update() {

        if(array_key_exists($this->getModificationDateFieldName(), $this->values) && !$this->skipUpdateTimestamp && !isset($this->values[$this->getModificationDateFieldName()])) {
            $this->values[$this->getModificationDateFieldName()]=date('Y-m-d H:i:s');
        }


        $updateStrings=array();

        foreach ($this->values as $fieldName=>$value) {
            if($fieldName!==static::getPrimaryKeyFieldName()) {
                if($value!==null) {
                    $updateStrings[] = $fieldName . "='" . $this->escape($value) . "'";
                }
            }
        }

        $query="
            UPDATE ".static::getTableName()." SET ".implode(',', $updateStrings)."
            WHERE ".static::getPrimaryKeyFieldName()."=".$this->getGetPrimaryKeyValue()
        ;
        $this->query($query);

        return $this;
    }













    public function getSource() {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }
        return $this->source;
    }




    public function setSource($source) {
        if(is_string($source)) {
            $source=\PMD\Capital\Configuration\DataSource::get($source);
        }
        $this->source=$source;
    }

    public function getDefaultSource() {
        return \PMD\Capital\Configuration\DataSource::get('default');
    }



    public function getValue($name, $default=false) {
        if(isset($this->values[$name])) {
            return $this->values[$name];
        }
        else {
            return $default;
        }
    }

    public function setValue($name, $value, $force=false) {
        if(array_key_exists($name, $this->values) || $force) {
            $this->values[$name]=$value;
            return $this;
        }
        else {
            return false;
        }
    }



    public function escape($string) {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }
        return $this->source->escape($string);
    }


    public function query($query) {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }

        $statement=$this->source->query($query);
        return $statement;
    }


    public function queryAndFetch($query) {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }
        return $this->source->queryAndFetch($query);
    }

    public function queryAndFetchOne($query) {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }
        return $this->source->queryAndFetchOne($query);
    }

    public function queryAndFetchValue($query) {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }
        return $this->source->queryAndFetchValue($query);
    }


    public function getLastInsertId() {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }
        return $this->source->getLastInsertId();
    }


    public function autocommit($value=null) {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }

        if($value===null) {
            return $this->source->autocommit();
        }


        $this->source->autocommit($value);
        return $this;
    }

    public function commit() {
        if(!$this->source) {
            $this->source=$this->getDefaultSource();
        }

        $this->source->commit();
        return $this;
    }

}



