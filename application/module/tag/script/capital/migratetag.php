<?php


use \PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;

use PMD\Capital\Module\Tag\Model\EzPublish\Tag as EzTag;


$model=DataSource::get('tag');

$newModel=DataSource::get('new');







$treeTag=new Tag($newModel);
$rootTag=$treeTag->getRoot();








$query="
  SELECT
    tag.id,
    tag.parent_id,
    tag.main_tag_id,
    tag.keyword,
    slug.slug


  FROM ".EzTag::getTableName()." tag
  JOIN pmd_tagslug slug
    ON slug.id_tag=tag.id
  "
;

$rows=$model->queryAndFetch($query);


$oldTags=array();

foreach ($rows as $row) {
    $oldTags[$row['id']]=$row;
}



$newTagMapping=array();


$newModel->autocommit(false);

foreach ($oldTags as $row) {

    $tag=new Tag();
    $tag->setSource($newModel);

    $tag->setCaption($row['keyword']);
    $tag->setSlug($row['slug']);


    $tag->insert();
    $newTagMapping[$row['id']]=$tag->getId();

    echo $tag->getId()."\t".$row['keyword']."\n";

}





foreach ($newTagMapping as $oldId=>$newId) {
    $tag=new Tag();
    $tag->setSource($newModel);
    $tag->loadById($newId);


    if($oldTags[$oldId]['main_tag_id']) {
        $oldParentId=$oldTags[$oldId]['main_tag_id'];
    }
    else {
        $oldParentId=$oldTags[$oldId]['parent_id'];
    }



    $parentTagId=false;



    if(isset($newTagMapping[$oldParentId])) {
        $parentTagId=$newTagMapping[$oldParentId];
    }




    if(!$parentTagId) {
        $parentTagId=$rootTag->getId();
    }

    echo $oldParentId."\t".$parentTagId."\t".$oldTags[$oldId]['keyword']."\n";


    if($oldTags[$oldId]['main_tag_id']) {
        $oldMasterTagId=$oldTags[$oldId]['main_tag_id'];
        $tag->setMasterTagId($newTagMapping[$oldMasterTagId]);
    }


    $tag->setParentId($parentTagId);
    $tag->update();

}




$startTime=microtime(true);

$tagTree=new Tag();
$tagTree->setSource($newModel);
$tagTree->reset();
$tagTree->buildTree();

$newModel->commit();

$elapsed=microtime(true)-$startTime;
echo "Tag tree building duration : ".$elapsed."\n";




















