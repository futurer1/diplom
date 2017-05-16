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
	//document.getElementById('divc').innerHTML = '<embed id="scene_vrml" codebase="http://www.parallelgraphics.com/bin/cortvrml.cab#Version=4,2,0,93" vrml_dashboard="false" RendererName="DirectX Renderer" RendererHints="5664" HEADLIGHT="TRUE" CONTEXTMENU="TRUE" VRML_SPLASHSCREEN="false" width="100%" height="100%" src="http://www.diplom.ru/gyro.wrl">';
	window.setTimeout("get_moments_value()",500);
	xmlHttp=createXmlHttpRequestObject();
	//window.alert(cortona.Viewpoints("mainview_cam").Fields("orientation").angle);
}
function get_moments_value(){
	//try{
		cortona = document.getElementById("scene_vrml").Engine;
		wrlscript=cortona.Nodes("Main_script");
		if(wrlscript.Fields("was_refresh_ecma_int").value){	//если ECMA обновил значения для JS
			int_m=wrlscript.Fields("proto_int_moment").value;
			int_tmp=int_m.split(",");		
			m=(Math.ceil(int_tmp[1]*100))/100;
			document.getElementById("int_m_"+(Math.floor(m*10)/10)).selected=true;
			if(int_tmp[0]!=0){
				document.getElementById("int_ramka"+int_tmp[0]+"_id").checked=true;
			}
			wrlscript.Fields("was_refresh_ecma_int").value=0;
		}
		if(wrlscript.Fields("was_refresh_ecma_ext").value){	//если ECMA обновил значения для JS
			ext_m=wrlscript.Fields("proto_ext_moment").value;
			ext_tmp=ext_m.split(",");		
			m=(Math.ceil(ext_tmp[1]*100))/100;
			document.getElementById("ext_m_"+(Math.floor(m*10)/10)).selected=true;
			if(ext_tmp[0]!=0){
				document.getElementById("ext_ramka"+ext_tmp[0]+"_id").checked=true;
			}
			wrlscript.Fields("was_refresh_ecma_ext").value=0;
		}	
	//} catch(e){}
	window.setTimeout("get_moments_value()",500);
}
function set_int_moment(){
	cortona = document.getElementById("scene_vrml").Engine;
	if(document.getElementById("int_ramka1_id").checked){
		cortona.Nodes("Main_script").Fields("js_int_moment").value="1,"+document.getElementById("int_ramka_moment_id").value;
	} else if(document.getElementById("int_ramka2_id").checked){
		cortona.Nodes("Main_script").Fields("js_int_moment").value="2,"+document.getElementById("int_ramka_moment_id").value;
	} else if(document.getElementById("int_ramka3_id").checked){
		cortona.Nodes("Main_script").Fields("js_int_moment").value="3,"+document.getElementById("int_ramka_moment_id").value;
	} else if(document.getElementById("int_ramka4_id").checked){
		cortona.Nodes("Main_script").Fields("js_int_moment").value="4,"+document.getElementById("int_ramka_moment_id").value;
	}
	cortona.Nodes("Main_script").Fields("was_refresh_js_int").value=1;
}
function set_ext_moment(){
	cortona = document.getElementById("scene_vrml").Engine;
	if(document.getElementById("ext_ramka1_id").checked){
		cortona.Nodes("Main_script").Fields("js_ext_moment").value="1,"+document.getElementById("ext_ramka_moment_id").value;
	} else if(document.getElementById("ext_ramka2_id").checked){
		cortona.Nodes("Main_script").Fields("js_ext_moment").value="2,"+document.getElementById("ext_ramka_moment_id").value;
	} else if(document.getElementById("ext_ramka3_id").checked){
		cortona.Nodes("Main_script").Fields("js_ext_moment").value="3,"+document.getElementById("ext_ramka_moment_id").value;
	} else if(document.getElementById("ext_ramka4_id").checked){
		cortona.Nodes("Main_script").Fields("js_ext_moment").value="4,"+document.getElementById("ext_ramka_moment_id").value;
	}
	cortona.Nodes("Main_script").Fields("was_refresh_js_ext").value=1;
}
function set_int_ramka(val){
	cortona = document.getElementById("scene_vrml").Engine;
	if(document.getElementById("int_ramka_moment_id").value){
		cortona.Nodes("Main_script").Fields("js_int_moment").value=val+","+document.getElementById("int_ramka_moment_id").value;
		cortona.Nodes("Main_script").Fields("was_refresh_js_int").value=1;
	}
}
function set_ext_ramka(val){
	cortona = document.getElementById("scene_vrml").Engine;
	if(document.getElementById("ext_ramka_moment_id").value){
		cortona.Nodes("Main_script").Fields("js_ext_moment").value=val+","+document.getElementById("ext_ramka_moment_id").value;
		cortona.Nodes("Main_script").Fields("was_refresh_js_ext").value=1;
	}
}
function disabled_form(){
	document.getElementById("rotor_n").disabled=true;
	document.getElementById("rotor_j").disabled=true;
	document.getElementById("rotor_way").disabled=true;
	document.getElementById("int_ramka_moment_id").disabled=true;
	document.getElementById("int_ramka1_id").disabled=true;
	document.getElementById("int_ramka2_id").disabled=true;
	document.getElementById("int_ramka3_id").disabled=true;
	document.getElementById("int_ramka4_id").disabled=true;
	
	document.getElementById("ext_ramka_moment_id").disabled=true;
	document.getElementById("ext_ramka1_id").disabled=true;
	document.getElementById("ext_ramka2_id").disabled=true;
	document.getElementById("ext_ramka3_id").disabled=true;
	document.getElementById("ext_ramka4_id").disabled=true;

	document.getElementById("time_scale").disabled=true;
	document.getElementById("delta_measurements").disabled=true;
	document.getElementById("time_action").disabled=true;
	document.getElementById("button_start").disabled=true;	
	document.getElementById("button_stop").disabled=false;	
}
function enabled_form(){
	document.getElementById("rotor_n").disabled=false;
	document.getElementById("rotor_j").disabled=false;
	document.getElementById("rotor_way").disabled=false;
	document.getElementById("int_ramka_moment_id").disabled=false;
	document.getElementById("int_ramka1_id").disabled=false;
	document.getElementById("int_ramka2_id").disabled=false;
	document.getElementById("int_ramka3_id").disabled=false;
	document.getElementById("int_ramka4_id").disabled=false;
	
	document.getElementById("ext_ramka_moment_id").disabled=false;
	document.getElementById("ext_ramka1_id").disabled=false;
	document.getElementById("ext_ramka2_id").disabled=false;
	document.getElementById("ext_ramka3_id").disabled=false;
	document.getElementById("ext_ramka4_id").disabled=false;

	document.getElementById("time_scale").disabled=false;
	document.getElementById("delta_measurements").disabled=false;
	document.getElementById("time_action").disabled=false;
	document.getElementById("button_start").disabled=false;	
	document.getElementById("button_stop").disabled=true;	
}
 var num_timeout=0; var time_bit=2000;
function check_params(){
	cortona = document.getElementById("scene_vrml").Engine;
	fl_set=false; cycletime_int=0; cycletime_ext=0;
	n=document.getElementById("rotor_n").value;
	j=document.getElementById("rotor_j").value;
	if(document.getElementById("int_ramka_moment_id").value!=0){
		m_int=document.getElementById("int_ramka_moment_id").value;
		m_int=m_int*1;	//перевод в численный вид
	} else { m_int=0; }
	if(document.getElementById("ext_ramka_moment_id").value!=0){
		m_ext=document.getElementById("ext_ramka_moment_id").value;
		m_ext=m_ext*1;	//перевод в численный вид
	} else { m_ext=0; }
	if( m_int==0 &&  m_ext==0 ){ window.alert("Задайте момент минимум на одной рамке."); return;	}
	if(!n){ window.alert("Задайте частоту вращения ротора."); return; }
	if(!j){ window.alert("Задайте осевой момент инерции ротора."); return; }
	if(!document.getElementById("time_scale").value){ window.alert("Задайте масштаб времени."); return; }
	if(!document.getElementById("time_action").value){ window.alert("Задайте длительность проведения измерений."); return; }
	
	
	//вычисляем компенсацию моментов, их взаимо поглощение и взаимо суммирование
	if(m_int && m_ext){
		int_point=0;
		if(document.getElementById("int_ramka1_id").checked){
			int_point=1;
		} else if(document.getElementById("int_ramka2_id").checked){
			int_point=2;
		} else if(document.getElementById("int_ramka3_id").checked){
			int_point=3;
		} else if(document.getElementById("int_ramka4_id").checked){
			int_point=4;
		}
		ext_point=0;
		if(document.getElementById("ext_ramka1_id").checked){
			ext_point=1;
		} else if(document.getElementById("ext_ramka2_id").checked){
			ext_point=2;
		} else if(document.getElementById("ext_ramka3_id").checked){
			ext_point=3;
		} else if(document.getElementById("ext_ramka4_id").checked){
			ext_point=4;
		}
		was_compence_moms=0;	//переменная означающая, что моменты есть, но они компенсировались, поэтому запустить ротор 
									//всёравно нужно при нажатии на кнопку "пуск"
		m_int_res=0; m_ext_res=0;
		if(!document.getElementById("rotor_way").checked){	//если вращение ротора против часовой стрелки (обычное)
			if(m_int > m_ext){	//если момент на внутренней рамке больше
				if( (ext_point==1 && int_point==1) || (ext_point==1 && int_point==4) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==1 && int_point==2) || (ext_point==1 && int_point==3) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==2 && int_point==1) || (ext_point==2 && int_point==4) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==2 && int_point==2) || (ext_point==2 && int_point==3) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==3 && int_point==1) || (ext_point==3 && int_point==4) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==3 && int_point==2) || (ext_point==3 && int_point==3) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==4 && int_point==1) || (ext_point==4 && int_point==4) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==4 && int_point==2) || (ext_point==4 && int_point==3) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				}				
			} else if(m_int < m_ext){	//если момент на внешней рамке больше				
				if( (ext_point==1 && int_point==1) || (ext_point==1 && int_point==4) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				} else if( (ext_point==1 && int_point==2) || (ext_point==1 && int_point==3) ){
					m_ext_res = m_int + m_ext;
					m_int_res = 0;
				} else if( (ext_point==2 && int_point==1) || (ext_point==2 && int_point==4) ){
					m_ext_res = m_int + m_ext;
					m_int_res = 0;
				} else if( (ext_point==2 && int_point==2) || (ext_point==2 && int_point==3) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				} else if( (ext_point==3 && int_point==1) || (ext_point==3 && int_point==4) ){
					m_ext_res = m_int + m_ext;
					m_int_res = 0;
				} else if( (ext_point==3 && int_point==2) || (ext_point==3 && int_point==3) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				} else if( (ext_point==4 && int_point==1) || (ext_point==4 && int_point==4) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				} else if( (ext_point==4 && int_point==2) || (ext_point==4 && int_point==3) ){
					m_ext_res = m_int + m_ext;
					m_int_res = 0;
				}
			} else {	//если моменты на внутренней и внешней рамках равны
				if( (ext_point==1 && int_point==1) || (ext_point==1 && int_point==4) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==1 && int_point==2) || (ext_point==1 && int_point==3) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==2 && int_point==1) || (ext_point==2 && int_point==4) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==2 && int_point==2) || (ext_point==2 && int_point==3) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==3 && int_point==1) || (ext_point==3 && int_point==4) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==3 && int_point==2) || (ext_point==3 && int_point==3) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==4 && int_point==1) || (ext_point==4 && int_point==4) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==4 && int_point==2) || (ext_point==4 && int_point==3) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				}
			}
		} else {	//если ротор крутится по часовой стрелке
			if(m_int > m_ext){	//если момент на внутренней рамке больше
				if( (ext_point==1 && int_point==1) || (ext_point==1 && int_point==4) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==1 && int_point==2) || (ext_point==1 && int_point==3) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==2 && int_point==1) || (ext_point==2 && int_point==4) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==2 && int_point==2) || (ext_point==2 && int_point==3) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==3 && int_point==1) || (ext_point==3 && int_point==4) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				} else if( (ext_point==3 && int_point==2) || (ext_point==3 && int_point==3) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==4 && int_point==1) || (ext_point==4 && int_point==4) ){
					m_int_res=m_int + m_ext;
					m_ext_res=0;
				} else if( (ext_point==4 && int_point==2) || (ext_point==4 && int_point==3) ){
					m_int_res=m_int - m_ext;
					m_ext_res=0;
				}
			} else if(m_int < m_ext){	//если момент на внешней рамке больше				
				if( (ext_point==1 && int_point==1) || (ext_point==1 && int_point==4) ){
					m_ext_res = m_ext + m_int;
					m_int_res = 0;
				} else if( (ext_point==1 && int_point==2) || (ext_point==1 && int_point==3) ){
					m_ext_res = m_ext - m_int;
					
					m_int_res = 0;
				} else if( (ext_point==2 && int_point==1) || (ext_point==2 && int_point==4) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				} else if( (ext_point==2 && int_point==2) || (ext_point==2 && int_point==3) ){
					m_ext_res = m_ext + m_int;
					m_int_res = 0;
				} else if( (ext_point==3 && int_point==1) || (ext_point==3 && int_point==4) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				} else if( (ext_point==3 && int_point==2) || (ext_point==3 && int_point==3) ){
					m_ext_res = m_ext + m_int;
					m_int_res = 0;
				} else if( (ext_point==4 && int_point==1) || (ext_point==4 && int_point==4) ){
					m_ext_res = m_ext + m_int;
					m_int_res = 0;
				} else if( (ext_point==4 && int_point==2) || (ext_point==4 && int_point==3) ){
					m_ext_res = m_ext - m_int;
					m_int_res = 0;
				}
			} else {	//если моменты на внутренней и внешней рамках равны
				if( (ext_point==1 && int_point==1) || (ext_point==1 && int_point==4) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==1 && int_point==2) || (ext_point==1 && int_point==3) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==2 && int_point==1) || (ext_point==2 && int_point==4) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==2 && int_point==2) || (ext_point==2 && int_point==3) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==3 && int_point==1) || (ext_point==3 && int_point==4) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				} else if( (ext_point==3 && int_point==2) || (ext_point==3 && int_point==3) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==4 && int_point==1) || (ext_point==4 && int_point==4) ){
					m_ext_res = m_ext;
					m_int_res = m_int;
				} else if( (ext_point==4 && int_point==2) || (ext_point==4 && int_point==3) ){
					m_ext_res = 0;
					m_int_res = 0;
					was_compence_moms=1;
				}
			}
		}	// END of else	(если ротор крутится по часовой стрелке)
		m_int=m_int_res;
		m_ext=m_ext_res;
	}	// END of if(m_int && m_ext)
	
	
	if( (m_int && !m_ext) || (m_int==m_ext && m_int && m_ext) ){	//если есть момент только на внутренней рамке
																	//или случай, когда моменты на обоих рамках и равны
		tmp_int_w=m_int*60/(j*2*3.1415926*n);
		//tmp_int_w=tmp_int_w/3600/57.3;	//перевели скорость вращения в радианы в секунду
		cycletime_int=1/tmp_int_w;
		if(document.getElementById("time_scale").value){	//если задан масштаб времени
			//учитываем масштаб времени
			time_scale=document.getElementById("time_scale").value;
			cycletime_int=cycletime_int/time_scale;
		} else {
			time_scale=1;
		}
		cycletime_int=Math.ceil(cycletime_int);
		if(!document.getElementById("rotor_way").checked){	//если вращение ротора против часовой стрелки
			if(document.getElementById("int_ramka1_id").checked){
				tmp_str='0,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			} else if(document.getElementById("int_ramka2_id").checked){
				tmp_str='1,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			} else if(document.getElementById("int_ramka3_id").checked){
				tmp_str='1,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			} else if(document.getElementById("int_ramka4_id").checked){
				tmp_str='0,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			}
		} else {	//если вращение ротора по часовой стрелке
			if(document.getElementById("int_ramka1_id").checked){
				tmp_str='1,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			} else if(document.getElementById("int_ramka2_id").checked){
				tmp_str='0,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			} else if(document.getElementById("int_ramka3_id").checked){
				tmp_str='0,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			} else if(document.getElementById("int_ramka4_id").checked){
				tmp_str='1,'+cycletime_int;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,tmp_str);
			}
		}
		fl_set=true;
	} else {	//если нет момента на внутренней рамке
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(1,"0,0");
	}
	if( (m_ext && !m_int) || (m_int==m_ext && m_int && m_ext) ){	//если есть момент только на внешней рамке
		tmp_ext_w=m_ext*60/(j*2*3.1415926*n);
		cycletime_ext=1/tmp_ext_w;
		if(document.getElementById("time_scale").value){
			//учитываем масштаб времени
			time_scale=document.getElementById("time_scale").value;
			cycletime_ext=cycletime_ext/time_scale;
		} else {
			time_scale=1;
		}
		cycletime_ext=Math.ceil(cycletime_ext);
		if(!document.getElementById("rotor_way").checked){	//если вращение ротора против часовой стрелки
			if(document.getElementById("ext_ramka1_id").checked){
				tmp_str='0,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			} else if(document.getElementById("ext_ramka2_id").checked){
				tmp_str='1,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			} else if(document.getElementById("ext_ramka3_id").checked){
				tmp_str='1,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			} else if(document.getElementById("ext_ramka4_id").checked){
				tmp_str='0,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			}
		} else {	//если вращение ротора по часовой стрелке
			if(document.getElementById("ext_ramka1_id").checked){
				tmp_str='1,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			} else if(document.getElementById("ext_ramka2_id").checked){
				tmp_str='0,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			} else if(document.getElementById("ext_ramka3_id").checked){
				tmp_str='0,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			} else if(document.getElementById("ext_ramka4_id").checked){
				tmp_str='1,'+cycletime_ext;
				cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,tmp_str);
			}
		}
		fl_set=true;
	} else {	//если нет момента на внешней рамке
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(0,"0,0");
	}
	if(document.getElementById("rotor_way").checked){
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(2,"1");
	} else {
		cortona.Nodes("Main_script").Fields("js_params_rotation_gyro").setValue(2,"0");
	}
	if(fl_set || was_compence_moms){	
		cortona.Nodes("Main_script").Fields("was_set_params_rotation_gyro").value=1;
		disabled_form();
		//document.getElementById("rotation_info").innerHTML="";
		document.getElementById("rotation_info1").innerHTML="";
		if(cycletime_int && (Math.ceil((1/cycletime_int/time_scale)*1000000)/100)>0){
			if( (!document.getElementById("rotor_way").checked && 
				(document.getElementById("int_ramka1_id").checked || document.getElementById("int_ramka3_id").checked) 
			    ) || 
				(document.getElementById("rotor_way").checked && 
				(document.getElementById("int_ramka2_id").checked || document.getElementById("int_ramka4_id").checked) 
				) 
			){
				document.getElementById("rotation_info1").innerHTML+="<br />Вращение внешней рамки против часовой стрелки.";
			}
			if( (!document.getElementById("rotor_way").checked && 
				(document.getElementById("int_ramka2_id").checked || document.getElementById("int_ramka4_id").checked) 
			    ) || 
				(document.getElementById("rotor_way").checked && 
				(document.getElementById("int_ramka1_id").checked || document.getElementById("int_ramka3_id").checked) 
				) 
			){
				document.getElementById("rotation_info1").innerHTML+="<br />Вращение внешней рамки по часовой стрелке.";
			}
			document.getElementById("rotation_info1").innerHTML+="<br />Величина угловой скорости вращения внешней рамки: "+(Math.ceil((1/cycletime_int/time_scale)*1000000)/100)+"&nbsp;*&nbsp;<img width=\"24\" height=\"15\" src=\"imgs/104.gif\" /> град./час";
		} else {
			document.getElementById("rotation_info1").innerHTML+="<br />Величина угловой скорости вращения внешней рамки: 0 град./час<br />";
		}
		
		if(cycletime_ext && (Math.ceil((1/cycletime_ext/time_scale)*1000000)/100)>0){
			if( (!document.getElementById("rotor_way").checked && 
				(document.getElementById("ext_ramka1_id").checked || document.getElementById("ext_ramka4_id").checked) 
			    ) || 
				(document.getElementById("rotor_way").checked && 
				(document.getElementById("ext_ramka2_id").checked || document.getElementById("ext_ramka3_id").checked) 
				) 
			){
				document.getElementById("rotation_info1").innerHTML+="<br />Вращение внутренней рамки против часовой стрелки.";
			}
			if( (!document.getElementById("rotor_way").checked && 
				(document.getElementById("ext_ramka2_id").checked || document.getElementById("ext_ramka3_id").checked) 
			    ) || 
				(document.getElementById("rotor_way").checked && 
				(document.getElementById("ext_ramka1_id").checked || document.getElementById("ext_ramka4_id").checked) 
				) 
			){
				document.getElementById("rotation_info1").innerHTML+="<br />Вращение внутренней рамки по часовой стрелке.";
			}
			document.getElementById("rotation_info1").innerHTML+="<br />Величина угловой скорости вращения внутренней рамки: "+(Math.ceil((1/cycletime_ext/time_scale)*1000000)/100)+"&nbsp;*&nbsp;<img width=\"24\" height=\"15\" src=\"imgs/104.gif\" /> град./час";
		} else {
			document.getElementById("rotation_info1").innerHTML+="<br />Величина угловой скорости вращения внутренней рамки: 0 град./час<br />";
		}
		
		if( (cycletime_int && (Math.ceil((1/cycletime_int/time_scale)*1000000)/100)>0) || (cycletime_ext && (Math.ceil((1/cycletime_ext/time_scale)*1000000)/100)>0) ){	//если есть угловые скорости вращения, то запускаем измеритель углов
			
			document.getElementById("result_area_td").innerHTML='<table cellpadding="0" cellspacing="0" border="0"><tr><td width="30"></td><td width="500"><p class="normaltext"><b>Таблица 1. Результаты измерений.</b></p></td></tr><tr><td width="30"></td>						<td width="500"><table id="result_table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td colspan="9" bgcolor="#000000"></td></tr><tr bgcolor="#f0f0f0"><td bgcolor="#000000"></td><td width="50" align="center" height="40"><img width="24" height="16" src="imgs/number.gif" /></td><td bgcolor="#000000"></td><td width="100" align="center"  height="40"><img width="51" height="18" src="imgs/t.gif" /></td><td bgcolor="#000000"></td><td width="200" align="center"  height="40"><img width="63" height="21" src="imgs/teta.gif" /></td><td bgcolor="#000000"></td><td width="200" align="center"  height="40"><img width="67" height="21" src="imgs/psi.gif" /></td><td bgcolor="#000000"></td></tr><tr><td colspan="9" bgcolor="#000000"></td></tr></table></td></tr></table>';			
			num_timeout=1; num_result=0;
			document.getElementById("graphics_area_id").innerHTML="";	//убираем предыдущие графики
			mass_points_teta = new Array(); mass_points_psi = new Array();	//обнуляем массивы
			time_bit=document.getElementById("delta_measurements").value*1000;
			window.setTimeout("get_rotation_ramki()",time_bit);
			act_time=document.getElementById("time_action").value*1;
			stop_timeout=window.setTimeout("stop_action()",((act_time*1000)+time_bit));
		}
	}
}
function stop_action(){
	stop_rotation();
}
function stop_rotation(){
	cortona = document.getElementById("scene_vrml").Engine;
	cortona.Nodes("Main_script").Fields("was_stop").value=1;
	enabled_form();
	send_points();
}
var num_result=0; var stop_timeout;
function get_rotation_ramki(){
	cortona = document.getElementById("scene_vrml").Engine;
	//document.getElementById("rotation_info1").innerHTML+=(Math.floor((act_time/(time_bit/1000))))+' > '+(num_result)+'<br />';
	if(!cortona.Nodes("Main_script").Fields("was_stoped").value ){ //если не было остановлено движение
		if( Math.floor((act_time/(time_bit/1000)))>num_result ){
			tmp_rot_int = cortona.Nodes("Main_script").Fields("proto_int_rotation").angle;
			tmp_rot_ext = cortona.Nodes("Main_script").Fields("proto_ext_rotation").angle;
			
			var res_tbl=document.getElementById("result_table");
			num_result++;
			res_tbl.insertRow();							//вставили <tr>
			res_tbl.insertRow();							//вставили <tr>
			last_tr=res_tbl.rows.length-1;			//индекс последнего <tr>
			
			res_tbl.rows[last_tr-1].insertCell(0);				//	<td bgcolor="#000000"></td>
			res_tbl.rows[last_tr-1].cells[0].style.backgroundColor="#000000";
			
			res_tbl.rows[last_tr-1].insertCell(1);	//<td width="50" align="center" height="40"><p class="normaltext_c">'+num_result+'</p></td>
			res_tbl.rows[last_tr-1].cells[1].width=50;
			res_tbl.rows[last_tr-1].cells[1].height=40;
			res_tbl.rows[last_tr-1].cells[1].innerHTML="<p class=\"normaltext_c\">"+num_result+"</p>";
			
			res_tbl.rows[last_tr-1].insertCell(2);				//	<td bgcolor="#000000"></td>
			res_tbl.rows[last_tr-1].cells[2].style.backgroundColor="#000000";
			
			res_tbl.rows[last_tr-1].insertCell(3);	//<td width="100" align="center" height="40"><p class="normaltext_c">'+num_result+'</p></td>
			res_tbl.rows[last_tr-1].cells[3].width=100;
			res_tbl.rows[last_tr-1].cells[3].height=40;
			res_tbl.rows[last_tr-1].cells[3].innerHTML="<p class=\"normaltext_c\">"+(num_timeout*time_bit/1000)+"</p>";
			
			res_tbl.rows[last_tr-1].insertCell(4);				//	<td bgcolor="#000000"></td>
			res_tbl.rows[last_tr-1].cells[4].style.backgroundColor="#000000";
			
			res_tbl.rows[last_tr-1].insertCell(5);	//<td width="200" align="center" height="40"><p class="normaltext_c">'+(Math.ceil(tmp_rot_int*10000)/10000)+'</p></td>
			res_tbl.rows[last_tr-1].cells[5].width=200;
			res_tbl.rows[last_tr-1].cells[5].height=40;
				
			if(cortona.Nodes("Main_script").Fields("proto_int_rotation").y==1){
				if((Math.ceil(tmp_rot_int*100000)/100000)!=0){
					res_tbl.rows[last_tr-1].cells[5].innerHTML="<p class=\"normaltext_c\">-"+(Math.ceil(tmp_rot_int*100000)/100000)+"</p>";			
					mass_points_teta.push((num_timeout*time_bit/1000)+',-'+(Math.ceil(tmp_rot_int*100000)/100000));
				} else {
					res_tbl.rows[last_tr-1].cells[5].innerHTML="<p class=\"normaltext_c\">"+(Math.ceil(tmp_rot_int*100000)/100000)+"</p>";			
					mass_points_teta.push((num_timeout*time_bit/1000)+','+(Math.ceil(tmp_rot_int*100000)/100000));
				}
			} else{
				res_tbl.rows[last_tr-1].cells[5].innerHTML="<p class=\"normaltext_c\">"+(Math.ceil(tmp_rot_int*100000)/100000)+"</p>";			
				mass_points_teta.push((num_timeout*time_bit/1000)+','+(Math.ceil(tmp_rot_int*100000)/100000));
			}
			
			res_tbl.rows[last_tr-1].insertCell(6);				//	<td bgcolor="#000000"></td>
			res_tbl.rows[last_tr-1].cells[6].style.backgroundColor="#000000";
			
			res_tbl.rows[last_tr-1].insertCell(7);	//<td width="200" align="center" height="40"><p class="normaltext_c">'+(Math.ceil(tmp_rot_ext*100000)/100000)+'</p></td>
			res_tbl.rows[last_tr-1].cells[7].width=200;
			res_tbl.rows[last_tr-1].cells[7].height=40;
			
			if(cortona.Nodes("Main_script").Fields("proto_ext_rotation").y==1){
				if((Math.ceil(tmp_rot_ext*100000)/100000)!=0){
					res_tbl.rows[last_tr-1].cells[7].innerHTML="<p class=\"normaltext_c\">-"+(Math.ceil(tmp_rot_ext*100000)/100000)+ "</p>";
					mass_points_psi.push((num_timeout*time_bit/1000)+',-'+(Math.ceil(tmp_rot_ext*100000)/100000));
				} else {
					res_tbl.rows[last_tr-1].cells[7].innerHTML="<p class=\"normaltext_c\">"+(Math.ceil(tmp_rot_ext*100000)/100000)+ "</p>";
					mass_points_psi.push((num_timeout*time_bit/1000)+','+(Math.ceil(tmp_rot_ext*100000)/100000));
				}
			} else {
				res_tbl.rows[last_tr-1].cells[7].innerHTML="<p class=\"normaltext_c\">"+(Math.ceil(tmp_rot_ext*100000)/100000)+ "</p>";
				mass_points_psi.push((num_timeout*time_bit/1000)+','+(Math.ceil(tmp_rot_ext*100000)/100000));
			}
			
			res_tbl.rows[last_tr-1].insertCell(8);				//	<td bgcolor="#000000"></td>
			res_tbl.rows[last_tr-1].cells[8].style.backgroundColor="#000000";
			
			
			for(q=0;q<9;q++){
				res_tbl.rows[last_tr].insertCell(q);		//<td colspan="9" height="1" bgcolor="#000000"></td>
				res_tbl.rows[last_tr].cells[q].height=1;
				res_tbl.rows[last_tr].cells[q].style.backgroundColor="#000000";
			}
			
			//document.getElementById("rotation_info").innerHTML+="внутр. рамка = "+(Math.ceil(tmp_rot_int*10000)/10000)+" внешн. рамка = "+(Math.ceil(tmp_rot_ext*100000)/100000)+"<br />";
			if(Math.floor((act_time/(time_bit/1000)))!=num_result){
				num_timeout++;
				window.setTimeout("get_rotation_ramki()",time_bit);
			} else {
				window.clearTimeout(stop_timeout);
				stop_action();
			}
		}
	}
}
function send_points(){
	str_send="points1="+encodeURIComponent(mass_points_teta.join('#'))+"&points2="+encodeURIComponent(mass_points_psi.join('#'));
	//window.alert(str_send);
    if(xmlHttp){ 
        try{
            xmlHttp.open("post","get_img.php", true); 
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
		<div><p class="normaltext"><font style="text-decoration: underline; font-weight:600">Закон прецессии</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index1.php" class="normaltext">Нутационные колебания</a></p></div>
		<table cellpadding="0" cellspacing="0" border="0"><tr><td align="left" valign="top">
		<div class="vrml_scene" id="divc">
			<?php if(false){ ?>
			<OBJECT id="scene_vrml" CLASSID="CLSID:86A88967-7A20-11D2-8EDA-00600818EDB1" width="100%" height="100%">
				<PARAM name="SRC" value="gyro.wrl">
				<PARAM name="rendererName" value="DirectX Renderer"><!-- value="DirectX Renderer" value="OpenGL"  -->
				<PARAM name="rendererhints" value="5664">
				<PARAM name="VRML_DASHBOARD" value="FALSE">
				<!-- <PARAM name="consolemode" value="1">	 -->
				<!-- <PARAM name="contextmenu" value="FALSE">  -->
				<PARAM name="WaitForAllResourses" value="TRUE">
				<PARAM name="HEADLIGHT" value="TRUE">
			<!--<embed id="Scene" name="Cortona" type="application/x-cc3d" onfocus="" CONTEXTMENU="TRUE" VRML_DASHBOARD="FALSE" headlight="true" border=0 width="100%" height="100%">-->
			</OBJECT>
			<?php } else { ?>
			<embed id="scene_vrml" codebase="http://www.parallelgraphics.com/bin/cortvrml.cab#Version=4,2,0,93" vrml_dashboard="false" RendererName="DirectX Renderer" RendererHints="5664" HEADLIGHT="TRUE" CONTEXTMENU="TRUE" VRML_SPLASHSCREEN="false" width="100%" height="100%" src="http://www.diplom.ru/gyro.wrl">
			<?php } ?>
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
								Число оборотов ротора:&nbsp;<input type="Text" id="rotor_n" maxlength="5" value="18000" style="width:50" />&nbsp;в минуту<br />
								</p>
								<p class="normaltext">
								Осевой момент инерции:&nbsp;<input type="Text" id="rotor_j" maxlength="5" value="4.38" style="width:50" />&nbsp;Гсмсек2<br />
								Вращение ротора по часовой стрелке:&nbsp;<input type="Checkbox" id="rotor_way" style="cursor: pointer" />
								</p>
								<p class="normaltext">
								Момент по внутренней рамке:&nbsp;<select name="int_ramka_moment" id="int_ramka_moment_id" style="width:50" class="normaltext" onchange="set_int_moment()">
									<option id="int_m_0" value="0" />0
			<?php
								for($i=1;$i<5.2;$i=$i+0.2){
			?>
									<option id="int_m_<?php echo $i; ?>" value="<?php echo $i; ?>" /><?php echo $i; ?>
			<?php
								}
			?>
								</select>&nbsp;Гсм
								</p>
								<fieldset class="normaltext" style="width:250px"><legend><p class="normaltext">&nbsp;&nbsp;Точка приложения момента на внутр. рамке&nbsp;&nbsp;</p></legend><input value="1" name="int_ramka" id="int_ramka1_id" onclick="set_int_ramka(1)" style="cursor: pointer" checked type="Radio" />&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;<input value="2" name="int_ramka" id="int_ramka2_id" onclick="set_int_ramka(2)" style="cursor: pointer" type="Radio" />&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;<input value="3" name="int_ramka" id="int_ramka3_id" onclick="set_int_ramka(3)" style="cursor: pointer" type="Radio" />&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;<input value="4" name="int_ramka" id="int_ramka4_id" onclick="set_int_ramka(4)" style="cursor: pointer" type="Radio" />&nbsp;4</fieldset><br />
								<p class="normaltext">
								Момент по внешней рамке:&nbsp;<select name="ext_ramka_moment" id="ext_ramka_moment_id" style="width:50" class="normaltext" onchange="set_ext_moment()">
									<option id="ext_m_0" value="0" />0
			<?php
								for($i=1;$i<5.2;$i=$i+0.2){
			?>
									<option id="ext_m_<?php echo $i; ?>" value="<?php echo $i; ?>" /><?php echo $i; ?>
			<?php
								}
			?>
								</select>&nbsp;Гсм
								</p>
								<fieldset class="normaltext" style="width:250px"><legend><p class="normaltext">&nbsp;&nbsp;Точка приложения момента на внешн. рамке&nbsp;&nbsp;</p></legend><input value="1" name="ext_ramka" id="ext_ramka1_id" onclick="set_ext_ramka(1)" style="cursor: pointer" checked type="Radio" />&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;<input value="2" name="ext_ramka" id="ext_ramka2_id" onclick="set_ext_ramka(2)" style="cursor: pointer" type="Radio" />&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;<input value="3" name="ext_ramka" id="ext_ramka3_id" onclick="set_ext_ramka(3)" style="cursor: pointer" type="Radio" />&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;<input value="4" name="ext_ramka" id="ext_ramka4_id" onclick="set_ext_ramka(4)" style="cursor: pointer" type="Radio" />&nbsp;4</fieldset><br />
						</div>
					</td>
				</tr>
				<tr>
					<td height="40"></td>
				</tr>
				<tr>
					<td>
						<p class="normaltext">Масштаб времени:<br />1 сек. =&nbsp;<input type="Text" id="time_scale" maxlength="4" value="1" style="width:45" />&nbsp;сек. в виртуальном пространстве</p><br />
						<p class="normaltext">Длительность проведения измерений:&nbsp;<input type="Text" id="time_action" maxlength="4" value="900" style="width:45" />&nbsp;сек.</p><br />
						<p class="normaltext">Замерять углы поворотов каждые:&nbsp;<select name="delta_measurements" id="delta_measurements" style="width:50" class="normaltext">
									<option value="0.5" />0.5
									<option value="1" />1
									<option selected value="2" />2
									<option value="5" />5
									<option value="10" />10
									<option value="30" />30
								</select>&nbsp;сек.</p><br />
						<input type="Button" value="пуск" id="button_start" style="cursor: pointer" onclick="check_params()" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="Button" value="стоп" disabled id="button_stop" style="cursor: pointer" onclick="stop_rotation()" />
					</td>
				</tr>
				<tr>
					<td>
						<p id="rotation_info1" class="normaltext"></p>
					</td>
				</tr>
				<tr>
					<td>
					</td>
				</tr>
			</table>
		</td>
		</tr>
		<tr>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td id="result_area_td" width="500" valign="top">
					</td>
					<td width="10"></td>
					<td id="graphics_area_id" width="470" valign="top"></td>
					<td width="10"></td>
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
