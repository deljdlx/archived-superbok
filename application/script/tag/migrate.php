<?php

$model=\PMD\Capital\Configuration\DataSource::get('tag');

$newModel=\PMD\Capital\Configuration\DataSource::get('new');

$newModel->query('TRUNCATE TABLE '.\PMD\Capital\Model\Tag::getTableName());


echo "Create root tag \n";


$rootTag=new \PMD\Capital\Model\Tag();
$rootTag->setSource($newModel);
$rootTag->setCaption('#');
$rootTag->insert();






$query="
  SELECT
    tag.id,
    tag.parent_id,
    tag.main_tag_id,
    tag.keyword,
    slug.slug


  FROM ".\PMD\Capital\Model\EzPublish\Tag::getTableName()." tag
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

    $tag=new \PMD\Capital\Model\Tag();
    $tag->setSource($newModel);

    $tag->setCaption($row['keyword']);
    $tag->setSlug($row['slug']);


    $tag->insert();
    $newTagMapping[$row['id']]=$tag->getId();

    echo $tag->getId()."\t".$row['keyword']."\n";

}





foreach ($newTagMapping as $oldId=>$newId) {
    $tag=new \PMD\Capital\Model\Tag();
    $tag->setSource($newModel);
    $tag->loadById($newId);



    $oldParentId=$oldTags[$oldId]['parent_id'];

    $parentTagId=$newTagMapping[$oldParentId];



    if(!$parentTagId) {
        $parentTagId=$rootTag->getId();
    }

    if($oldTags[$oldId]['main_tag_id']) {
        $oldMasterTagId=$oldTags[$oldId]['main_tag_id'];
        $tag->setMasterTagId($newTagMapping[$oldMasterTagId]);
    }



    echo $oldParentId."\t".$parentTagId."\t".$oldTags[$oldId]['keyword']."\n";


    $tag->setParentId($parentTagId);
    $tag->update();
}




$tagTree=new \PMD\Capital\Model\Tag();
$tagTree->setSource($newModel);
$tagTree->buildTree();

$newModel->commit();




















