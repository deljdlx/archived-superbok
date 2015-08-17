<?php
namespace PMD\Capital\Module\Tag\Model;
use PMD\Capital\Model\DatabaseElement;
use PMD\Capital\Model\HasInheritableAttribute;
use PMD\Capital\Model\Tree;
use PMD\Capital\Model\InheritableAttribute;

class Type extends DatabaseElement
{

    use HasInheritableAttribute;

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
        return 'pmd_tagtype';
    }



}


