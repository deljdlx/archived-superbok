<?php
namespace PMD\Capital\Module\Tag\Controller;


use PMD\Capital\Configuration\DataSource;
use PMD\Capital\Library\AttributeRenderer;
use PMD\Capital\Module\Tag\Model\Tag;
use PMD\Capital\Model\ObjectType;
use PMD\Capital\Module\Tag\Model\Type;
use PMD\Capital\Module\Tag\Model\Association;
use PMD\Capital\Module\Tag\Model\AssociationType;


class TagManager extends Controller
{


    public function getTree($nodeId) {
        $tree=new Tag($this->getDataSource());


        if((int) $nodeId) {
            $tree->loadById($nodeId);
        }
        else {
            $tree=$tree->getRoot();
        }

        $children=$tree->getChildren();


        $nodes=array();

        foreach ($children as $child) {

            $childrenExists=$child->childrenExists();

            //if(!$child->getValue('mastertag_id')) {

                $type=$child->getType();
                $icon='fa tag-'.$type->getValue('qname');


                $nodes[strtolower($child->getValue('slug'))]=array(
                    'id'=>$child->getId(),
                    'text'=>''.$child->getValue('caption'),
                    'children'=>$childrenExists,
                    'something'=>'test',
                    'type'=>array(
                        'caption'=>$type->getValue('caption'),
                        'qname'=>$type->getValue('qname'),
                        'id'=>$type->getValue('id'),
                    ),
                    'icon'=>$icon,
                    'data'=>$child->getValue('data'),
                );
            //}
        }

        ksort($nodes);


        $nodes=array_values($nodes);

        if(!(int) $nodeId) {


            $type=$tree->getType();
            $icon='fa tag-'.$type->getValue('qname');

            $rootNode[strtolower($tree->getValue('slug'))]=array(
                'id'=>$tree->getId(),
                'text'=>''.$tree->getValue('caption'),
                'children'=>$nodes,
                'something'=>'test',
	            'state'=>array(
		            'opened'=>true,
	            ),
                'type'=>array(
                    'caption'=>$type->getValue('caption'),
                    'qname'=>$type->getValue('qname'),
                    'id'=>$type->getValue('id'),
                ),
                'icon'=>$icon,
                'data'=>$tree->getValue('data'),
            );

            $nodes=array_values($rootNode);
        }

        return $nodes;
    }

    public function getForm($nodeId) {

        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $nodeId);


        $values=$tag->getInheritedAttributesValues();




        $inputs=array();
        foreach ($values['attributes'] as $name=>&$attribute) {


            $renderer=new AttributeRenderer($name, $attribute);
            $inputs[$name]=$renderer->toWebComponent('pmd-form');
        }

        return $inputs;
    }


    public function save($tagId, $attributes) {
        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $tagId);
        $tag->setInheritableAttributesValues(array('attributes'=>$attributes), true);
        $tag->update();
        return $tag->getInheritedAttributesValues();
    }

    public function delete($tagId) {
        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $tagId);
        $tag->delete();

        return $tag->getInheritedAttributesValues();
    }

    public function move($tagId, $parentId) {
        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $tagId);
        $tag->setValue('parent_id', $parentId);
        $tag->update();
        return $tag;
    }


    public function rename($tagId, $caption) {
        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $tagId);
        $tag->setValue('caption', $caption);
        $tag->setValue('slug', slugify($caption));
        $tag->setInheritableAttributesValues(array('attributes'=>array(
            'title'=>$caption
        )), true);
        $tag->update();
        return $tag;
    }


    public function getParents($tagId) {
        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $tagId);
        $parents=$tag->getParents();

        $data=array();

        foreach ($parents as $parent) {
            $data[]=$parent->getValues();
        }
        return $data;


    }




}


