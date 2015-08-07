<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('new');




$tagDataSource->query('DROP TABLE '.Tag::getTableName().'');
$tagDataSource->query('DROP TABLE '.ObjectType::getTableName().'');
$tagDataSource->query('DROP TABLE '.Type::getTableName().'');
$tagDataSource->query('DROP TABLE '.Association::getTableName().'');
$tagDataSource->query('DROP TABLE '.AssociationType::getTableName().'');





echo "Create pmd_tag table\n";
$query="
        CREATE TABLE `".Tag::getTableName()."` (
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
        CREATE TABLE `".Type::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `parent_id` INT(32),
        `caption` VARCHAR(100) NOT NULL,
        `qname` VARCHAR(100) NOT NULL,
        `leftbound` INT(32) UNSIGNED,
        `rightbound` INT(32) UNSIGNED,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        `data` TEXT,
        PRIMARY KEY (`id`),
        INDEX `pmdtagtype_parentid` (`parent_id`),
        INDEX `pmdtagtype_qname` (`qname`),
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
        CREATE TABLE `".Association::getTableName()."` (
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
        CREATE TABLE `".ObjectType::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `parent_id` INT(32),
        `qname` VARCHAR(100) NOT NULL,
        `caption` VARCHAR(100) NOT NULL,
        `leftbound` INT(32) UNSIGNED,
        `rightbound` INT(32) UNSIGNED,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        `data` TEXT,
        PRIMARY KEY (`id`),
        INDEX `pmdobjecttype_parentid` (`parent_id`),
        INDEX `pmdobjecttype_qname` (`qname`),
        INDEX `pmdobjecttype_caption` (`caption`),
        INDEX `pmdobjecttype_bounds` (`leftbound`, `rightbound`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";

$tagDataSource->query($query);










echo "Create pmd_tagassociationtype table\n";
$query="
        CREATE TABLE `".AssociationType::getTableName()."` (
        `id` INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
        `qname` VARCHAR(100) NOT NULL,
        `caption` VARCHAR(100) NOT NULL,
        `datecreation` DATETIME,
        `datemodification` DATETIME,
        PRIMARY KEY (`id`),
        INDEX `pmdassociationtype_qname` (`qname`),
        INDEX `pmdassociationtype_caption` (`caption`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
";
$tagDataSource->query($query);
















