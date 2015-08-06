<?php
namespace PMD\Capital\Model;


class TagAssociationType extends DatabaseElement
{


    protected $values=array(
        'id'=>null,
        'caption'=>null,
        'datecreation'=>null,
        'datemodification'=>null,
    );

    public static function getTableName() {
        return 'pmd_tagassociationtype';
    }
}



