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
          'Basique'
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



echo "Construction de l'arbre des types\n";
$tree=new ObjectType();
$tree->setSource($tagDataSource);
$tree->buildTree();










echo "Create Default tag type\n";

$query="
  INSERT INTO ".Type::getTableName()." (
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


echo "Create  Flag type\n";
$rootTagId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO ".Type::getTableName()." (
    parent_id,
    qname,
    caption,
    datecreation
  ) VALUES (
    ".$rootTagId.",
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
    ".$rootTagId.",
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
    ".$rootTagId.",
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





