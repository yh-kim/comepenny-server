<?php 

$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1. 파리미터 받아오기
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}

// 2. DB 접속

$conn = db_connect();

// 4. DB에 저장된, 부스 리스트를 불러온다.
$cursor = $conn->query(
		"select id, idea_num, like_num 
		from booths");

if(!$cursor){
	set_error(4, $callback);
}

$ret = db_result_to_array($cursor);

// JSON 객체 만들자.

$result['err'] = 0;
$result['cnt'] = count($ret);
$result['ret'] = $ret;

// 6. 전송
if($callback){
	echo $callback.'('.json_encode($result).')';
}else{
	echo json_encode($result);
}

?>








