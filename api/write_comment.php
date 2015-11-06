<?php 
$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}

$idea_id = $_REQUEST['idea_id'];
$user_id = $_REQUEST['user_id'];
$comment = $_REQUEST['comment'];

// 1.1 validation check
if(!isset($idea_id) ||!isset($user_id) || !isset($comment)){
	set_error(1, $callback);
}

// 1.2 해당 유저가 회원이냐.

// 한국시간
date_default_timezone_set("Asia/Seoul");

// 2. DB 접속
$conn = db_connect();

	// 4. DB 인서트
	$query = "insert into `comments` (idea_id, user_id, comment, date) values
		(" . $idea_id . ",'" .$user_id. "','".$comment."', '".date("Y-m-d H:i:s")."')";
	
	$conn->query ( $query );
	
	if ($conn->affected_rows != 1) {
		set_error ( 4, $callback );
	}

	// 아이디어의의 comment_num값 올리기
	$query = "UPDATE ideas SET comment_num = comment_num+1 WHERE id= ".$idea_id;
	$conn->query($query);

	// comment_num 가져오기
	$cursor = $conn->query(
		"SELECT comment_num
		FROM ideas
		WHERE id =".$idea_id);

	$ret = db_result_to_array($cursor);

	// 5. JOSN 으로 만든다.
	
	$result['err'] = 0;
	$result['comment_num'] = $ret[0]['comment_num'];
	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>