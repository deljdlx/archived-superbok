<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('new');



echo "Create default association type\n";
$query="
        INSERT INTO ".AssociationType::getTableName()." (
          qname,
          caption,
          datecreation
        ) VALUES (
          'default',
          'Association par défaut',
          NOW()
        );
";
$tagDataSource->query($query);




echo "Create related object association type\n";
$query="
        INSERT INTO ".AssociationType::getTableName()." (
          qname,
          caption,
          datecreation
        ) VALUES (
          'related',
          'Relation de contenu',
          NOW()
        );
";
$tagDataSource->query($query);






echo "Create Default Object type\n";
$query="
    INSERT INTO pmd_objecttype (
      qname,
      caption,
      datecreation
    ) VALUES (
      'default',
      'Type par défaut',
      NOW()
    )
";
$tagDataSource->query($query);


$parentId=$tagDataSource->getLastInsertId();

echo "Create Tag Object type\n";
$query="
    INSERT INTO pmd_objecttype (
      parent_id,
      qname,
      caption,
      datecreation
    ) VALUES (
      ".$parentId.",
      'tag',
      'Tag',
      NOW()
    )
";
$tagDataSource->query($query);








echo "Construction de l'arbre des types\n";
$tree=new ObjectType();
$tree->setSource($tagDataSource);
$tree->buildTree();










echo "Create Default tag type\n";

$query="
  INSERT INTO ".Type::getTableName()." (
    qname,
    caption,
    datecreation,
    data
  ) VALUES (
    'default',
    'Type par défaut',
    NOW(),
    ''
  )
";
$tagDataSource->query($query);


$tagTypeTree=new Type($tagDataSource);
$rootTypeNode=$tagTypeTree->getRoot();

$rootTypeNode->setValue('data','{
    "attributes": {
        "image": {
            "caption": "Image",
            "mandatory": false,
            "type": "image",
            "default": null
        },
        "title": {
            "caption": "Titre",
            "mandatory": false,
            "type": "text",
            "default": null
        },
        "description": {
            "caption": "Description",
            "mandatory": false,
            "type": "text",
            "subtype": "html",
            "default": null
        }
    },
    "rules": []
}');
$rootTypeNode->update();




echo "Create  Flag type\n";
$rootTagId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$rootTypeNode->getId().",
    'flag',
    'Flag',
    NOW()
  )
";
$tagDataSource->query($query);



echo "Create Category tag type\n";
$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$rootTypeNode->getId().",
    'category',
    'Catégorie',
    NOW()
  )
";
$tagDataSource->query($query);




echo "Create Content tag type\n";
$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$rootTypeNode->getId().",
    'content',
    'Contenu',
    NOW()
  )
";
$tagDataSource->query($query);




$tree=new Type();
$tree->setSource($tagDataSource);
$tree->buildTree();




echo "Create root tag \n";


$tag=new Tag();
$tag->setSource($tagDataSource);
$tag->setCaption('#');
$tag->insert();

$tagDataSource->commit();





