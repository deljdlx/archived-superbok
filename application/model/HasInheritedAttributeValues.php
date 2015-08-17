<?php

namespace PMD\Capital\Model;




Trait HasInheritedAttributeValues
{

    protected $inheritableAttributesValues=array();




    public function setInheritableAttributesRawValues($values) {
        $this->inheritableAttributesValues=$values;
        return $this;
    }


    public function setInheritableAttributesValues($values) {

        $objectProperties=$this->getInheritableAttributes();

        array_walk_recursive($values, function(&$value, $name) {
            $value=array(
                'value'=>$value
            );
        });

        $this->inheritableAttributesValues=array_replace_recursive($objectProperties, $values);
        return $this;
    }

    public function getInheritedAttributesValues() {
        if(empty($this->inheritableAttributesValues)) {
            $this->inheritableAttributesValues=$this->getInheritableAttributes();
        }
        return $this->inheritableAttributesValues;
    }


}
