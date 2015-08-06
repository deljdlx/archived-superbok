<?php

ini_set('memory_limit', '-1');

$oldModel=\PMD\Capital\Configuration\DataSource::get('old');
$newModel=\PMD\Capital\Configuration\DataSource::get('new');




$ezObjectType=new \PMD\Capital\Model\ObjectType($newModel);
$ezObjectType->loadBy('caption', 'ezobject');



$defaultAssociation=new \PMD\Capital\Model\TagAssociationType($newModel);
$defaultAssociation->loadBy('caption', 'ezobject');

$query="
    SELECT
      *
    FROM eztags_attribute_link association
    JOIN eztags tag
      ON tag.id=association.keyword_id

";


$rows=$oldModel->queryAndFetch($query);

$newModel->autocommit(false);
foreach ($rows as $row) {
    $tag=new \PMD\Capital\Model\Tag($newModel);
    $tag->loadBy('caption', $row['keyword']);

    $association=new \PMD\Capital\Model\TagAssociation($newModel);
    $association->setValue('tag_id', $tag->getId());
    $association->setValue('object_id', $row['object_id']);
    $association->setObjectType($ezObjectType);
    $association->setType($defaultAssociation);
    $association->insert();

    echo $tag->getValue('caption')."\t".$row['object_id']."\n";



}

$newModel->commit();


//print_r($rows);



