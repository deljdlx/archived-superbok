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
            $tree->loadById($_GET['nodeId']);
        }
        else {
            $tree=$tree->getRoot();
        }

        $children=$tree->getChildren();


        $nodes=array();

        foreach ($children as $child) {

            $childrenExists=$child->childrenExists();

            if(!$child->getValue('mastertag_id')) {

                $type=$child->getType();
                $icon='fa tag-'.$type->getValue('qname');


                $nodes[strtolower($child->getValue('slug'))]=array(
                    'id'=>$child->getId(),
                    'text'=>''.$child->getValue('caption'),
                    'children'=>$childrenExists,
                    'something'=>'test',
                    'type'=>$type->getValue('caption'),
                    'icon'=>$icon,
                    'data'=>$child->getValue('data'),
                );
            }
        }

        ksort($nodes);


        $nodes=array_values($nodes);

        return $nodes;
    }

    public function getForm($nodeId) {

        $tag=new Tag($this->getDataSource());
        $tag->loadById((int) $nodeId);

        $type=$tag->getType();

        $attributes=$type->getInheritableAttributes();




        $values=json_decode($tag->getValue('data'), true);

        if($values) {
            $tag->setInheritableAttributesRawValues($values);
        }



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
        $tag->setInheritableAttributesValues(array('attributes'=>$attributes));
        $tag->update();

        return $tag->getInheritedAttributesValues();

    }


}


