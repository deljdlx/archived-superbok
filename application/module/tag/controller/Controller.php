<?php
namespace PMD\Capital\Module\Tag\Controller;


use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


class Controller
{

    public function getDataSource() {
        return DataSource::get('new');
    }


}