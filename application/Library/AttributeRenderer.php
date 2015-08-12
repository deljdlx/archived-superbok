<?php
namespace PMD\Capital\Library;



class AttributeRenderer
{


    protected $attribute;

    public function __construct($attribute) {
        $this->attribute=$attribute;
    }


    public function toWebComponent($value='', $prefix='pmd') {

        $buffer='';

            $buffer.='<'.$prefix.'-'.$this->attribute->type;
            if(isset($this->attribute->subtype)) {
                $buffer.=' is="'.$this->attribute->subtype.'"';
            }
            $buffer.=' data-value="'.htmlentities(json_encode($value), ENT_COMPAT).'"';

            foreach ($this->attribute as $name=>$value) {
                $buffer.=' data-'.$name.'="'.htmlentities($value).'"';
            }


        $buffer.='>';


        $buffer.='</'.$prefix.'-'.$this->attribute->type.'>';


        return $buffer;


    }


}



