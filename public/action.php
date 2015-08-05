<?php
include('../bootstrap.php');



$tree=new \PMD\Capital\Model\Tag('new');


if((int) $_GET['nodeId']) {
    $tree->loadById($_GET['nodeId']);

}
else {
    $tree=$tree->getRoot();
}

$children=$tree->getChildren();



$nodes=array();

foreach ($children as $child) {

    $childrenExists=$child->childrenExists();

    if(!empty($childrenExists)) {
        $icon='fa fa-tags';
    }
    else {
        $icon='fa fa-tag';
    }


    if(!$child->getValue('mastertag_id')) {

        $type=$child->getType();
        $icon='fa tag-'.$type->getValue('caption');


        $nodes[strtolower($child->getValue('slug'))]=array(
            'id'=>$child->getId(),
            'text'=>''.$child->getValue('caption'),
            'children'=>$childrenExists,
            'something'=>'test',
            'icon'=>$icon
        );
    }


}

ksort($nodes);


$nodes=array_values($nodes);

echo json_encode($nodes);
