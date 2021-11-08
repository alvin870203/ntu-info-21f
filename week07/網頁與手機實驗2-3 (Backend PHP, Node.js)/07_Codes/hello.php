<?php 
 $NAME=$_GET['name']; 
 $IP=$_SERVER['REMOTE_ADDR'];
 echo $_GET['greet']." $NAME from $IP <br>";
 exec("python py_backend.py $NAME",$out);
 exec("echo $NAME >> data.txt");  // append to data.txt
 for($i=0;$i<count($out);$i++){
  echo $out[$i].'<br>';
 }
?>
