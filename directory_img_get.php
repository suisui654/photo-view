<?php

// ディレクトリのパスを指定する
$dir = "./img/" ;

// 画像のパスを入れる配列
$img_array = array();

if( is_dir( $dir ) && $handle = opendir( $dir ) ) {

	$path = "";
	while( ($file = readdir($handle)) !== false ) {
		$path = $dir . $file;
		if( filetype( $path ) == "file" && $type = exif_imagetype($path) ) {
 			$img_array[] = $path;
		}
	}
}

//json形式で出力
header('Content-type: application/json');

$response = array();
$response['response_deta'] = $img_array;

echo json_encode($response) ;

