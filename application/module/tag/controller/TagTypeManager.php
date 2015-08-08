<?php
namespace PMD\Capital\Module\Tag\Controller;

use PMD\Capital\Module\Tag\Model\Type;


class TagTypeManager
{


    public function getTree($nodeId) {

        $tree=new Type('new');

        if((int) $nodeId) {
            $tree->loadById($nodeId);
            $rootNode=null;
        }
        else {

            $tree=$tree->getRoot();

            $rootNode=$tree;
        }

        $children=$tree->getChildren();


        $nodes=array();

        foreach ($children as $child) {

            $childrenExists=$child->childrenExists();

            if(!$child->getValue('mastertag_id')) {


                $icon='fa fa-tag';


                $nodes[strtolower($child->getValue('caption'))]=array(
                    'id'=>$child->getId(),
                    'text'=>''.$child->getValue('caption'),
                    'children'=>$childrenExists,
                    'data'=>$child->getValue('data'),
                    'icon'=>$icon
                );
            }


        }

        ksort($nodes);
        $nodes=array_values($nodes);

        if($rootNode) {
            $icon='fa fa-tag';
            $data=array(
                'id'=>$rootNode->getId(),
                'text'=>$rootNode->getValue('caption'),
                'state'=>array(
                    'opened'=>true,
                ),
                'data'=>$rootNode->getValue('data'),
                'icon'=>$icon,
                'children'=>$nodes,
            );

            return $data;
        }
        else {
            return $nodes;
        }




    }


}


