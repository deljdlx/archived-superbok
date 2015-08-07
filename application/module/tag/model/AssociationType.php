<?php

namespace PMD\Capital\Module\Tag\Model;
use PMD\Capital\Model\DatabaseElement;



class AssociationType extends DatabaseElement
{


    protected $values=array(
        'id'=>null,
        'qname'=>null,
        'caption'=>null,
        'datecreation'=>null,
        'datemodification'=>null,
    );

    public static function getTableName() {
        return 'pmd_tagassociationtype';
    }
}



