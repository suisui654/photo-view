<?php
// ディレクトリのパスを指定する
$dir = "./img/" ;
// 画像のパスを入れる配列
$img_array = array();
if( is_dir( $dir ) && $handle = opendir( $dir ) ) {
	$path = "";
	while( ($file = readdir($handle)) !== false ) {

		$path = $dir . $file;
		$path_info = pathinfo( $path );

		//画像ファイルかチェックする
		if( img_check($path_info['extension']) ) {
 			$img_array[] = $path;
		}
	}
}

//json形式で出力
header('Content-type: application/json');
$response = array();
$response['response_deta'] = $img_array;
echo json_encode($response);

//画像チェック
function img_check($extension){
	switch ($extension) {
	    case "jpg": return true;
	    case "png": return true;
	    case "gif": return true;
	    default: return false;
	}
}