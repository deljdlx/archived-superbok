<?php


//on met les bon types de tags pour les tags existants

$tree=new \PMD\Capital\Model\Tag('new');
$tree=$tree->getRoot();



$rows=$tree->queryAndFetch('SELECT * FROM pmd_tagtype');



$types=array();
foreach ($rows as $row) {
    $types[$row['caption']]=$row;
}


$children=$tree->getChildren();


$tree->autocommit(false);

foreach ($children as $child) {
    $child->setValue('type_id', $types['category']['id']);
    $child->update();

    if($child->getValue('caption')=='Entreprise') {
        $entreprises=$child->getChildren(true);
        foreach ($entreprises as $entreprise) {
            $entreprise->setValue('type_id', $types['company']['id']);
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




