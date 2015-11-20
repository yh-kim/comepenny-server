<?php 
$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}
$comment_id = $_REQUEST['comment_id'];
$comment = $_POST['comment'];

// 1.1 validation check
if(!isset($comment_id) || !isset($comment)){
	set_error(1, $callback);
}

// 1.2 해당 유저가 회원이냐.

// 한국시간
date_default_timezone_set("Asia/Seoul");

// 2. DB 접속
$conn = db_connect();

	// 4. DB 인서트
	$query = "UPDATE comments 
			  SET comment = '".$comment."'     
			  WHERE id = ".$comment_id;
	
	$conn->query ( $query );
	
	if ($conn->affected_rows != 1) {
		set_error ( 4, $callback );
	}

	// 5. JOSN 으로 만든다.
	$result['err'] = 0;
	// insert한 idea_id
	//$result['idea_id'] = $id;
	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>