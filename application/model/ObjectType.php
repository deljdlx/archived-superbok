<?php
namespace PMD\Capital\Model;

use PMD\Capital\Model\Tree;

class ObjectType extends DatabaseElement
{

    use Tree;


    protected $values=array(
        'id'=>null,
        'parent_id'=>null,
        'qname'=>null,
        'caption'=>null,
        'leftbound'=>null,
        'rightbound'=>null,
        'datecreation'=>null,
        'datemodification'=>null,
    );

    static public function getTableName() {
        return 'pmd_objecttype';
    }



}


