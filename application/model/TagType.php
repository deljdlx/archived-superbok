<?php
namespace PMD\Capital\Model;

use PMD\Capital\Model\Tree;

class TagType extends DatabaseElement
{

    use Tree;

    static public function getTableName() {
        return 'pmd_tagtype';
    }



}


