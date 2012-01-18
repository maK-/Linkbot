<?php
mysql_connect ("mysql.database", "mak","password")  or die (mysql_error());
mysql_select_db ("mak");

$sql = mysql_query("select link,title,user from links where link like '%youtube.com%' order by RAND() limit 1");
$x = mysql_fetch_array($sql);
$split = explode('v=',$x[0]);

?>

<!DOCTYPE html><html><head><title>Youtube Loader!</title><link rel="stylesheet" type="text/css" media="screen,projection" href="Link.css" /></head><body background="bg.jpg" bgcolor="#B0B0B0"><h1 align="center"><font face="Comic Sans MS" color="#D8D8D8">Watch #lobby youtube links!</font></h1>
<p align="center"><font face="Comic Sans MS" color="#888888">Press the youtube image to load a video...</font></p><hr>
<a href="youtube.php"><img src="you-tube.png" align="left" width="90" height="40"></a>
<?php
print('<p align="center"><font face="Comic Sans MS" color="#D8D8D8">'.$x[1].' - Linked by <font color="red">'.$x[2].'</font></font></p>');
?>
<hr>
<body>

<?php 
if(count($split) > 1){
    $url = $split[1];
    $split2 = explode('&',$url);
    if(count($split2) > 1)
        $url = $split2[0];
    print('<p align="center"><iframe width="420" height="315" src="http://www.youtube.com/embed/'.$url.'" frameborder="0" allowfullscreen></iframe></p>'); 
}

?>
<hr>
<p align="center"><a href="http://www.redbrick.dcu.ie/~mak/" target="_blank"><font size="5" face="Comic Sans MS" color="#D8D8D8">Home</font></a></p>
</body>
</html>


