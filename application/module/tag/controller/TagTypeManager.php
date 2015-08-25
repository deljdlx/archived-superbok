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


        $tree=$tree->getRoot();


        $children=$tree->getChildren(true, true, function($node, $children) {
            $data=array(
                'node'=>$node->getValues(),
                'children'=>array()
            );
            foreach ($children as $child) {
                $data['children'][$child->getId()]=$child->getValues();
            }

            return $data;



        });


        echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
        echo '<div style="background-color:rgba(100,100,100,1); color: rgba(255,255,255,1)">' . __FILE__ . '@' . __LINE__ . '</div>';
        print_r($children);
        echo '</pre>';



        $icon='fa fa-tag';
        $childrenExists=$tree->childrenExists();
        $rootNode=array(
            'id'=>$tree->getId(),
            'text'=>''.$tree->getValue('caption'),
            'children'=>$childrenExists,
            'data'=>$tree->getValue('data'),
            'icon'=>$icon
        );

        $nodes[strtolower($tree->getValue('caption'))]=$rootNode;



        die('EXIT '.__FILE__.'@'.__LINE__);


        $nodes=array();


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

	public function updateInheritableAttributes($nodeId, $json='', $values=null) {

		$tagCategory=new Type($this->getDataSource());
		$tagCategory->loadById($nodeId);
		$tagCategory->setValue('data', $json);
		$tagCategory->update();


		return $tagCategory->getValue('data');
	}


    public function create($parentId, $caption) {
        $tagCategory=new Type($this->getDataSource());
        $tagCategory->setValue('parent_id', $parentId);
        $tagCategory->setValue('caption', $caption);
        $tagCategory->insert();

        $rootCategory=new Type($this->getDataSource());
        $rootCategory->loadBy('qname', 'default');


        $tagCategory->buildTree($rootCategory->getId(), true);

        return $tagCategory->getValues();
    }

}


