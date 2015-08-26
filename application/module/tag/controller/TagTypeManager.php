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

        $render=function($children) use($tree, &$render) {
            $icon='fa fa-tag';
            $nodes=array();
            if(is_array($children)) {
                foreach ($children as $child) {
                    $nodes[]=array(
                        'id'=>$child->getId(),
                        'text'=>''.$child->getValue('caption'),
                        'children'=>$render($child->getChildren()),
                        'data'=>$child->getValue('data'),
                        'icon'=>$icon,
                        'state'=>array(
                            'opened'=>true
                        )
                       );
                }
            }
            return $nodes;
        };


        $nodes=array();



        $icon='fa fa-tag';
        $nodes[]=array(
            'id'=>$tree->getId(),
            'text'=>''.$tree->getValue('caption'),
            'children'=>$render($tree->getChildren()),
            'data'=>$tree->getValue('data'),
            'icon'=>$icon,
            'state'=>array(
                'opened'=>true
            )
        );
        return $nodes;
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


    public function delete($nodeId) {

        $tagCategory=new Type($this->getDataSource());

        $tagCategory->autocommit(false);
        $tagCategory->loadById($nodeId);

        $tagCategory->purge();


        $tagCategory->commit();

        return $tagCategory->getValues();


        $deletedChildren=$tagCategory->deleteChildren();


        foreach ($deletedChildren as $child) {
            $child->deleteTags();
        }

        $tagCategory->deleteTags();
        $tagCategory->delete();

        return $tagCategory->getValues();

    }



}


