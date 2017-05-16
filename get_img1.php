<?php
Header("Content-type: text/html; charset=windows-1251");
Header("Cache-Control: no-cache");
Header("Cache-Control: no-store, no-cache, must-revalidate");
Header("Cache-Control: post-check=0, pre-check=0", false);
Header("Pragma: no-cache");
//echo "Точки первого графика ".$_POST['points1']."<br /><br />Точки второго графика ".$_POST['points2'];
$mas_tmp1=explode("#",$_POST['points1']);
$mas_points1=array();
$mas_tmp2=explode("#",$_POST['points2']);
$mas_points2=array();
//echo implode($mas_tmp1," ")."<br /><br />";
//echo implode($mas_tmp2," ");
$w=470; $h=470;
$h_displacement=18;
$w_displacement=18;

$polus_tmp=explode("#",$_POST['polus']);
//echo "polus= ".$polus_tmp[0]." _ ".$polus_tmp[1];

//=============================ГРАФИК 1========================================
$dest_img = imageCreateFromGif("imgs/graph11_base.gif");
$background = imagecolorallocate($dest_img,255,255,255);
$graph_color = imagecolorallocate($dest_img,255,0,0);
$axis_color = imagecolorallocate($dest_img,0,0,0);
$grid_color = imagecolorallocate($dest_img,240,240,240);
//зальём его цветом background
//imagefill($dest_img, 0, 0, $background);
$last_x=0+$w_displacement; $last_y=$h-$h_displacement;
$tmp_max_corner=$mas_tmp1[count($mas_tmp1)-1];
$max_corner=explode(",",$tmp_max_corner);
if($max_corner[1]*1>0){	//решение где рисовать график (выше или ниже оси Х)
	$up_axis=1;
} else {
	$up_axis=0;
}

//принимаем решение как подписывать вертикальную ось и рисовать сетку
if(count($mas_tmp1)>20){
	$paste=ceil(1/(20/count($mas_tmp1)));	//узнаём через сколько надо подписывать ось
} else {
	$paste=1;
}
//принимаем решение как подписывать горизонтальную ось и рисовать сетку
if(count($mas_tmp1)>15){
	$paste_t=ceil(1/(15/count($mas_tmp1)));	//узнаём через сколько надо подписывать ось
} else {
	$paste_t=1;
}

//рисуются вертикальные линии сетки сверху оси
$num=0;
for($i=0;$i<count($mas_tmp1);$i++){
	$num++;
	if($num==$paste){
		$x=(390/count($mas_tmp1))*($i+1);
		ImageLine($dest_img, $x+$w_displacement, $last_y-2, $x+$w_displacement, $last_y-390, $grid_color);
		$num=0;
	}
}
//рисуются горизонтальные линии сетки сверху оси
$num=0;
for($i=0;$i<count($mas_tmp1);$i++){
	$num++;
	if($num==$paste){
		$y=$last_y-(390/count($mas_tmp1))*($i+1);
		ImageLine($dest_img, $last_x+2, $y, $last_x+390, $y, $grid_color);
		$num=0;
	}
}

//рисуются вертикальные линии сетки снизу оси
$num=0;
for($i=0;$i<count($mas_tmp1);$i++){
	$num++;
	if($num==$paste){
		$x=(390/count($mas_tmp1))*($i+1);
		ImageLine($dest_img, $x+$w_displacement, $last_y+2, $x+$w_displacement, $last_y+390, $grid_color);
		$num=0;
	}
}
//рисуются горизонтальные линии сетки снизу оси
$num=0;
for($i=0;$i<count($mas_tmp1);$i++){
	$num++;
	if($num==$paste){
		$y=$last_y+(390/count($mas_tmp1))*($i+1);
		ImageLine($dest_img, $last_x+2, $y, $last_x+390, $y, $grid_color);
		$num=0;
	}
}

$num=0;
for($i=0;$i<count($mas_tmp1);$i++){
	$num++;
	$x=(390/count($mas_tmp1))*($i+1);
	if($num==$paste_t){
		ImageLine($dest_img, $x+$w_displacement, $last_y+1, $x+$w_displacement, $last_y-4, $axis_color);	//насечка на оси X
		$tmp=explode(",",$mas_tmp1[$i]);
		//$str_sizes=imageTTFBBox(10, 0, "gost_a.ttf", $tmp[0]);	//узнаём размеры занимаемые текстом для его дальнейшей центровки
		//ImageTTFText( $dest_img, 10, 0, $x+$w_displacement-(($str_sizes[2]-$str_sizes[0])/2), $last_y+13, $axis_color, "gost_a.ttf", $tmp[0] );		//подписываем ось X
		$num=0;
	} else {
		ImageLine($dest_img, $x+$w_displacement, $last_y+1, $x+$w_displacement, $last_y-2, $axis_color);	//насечка на оси X
	}
}
$num=0;
for($i=0;$i<count($mas_tmp1);$i++){
	$num++;
	$y=$last_y-(390/count($mas_tmp1))*($i+1);	//насечка выше оси Х
	$y1=$last_y+(390/count($mas_tmp1))*($i+1);	//насечка ниже оси Х
	if($num==$paste){
		ImageLine($dest_img, $last_x-1, $y, $last_x+4, $y, $axis_color);	//насечка на оси Y
		ImageLine($dest_img, $last_x-1, $y1, $last_x+4, $y1, $axis_color);	//насечка на оси Y
		if(ceil(abs($max_corner[1])*(($i+1)/count($mas_tmp1))*1000000)/1000 != 0){ //подписываем если это не ноль
			ImageTTFText($dest_img,10,0,$last_x+5,$y+5,$axis_color,"gost_a.ttf", ceil(abs($max_corner[1])*(($i+1)/count($mas_tmp1))*1000000)/1000 );//подписываем ось Y выше оси Х
			ImageTTFText($dest_img,10,0,$last_x+7,$y1+4,$axis_color,"gost_a.ttf", "-".(ceil(abs($max_corner[1])*(($i+1)/count($mas_tmp1))*1000000)/1000) );//подписываем ось Y ниже оси Х
		}
		$num=0;
	} else {
		ImageLine($dest_img, $last_x-1, $y, $last_x+2, $y, $axis_color);	//насечка на оси Y
		ImageLine($dest_img, $last_x-1, $y1, $last_x+2, $y1, $axis_color);	//насечка на оси Y
	}
}
//рисуем сам график
$last_x=0+$w_displacement;
$last_y=$h-$h_displacement+($polus_tmp[0]/$max_corner[1]*390)-390;
if($up_axis){
	for($i=0;$i<count($mas_tmp1);$i++){
		$tmp=explode(",",$mas_tmp1[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp1))*($i+1);
		$new_y=$h-$h_displacement+($polus_tmp[0]/$max_corner[1]*390)-(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
} else {
	for($i=0;$i<count($mas_tmp1);$i++){
		$tmp=explode(",",$mas_tmp1[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp1))*($i+1);
		$new_y=$h-$h_displacement+($polus_tmp[0]/$max_corner[1]*390)+(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
}
// сохраняем в файл
imagegif($dest_img,"imgs/graphic1.gif");
imagedestroy($dest_img);
unset($dest_img);
//========END==================ГРАФИК 1==================END===================

//=============================ГРАФИК 2========================================
$dest_img = imageCreateFromGif("imgs/graph22_base.gif");
$background = imagecolorallocate($dest_img,255,255,255);
$graph_color = imagecolorallocate($dest_img,255,0,0);
$axis_color = imagecolorallocate($dest_img,0,0,0);
$grid_color = imagecolorallocate($dest_img,240,240,240);
//зальём его цветом background
//imagefill($dest_img, 0, 0, $background);
$last_x=0+$w_displacement; $last_y=$h-$h_displacement;
$tmp_max_corner=$mas_tmp2[(count($mas_tmp2)-1)/4];
//echo $tmp_max_corner;
$max_corner=explode(",",$tmp_max_corner);
if($max_corner[1]*1>0){	//решение где рисовать график (выше или ниже оси Х)
	$up_axis=1;
} else {
	$up_axis=0;
}

//принимаем решение как подписывать вертикальную ось и рисовать сетку
if(count($mas_tmp2)>20){
	$paste=ceil(1/(20/count($mas_tmp2)));	//узнаём через сколько надо подписывать ось
} else {
	$paste=1;
}
//принимаем решение как подписывать горизонтальную ось и рисовать сетку
if(count($mas_tmp2)>15){
	$paste_t=ceil(1/(15/count($mas_tmp2)));	//узнаём через сколько надо подписывать ось
} else {
	$paste_t=1;
}

//рисуются вертикальные линии сетки сверху оси
$num=0;
for($i=0;$i<count($mas_tmp2);$i++){
	$num++;
	if($num==$paste){
		$x=(390/count($mas_tmp2))*($i+1);
		ImageLine($dest_img, $x+$w_displacement, $last_y-2, $x+$w_displacement, $last_y-390, $grid_color);
		$num=0;
	}
}
//рисуются горизонтальные линии сетки сверху оси
$num=0;
for($i=0;$i<count($mas_tmp2);$i++){
	$num++;
	if($num==$paste){
		$y=$last_y-(390/count($mas_tmp2))*($i+1);
		ImageLine($dest_img, $last_x+2, $y, $last_x+390, $y, $grid_color);
		$num=0;
	}
}

//рисуются вертикальные линии сетки снизу оси
$num=0;
for($i=0;$i<count($mas_tmp2);$i++){
	$num++;
	if($num==$paste){
		$x=(390/count($mas_tmp2))*($i+1);
		ImageLine($dest_img, $x+$w_displacement, $last_y+2, $x+$w_displacement, $last_y+390, $grid_color);
		$num=0;
	}
}
//рисуются горизонтальные линии сетки снизу оси
$num=0;
for($i=0;$i<count($mas_tmp2);$i++){
	$num++;
	if($num==$paste){
		$y=$last_y+(390/count($mas_tmp2))*($i+1);
		ImageLine($dest_img, $last_x+2, $y, $last_x+390, $y, $grid_color);
		$num=0;
	}
}

$num=0;
for($i=0;$i<count($mas_tmp2);$i++){
	$num++;
	$x=(390/count($mas_tmp2))*($i+1);
	if($num==$paste_t){
		ImageLine($dest_img, $x+$w_displacement, $last_y+1, $x+$w_displacement, $last_y-4, $axis_color);	//насечка на оси X
		$tmp=explode(",",$mas_tmp2[$i]);
		$str_sizes=imageTTFBBox(10, 0, "gost_a.ttf", $tmp[0]);	//узнаём размеры занимаемые текстом для его дальнейшей центровки
		//ImageTTFText( $dest_img, 10, 0, $x+$w_displacement-(($str_sizes[2]-$str_sizes[0])/2), $last_y+13, $axis_color, "gost_a.ttf", $tmp[0] );		//подписываем ось X
		$num=0;
	} else {
		ImageLine($dest_img, $x+$w_displacement, $last_y+1, $x+$w_displacement, $last_y-2, $axis_color);	//насечка на оси X
	}
}
$num=0;
for($i=0;$i<count($mas_tmp2);$i++){
	$num++;
	$y=$last_y-(390/count($mas_tmp2))*($i+1);	//насечка выше оси Х
	$y1=$last_y+(390/count($mas_tmp2))*($i+1);	//насечка ниже оси Х
	if($num==$paste){
		ImageLine($dest_img, $last_x-1, $y, $last_x+4, $y, $axis_color);	//насечка на оси Y
		ImageLine($dest_img, $last_x-1, $y1, $last_x+4, $y1, $axis_color);	//насечка на оси Y
		if( ceil(abs($max_corner[1])*(($i+1)/count($mas_tmp2))*1000000)/1000 !=0 ){
			ImageTTFText($dest_img,10,0,$last_x+5,$y+5,$axis_color,"gost_a.ttf", ceil(abs($max_corner[1])*(($i+1)/count($mas_tmp2))*1000000)/1000 );//подписываем ось Y выше оси Х
			ImageTTFText($dest_img,10,0,$last_x+7,$y1+4,$axis_color,"gost_a.ttf", "-".(ceil(abs($max_corner[1])*(($i+1)/count($mas_tmp2))*1000000)/1000) );//подписываем ось Y ниже оси Х
		}
		$num=0;
	} else {
		ImageLine($dest_img, $last_x-1, $y, $last_x+2, $y, $axis_color);	//насечка на оси Y
		ImageLine($dest_img, $last_x-1, $y1, $last_x+2, $y1, $axis_color);	//насечка на оси Y
	}
}
//рисуем сам график
$last_x=0+$w_displacement;
$last_y=$h-$h_displacement+($polus_tmp[1]/$max_corner[1]*390);
if($up_axis){
	for($i=0;$i<count($mas_tmp2);$i++){
		$tmp=explode(",",$mas_tmp2[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp2))*($i+1);
		$new_y=$h-$h_displacement+($polus_tmp[1]/$max_corner[1]*390)-(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
} else {
	for($i=0;$i<count($mas_tmp2);$i++){
		$tmp=explode(",",$mas_tmp2[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp2))*($i+1);
		$new_y=$h-$h_displacement+($polus_tmp[1]/$max_corner[1]*390)+(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
}
// сохраняем в файл
imagegif($dest_img,"imgs/graphic2.gif");
imagedestroy($dest_img);
//========END==================ГРАФИК 2==================END===================

//=============================ГРАФИК 3===(эллипс)=============================
$w=470; $h=470;
$h_displacement=453;	//смещение нулевой точки
$w_displacement=447;
$dest_img = imageCreateFromGif("imgs/graph33_base.gif");
$background = imagecolorallocate($dest_img,255,255,255);
$graph_color = imagecolorallocate($dest_img,255,0,0);
$axis_color = imagecolorallocate($dest_img,0,0,0);
$grid_color = imagecolorallocate($dest_img,240,240,240);
$last_x=$w_displacement; $last_y=$h_displacement;
//для одной оси
$tmp_max_corner1=$mas_tmp1[count($mas_tmp1)-1];
$max_corner1=explode(",",$tmp_max_corner1);
//для второй оси
$tmp_max_corner2=$mas_tmp2[(count($mas_tmp2)-1)/4];
$max_corner2=explode(",",$tmp_max_corner2);
unset($max_corner);
if($max_corner1[1]>$max_corner2[1]){
	$max_corner=abs($max_corner1[1]);
} else {
	$max_corner=abs($max_corner2[1]);
}
//принимаем решение как подписывать вертикальную ось и рисовать сетку
if(count($mas_tmp1)>20){
	$paste=ceil(1/(20/((count($mas_tmp1)-1)/4) ));	//узнаём через сколько надо подписывать ось
} else {
	$paste=1;
}
if(count($mas_tmp1)>10){
	$paste_x=ceil(1/(10/((count($mas_tmp1)-1)/4) ));	//узнаём через сколько надо подписывать ось
} else {
	$paste_x=1;
}
//рисуются вертикальные линии сетки сверху оси
$num=0;
for($i=0;$i<((count($mas_tmp1)-1)/4);$i++){
	$num++;
	if($num==$paste){
		$x=(390/( ((count($mas_tmp1)-1)/4) ) )*($i+1);
		ImageLine($dest_img, $x+$w_displacement, $last_y-1, $x+$w_displacement, $last_y-390, $grid_color);
		ImageLine($dest_img, $x+$w_displacement-390, $last_y-1, $x+$w_displacement-390, $last_y-390, $grid_color);
		$num=0;
	}
}
//рисуются горизонтальные линии сетки сверху оси
$num=0;
for($i=0;$i<((count($mas_tmp1)-1)/4);$i++){
	$num++;
	if($num==$paste){
		$y=$last_y-(390/( ((count($mas_tmp1)-1)/4) ) )*($i+1);
		ImageLine($dest_img, $last_x-390, $y, $last_x+390, $y, $grid_color);
		$num=0;
	}
}
//рисуются вертикальные линии сетки снизу оси
$num=0;
for($i=0;$i<((count($mas_tmp1)-1)/4);$i++){
	$num++;
	if($num==$paste){
		$x=(390/( ((count($mas_tmp1)-1)/4) ) )*($i+1);
		ImageLine($dest_img, $x+$w_displacement, $last_y+1, $x+$w_displacement, $last_y+390, $grid_color);
		ImageLine($dest_img, $x+$w_displacement-390, $last_y+1, $x+$w_displacement-390, $last_y+390, $grid_color);
		$num=0;
	}
}
//рисуются горизонтальные линии сетки снизу оси
$num=0;
for($i=0;$i<((count($mas_tmp1)-1)/4);$i++){
	$num++;
	if($num==$paste){
		$y=$last_y+(390/( ((count($mas_tmp1)-1)/4) ) )*($i+1);
		ImageLine($dest_img, $last_x-390, $y, $last_x+390, $y, $grid_color);
		$num=0;
	}
}
$num=0; $max_elems=(count($mas_tmp1)-1)/4;
for($i=0;$i<((count($mas_tmp1)-1)/4);$i++){
	$num++;
	$y=$h_displacement-(390/$max_elems)*($i+1);	//насечка выше оси Х
	//echo "y = ".$y."<br /><br />";
	$y1=$h_displacement+(390/$max_elems)*($i+1);	//насечка ниже оси Х
	
	if($num==$paste){
		ImageLine($dest_img, $last_x, $y, $last_x+5, $y, $axis_color);	//насечка на оси Y
		ImageLine($dest_img, $last_x, $y1, $last_x+5, $y1, $axis_color);	//насечка на оси Y
		if(ceil(abs($max_corner)*(($i+1)/((count($mas_tmp1)-1)/4))*1000000)/1000 != 0){ //подписываем если это не ноль
			ImageTTFText($dest_img,10,0,$last_x+7,$y+5,$axis_color,"gost_a.ttf", ceil(abs($max_corner)*(($i+1)/$max_elems)*1000000)/1000 );//подписываем ось Y выше оси Х
			ImageTTFText($dest_img,10,0,$last_x+7,$y1+4,$axis_color,"gost_a.ttf", "-".(ceil(abs($max_corner)*(($i+1)/$max_elems)*1000000)/1000) );//подписываем ось Y ниже оси Х
		}
		$num=0;
	} else {
		ImageLine($dest_img, $last_x, $y, $last_x+2, $y, $axis_color);	//насечка на оси Y
		ImageLine($dest_img, $last_x, $y1, $last_x+2, $y1, $axis_color);	//насечка на оси Y
	}
}
$num=0; $max_elems=(count($mas_tmp1)-1)/4;
for($i=0;$i<((count($mas_tmp1)-1)/4);$i++){
	$num++;
	$x=$w_displacement-(390/$max_elems)*($i+1);	//насечка левее оси Y
	//echo "y = ".$y."<br /><br />";
	$x1=$w_displacement+(390/$max_elems)*($i+1);	//насечка правее оси Y
	
	if($num==$paste_x){
		ImageLine($dest_img, $x, $last_y-1, $x, $last_y-6, $axis_color);	//насечка на оси X левее оси Y
		ImageLine($dest_img, $x1, $last_y-1, $x1, $last_y-6, $axis_color);	//насечка на оси X правее оси Y
		if(ceil(abs($max_corner)*(($i+1)/((count($mas_tmp1)-1)/4))*1000000)/1000 != 0){ //подписываем если это не ноль
			$tmp_txt=ceil(abs($max_corner)*(($i+1)/$max_elems)*1000000)/1000;
			$str_sizes1=imageTTFBBox(10, 0, "gost_a.ttf", "-".$tmp_txt);	//узнаём размеры занимаемые текстом для его дальнейшей центровки
			$str_sizes2=imageTTFBBox(10, 0, "gost_a.ttf", $tmp_txt);	//узнаём размеры занимаемые текстом для его дальнейшей центровки
			ImageTTFText($dest_img,10,0,$x-(($str_sizes1[2]-$str_sizes1[0])/2),$last_y+13,$axis_color,"gost_a.ttf", "-".$tmp_txt );//подписываем ось X левее оси Y
			ImageTTFText($dest_img,10,0,$x1-(($str_sizes2[2]-$str_sizes2[0])/2),$last_y+13,$axis_color,"gost_a.ttf", $tmp_txt );//подписываем ось X правее оси Y
		}
		$num=0;
	} else {
		ImageLine($dest_img, $x, $last_y-1, $x, $last_y-3, $axis_color);	//насечка на оси X
		ImageLine($dest_img, $x1, $last_y-1, $x1, $last_y-3, $axis_color);	//насечка на оси X
	}
}
//рисуем сам график		ЭЛЛИПС ВЫТЯНУТЫЙ СВЕРХУ ВНИЗ
$tmp_max_corner=$mas_tmp1[count($mas_tmp1)-1];
$max_corner_teta=explode(",",$tmp_max_corner);
$max_teta=abs($max_corner_teta[1]);
$tmp_max_corner=$mas_tmp2[(count($mas_tmp2)-1)/4];
$max_corner_psi=explode(",",$tmp_max_corner);
$max_psi=abs($max_corner_psi[1]);
if($max_psi<$max_teta){
/*
$tmp_max_corner=$mas_tmp1[count($mas_tmp1)-1];
$max_corner_teta=explode(",",$tmp_max_corner);
$max_teta=abs($max_corner_teta[1]);
$tmp_max_corner=$mas_tmp2[(count($mas_tmp2)-1)/4];
$max_corner_psi=explode(",",$tmp_max_corner);
$max_psi=abs($max_corner_psi[1]);
*/

	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement-($max_teta/$max_corner*390)+($polus_tmp[0]/$max_corner*390);
	$k=0;
	$mas_tt=array();
	for($i=0;$i<=$max_elems*4;$i++){
		if($k>$max_elems){$k=0;}
		if($i>=0 && $i<=$max_elems){	//первая четверть
			$tmp1=explode(",",$mas_tmp1[$i]);
			$tmp2=explode(",",$mas_tmp2[$i]);
			$teta_a=$tmp1[1];
			$psi_a=$tmp2[1];
			//echo "1) teta_a= ".$teta_a." psi_a= ".$psi_a."<br />";
			$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)+($psi_a/$max_corner*390)*(($k+1)/$max_elems);
			$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)-($max_teta/$max_corner*390)+(390-($teta_a/$max_corner*390));
			$mas_tt[]=$psi_a.",".$teta_a;
			ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
			$last_x=$new_x;
			$last_y=$new_y;
			$k++;
		}
	}
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement+($max_teta/$max_corner*390)+($polus_tmp[0]/$max_corner*390);
	for($i=0;$i<count($mas_tt);$i++){	//вторая четверть
		$tmp1=explode(",",$mas_tt[$i]);
		$teta_a=$tmp1[1];
		$psi_a=$tmp1[0];
		//echo "2) teta_a= ".$teta_a." psi_a= ".$psi_a."<br />";
		$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)+($psi_a/$max_corner*390)*(($i+1)/$max_elems);
		$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)+($max_teta/$max_corner*390)-(390-($teta_a/$max_corner*390));
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
	
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement+($max_teta/$max_corner*390)+($polus_tmp[0]/$max_corner*390);
	$mas_tt=array();
	for($i=0;$i<=$max_elems*4;$i++){
		if($k>$max_elems){$k=0;}
		if($i>=$max_elems*2 && $i<=$max_elems*3){	//третья четверть
			$tmp1=explode(",",$mas_tmp1[$i]);
			$tmp2=explode(",",$mas_tmp2[$i]);
			$teta_a=abs($tmp1[1]);
			$psi_a=abs($tmp2[1]);
			$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)+$mas_psi-($psi_a/$max_corner*390)*(($k+1)/$max_elems);
			$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)+($teta_a/$max_corner*390);
			$mas_tt[]=$psi_a.",".$teta_a;
			ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
			$last_x=$new_x;
			$last_y=$new_y;
			$k++;
		}
	}
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement-($max_teta/$max_corner*390)+($polus_tmp[0]/$max_corner*390);
	for($i=0;$i<count($mas_tt);$i++){	//четвертая четверть
		$tmp1=explode(",",$mas_tt[$i]);
		$teta_a=$tmp1[1];
		$psi_a=$tmp1[0];
		//echo "2) teta_a= ".$teta_a." psi_a= ".$psi_a."<br />";
		$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)-($psi_a/$max_corner*390)*(($i+1)/$max_elems);
		$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)-($max_teta/$max_corner*390)+(390-($teta_a/$max_corner*390));
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
} else {
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement+($polus_tmp[0]/$max_corner*390)-($max_teta/$max_corner*390);
	//echo "max_teta = ".$max_teta."<br />";
	//echo "max_elems = ".$max_elems."<br />";
	$k=0;
	$mas_tt=array(); $delta=0;
	for($i=0;$i<=$max_elems*4;$i++){
		if($k>$max_elems){$k=0;}
		if($i>=0 && $i<=$max_elems){	//первая четверть
			$tmp1=explode(",",$mas_tmp1[$i]);
			$tmp2=explode(",",$mas_tmp2[$i]);
			$teta_a=$tmp1[1];
			$psi_a=$tmp2[1];
			$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)+(($psi_a/$max_corner)*390)*(($i+1)/($max_elems+1));
			$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)-($max_teta/$max_psi*390)+(390-($teta_a/$max_corner*390));
			if($i==0){
				$delta=$last_y-$new_y;
			}
			$new_y=$new_y+$delta;
			$mas_tt[]=$psi_a.",".$teta_a;
			ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
			$last_x=$new_x;
			$last_y=$new_y;
			$k++;
		}
	}
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement+($polus_tmp[0]/$max_corner*390)+($max_teta/$max_corner*390);
	for($i=0;$i<count($mas_tt);$i++){	//вторая четверть
		$tmp1=explode(",",$mas_tt[$i]);
		$teta_a=$tmp1[1];
		$psi_a=$tmp1[0];
		//echo "2) teta_a= ".$teta_a." psi_a= ".$psi_a."<br />";
		$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)+($psi_a/$max_corner*390)*(($i+1)/($max_elems+1));
		$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)+($max_teta/$max_corner*390)-(390-($teta_a/$max_corner*390));
		$new_y=$new_y-$delta;
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement+($polus_tmp[0]/$max_corner*390)+($max_teta/$max_corner*390);
	$mas_tt=array();
	for($i=0;$i<=$max_elems*4;$i++){
		if($k>$max_elems){$k=0;}
		if($i>=$max_elems*2 && $i<=$max_elems*3){	//третья четверть
			$tmp1=explode(",",$mas_tmp1[$i]);
			$tmp2=explode(",",$mas_tmp2[$i]);
			$teta_a=abs($tmp1[1]);
			$psi_a=abs($tmp2[1]);
			$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)+$mas_psi-($psi_a/$max_corner*390)*(($k+1)/($max_elems+1));
			$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)+($teta_a/$max_corner*390);
			$mas_tt[]=$psi_a.",".$teta_a;
			ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
			$last_x=$new_x;
			$last_y=$new_y;
			$k++;
		}
	}
	$last_x=$w_displacement+($polus_tmp[1]/$max_corner*390);
	$last_y=$h_displacement+($polus_tmp[0]/$max_corner*390)-($max_teta/$max_corner*390);
	for($i=0;$i<count($mas_tt);$i++){	//четвертая четверть
		$tmp1=explode(",",$mas_tt[$i]);
		$teta_a=$tmp1[1];
		$psi_a=$tmp1[0];
		//echo "2) teta_a= ".$teta_a." psi_a= ".$psi_a."<br />";
		$new_x=$w_displacement+($polus_tmp[1]/$max_corner*390)-($psi_a/$max_corner*390)*(($i+1)/($max_elems+1));
		$new_y=$h_displacement+($polus_tmp[0]/$max_corner*390)-($max_teta/$max_corner*390)+(390-($teta_a/$max_corner*390));
		$new_y=$new_y+$delta;
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
}
// сохраняем в файл
imagegif($dest_img,"imgs/graphic3.gif");
imagedestroy($dest_img);

//========END==================ГРАФИК 3==================END===================


echo "<p class=\"normaltext\"><b>Рис.1 Графики зависимостей.</b></p>";
if(file_exists("imgs/graphic1.gif")){
	echo "<img width=\"470\" height=\"901\" border=\"0\" src=\"imgs/graphic1.gif\" />";
}
if(file_exists("imgs/graphic2.gif")){
	echo "<img width=\"470\" height=\"901\" border=\"0\" src=\"imgs/graphic2.gif\" />";
}
if(file_exists("imgs/graphic3.gif")){
	echo "<br /><img width=\"900\" height=\"901\" border=\"0\" src=\"imgs/graphic3.gif\" /><br />";
}
?>
