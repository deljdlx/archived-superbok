<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('new');

$tagTypeTree=new Type($tagDataSource);

$tagTypeTree->loadBy('qname', 'company');

$attributes=$tagTypeTree->getInheritableAttributes();


print_r($attributes);

/*
$parents=$tagTypeTree->getParents();

foreach ($parents as $parent) {
    print_r($parent->loadAttributes());
}
*/




