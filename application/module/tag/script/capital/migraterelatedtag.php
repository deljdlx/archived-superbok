<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('old');


//selection de toutes les relations pagehub/tag de l'ancien système


$query="SELECT * FROM eztags";

$rows=$tagDataSource->queryAndFetch($query);


//print_r($rows);


foreach ($rows as $values) {
    $query="
    SELECT
        DISTINCT
            originTag.id as tagId,
            originTag.remote_id as pagehubId,
            originTag.keyword as fromKeyword,
            link.keyword_id
    FROM eztags originTag
    JOIN eztags_attribute_link link
        ON link.object_id=originTag.remote_id
    WHERE originTag.id=".$values['id']."
    ";

    //print_r($query);
    //exit();

    $relationData=$tagDataSource->queryAndFetch($query);

    if(!empty($relationData)) {
        print_r($relationData);
    }



}








