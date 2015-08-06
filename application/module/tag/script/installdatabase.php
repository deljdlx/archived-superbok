<?php


$tagDataSource=\PMD\Capital\Configuration\DataSource::get('new');




$tagDataSource->query('DROP TABLE '.\PMD\Capital\Model\Tag::getTableName().'');
$tagDataSource->query('DROP TABLE '.\PMD\Capital\Model\ObjectType::getTableName().'');
$tagDataSource->query('DROP TABLE '.\PMD\Capital\Model\TagType::getTableName().'');
$tagDataSource->query('DROP TABLE '.\PMD\Capital\Model\TagAssociation::getTableName().'');
$tagDataSource->query('DROP TABLE '.\PMD\Capital\Model\TagAssociationType::getTableName().'');





echo "Create pmd_tag table\n";
$query="
        CREATE TABLE `".\PMD\Capital\Model\Tag::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `parent_id` INT(32),
        `mastertag_id` INT(32),
        `type_id` INT(32) UNSIGNED,
        `caption` VARCHAR(100) NOT NULL,
        `leftbound` INT(32) UNSIGNED,
        `rightbound` INT(32) UNSIGNED,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        `slug` VARCHAR(100) NOT NULL,
        `data` TEXT,
        PRIMARY KEY (`id`),
        INDEX `pmdtag_parentid` (`parent_id`),
        INDEX `pmdtag_mastertagid` (`mastertag_id`),
        INDEX `pmdtag_bounds` (`leftbound`, `rightbound`),
        INDEX `pmdtag_keyword` (`caption`),
        INDEX `pmdtag_slug` (`slug`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";

$tagDataSource->query($query);


echo "Create pmd_tagtype table\n";
$query="
        CREATE TABLE `".\PMD\Capital\Model\TagType::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `parent_id` INT(32),
        `caption` VARCHAR(100) NOT NULL,
        `leftbound` INT(32) UNSIGNED,
        `rightbound` INT(32) UNSIGNED,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        `data` TEXT,
        PRIMARY KEY (`id`),
        INDEX `pmdtagtype_parentid` (`parent_id`),
        INDEX `pmdtagtype_bounds` (`leftbound`, `rightbound`),
        INDEX `pmdtagtype_caption` (`caption`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";

$tagDataSource->query($query);



echo "Create pmd_tagassociation table\n";
$query="
        CREATE TABLE `".\PMD\Capital\Model\TagAssociation::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `tag_id` INT(32) UNSIGNED NOT NULL,
        `object_id` INT(32) UNSIGNED NOT NULL,
        `objecttype_id` INT(32) UNSIGNED NOT NULL,
        `type_id` INT(32) UNSIGNED NOT NULL,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        PRIMARY KEY (`id`),
        INDEX `pmdtagassociation_tagid` (`tag_id`),
        INDEX `pmdtagassociation_objectid` (`object_id`),
        INDEX `pmdtagassociation_objecttypeid` (`objecttype_id`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";

$tagDataSource->query($query);






echo "Create pmd_objecttype table\n";
$query="
        CREATE TABLE `".\PMD\Capital\Model\ObjectType::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `parent_id` INT(32),
        `caption` VARCHAR(100) NOT NULL,
        `leftbound` INT(32) UNSIGNED,
        `rightbound` INT(32) UNSIGNED,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        `data` TEXT,
        PRIMARY KEY (`id`),
        INDEX `pmdobjecttype_parentid` (`parent_id`),
        INDEX `pmdobjecttype_caption` (`caption`),
        INDEX `pmdobjecttype_bounds` (`leftbound`, `rightbound`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";

$tagDataSource->query($query);


echo "Create Default Object type\n";
$query="
    INSERT INTO pmd_objecttype (
      caption,
      datecreation
    ) VALUES (
      'default',
      NOW()
    )
";
$tagDataSource->query($query);








echo "Create pmd_tagassociationtype table\n";
$query="
        CREATE TABLE `".\PMD\Capital\Model\TagAssociationType::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `caption` VARCHAR(100) NOT NULL,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        PRIMARY KEY (`id`),
        INDEX `pmdassociationtype_caption` (`caption`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";
$tagDataSource->query($query);

echo "Create default association type\n";
$query="
        INSERT INTO ".\PMD\Capital\Model\TagAssociationType::getTableName()." (
          caption,
          datecreation
        ) VALUES (
          'default',
          NOW()
        );
";
$tagDataSource->query($query);








$rootObjectTypeId=$tagDataSource->getLastInsertId();
echo "Create ezObject Object type\n";
$query="
    INSERT INTO ".\PMD\Capital\Model\ObjectType::getTableName()." (
      caption,
      parent_id,
      datecreation
    ) VALUES (
      'ezobject',
      '".$rootObjectTypeId."',
      NOW()
    )
";
$tagDataSource->query($query);


echo "Construction de l'arbre des types\n";
$tree=new \PMD\Capital\Model\ObjectType();
$tree->setSource($tagDataSource);
$tree->buildTree();










echo "Create Default tag type\n";

$query="
  INSERT INTO ".\PMD\Capital\Model\TagType::getTableName()." (
    caption,
    datecreation
  ) VALUES (
    'default',
    NOW()
  )
";
$tagDataSource->query($query);


echo "Create  Flag type\n";
$rootTagId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO ".\PMD\Capital\Model\TagType::getTableName()." (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$rootTagId.",
    'flag',
    NOW()
  )
";
$tagDataSource->query($query);



echo "Create Category tag type\n";
$query="
  INSERT INTO ".\PMD\Capital\Model\TagType::getTableName()." (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$rootTagId.",
    'category',
    NOW()
  )
";
$tagDataSource->query($query);




echo "Create Content tag type\n";
$query="
  INSERT INTO ".\PMD\Capital\Model\TagType::getTableName()." (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$rootTagId.",
    'content',
    NOW()
  )
";
$tagDataSource->query($query);



echo "Create Company tag type\n";
$contentTagId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO ".\PMD\Capital\Model\TagType::getTableName()." (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$contentTagId.",
    'company',
    NOW()
  )
";
$tagDataSource->query($query);


echo "Create Person tag type\n";
$parentId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO ".\PMD\Capital\Model\TagType::getTableName()." (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$contentTagId.",
    'person',
    NOW()
  )
";
$tagDataSource->query($query);



$tree=new \PMD\Capital\Model\TagType();
$tree->setSource($tagDataSource);
$tree->buildTree();




echo "Create root tag \n";


$tag=new \PMD\Capital\Model\Tag();
$tag->setSource($tagDataSource);
$tag->setCaption('#');
$tag->insert();










