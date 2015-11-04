<?php 

$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1. 파리미터 받아오기
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}
$offset =  $_REQUEST['offset'];
$offset = (int)$offset;

// 아이디어 눌렀을 때 댓글 불러오기
$idea_id = $_REQUEST ['idea_id']; //사용자가 넘겨준거

$query ="SELECT comment, email, date
      FROM comments
      INNER JOIN users 
      ON comments.idea_id = ".$idea_id." 
      WHERE comments.user_id= users.id
      LIMIT ".$offset.",6";

// 2. DB 접속

$conn = db_connect();

// 4. DB에 저장된, 특정부스 리스트를 불러온다.
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