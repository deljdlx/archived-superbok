<?php






function getChildren($nodeId) {


	$db=new mysqli('192.168.1.64', 'root', '514ever', 'cap');
	$db->set_charset('utf8');
	
	$query="
		SELECT * FROM pmd_content_node
		WHERE parent_id=".$nodeId."
		ORDER BY rank, caption;
	";

	$s=$db->query($query);

	$rows=array();
	while($row=$s->fetch_assoc()) {
		$rows[]=$row;
	}
	
	
	$children=array();
	foreach($rows as $row) {
	
		if(($row['rightbound']-$row['leftbound'])>1) {
			$childrenExists=true;
		}
		else {
			$childrenExists=false;
		}
	
		$children[]=array(
			'id'=>$row['id'],
			'text'=>''.$row['caption'],
			'children'=>$childrenExists,
            'something'=>'test'
		);
	}
	
	return $children;
}


if($_GET['nodeId']=='#') {
	
	$children=array(
		'id'=>0,
		'text'=>'Racine',
		'children'=>getChildren(0),
		'state'=> array(
			'opened'=> true
		)
	);
}
else {
	$children=getChildren($_GET['nodeId']);
}




echo json_encode($children);



?>