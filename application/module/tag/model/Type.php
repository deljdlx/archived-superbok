<?php
namespace PMD\Capital\Module\Tag\Model;
use PMD\Capital\Model\DatabaseElement;
use PMD\Capital\Model\HasInheritableAttribute;
use PMD\Capital\Model\Tree;
use PMD\Capital\Model\InheritableAttribute;
use PMD\Capital\Module\Tag\Model\EzPublish\Tag as ezTag;

class Type extends DatabaseElement
{

    use HasInheritableAttribute;

    protected $values=array(
        'id'=>null,
        'parent_id'=>null,
        'qname'=>null,
        'caption'=>null,
        'leftbound'=>null,
        'rightbound'=>null,
        'datecreation'=>null,
        'datemodification'=>null,
    );

    static public function getTableName() {
        return 'pmd_tagtype';
    }



    public function delete() {
        $this->deleteChildren();
        $this->deleteTags();
        parent::delete();
        return $this;
    }


    public function purge() {


        $deletedChildren=$this->deleteChildren();


        $typeIds=array();
        foreach ($deletedChildren as $child) {
            $typeIds[]=$this->escape($child->getId());
        }
        $typeIds[]=$this->escape($this->getId());



        $query="
            SELECT id FROM ".Tag::getTableName()." tag
            WHERE type_id IN (".implode($typeIds).")
        ";
        $rows=$this->queryAndFetch($query);


        $tagsId=array();
        foreach ($rows as $values) {
            $tagsId[]=$this->escape($values['id']);
        }

        $query="
            SELECT id FROM ".Tag::getTableName()." tag
            WHERE tag.mastertag_id IN (".implode(',', $tagsId).")
        ";
        $rows=$this->queryAndFetch($query);

        foreach ($rows as $values) {
            $tagsId[]=$this->escape($values['id']);
        }


        //suppression des tags
        $query="
            DELETE tag FROM ".Tag::getTableName()." tag
            WHERE
                tag.mastertag_id IN (".implode(',', $tagsId).")
                OR tag.id IN (".implode(',', $tagsId).")
        ";
        $this->query($query);


        //suppression des associations
        $query="
            DELETE association FROM ".Association::getTableName()." association
            WHERE association.tag_id IN (".implode(',', $tagsId).")
        ";
        $this->query($query);






        //supression des types
        $query="
            DELETE type FROM ".Type::getTableName()." type
            WHERE type.id IN (".implode(',', $typeIds).")
        ";


        $this->query($query);




    }




    public function deleteTags() {
        $query="
            SELECT *
            FROM ".Tag::getTableName()." tag
            WHERE type_id='".$this->escape($this->getId())."'
        ";

        $rows=$this->queryAndFetch($query);

        $deletedTags=array();

        foreach ($rows as $values) {
            $tag=new Tag($this->getSource());
            $tag->setValues($values);
            $deletedTags[]=$tag->delete();
        }
        return $deletedTags;
    }



}


