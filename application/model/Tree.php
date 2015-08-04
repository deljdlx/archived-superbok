<?php

namespace PMD\Capital\Model;


Trait Tree
{

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
