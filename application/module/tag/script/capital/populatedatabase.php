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
    datecreation,
    data
  ) VALUES (
    ".$contentTagId.",
    'company',
    'Entreprise',
    NOW(),
    '".'{
    "attributes": {
        "isin": {
            "caption" : "Code ISIN",
            "mandatory": false,
            "type": "text",
            "default": null,
            "enable": true
        },
        "listed": {
            "caption" : "Entreprise cotée",
            "mandatory": false,
            "type": "boolean",
            "default": false,
            "enable": true
        },
        "assetId": {
            "caption" : "Asset ID",
            "mandatory": false,
            "type": "integer",
            "default": null,
            "enable": true
        },
        "sixId": {
            "caption" : "Six ID",
            "mandatory": false,
            "type": "text",
            "default": null,
            "enable": true
        },
        "cofisemId": {
            "caption" : "Cofisem ID",
            "mandatory": false,
            "type": "integer",
            "default": null,
            "enable": true
        }
    },
    "rules": []
}'.

    "'
  )
";
$tagDataSource->query($query);





echo "Create ISIN tag type\n";


$entrepriseTagType=new Type($tagDataSource);
$entrepriseTagType->loadBy('qname', 'company');

$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$contentTagId.",
    'isin',
    'Code ISIN',
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







