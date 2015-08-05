<?php

namespace PMD\Capital\Model;


Trait Tree
{

    protected $children=null;
    protected $childrenExist=null;

    abstract public static function getTableName();
    abstract public static function getSource();
    abstract public function loadValues($values);

    public static  function getPrimaryKeyFieldName() {
        return 'id';
    }

    public function getParentIdFieldName() {
        return 'parent_id';
    }

    public function getLeftBoundFieldName() {
        return 'leftbound';
    }

    public function getRightBoundFieldName() {
        return 'rightbound';
    }




    public function getRoot() {
        $query="SELECT * FROM ".static::getTableName()." WHERE ".$this->getParentIdFieldName()."=0 OR ".$this->getParentIdFieldName()." IS NULL";
        $data=$this->queryAndFetchOne($query);
        $instance=new static($this->getSource());
        $instance->loadValues($data);
        return $instance;
    }

    public function getChildren($all=false) {

        if($this->children===null) {
            $this->loadChildren($all);
        }


        return $this->children;
    }


    public function childrenExists() {

        if($this->childrenExist!==null) {
            return $this->childrenExist;
        }

        if(!empty($this->children) && $this->children!==null) {
            $this->childrenExist=true;
            return true;
        }

        $query="
            SELECT * FROM ".static::getTableName()."
            WHERE ".$this->escape($this->getParentIdFieldName())."=".$this->escape($this->values[$this->getPrimaryKeyFieldName()])."
            LIMIT 1;
        ";


        $rows=$this->queryAndFetchOne($query);


        if(!empty($rows)) {
            $this->childrenExist=true;
        }
        else {
            $this->childrenExist=false;
        }

        return $this->childrenExist;
    }


    public function loadChildren($all=false) {


        $fieldNames=array_keys($this->values);


        $selectedFields=array();
        foreach ($fieldNames as $name) {
            $selectedFields[]='node.'.$name;
        }

        if($all) {
            $query = "
                SELECT
                  " . implode(',', $selectedFields) . "
                FROM " . static::getTableName() . " root
                    JOIN " . static::getTableName() . " node
                        ON root." . $this->getLeftBoundFieldName() . "<node." . $this->getLeftBoundFieldName() . "
                        AND root." . $this->getRightBoundFieldName() . ">node." . $this->getRightBoundFieldName() . "
                    WHERE root." . $this->getPrimaryKeyFieldName() . "=" . $this->values[$this->getPrimaryKeyFieldName()] . "
            ";
        }
        else {
            $query="
                SELECT
                  " . implode(',', $selectedFields) . "
                FROM " . static::getTableName() . " node
                WHERE ".$this->getParentIdFieldName()."=".$this->values[$this->getPrimaryKeyFieldName()]."
            ";
        }
        $rows=$this->queryAndFetch($query);


        $nodes=array();
        foreach ($rows as $row) {
            $node=new Static($this->getSource());
            $node->loadValues($row);
            $nodes[$row[$this->getPrimaryKeyFieldName()]]=$node;
        }

        foreach ($nodes as $node) {

            if($node->getValue($this->getParentIdFieldName())==$this->getValue($this->getPrimaryKeyFieldName())) {
                $this->addChild($node);
            }
            else if(isset($nodes[$node->getValue($this->getParentIdFieldName())])) {
                $nodes[$node->getValue($this->getParentIdFieldName())]->addChild($node);
            }
        }
    }



    public function addChild($node) {
        $this->children[]=$node;
    }





    public function reset() {
        $query="
		UPDATE ".static::getTableName()."
			SET
				".$this->getLeftBoundFieldName()."=NULL,
				".$this->getRightBoundFieldName()."=NULL
		";
        $this->query($query);
    }

    public function buildTree($idNode=1) {
        $this->autocommit(false);
        $bound=0;

        $this->updateLeftbound($idNode, $bound);

        $this->commit();
        $this->autocommit(true);
    }



    protected function updateLeftbound($idNode=1, &$bound) {
        $query="
			UPDATE ".static::getTableName()."
				SET
					".$this->getLeftBoundFieldName()."=".$bound."
				WHERE ".$this->getPrimaryKeyFieldName()."=".$idNode."
		";



        $this->query($query);


        $bound++;
        $query="
			SELECT
				*
			FROM ".static::getTableName()."
				WHERE ".$this->getParentIdFieldName()."=".$idNode."
				AND ".$this->getLeftBoundFieldName()." IS NULL;
		";

        $nodes=$this->queryAndFetch($query);


        if(!count($nodes)) {
            $this->updateRightbound($idNode, $bound);
        }
        else {
            foreach($nodes as $node) {
                $this->updateLeftBound($node[$this->getPrimaryKeyFieldName()], $bound);
                $bound++;
                $this->updateRightBound($idNode, $bound);
            }
        }
    }


    protected function updaterightbound($idNode, &$bound) {
        $query="
				UPDATE ".$this->getTableName()."
					SET
						".$this->getRightBoundFieldName()."=".$bound."
					WHERE ".$this->getPrimaryKeyFieldName()."=".$idNode."
			";
        $this->query($query);

    }


}
