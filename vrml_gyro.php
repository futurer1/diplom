<?php
header("Content-type: model/vrml");
$fp="gyro.wrl";
$fp=@fopen ($fn,"r");		//читаем файл
$vrml_data=fread($fp,filesize($fn));
fclose($fp);
echo $vrml_data;
?>