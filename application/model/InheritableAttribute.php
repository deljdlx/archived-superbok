<?php

namespace PMD\Capital\Model;




Trait InheritableAttribute
{

    use Tree;


    protected $inheritableAttributes=null;


    public static function getDataFieldName() {
        return 'data';
    }


    public function getInheritableAttributes($returnArray=false) {
        if($this->inheritableAttributes===null) {
            $this->inheritableAttributes=array();
            $this->loadInheritableAttributes();
        }

        if($returnArray) {
            return json_decode(json_encode($this->inheritableAttributes), true); //convert object to array
        }
        else {
            return $this->inheritableAttributes;
        }

    }


    public function loadInheritableAttributes() {

        $parents=$this->getParents();
        $parents=array_reverse($parents);

        $attributes=array();


        foreach ($parents as $parent) {
            $parentAttributesData=$parent->getValue(static::getDataFieldName());


            if($parentAttributes=json_decode($parentAttributesData, true)) {
                $attributes=array_replace_recursive($attributes, $parentAttributes);
            }
        }

        if($nodeAttributes=json_decode($this->getValue(static::getDataFieldName()), true)) {
            $attributes=array_replace_recursive($attributes, $nodeAttributes);
        }

        $this->inheritableAttributes=json_decode(json_encode($attributes)); //convert array to object
        return $this;
    }





}
