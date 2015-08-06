<?php
namespace PMD\Capital\Model;

use PMD\Capital\Model\Tree;

class ObjectType extends DatabaseElement
{

    use Tree;

    static public function getTableName() {
        return 'pmd_objecttype';
    }



}


