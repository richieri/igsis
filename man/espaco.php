<?php
function foldersize($dir){
 $count_size = 0;
 $count = 0;
 $dir_array = scandir($dir);
 foreach($dir_array as $key=>$filename){
  if($filename!=".." && $filename!="."){
   if(is_dir($dir."/".$filename)){
    $new_foldersize = foldersize($dir."/".$filename);
    $count_size = $count_size + $new_foldersize[0];
    $count = $count + $new_foldersize[1];
   }else if(is_file($dir."/".$filename)){
    $count_size = $count_size + filesize($dir."/".$filename);
    $count++;
   }
  }

 }

 return $count_size;
}

$dir = '/var/www/html/igsisv1';
$free = disk_free_space($dir);
$total = disk_total_space($dir);
$free_to_kbs = $free / (1024*1024*1024);
$total_to_kbs = $total / (1024*1024*1024);


$relatorio .= "<h2>Gerenciamento de espaço no Servidor</h2>";
$uploads = (int)(foldersize("/var/www/html/igsis/uploads")/1024);
$uploadsdocs = (int)(foldersize("/var/www/html/igsis/uploadsdocs")/1024);
$sql = (int)(foldersize("/var/www/html/igsis/sql")/1024);
$relatorio .= "<p> A pasta <b>uploads</b> possui $uploads kb (".(int)($uploads/1024)." Mb)</p>";
$relatorio .= "<p> A pasta <b>uploadsdocs</b> possui $uploadsdocs kb (".(int)($uploadsdocs/1024)." Mb)</p>";
$relatorio .= "<p> A pasta <b>sql (backups)</b> possui $sql kb (".(int)($sql/1024)." Mb)</p>";
$relatorio .= "<p>'Espaço livre: ".$free_to_kbs." Mbs de ".$total_to_kbs." Mbs em disco</p>";
?>
