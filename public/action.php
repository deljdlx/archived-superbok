<?php
include('../bootstrap.php');



$test=new \PMD\Capital\Module\Tag\Controller\TagTypeManager();
$data=$test->getTree($_GET['nodeId']);



echo json_encode($data);

exit();



$tree=new \PMD\Capital\Model\Tag('new');

$tree=new \PMD\Capital\Model\TagType('new');


if((int) $_GET['nodeId']) {
    $tree->loadById($_GET['nodeId']);

}
else {
    $tree=$tree->getRoot();
}

$children=$tree->getChildren();

echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
echo '<div style="background-color:rgba(100,100,100,1); color: rgba(255,255,255,1)">' . __FILE__ . '@' . __LINE__ . '</div>';
print_r($children);
echo '</pre>';

die('EXIT '.__FILE__.'@'.__LINE__);

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
