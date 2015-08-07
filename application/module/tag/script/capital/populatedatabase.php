<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('new');



$tagDataSource->autocommit(false);



$rootObjectType=new ObjectType($tagDataSource);
$rootObjectType->loadBy('qname', 'default');


$rootObjectTypeId=$rootObjectType->getId();
echo "Create ezObject Object type\n";
$query="
    INSERT INTO ".ObjectType::getTableName()." (
      qname,
      caption,
      parent_id,
      datecreation
    ) VALUES (
      'ezobject',
      'Objet EzPublish',
      '".$rootObjectTypeId."',
      NOW()
    )
";
$tagDataSource->query($query);


echo "Construction de l'arbre des types d'objet\n";
$tree=new ObjectType();
$tree->setSource($tagDataSource);
$tree->reset();
$tree->buildTree();





$contentTagType=new Type($tagDataSource);
$contentTagType->loadBy('qname', 'content');




echo "Create Company tag type\n";
$contentTagId=$contentTagType->getId();

$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$contentTagId.",
    'company',
    'Entreprise',
    NOW()
  )
";
$tagDataSource->query($query);


echo "Create Person tag type\n";

$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$contentTagId.",
    'person',
    'Personnalité',
    NOW()
  )
";
$tagDataSource->query($query);



echo "Reconstruction de l'arbre des type des tag\n";

$tree=new Type();
$tree->setSource($tagDataSource);
$tree->reset();
$tree->buildTree();

$tagDataSource->commit();







