<?php
include('config.php');
	
	


$sql=mysql_query("select * from tig_linkmsg limit 20");

echo '{"posts": [';
while($row=mysql_fetch_array($sql))
{
$title=$row['title'];
$url=$row['url'];
echo '
    {
	"title":"'.$title.'",
	"url":"'.$url.'"
	},';	
}
echo ']}';
?>