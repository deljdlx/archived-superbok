<?php

namespace PMD\Capital\Model;


Trait Tree
{

    protected $children=null;
    protected $allChildren=null;


    protected $childrenExist=null;

    protected $parents=null;
    protected $parent=null;


    abstract public static function getTableName();
    abstract public static function getSource();

    abstract public function queryAndFetch($query);
    abstract public function escape($value);
    abstract public function autocommit($value=null);
    abstract public function commit();
    abstract public function getValue($name, $default=false);
    abstract public function setValues($values);

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
        $instance->setValues($data);
        return $instance;
    }

    public function getChildren($all=false) {

        if($all) {
            if($this->allChildren===null) {
                $this->loadChildren($all);
            }
            return $this->allChildren;
        }
        else {
            if($this->children===null) {
                $this->loadChildren($all);
            }
            return $this->children;
        }
    }


    public function childrenExists() {

        if($this->childrenExist!==null) {
            return $this->childrenExist;
        }

        if(!empty($this->children) && $this->children!==null) {
            $this->childrenExist=true;
            return true;
        }


        if(($this->getValue($this->getRightBoundFieldName())-$this->getValue($this->getLeftBoundFieldName()))==1) {
            $this->childrenExist=false;
        }
        else {
            $this->childrenExist=true;
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
            $node->setValues($row);
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

        if($all) {
            $this->allChildren=array_values($nodes);
        }





    }



    public function addChild($node) {
        $this->children[]=$node;
    }


    public function getParent() {
        if($this->parent===null) {
            $this->parent=array();
            $this->loadParent();
        }
        return $this->parent;
    }

    public function loadParent() {


        if(is_array($this->values)) {
            $fieldNames = array_keys($this->values);
            $selectedFields = array();
            foreach ($fieldNames as $name) {
                $selectedFields[] = 'node.' . $name;
            }

            $query = "
                    SELECT
                      " . implode(',', $selectedFields) . "
                    FROM " . static::getTableName() . " node
                        WHERE node." . $this->getPrimaryKeyFieldName() . "=" . $this->values[$this->getParentIdFieldName()] . "
                ";


            $values = $this->queryAndFetchOne($query);

            $node = new Static($this->getSource());
            $node->setValues($values);
            $this->parent = $node;
        }
        return $this;
    }



    public function deleteChildren() {
        $children=$this->getChildren(true);
        foreach ($children as $child) {
            $child->delete();
        }
        return $children;
    }





    public function getParents() {
        if($this->parents===null) {
            $this->parents=array();
            $this->loadParents();
        }
        return $this->parents;
    }


    public function loadParents() {


        if(is_array($this->values)) {
            $fieldNames = array_keys($this->values);
            $selectedFields = array();
            foreach ($fieldNames as $name) {
                $selectedFields[] = 'node.' . $name;
            }

            $query = "
                    SELECT
                      " . implode(',', $selectedFields) . "
                    FROM " . static::getTableName() . " root
                        JOIN " . static::getTableName() . " node
                            ON root." . $this->getLeftBoundFieldName() . ">node." . $this->getLeftBoundFieldName() . "
                            AND root." . $this->getRightBoundFieldName() . "<node." . $this->getRightBoundFieldName() . "
                        WHERE root." . $this->getPrimaryKeyFieldName() . "=" . $this->values[$this->getPrimaryKeyFieldName()] . "
                        ORDER BY " . $this->getLeftBoundFieldName() . " DESC
                ";

            $rows = $this->queryAndFetch($query);

            foreach ($rows as $values) {
                $node = new Static($this->getSource());
                $node->setValues($values);
                $this->parents[] = $node;
            }
        }
        return $this;
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

    public function buildTree($idNode=null, $reset=false) {

        if($reset) {
            $this->reset();
        }

        if($idNode===null) {
            $query="
                SELECT ".$this->getPrimaryKeyFieldName()." id FROM ".static::getTableName()."
                WHERE ".$this->getParentIdFieldName()." IS NULL
            ";
            $idNode=$this->queryAndFetchValue($query);
        }




        $autocommitState=$this->autocommit();

        $this->autocommit(false);
        $bound=0;

        $this->updateLeftbound($idNode, $bound);

        $this->commit();
        $this->autocommit($autocommitState);
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
