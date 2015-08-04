<?php


$tagDataSource=\PMD\Capital\Configuration\DataSource::get('new');




$tagDataSource->query('DROP TABLE pmd_tag');
$tagDataSource->query('DROP TABLE pmd_tagtype');
$tagDataSource->query('DROP TABLE pmd_tagasociation');





echo "Create pmd_tag table\n";
$query="
        CREATE TABLE `pmd_tag` (
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
        CREATE TABLE `pmd_tagtype` (
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
        CREATE TABLE `pmd_tagasociation` (
        `tag_id` INT(32) UNSIGNED NOT NULL,
        `object_id` INT(32) UNSIGNED NOT NULL,
        `object_typeid` INT(32) UNSIGNED NOT NULL,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        PRIMARY KEY (`tag_id`, `object_id`),
        INDEX `pmdtagassociation_tagid` (`tag_id`),
        INDEX `pmdtagassociation_objectid` (`object_id`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";

$tagDataSource->query($query);




echo "Create Default tag type\n";

$query="
  INSERT INTO pmd_tagtype (
    caption,
    datecreation
  ) VALUES (
    'default',
    NOW()
  )
";
$tagDataSource->query($query);




echo "Create Content tag type\n";
$parentId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO pmd_tagtype (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$parentId.",
    'content',
    NOW()
  )
";
$tagDataSource->query($query);



echo "Create Company tag type\n";
$parentId=$tagDataSource->getLastInsertId();

$query="
  INSERT INTO pmd_tagtype (
    parent_id,
    caption,
    datecreation
  ) VALUES (
    ".$parentId.",
    'company',
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










