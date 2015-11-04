<?php 

$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1. 파리미터 받아오기
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}

//관리자가 선택한 부스들 받아오기
if(isset($_REQUEST ['booth_is_main'])){
$booth_is_main = $_REQUEST ['booth_is_main']; //사용자가 넘겨준거

$query ="SELECT id, idea_num, like_num
      FROM booths
      WHERE is_main= 1";
  }

// 카테고리 탭에서 부스들 받아오기
else {
$query ="SELECT id, idea_num, like_num
      FROM booths";
  }

// 2. DB 접속

$conn = db_connect();

// 4. DB에 저장된, 부스 리스트를 불러온다.
$cursor = $conn->query($query);

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