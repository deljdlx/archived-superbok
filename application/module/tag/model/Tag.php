<?php
namespace PMD\Capital\Module\Tag\Model;
use PMD\Capital\Model\DatabaseElement;
use PMD\Capital\Model\HasInheritedAttributeValues;
use PMD\Capital\Model\Tree;

class Tag extends DatabaseElement
{


    use Tree;
    use HasInheritedAttributeValues;


    protected $type=null;

    protected $values=array(
        'id'=>null,
        'parent_id'=>null,
        'type_id'=>null,
        'mastertag_id'=>null,
        'caption'=>'',
        'slug'=>'',
        'data'=>null,
        'datecreation'=>null,
        'datemodification'=>null,
    );




    public static function getTableName() {
        return 'pmd_tag';
    }



    public function getType() {
        if($this->type!==null) {
            return $this->type;
        }
        else {
            $this->type=new Type($this->getSource());
            $this->type->loadById($this->getValue('type_id'));
            return $this->type;
        }
    }





    public function getInheritableAttributes() {
           return $this->getType()->getInheritableAttributes();
    }


    public function getInheritedAttributesValues() {

        if(empty($this->inheritableAttributesValues)) {

            //vérifie que le tag a bien un type , car un nouveau tag peut ne pas avoir encore de type
            //si le tag n'a pas de type définit, alors il ne peut pas avoir de valeurs héritées
            if($type=$this->getType()) {
                if ($type->getId()) {
                    $this->inheritableAttributesValues = $type->getInheritableAttributes();
                }
            }
            else {
                $this->inheritableAttributesValues=array();
            }
        }


        //on merge les valeurs avec la descriptions des attributs hérités
        if($data=json_decode($this->getValue('data'), true)) {
            $this->inheritableAttributesValues=array_replace_recursive($data, $this->inheritableAttributesValues);
        }


        return $this->inheritableAttributesValues;
    }


    public function setInheritableAttributesValues($values, $remplace=false) {

        $objectProperties=$this->getInheritableAttributes();


        /*
         * transformation d'une structure array('attributeName'=>$value)
         * vers une structure du type
         *
         * array('attributeName'=>array(
         *  'value'=>$value
         * )
         */

        array_walk_recursive($values, function(&$value, $name) {
            $value=array(
                'value'=>$value
            );
        });


        //on merge les valeurs avec la descriptions des attributs hérités
        if(!$remplace) {
            //si l'on n'écrase pas les valeurs, on merge les attributs hérités avec les valeurs courantes
            if ($currentValues = json_decode($this->getValue('data'), true)) {
                $this->inheritableAttributesValues = array_replace_recursive($currentValues, $objectProperties);
            }
        }
        else {
            //sinon on reset les valeurs en initialisant juste avec les propriété de l'attribut
            $this->inheritableAttributesValues = $objectProperties;
        }



        $this->inheritableAttributesValues=array_replace_recursive($this->inheritableAttributesValues, $values);

        $this->setValue('data', json_encode($this->inheritableAttributesValues, JSON_PRETTY_PRINT));


        return $this;
    }



    public function update() {

        $values=json_encode($this->getInheritedAttributesValues(), JSON_PRETTY_PRINT);

        $this->setValue('data', $values);
        parent::update();
        return $this;
    }


    public function loadById($id) {
        parent::loadById($id);
    }







    public function setCaption($caption) {
        $this->values['caption']=$caption;
        return $this;
    }

    public function setParentId($id) {
        $this->values['parent_id']=$id;
        return $this;
    }

    public function setMasterTagId($id) {
        $this->values['mastertag_id']=$id;
        return $this;
    }


    public function setSlug($slug) {
        $this->values['slug']=$slug;
        return $this;
    }



    public function getParentId() {
        return $this->values['parent_id'];
    }

    public function getMasterTagId() {
        return $this->values['mastertag_id'];
    }

    public function getCaption() {
        return $this->values['caption'];
    }

    public function getTypeId() {
        return $this->values['type_id'];
    }


    public function getSlug() {
        return $this->values['slug'];
    }

    public function getData() {
        return $this->values['data'];
    }






}



