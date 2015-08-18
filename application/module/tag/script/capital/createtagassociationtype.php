<?php

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


$tagDataSource=DataSource::get('new');





echo "Create pagehub association type\n";
$query="
        INSERT INTO ".AssociationType::getTableName()." (
          qname,
          caption,
          datecreation
        ) VALUES (
          'pagehub',
          'Page Hub',
          NOW()
        );
";
$tagDataSource->query($query);


