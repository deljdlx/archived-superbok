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
    $child->setValue('type_id', $types['category']['id']);
    $child->update();


    echo $child->getValue('caption')."\n";

    if($child->getValue('caption')=='Entreprise') {
        $entreprises=$child->getChildren(true);

        foreach ($entreprises as $entreprise) {

            echo "\t".$entreprise->getValue('caption')."\n";

            if(preg_match('`\w{2}\w{10}`', $entreprise->getValue('caption')) && $entreprise->getValue('mastertag_id')) {
                $entreprise->setValue('type_id', $types['isin']['id']);
            }
            else {
                $entreprise->setValue('type_id', $types['company']['id']);
            }
            $entreprise->update();


        }
    }
    elseif($child->getValue('caption')=='Personnalité') {
        $persons=$child->getChildren(true);
        foreach ($persons as $person) {
            $person->setValue('type_id', $types['person']['id']);
            $person->update();
        }
    }
    elseif($child->getValue('caption')=='Divers') {
        $tags=$child->getChildren(true);
        foreach ($tags as $tag) {
            $tag->setValue('type_id', $types['content']['id']);
            $tag->update();
        }
    }
}

$tree->commit();




