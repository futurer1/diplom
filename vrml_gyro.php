<?php
header("Content-type: model/vrml");
$fp="gyro.wrl";
$fp=@fopen ($fn,"r");		//������ ����
$vrml_data=fread($fp,filesize($fn));
fclose($fp);
echo $vrml_data;
?>