<!DOCTYPE html><html><head><title>Link Search</title><link rel="stylesheet" type="text/css" media="screen,projection" href="Link.css" /></head><body background="bg.jpg" bgcolor="#B0B0B0"><h1 align="center"><font face="Comic Sans MS" color="#D8D8D8">Search for Links!</font></h1><hr>
 <form action="link.php" method="post" align="center">
 <font face="Comic Sans MS" color="#D8D8D8">Search for: </font><input type="text" name="term" /> <font face="Comic Sans MS" color="#D8D8D8">in </font>
 <Select NAME="field">
 <Option VALUE="title">Title</option>
 <Option VALUE="user">Username</option>
 <Option VALUE="link">Link-Url</option>
 <Option VALUE="date">Date(YYYY-MM-DD)</option>
 <Option VALUE="all">All</option>
 </Select>
         <input type="Submit" name="Submit" value="Submit" />
 </form>
<?php
mysql_connect ("mysql.server", "username","password")  or die (mysql_error());
mysql_select_db ("database-name");
$term = $_POST['term'];
$field = $_POST['field'];
$xx = False;
$term = mysql_real_escape_string($term);
$field = mysql_real_escape_string($field);
$sql = mysql_query("select * from links where $field like '%$term%' order by date desc");
if($field == 'date'){
    $sql = mysql_query("select * from links where date like '%$term%' order by date desc");
}
if($field == 'all'){
    $sql = mysql_query("select * from links where `link` like '%$term%' or `title` like '%$term%' or `user` like '%$term%' order by `date` desc");
}
if (isset($_POST['Submit'])){
    if($term == ''){
        print('<p align="center"><font face="Comic Sans MS" color="RED">ERROR: Please enter a search value!</font></p>');
        $xx = True;
    }
    $header = 0;
    $j = mysql_num_rows($sql);
    if($j > 0){
        $header = 1;
    }
    if($xx == False){
        if($header > 0){
            echo'<table align ="center" cellspacing="1"><tr><th>Date/Time</th><th>Link</th><th>Title</th><th>Username</th></tr>';
        }
        if($header == 0){
            print('<p align="center"><font face="Comic Sans MS" color="RED">ERROR: Nothing found!</font></p>');
        }
        while($rowx = mysql_fetch_array($sql)){
           echo '<tr><td>'.$rowx['date'].'</td>';
           echo '<td><a href="'.$rowx['link'].'">'.$rowx['link'].'</a></td>';
           echo '<td>'.$rowx['title'].'</td>';
           echo '<td>'.$rowx['user'].'</td>';
           echo '</tr>';
        }
    }
}
?>
<hr style="clear:both"><h2 align="center"><font face="Comic Sans MS" color="#D8D8D8">Link Stats</font></h2><hr>
<?php

    $stat1 = mysql_query("select * from links");
    $z = mysql_num_rows($stat1);
    echo'<p align="center"><font face="Comic Sans MS" color="#D8D8D8">Total number of Links: '.$z.'</font></p>';
    $stat2 = mysql_query("select * from links where count = 0");
    $r = mysql_num_rows($stat2);
    echo'<p align="center"><font face="Comic Sans MS" color="#D8D8D8">Total number of Images: '.$r.'</font></p>';
    
    $stat2 = mysql_query("select * from links where link like '%youtu%'");
    $zz = mysql_num_rows($stat2);
    $stat4 = mysql_query("select * from links where link like '%imgur%'");
    $zzz =  mysql_num_rows($stat4);
    
    echo '<p align="center"><font face="Comic Sans MS" color="#D8D8D8">Youtube:'.$zz.'&nbsp;&nbsp;&nbsp;Imgur:'.$zzz.'</font></p>';
    
    $stat5 = mysql_query("select * from links group by user order by count(user) desc limit 1");
    $theUser = mysql_fetch_array($stat5);
    $rrr = $theUser['user'];
    $stat5 =  mysql_query("select * from links where user like '".$rrr."'");
    $zzzz = mysql_num_rows($stat5);
    print('<p align="center"><font face="Comic Sans MS" color="#D8D8D8">Biggest Linker:&nbsp;&nbsp;'.$rrr.' with '.$zzz.' links.</font></p>');
    
     $stat6 = mysql_query("select * from links where date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY) group by user order by count(user) desc limit 1");
    $User = mysql_fetch_array($stat6);
    $rrrr = $User['user'];
    $stat6 =  mysql_query("select * from links where user like '".$rrrr."' and date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)");
    $zzzzz = mysql_num_rows($stat6);
    print('<p align="center"><font face="Comic Sans MS" color="#D8D8D8">Biggest Linker today:&nbsp;&nbsp;'.$rrrr.' with '.$zzzzz.' links</font></p>');
    
?>

<p align="center"><a href="http://www.redbrick.dcu.ie/~mak/" target="_blank"><font size="5" face="Comic Sans MS" color="#D8D8D8">Home</font></a></p>


</body>
</html>



