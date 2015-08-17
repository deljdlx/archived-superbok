<?php

use \PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;



//on met les bon types de tags pour les tags existants

$tree=new Tag('new');
$tree=$tree->getRoot();



$rows=$tree->queryAndFetch('SELECT * FROM pmd_tagtype');



$types=array();
foreach ($rows as $row) {
    $types[$row['qname']]=$row;
}


$children=$tree->getChildren();


$tree->autocommit(false);

foreach ($children as $child) {


        $entreprises=$child->getChildren(true);
        foreach ($entreprises as $entreprise) {

            if(preg_match('`\s`', $entreprise->getValue('caption'))) {
                $caption=ucwords(mb_strtolower($entreprise->getValue('caption')));
            }
            else if(strlen($entreprise->getValue('caption'))>3) {
                $caption=ucwords(mb_strtolower($entreprise->getValue('caption')));
            }
            else {
                $caption=mb_strtoupper($entreprise->getValue('caption'));
            }


            echo $caption."\n";

            $entreprise->setInheritableAttributesValues(array('attributes'=>array(
                'title'=>$caption
            )));
            $entreprise->update();
        }

}

$tree->commit();




