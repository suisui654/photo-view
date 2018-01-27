<?php

$data = array();

$rss_url = "https://www.pinterest.jp/". $_GET["pint_user"] ."/feed.rss/";
$rssdata = simplexml_load_string(file_get_contents($rss_url));

$format = rss_format_get($rssdata);
//ATOM
if($format == "ATOM"){
	$info_data = atom_info_get($rssdata);
	$feed_data = atom_feed_get($rssdata);
}
//RSS1.0
elseif($format == "RSS1.0"){
	$info_data = rss1_info_get($rssdata);
	$feed_data = rss1_feed_get($rssdata);

}
//RSS2.0
elseif($format == "RSS2.0"){
	$info_data = rss2_info_get($rssdata);
	$feed_data = rss2_feed_get($rssdata);
}
else {
	print("FORMAT ERROR\n");exit;
}

header('Content-type: application/json');

$response = array();
$response['error_status'] = "0";
$response['response_feed_count'] = count($feed_data);
$response['request_url'] = $rss_url;
$response['rss_format'] = $format;
$response['response_info'] = $info_data;
$response['response_feed'] = $feed_data;

echo json_encode($response) ;

/*
 function
*/
function rss_format_get($rssdata){
	if($rssdata->entry){
		//ATOM
		return "ATOM";
	} elseif ($rssdata->item){
		//RSS1.0
		return "RSS1.0";
	} elseif ($rssdata->channel->item){
		//RSS2.0
		return "RSS2.0";
	} else {
		print("FORMAT ERROR");
		exit;
	}
}


// info_get
function rss1_info_get($rssdata){
	foreach ($rssdata->channel as $channel) {
		$work = array();
		foreach ($channel as $key => $value) {
			$work[$key] = (string)$value;
		}
		$data[] = $work;
	}
	return $data;
}
function rss2_info_get($rssdata){
	foreach ($rssdata->channel as $channel) {
		$work = array();
		foreach ($channel as $key => $value) {
			$work[$key] = (string)$value;
		}
		$data[] = $work;
	}
	return $data;
}
function atom_info_get($rssdata){
	foreach ($rssdata as $item){
		$work = array();
		$work['title'] = (string)$item;
		$data[] = $work;
	}
	return $data;
}

// feed_get
function rss1_feed_get($rssdata){
	foreach ($rssdata->item as $item) {
		$work = array();

		foreach ($item as $key => $value) {
			$work[$key] = (string)$value;
		}

		//dc
		foreach ($item->children('dc',true) as $key => $value) {
			$work['dc:'. $key] = (string)$value;
		}

		//content
		foreach ($item->children('content',true) as $key => $value) {
			$work['content:'. $key] = (string)$value;
		}

		$data[] = $work;
	}
	return $data;
}
function rss2_feed_get($rssdata){
	foreach ($rssdata->channel->item as $item) {
		$work = array();
		foreach ($item as $key => $value) {
			$work[$key] = (string)$value;
		}
		$data[] = $work;
	}
	return $data;
}
function atom_feed_get($rssdata){
	foreach ($rssdata->entry as $item){
		$work = array();
		foreach ($item as $key => $value) {
			if( $key == "link"){
				$work[$key] = (string)$value->attributes()->href;;
			} else {
				$work[$key] = (string)$value;
			}
		}
		$data[] = $work;
	}
	return $data;
}