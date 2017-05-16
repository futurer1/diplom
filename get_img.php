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


//=============================ГРАФИК 1========================================
$dest_img = imageCreateFromGif("imgs/graph11_base.gif");
$background = imagecolorallocate($dest_img,255,255,255);
$graph_color = imagecolorallocate($dest_img,255,0,0);
$axis_color = imagecolorallocate($dest_img,0,0,0);
$grid_color = imagecolorallocate($dest_img,240,240,240);
//зальём его цветом background
imagefill($dest_img, 0, 0, $background);
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
		$str_sizes=imageTTFBBox(10, 0, "gost_a.ttf", $tmp[0]);	//узнаём размеры занимаемые текстом для его дальнейшей центровки
		ImageTTFText( $dest_img, 10, 0, $x+$w_displacement-(($str_sizes[2]-$str_sizes[0])/2), $last_y+13, $axis_color, "gost_a.ttf", $tmp[0] );		//подписываем ось X
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
$last_y=$h-$h_displacement;
if($up_axis){
	for($i=0;$i<count($mas_tmp1);$i++){
		$tmp=explode(",",$mas_tmp1[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp1))*($i+1);
		$new_y=$h-$h_displacement-(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
} else {
	for($i=0;$i<count($mas_tmp1);$i++){
		$tmp=explode(",",$mas_tmp1[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp1))*($i+1);
		$new_y=$h-$h_displacement+(($tmp[1]*390)/$max_corner[1]);
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
imagefill($dest_img, 0, 0, $background);
$last_x=0+$w_displacement; $last_y=$h-$h_displacement;
$tmp_max_corner=$mas_tmp2[count($mas_tmp2)-1];
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
		ImageTTFText( $dest_img, 10, 0, $x+$w_displacement-(($str_sizes[2]-$str_sizes[0])/2), $last_y+13, $axis_color, "gost_a.ttf", $tmp[0] );		//подписываем ось X
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
$last_y=$h-$h_displacement;
if($up_axis){
	for($i=0;$i<count($mas_tmp2);$i++){
		$tmp=explode(",",$mas_tmp2[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp2))*($i+1);
		$new_y=$h-$h_displacement-(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
} else {
	for($i=0;$i<count($mas_tmp2);$i++){
		$tmp=explode(",",$mas_tmp2[$i]);
		$new_x=$w_displacement+(390/count($mas_tmp2))*($i+1);
		$new_y=$h-$h_displacement+(($tmp[1]*390)/$max_corner[1]);
		ImageLine($dest_img, $last_x, $last_y, $new_x, $new_y, $graph_color);
		$last_x=$new_x;
		$last_y=$new_y;
	}
}
// сохраняем в файл
imagegif($dest_img,"imgs/graphic2.gif");
imagedestroy($dest_img);
//========END==================ГРАФИК 2==================END===================
echo "<p class=\"normaltext\"><b>Рис.1 Графики зависимостей.</b></p>";
if(file_exists("imgs/graphic1.gif")){
	echo "<img width=\"470\" height=\"901\" border=\"0\" src=\"imgs/graphic1.gif\" />";
}
if(file_exists("imgs/graphic2.gif")){
	echo "<br /><img width=\"470\" height=\"901\" border=\"0\" src=\"imgs/graphic2.gif\" />";
}
?>