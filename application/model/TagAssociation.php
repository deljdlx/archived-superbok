<?php
namespace PMD\Capital\Model;


class TagAssociation extends DatabaseElement
{


    protected $values=array(
        'id'=>null,
        'tag_id'=>null,
        'object_id'=>null,
        'objecttype_id'=>null,
        'type_id'=>null,
        'datecreation'=>null,
        'datemodification'=>null,
    );

    protected $objectType;
    protected $associationType;


    public function __construct($source) {
        parent::__construct($source);
        $object=new TagAssociationType($this->getSource());
        $object->loadBy('caption', 'dafault');
        $this->associationType=$object;

        $object=new ObjectType($this->getSource());
        $object->loadBy('caption', 'default');
        $this->objectType=$object;
    }


    public static function getTableName() {
        return 'pmd_tagassociation';
    }

    public function setObjectType($objectType) {
        if($objectType instanceof ObjectType) {
               $this->objectType=$objectType;
           }
        else if(is_int($objectType)){
            $object=new ObjectType($this->getSource());
            $object->loadById($objectType);
            $this->objectType=$object;
        }

        $this->setValue('objecttype_id', $this->objectType->getId());

        return $this;
    }



    public function getObjectType() {
        if($this->objectType==null) {
            $object=new ObjectType($this->getSource());
            $object->loadById($this->getValue['objecttype_id']);
            $this->$objectType=$object;
        }
        return $this->objectType;
    }


    public function getType() {
        if($this->associationType==null) {
            $object=new AssociationType($this->getSource());
            $object->loadById($this->getValue['type_id']);
            $this->associationType=$object;
        }
        return $this->associationType;
    }


    public function setType($associationType) {
        if($associationType instanceof AssociationType) {
            $this->associationType=$associationType;
        }
        else if(is_int($associationType)) {
            $object=new AssociationType($this->getSource());
            $object->loadById($associationType);
            $this->associationType=$object;
        }

        $this->setValue('type_id', $this->associationType->getId());

        return $this;
    }

}



