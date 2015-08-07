<?php
use \PMD\Capital\Configuration\DataSource;
use \PMD\Capital\Configuration\ObjectType;


use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Module\Tag\Model\Association;


ini_set('memory_limit', '-1');

$oldModel=DataSource::get('old');
$newModel=DataSource::get('new');




$ezObjectType=new ObjectType($newModel);
$ezObjectType->loadBy('qname', 'ezobject');



$defaultAssociation=new AssociationType($newModel);
$defaultAssociation->loadBy('qname', 'default');




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
    $tag=new Tag($newModel);
    $tag->loadBy('caption', $row['keyword']);

    $association=new Association($newModel);
    $association->setValue('tag_id', $tag->getId());
    $association->setValue('object_id', $row['object_id']);
    $association->setObjectType($ezObjectType);
    $association->setType($defaultAssociation);
    $association->insert();

    echo $tag->getValue('caption')."\t".$row['object_id']."\n";



}

$newModel->commit();


//print_r($rows);



