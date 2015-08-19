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




$relatedAssociation=new AssociationType('new');
$relatedAssociation->loadBy('qname', 'related');


$tagType=new ObjectType('new');
$tagType->loadBy('qname', 'tag');




foreach ($rows as $values) {
    $query="
    SELECT
        DISTINCT
            originTag.id as tagId,
            originTag.remote_id as pagehubId,
            originTag.keyword as fromKeyword,
            destinationTag.id as destinationId,
            destinationTag.keyword as destinationKeyword
    FROM eztags originTag
    JOIN eztags_attribute_link link
        ON link.object_id=originTag.remote_id
    JOIN eztags destinationTag
        ON destinationTag.id=link.keyword_id
    WHERE originTag.id=".$values['id']."
    ";

    $relationData=$tagDataSource->queryAndFetch($query);

    if(!empty($relationData)) {

        foreach ($relationData as $values) {

            //récupération du tag d'origine dans la nouvelle bdd
            $fromTag=new Tag('new');
            $fromTag->loadBy('caption', $values['fromKeyword']);

            $relatedTag=new Tag('new');
            $relatedTag->loadBy('caption', $values['destinationKeyword']);
            //récupération pour le related tag


            if($fromTag->getId() && $relatedTag->getId()) {
                $association=new Association('new');
                $association->setType($relatedAssociation);
                $association->setObjectType($tagType);
                $association->setValue('tag_id', $fromTag->getId());
                $association->setValue('object_id', $relatedTag->getId());
                $association->insert();

                echo $values['tagId']."\t".$values['fromKeyword']."\t".$values['destinationId']."\t".$values['destinationKeyword']."\n";
            }


            //echo $value[]"\n"


        }
    }

}








