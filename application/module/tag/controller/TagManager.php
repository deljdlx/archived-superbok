<?php
namespace PMD\Capital\Module\Tag\Controller;


use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


class TagManager
{


    public function getTree($nodeId) {
        $tree=new Tag('new');


        if((int) $nodeId) {
            $tree->loadById($_GET['nodeId']);
        }
        else {
            $tree=$tree->getRoot();
        }

        $children=$tree->getChildren();


        $nodes=array();

        foreach ($children as $child) {

            $childrenExists=$child->childrenExists();

            if(!empty($childrenExists)) {
                $icon='fa fa-tags';
            }
            else {
                $icon='fa fa-tag';
            }


            if(!$child->getValue('mastertag_id')) {

                $type=$child->getType();
                $icon='fa tag-'.$type->getValue('qname');


                $nodes[strtolower($child->getValue('slug'))]=array(
                    'id'=>$child->getId(),
                    'text'=>''.$child->getValue('caption'),
                    'children'=>$childrenExists,
                    'something'=>'test',
                    'icon'=>$icon,
                    'data'=>$child->getValue('data'),
                );
            }


        }

        ksort($nodes);


        $nodes=array_values($nodes);

        return $nodes;
    }


}


