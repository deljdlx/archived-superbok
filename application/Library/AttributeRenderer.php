<?php
namespace PMD\Capital\Library;



class AttributeRenderer
{


    protected $attribute;

    public function __construct($name, $attribute) {
        $this->name=$name;
        $this->attribute=$attribute;
    }


    public function toWebComponent($prefix='pmd') {


        $buffer='';

            $buffer.='<'.$prefix.'-'.$this->attribute['type'].' data-name="'.$this->name.'"';
            if(isset($this->attribute['subtype'])) {
                $buffer.=' is="'.$this->attribute['subtype'].'"';
            }


            foreach ($this->attribute as $name=>$value) {

                $buffer.=' data-'.$name.'="'.htmlentities($value).'"';
            }


        $buffer.='>';


        $buffer.='</'.$prefix.'-'.$this->attribute['type'].'>';


        return $buffer;


    }


}



