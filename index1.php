<?php
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<link href="style.css.php" rel="stylesheet" type="text/css" />
	<title>Лабораторная установка по исследованию свойств гироскопа с 3-мя степенями свободы.</title>
<script language="JavaScript" type="text/javascript">
//var cortona;
var xmlHttp;
var mass_points_teta = new Array();	//массивы для хранения данных о точках для формирования графиков
var mass_points_psi = new Array();
var stop_timeout;
var delta_polus;
function createXmlHttpRequestObject(){ 
    var xmlHttp; 
    try{ 
        xmlHttp = new XMLHttpRequest(); 
    }catch(e){ 
        var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0","Microsoft.XMLHTTP"); 
        for(var i=0;i < XmlHttpVersions.length && !xmlHttp;i++){ 
            try{ 
                xmlHttp=new ActiveXObject(XmlHttpVersions[i]); 
            }catch(e){} 
        } 
    } 
    if(!xmlHttp){ 
        alert('Ошибка создания объекта XMLHttpRequest.'); 
    }else return xmlHttp; 
}
function init(){
	window.setTimeout("get_ecma_values()",500);
	xmlHttp=createXmlHttpRequestObject();
}
function get_ecma_values(){
	cortona = document.getElementById("scene_vrml").Engine;
	wrlscript=cortona.Nodes("Main_script");
	if(wrlscript.Fields("was_refresh_ecma").value){	//если ECMA обновил значения для JS
		//moment=wrlscript.Fields("proto_moment").value;	
		//m=(Math.ceil(moment*100))/100;
		//document.getElementById("m_"+(Math.floor(m*10)/10)).selected=true;
		wrlscript.Fields("was_refresh_ecma").value=0;
		angle=wrlscript.Fields("arrow_angle").value;
		document.getElementById("angle_m").value=(Math.floor(angle*57.295*100)/100);
	}
	window.setTimeout("get_ecma_values()",500);
}
function set_angle_arrow(){
	cortona = document.getElementById("scene_vrml").Engine;
	if(document.getElementById("angle_m").value){
		cortona.Nodes("Main_script").Fields("js_angle").value=document.getElementById("angle_m").value/57.295;
		cortona.Nodes("Main_script").Fields("was_refresh_angle_js").value=1;
	}
}
function disabled_form(){
	document.getElementById("rotor_n").disabled=true;
	document.getElementById("rotor_j").disabled=true;
	document.getElementById("rotor_way").disabled=true;

	//document.getElementById("time_action").disabled=true;
	document.getElementById("button_start").disabled=true;	
	document.getElementById("button_stop").disabled=false;
	document.getElementById("moment_id").disabled=true;
	document.getElementById("angle_m").disabled=true;
	document.getElementById("int_ramka_j").disabled=true;
	document.getElementById("ext_ramka_j").disabled=true;
	document.getElementById("time_scale").disabled=true;
	
}
function enabled_form(){
	document.getElementById("rotor_n").disabled=false;
	document.getElementById("rotor_j").disabled=false;
	document.getElementById("rotor_way").disabled=false;

	//document.getElementById("time_action").disabled=false;
	document.getElementById("button_start").disabled=false;	
	document.getElementById("button_stop").disabled=true;	
	
	document.getElementById("moment_id").disabled=false;
	document.getElementById("angle_m").disabled=false;
	document.getElementById("int_ramka_j").disabled=false;
	document.getElementById("ext_ramka_j").disabled=false;
	document.getElementById("time_scale").disabled=false;
}
var num_timeout=0; var time_bit=2000;
function check_params(){
	cortona = document.getElementById("scene_vrml").Engine;
	fl_set=false; cycletime_int=0; cycletime_ext=0;
	n=document.getElementById("rotor_n").value;
	j=document.getElementById("rotor_j").value;
	j_int=document.getElementById("int_ramka_j").value;
	j_ext=document.getElementById("ext_ramka_j").value;
	time_scale=document.getElementById("time_scale").value;
	if(!n){ window.alert("Задайте частоту вращения ротора."); return; }
	if(!j){ window.alert("Задайте осевой момент инерции ротора."); return; }
	if(document.getElementById("moment_id").value==0){ window.alert("Задайте момент мгновенных сил."); return; }
	if(!document.getElementById("time_scale").value){ window.alert("Задайте масштаб времени."); return; }
	//if(!document.getElementById("time_action").value){ window.alert("Задайте длительность проведения измерений."); return; }
	moment=document.getElementById("moment_id").value;
	angle=document.getElementById("angle_m").value*(Math.PI/180);
	document.getElementById("params_area").innerHTML="";
	
	//расчёт для внешней рамки
	if(document.getElementById("angle_m").value!=0){
		if(document.getElementById("angle_m").value==90 || document.getElementById("angle_m").value==-90){
			ext_m=moment;	//часть проекции момента действующая по внутренней рамке
		} else {
			ext_m=moment*Math.sin(angle);	//часть проекции момента действующая по внутренней рамке
		}
		if(ext_m){
			tmp_int_w=ext_m*60/(j*2*3.1415926*n);	//угловая скорость движения внешней рамки при действии момента на внутреннюю рамку
			cycletime_int=Math.ceil(1/tmp_int_w);	//период колебания рамки
		}
	} else {
		cycletime_int=0;
		ext_m=0;
	}
	
	//расчёт для внутренней рамки
	if(document.getElementById("angle_m").value!=90 && document.getElementById("angle_m").value!=-90){
		if(document.getElementById("angle_m").value==0){
			int_m=moment;	//часть проекции момента действующая по внешней рамке
		} else {
			int_m=moment*Math.cos(angle);	//часть проекции момента действующая по внешней рамке
		}
		tmp_ext_w=int_m*60/(j*2*Math.PI*n);	//угловая скорость движения внутренней рамки при действии момента на внешнюю рамку
		cycletime_ext=Math.ceil(1/tmp_ext_w);	//период колебания рамки
	} else {
		cycletime_ext=0;
		int_m=0;
	}
	
	//window.alert('внутр. интервал='+cycletime_int+' внутр. момент='+int_m+' внешн. интервал='+cycletime_ext+' внешн. момент='+ext_m);
	document.getElementById("params_area").innerHTML+='<p class="normaltext">Проекция момента на внутр. рамку = '+(Math.ceil(int_m*100)/100)+'&nbsp;Гсм<br />Проекция момента на внешн. рамку ='+(Math.ceil(ext_m*100)/100)+'&nbsp;Гсм</p>';
	
	omega=(2*Math.PI*n)/60;			
	teta_0=ext_m*60/(j*2*Math.PI*n);	//начальная угловая скорость внутр. рамки, которую сообщил момент
	psi_0=int_m*60/(j*2*Math.PI*n);		//начальная угловая скорость внешней. рамки, которую сообщил момент
	//teta_0=0.087;
	//psi_0=0.209;
	//window.alert('teta_0='+teta_0+'  psi_0='+psi_0);
	//document.getElementById("params_area").innerHTML+='<p class="normaltext">Начальная угловая скорость внутр. рамки = '+teta_0+'<br />Начальная угловая скорость внешн. рамки = '+psi_0+'</p>';
	
	//вычислим круговую частоту и период нутационных колебаний
	n_krug=(j*omega)/Math.sqrt(j_int*j_ext);
	//window.alert('n_krug='+n_krug);
	t_nutacii=2*Math.PI/n_krug;
	//window.alert('t_nutacii='+t_nutacii);
	document.getElementById("params_area").innerHTML+='<p class="normaltext">Период нутационных колебаний = '+(Math.ceil(t_nutacii*100000)/100000)+'&nbsp;сек.</p>';
	if(time_scale){
		tmp_time=Math.ceil(((1/time_scale)*(Math.ceil(t_nutacii*100000)/100000))*100000)/100000;
		//window.alert(tmp_time);
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(2,tmp_time.toString());
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(5,tmp_time.toString());
	}
	
	//вычислим значения амплитуд нутационных колебаний
	//j_int=j_в j_ext=j_с
	teta_a=(Math.sqrt(j_ext * (j_ext*psi_0*psi_0 + j_int*teta_0*teta_0) ))/(j*omega);
	//window.alert('teta_a=(Math.sqrt('+j_ext+' * ('+j_ext+'*'+psi_0+'*'+psi_0+' + '+j_int+'*'+teta_0+'*'+teta_0+') ))/('+j+'*'+omega+')='+((Math.sqrt(j_ext * (j_ext*psi_0*psi_0 + j_int*teta_0*teta_0) ))/(j*omega))+';');
	//window.alert('psi_a=(Math.sqrt('+j_int+' * ('+j_ext+'*'+psi_0+'*'+psi_0+' + '+j_int+'*'+teta_0+'*'+teta_0+') ))/('+j+'*'+omega+')='+((Math.sqrt(j_int * (j_ext*psi_0*psi_0 + j_int*teta_0*teta_0) ))/(j*omega))+';');
	psi_a=(Math.sqrt(j_int * (j_ext*psi_0*psi_0 + j_int*teta_0*teta_0) ))/(j*omega);
	//teta_a=0.3;
	//psi_a=0.1;
	//window.alert('teta_a='+teta_a+' psi_a='+psi_a);
	
	//вычислим смещение центра эллипса
	if(angle>0){
		teta_z=(Math.abs(j_ext)*Math.abs(psi_0))/(j*omega);
		psi_z=-(Math.abs(j_int)*Math.abs(teta_0))/(j*omega);
	} else if(angle<0){
		teta_z=(Math.abs(j_ext)*Math.abs(psi_0))/(j*omega);
		psi_z=(Math.abs(j_int)*Math.abs(teta_0))/(j*omega);
	} else {
		teta_z=(Math.abs(j_ext)*Math.abs(psi_0))/(j*omega);
		psi_z=0;
	}
	delta_polus="";
	delta_polus=(teta_z)/3+"#"+(psi_z)/3;
	//window.alert(delta_polus);
	
	document.getElementById("params_area").innerHTML+='<p class="normaltext">Амплитуда внутр. рамки = '+(Math.ceil(teta_a*100000)/100000)+'&nbsp;рад.<br />Амплитуда внешн. рамки = '+(Math.ceil(psi_a*100000)/100000)+'&nbsp;рад.</p>';

	//создаём значения key для интерполятора внешней рамки
	if(teta_a>0.2 || psi_a>0.2){	//степень детализации
		n_parts=256;
	} else if(teta_a>0.1 || psi_a>0.1){
		n_parts=128;
	} else {
		n_parts=64;
	}
	
	var key = new Array();
	/*
	var deltakey=1/n_parts;
	for(i=0;i<=n_parts;i++){
		key.push((Math.floor(deltakey*i*10000))/10000);
	}
	//window.alert(key.join(', '));
	tmp_str=key.join(',');
	*/
	//cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
	//cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(3,tmp_str);
	
	//расчёт параметров для ВНЕШНЕЙ РАМКИ ----------------------------------------------------------------
	a=psi_a;
	b=teta_a;
	//узнаём количество опорных точек на одном участке
	var ox = new Array();	
	count_points=n_parts+1;	
	for(i=(count_points-1)/4-1;i>=1;i--){
		oy=(Math.floor((1/((count_points-1)/4))*i*10000)/10000)*b;
		ox.push( Math.floor(Math.sqrt((a*a*b*b-oy*oy*a*a)/(b*b))*10000)/10000 );	//неравномерное движение
		
	}
	var ox1= new Array(); var ox2= new Array(); var ox3= new Array(); var ox4= new Array();
	for(i=0;i<ox.length;i++){
		ox1.push(ox[i]);
	}
	for(i=0;i<ox.length;i++){
		ox2.push(ox[ox.length-1-i]);
	}
	for(i=0;i<ox.length;i++){
		ox3.push(-ox[i]);
	}
	for(i=0;i<ox.length;i++){
		ox4.push(-ox[ox.length-1-i]);
	}
	//window.alert('0,'+ox1.join(',')+','+(Math.floor(a*10000)/10000)+','+ox2.join(',')+',0,'+ox3.join(',')+','+(-(Math.floor(a*10000)/10000))+','+ox4.join(',')+',0');
	tmp_str='0,'+ox1.join(',')+','+(Math.floor(a*10000)/10000)+','+ox2.join(',')+',0,'+ox3.join(',')+','+(-(Math.floor(a*10000)/10000))+','+ox4.join(',')+',0';
	var g_ox=tmp_str.split(",");
	//window.alert("кол-во g_ox: "+g_ox.length);
	if(!document.getElementById("rotor_way").checked){
		var tmp_reverse=new Array();
		for(i=0;i<g_ox.length;i++){
			tmp_reverse.push(g_ox[g_ox.length-1-i]);
		}
		tmp_str=tmp_reverse.join(",");
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(4,tmp_str);
	} else {
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(4,tmp_str);
	}
	//расчёт параметров для ВНЕШНЕЙ РАМКИ -------------------------------------------------END------------
	
	//расчёт параметров для ВНУТРЕННЕЙ РАМКИ ----------------------------------------------------------------
	a=psi_a;
	b=teta_a;
	//узнаём количество опорных точек на одном участке
	count_points=n_parts+1;
	var oy = new Array();
	//var st=4*b/(count_points-1), last_oy=b, k_sign=-1;
	//oy.push(Math.floor(b*100000)/100000);		// MAX -> (-MAX) -> MAX
	//for(i=0;i<count_points-1;i++){
	//	if(i>=(count_points-1)/2 && k_sign==-1){k_sign=-k_sign;}
	//	oy.push( Math.floor((last_oy+st*k_sign)*100000)/100000 );	//равномерное движение
	//	last_oy=last_oy+st*k_sign;
	//}
	for(i=(count_points-1)/4-1;i>=1;i--){
		ox=(Math.floor((1/((count_points-1)/4))*i*10000)/10000)*a;
		oy.push( Math.floor(Math.sqrt((a*a*b*b-ox*ox*b*b)/(a*a))*10000)/10000 );	//неравномерное движение
		
	}
	//window.alert('new oy= '+oy.join(", "));
	var oy1= new Array(); var oy2= new Array(); var oy3= new Array(); var oy4= new Array();
	for(i=0;i<oy.length;i++){
		oy1.push(oy[oy.length-1-i]);
	}
	for(i=0;i<oy.length;i++){
		oy2.push(-oy[i]);
	}
	for(i=0;i<oy.length;i++){
		oy3.push(-oy[oy.length-1-i]);
	}
	for(i=0;i<oy.length;i++){
		oy4.push(oy[i]);
	}
	tmp_str=(Math.floor(b*10000)/10000)+','+oy1.join(',')+',0,'+oy2.join(',')+','+(-(Math.floor(b*10000)/10000))+','+oy3.join(',')+',0,'+oy4.join(',')+','+(Math.floor(b*10000)/10000);
	var g_oy=tmp_str.split(",");
	//window.alert("кол-во g_oy: "+g_oy.length);
	//window.alert('tmp_str_новый= '+tmp_str);
	cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
	
	var summa=0, keys_mas=new Array();
	for(i=0;i<((g_ox.length-1)/4);i++){	//расчёт суммы длин отрезков траектории эллипса		//window.alert('Math.sqrt(('+g_ox[i+1]+'-'+g_ox[i]+')*('+g_ox[i+1]+'-'+g_ox[i]+')+('+g_oy[i]+'-'+g_oy[i+1]+')*('+g_oy[i]+'-'+g_oy[i+1]+'))'+' = '+Math.sqrt((g_ox[i+1]-g_ox[i])*(g_ox[i+1]-g_ox[i])+(g_oy[i]-g_oy[i+1])*(g_oy[i]-g_oy[i+1])));
		summa+=Math.sqrt((g_ox[i+1]-g_ox[i])*(g_ox[i+1]-g_ox[i])+(g_oy[i]-g_oy[i+1])*(g_oy[i]-g_oy[i+1]));
	}	
	for(i=0;i<(((g_ox.length-1)/4));i++){	//расчёт ключей (доли времени), пропорциональные длинам отрезков траектории элипса
			keys_mas.push(Math.floor((0.25*Math.sqrt((g_ox[i+1]-g_ox[i])*(g_ox[i+1]-g_ox[i])+(g_oy[i]-g_oy[i+1])*(g_oy[i]-g_oy[i+1]))/summa)*100000)/100000);
	}
	//window.alert(keys_mas.join(", ")+" = "+(keys_mas[0]+keys_mas[1]+keys_mas[2]+keys_mas[3]+keys_mas[4]+keys_mas[5]+keys_mas[6]+keys_mas[7]));
	var keys1=new Array(),keys2=new Array(),keys3=new Array(),keys4=new Array();
	keys1.push(0);
	for(i=0;i<keys_mas.length-1;i++){
		if(i==0){
			//keys1.push( keys_mas[keys_mas.length-1-i] );	//стыковка участков отрезков зеркально уч.1
			keys1.push( keys_mas[i] );
		} else {
			//keys1.push( keys1[keys1.length-1] + keys_mas[keys_mas.length-1-i] );	//стыковка участков отрезков зеркально уч.1
			keys1.push( keys1[keys1.length-1] + keys_mas[i] );
		}
	}
	keys2.push(0.25);
	for(i=0;i<keys_mas.length-1;i++){
		if(i==0){
			//keys2.push( 0.25 + keys_mas[i] );	//стыковка участков отрезков зеркально уч.2
			keys2.push( 0.25 + keys_mas[keys_mas.length-1-i] );
		} else {
			//keys2.push( keys2[keys2.length-1] + keys_mas[i] );	//стыковка участков отрезков зеркально уч.2
			keys2.push( keys2[keys2.length-1] + keys_mas[keys_mas.length-1-i] );
		}
	}
	keys3.push(0.5);
	for(i=0;i<keys_mas.length-1;i++){
		if(i==0){
			//keys3.push( 0.5 + keys_mas[keys_mas.length-1-i] );	//стыковка участков отрезков зеркально уч.3
			keys3.push( 0.5 + keys_mas[i] );
		} else {
			//keys3.push( keys3[keys3.length-1] + keys_mas[keys_mas.length-1-i] );	//стыковка участков отрезков зеркально уч.3
			keys3.push( keys3[keys3.length-1] + keys_mas[i] );
		}
	}
	keys4.push(0.75);
	for(i=0;i<keys_mas.length-1;i++){
		if(i==0){
			//keys4.push( 0.75 + keys_mas[i] );	//стыковка участков отрезков зеркально уч.4
			keys4.push( 0.75 + keys_mas[keys_mas.length-1-i] );
		} else {
			//keys4.push( keys4[keys4.length-1] + keys_mas[i] );	//стыковка участков отрезков зеркально уч.4
			keys4.push( keys4[keys4.length-1] + keys_mas[keys_mas.length-1-i] );
		}
	}
	keys4.push(1);	
	keys_str=keys1.join(',')+","+keys2.join(',')+","+keys3.join(',')+","+keys4.join(',');
	//window.alert('keys_str= '+keys_str);
	//window.alert("кол-во oy: "+oy.length+"\n\nкол-во "+keys_str.split(",").length);	
	//window.alert((Math.floor(b*100)/100)+','+oy1.join(',')+','+'0,'+oy2.join(',')+','+(-(Math.floor(b*100)/100))+','+oy3.join(',')+',0,'+oy4.join(',')+','+(Math.floor(b*100)/100));	//неравномерное движение 1
	//window.alert('0,'+oy1.join(',')+','+(-(Math.floor(b*10000)/10000))+','+oy2.join(',')+',0,'+oy3.join(',')+','+(Math.floor(b*10000)/10000)+','+oy4.join(',')+',0');	//неравномерное движение 2	
	
	cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,keys_str);
	cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(3,keys_str);
	//расчёт параметров для ВНУТРЕННЕЙ РАМКИ -------------------------------------------------END------------	
						
	num_timeout=1; num_result=0;
	if(document.getElementById("rotor_way").checked){
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(6,"1");
	} else {
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(6,"0");
	}
	cortona.Nodes("Main_script").Fields("was_set_params_rotation_gyro").value=1;
	document.getElementById("graphics_area_id").innerHTML="";	//убираем предыдущие графики
	mass_points_teta = new Array(); mass_points_psi = new Array();	//обнуляем массивы
	//act_time=document.getElementById("time_action").value*1;
	//stop_timeout=window.setTimeout("stop_action()",(act_time*1000));
	send_points();
	disabled_form();
}
function stop_action(){
	stop_rotation();
	//window.clearTimeout(stop_timeout);
}
function stop_rotation(){
	cortona = document.getElementById("scene_vrml").Engine;
	cortona.Nodes("Main_script").Fields("was_stop").value=1;
	enabled_form();
	//send_points();
}
function send_points(){
	cortona = document.getElementById("scene_vrml").Engine;
	k1=cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").getValue(0);
	kv1=cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").getValue(1);
	k2=cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").getValue(3);
	kv2=cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").getValue(4);
	if(!document.getElementById("rotor_way").checked){
		var g_ox=kv2.split(",");
		var tmp_reverse=new Array();
		for(i=0;i<g_ox.length;i++){
			tmp_reverse.push(g_ox[g_ox.length-1-i]);
		}
		kv2=tmp_reverse.join(",");
	}
	mas_k1=k1.split(',');
	mas_kv1=kv1.split(',');
	mas_k2=k2.split(',');
	mas_kv2=kv2.split(',');
	var mas1_graph = new Array();
	var mas2_graph = new Array();
	for(i=0;i<mas_k1.length;i++){
		mas1_graph.push(mas_k1[i]+','+mas_kv1[i]);
	}
	for(i=0;i<mas_k2.length;i++){
		mas2_graph.push(mas_k2[i]+','+mas_kv2[i]);
	}
	str_send="points1="+encodeURIComponent(mas1_graph.join('#'))+"&points2="+encodeURIComponent(mas2_graph.join('#'))+"&polus="+encodeURIComponent(delta_polus);
	//window.alert(str_send);
    if(xmlHttp){ 
        try{
            xmlHttp.open("post","get_img1.php", true); 
			xmlHttp.setRequestHeader("Cache-Control", "no-store, no-cache, must-revalidate");
		    xmlHttp.setRequestHeader("Pragma", "no-cache");
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange=img_res;
			xmlHttp.setRequestHeader('Content-Length', str_send.length); 
			xmlHttp.send(str_send);
        }catch(e){ 
            alert("Невозможно соединиться с сервером:\n" + e.toString()); 
        } 
    } 
}
function img_res(vall){
	if(xmlHttp.readyState == 4){ 
        if(xmlHttp.status==200){ 
            try{ <?php //пришёл ответ ?>
    			var result = xmlHttp.responseText;
				document.getElementById("graphics_area_id").innerHTML=result;
   			}catch(e){
                alert("Ошибка чтения ответа: " + e.toString()); 
            } 
  		} 
	}
}
</script>
</head>
<body onload="init()">
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" valign="top">
<div class="container">
	<div class="general">
		<div><p class="normaltext"><a href="index.php" class="normaltext">Закон прецессии</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="text-decoration: underline; font-weight:600">Нутационные колебания</font></p></div>
		<table cellpadding="0" cellspacing="0" border="0"><tr><td align="left" valign="top">
		<div class="vrml_scene" id="divc">
			<embed id="scene_vrml" codebase="http://www.parallelgraphics.com/bin/cortvrml.cab#Version=4,2,0,93" vrml_dashboard="false" RendererName="DirectX Renderer" RendererHints="5664" HEADLIGHT="TRUE" CONTEXTMENU="TRUE" VRML_SPLASHSCREEN="false" width="100%" height="100%" src="http://www.diplom.ru/gyro1.wrl">
		</div>
		</td>
		<td width="10"></td>
		<td align="left" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<div style="width: 480px">
							<form>
								<p class="normaltext">
								Число оборотов ротора:&nbsp;<input type="Text" id="rotor_n" maxlength="5" value="800" style="width:50" />&nbsp;в минуту<br />
								</p>
								<p class="normaltext">
								Осевой момент инерции:&nbsp;<input type="Text" id="rotor_j" maxlength="5" value="0.7" style="width:50" />&nbsp;Гсмсек2<br />
								Вращение ротора по часовой стрелке:&nbsp;<input type="Checkbox" id="rotor_way" style="cursor: pointer" /><br />
								Момент инерции гироскопа относ. внутренней оси:&nbsp;<input type="Text" id="int_ramka_j" maxlength="5" value="0.6" style="width:50" />&nbsp;Гсмсек2<br />
								Момент инерции гироскопа относ. наружной оси:&nbsp;<input type="Text" id="ext_ramka_j" maxlength="5" value="1.8" style="width:50" />&nbsp;Гсмсек2
								</p>
								<p class="normaltext">
								Угол воздействия момента мгновенных сил:&nbsp;<input type="Text" id="angle_m" value="0" onkeyup="set_angle_arrow()" maxlength="3" value="" style="width:50" /><br />
								Момент мгновенных сил:&nbsp;<select name="moment" id="moment_id" style="width:50" class="normaltext">
									<option id="m_0" value="0" />0
			<?php
								for($i=100;$i<520;$i=$i+20){
			?>
									<option id="m_<?php echo $i; ?>" value="<?php echo $i; ?>" /><?php echo $i; ?>
			<?php
								}
			?>
								</select>&nbsp;Гсм
								</p>
						</div>
					</td>
				</tr>
				<tr>
					<td height="40"></td>
				</tr>
				<tr>
					<td>
						<p class="normaltext">Масштаб времени:<br />1 сек. =&nbsp;<input type="Text" id="time_scale" maxlength="4" value="0.1" style="width:45" />&nbsp;сек. в виртуальном пространстве</p>
						<!-- <p class="normaltext">Длительность проведения измерений:&nbsp;<input type="Text" id="time_action" maxlength="4" value="20" style="width:45" />&nbsp;сек.</p><br /> -->
						<input type="Button" value="пуск" id="button_start" style="cursor: pointer" onclick="check_params()" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="Button" value="стоп" disabled id="button_stop" style="cursor: pointer" onclick="stop_rotation()" />
					</td>
				</tr>
				<tr>
					<td id="params_area">
					</td>
				</tr>
			</table>
		</td>
		</tr>
		<tr>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td id="graphics_area_id" align="center" valign="top"></td>
				</tr>
			</table>
		</tr>
			</form>
		</table>
	</div>
</div>
</td></tr></table>
</body>
</html>
