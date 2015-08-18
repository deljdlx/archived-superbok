<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('old');


$query="
    SELECT
      tag.id as tagId,
      tag.keyword as caption,
      tag.remote_id as pagehubId
    FROM eztags tag
    JOIN ezcontentobject object
      ON tag.remote_id=object.id

";
$rows=$tagDataSource->queryAndFetch($query);



//récupération du type d'association pagehub
$associationType=new AssociationType('new');
$associationType->loadBy('qname', 'pagehub');

//récupération du type d'objet ezpublich
$objectType=new ObjectType('new');
$objectType->loadBy('qname', 'ezobject');



foreach ($rows as $values) {

    $tag=new Tag('new');
    $tag->loadBy('caption', $values['caption']);

    $tagValues=$tag->getValues();

    if(!empty($tagValues)) {
        $association=new Association('new');
        $association->setValue('tag_id', $tag->getId());
        $association->setValue('object_id', $values['pagehubId']);
        $association->setObjectType($objectType);
        $association->setType($associationType);
        $association->insert();
        echo $values['caption']."\t".$values['pagehubId']."\n";
    }

}







