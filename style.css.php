<?php Header("Content-type: text/css"); 
//include("values_sql.php");
//$k=10; while(!($dblink = mysql_pconnect($db_host,$db_login,$db_passw))){ if(!$k--) break; } if($k){mysql_select_db($db_name,$dblink);}
?>
* {margin: 0; padding: 0}
html {height: 100%}
body { 
	height: auto !important;
  	min-height: 100%;
	margin: 0 auto; padding: 0; background-color: #ffffff;
<?php if(false){ ?>
	scrollbar-base-color: #518789;
	scrollbar-highlight-color:#004f5d;
	scrollbar-3dlight-color:#004f5d;
	scrollbar-arrow-color:#ffffff;
	scrollbar-shadow-color:#004f5d;
	scrollbar-darkshadow-color:#004f5d;
	scrollbar-track-color:#518789;
<?php } ?>
}
table,tr,td,tbody,thead {border-collapse: collapse}
span { white-space: nowrap }
img,embed {border: 0}
p,h5,h6,span,em,ul,li {cursor: default; font-style: normal; font-family: Verdana, Arial; padding: 0}
textarea,input,a {font-style: normal; font-family: Verdana, Arial; padding: 0; text-decoration: none}
a:link img, a:visited img { border-style: none }
a {cursor:pointer}

.container {background-color: #ffffff; position: relative; display: table; margin: 0 auto; padding: 0 0}
.body {position: absolute; margin: 0 auto}
.general {width: 990px; position: relative; visibility: visible; background-color: #f7f7f7; border: 0px solid #000}
.vrml_scene {width: 500px; height: 500px; border: 1px solid #c0c0c0}

.normaltext {color: #000000; font-size: 13px; font-weight: 400; text-align:justify; line-height: 20px; letter-spacing: -1px;}
.normaltext_c {color: #000000; font-size: 13px; font-weight: 400; text-align:center; line-height: 20px; letter-spacing: -1px;}