<?php
mysql_connect ("mysql.database", "mak","password")  or die (mysql_error());
mysql_select_db ("mak");

$sql = mysql_query("select link,user from links where link like '%.jpg' order by RAND() limit 1");
$sql2 = mysql_query("select link,user from links where link like '%.png' order by RAND() limit 1");
$sql3 = mysql_query("select link,user from links where link like '%.gif' order by RAND() limit 1");
$x = mysql_fetch_array($sql);
$xx = mysql_fetch_array($sql2);
$xxx = mysql_fetch_array($sql3);
$link = $x[0];
$user = $x[1];
$link2 = $xx[0];
$user2 = $xx[1];
$link3 = $xxx[0];
$user3 = $xxx[1];
$rand = rand(0,15);

?>
<!DOCTYPE html><html><head><title>Image Linkbot Loader!</title><link rel="stylesheet" type="text/css" media="screen,projection" href="Link.css" /></head><body background="bg.jpg" bgcolor="#B0B0B0"><h1 align="center"><font face="Comic Sans MS" color="#D8D8D8">#lobby Images!</font></h1>
<p align="center"><font face="Comic Sans MS" color="#888888">Press the image to change...(May be NSFW)</font></p><hr>
<?php
if($rand <= 5){
    print('<p align="center"><font face="Comic Sans MS" color="#888888"><font color="#D8D8D8"><a href="'.$link.'">'.$link.'</a> </font>Linked by <font color="red">'.$user.'</font></font></p>');
}
if(($rand > 5)&&($rand <= 10)){
    print('<p align="center"><font face="Comic Sans MS" color="#888888"><font color="#D8D8D8"><a href="'.$link2.'">'.$link2.' </a></font>Linked by <font color="red">'.$user2.'</font></font></p>');
}
if($rand > 10){
    print('<p align="center"><font face="Comic Sans MS" color="#888888"><font color="#D8D8D8"><a href="'.$link3.'">'.$link3.'</a> </font>Linked by <font color="red">'.$user3.'</font></font></p>');
}
?>

<hr>
<body>
<?php
if($rand <= 5){
    print('<p align="center"><a href="image.php"><img src="'.$link.'"></a></p>');
}
if(($rand > 5)&&($rand <= 10)){
    print('<p align="center"><a href="image.php"><img src="'.$link2.'"></a></p>');
}
if($rand > 10){
    print('<p align="center"><a href="image.php"><img src="'.$link3.'"></a></p>');
}
?>
<hr>
<p align="center"><a href="http://www.redbrick.dcu.ie/~mak/" target="_blank"><font size="5" face="Comic Sans MS" color="#D8D8D8">Home</font></a></p>
</body>
</html>


