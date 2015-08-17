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


    public function update() {
        $this->setValue('data', json_encode($this->getInheritedAttributesValues(), JSON_PRETTY_PRINT));
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



