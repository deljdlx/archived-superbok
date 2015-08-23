<?php
namespace PMD\Capital\Module\Tag\Controller;

use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Library\AttributeRenderer;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


class TagTypeManager extends Controller
{


    public function getTree($nodeId) {

        $tree=new Type($this->getDataSource());

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

	public function getNodeInfo($nodeId) {
		$tagCategory=new Type($this->getDataSource());
		$tagCategory->loadById($nodeId);


		return $tagCategory->getValues();
	}

	public function update($nodeId, $values, $json) {

		$tagCategory=new Type($this->getDataSource());
		$tagCategory->loadById($nodeId);
		$tagCategory->setValue('data', json_encode($values, JSON_PRETTY_PRINT));
		$tagCategory->update();


		return $tagCategory->getValue('data');
	}


}


