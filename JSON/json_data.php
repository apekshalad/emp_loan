<?php
//include('config.php');
	
	
	$posts []= array ('title'=>'hi','url'=>'url');
	
	
	header('Content-type: application/json');
	echo json_encode(array('posts'=>$posts));
		
		
		return;
//$sql=mysql_query("select * from tig_linkmsg limit 20");

echo '{"posts": [';

//while($row=mysql_fetch_array($sql))
//{
$title='hello';//$row['title'];
$url='hello world';//$row['url'];
echo '
    {
	"title":"'.$title.'",
	"url":"'.$url.'"
	},';	
//}
echo ']}';
?>