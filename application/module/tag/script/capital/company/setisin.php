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


    if($child->getValue('caption')=='Entreprise') {
        $entreprises = $child->getChildren(true);
        foreach ($entreprises as $entreprise) {

            $caption=$entreprise->getValue('caption');
            if(preg_match('`\w{2}\w{10}`', $caption)  && $entreprise->getValue('mastertag_id')) {

                $parentTag=$entreprise->getParent();
                echo $caption."\t".$parentTag->getValue('caption') . "\n";


                if($parentTag->getId()) {

                    $parentTag->setInheritableAttributesValues(array('attributes' => array(
                        'isin' => $caption,
                        'listed'=>true
                    )));
                    $parentTag->update();
                }
            }


            /*

            */
        }
    }

}

$tree->commit();




