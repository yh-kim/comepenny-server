<?php 
$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}
$user_id = $_REQUEST['user_id'];
$booth_id = $_REQUEST['booth_id'];
$content = $_REQUEST['content'];

// 1.1 validation check
if(!isset($user_id) || !isset($content)){
	set_error(1, $callback);
}

// 1.2 해당 유저가 회원이냐.
// 2. DB 접속

$conn = db_connect();

	// 4. DB 인서트
	$query = "insert into `ideas` (user_id, booth_id, content, hit, date) values
		(" . $user_id . ", '" . $booth_id . "', '".$content."'0, NOW() )";
	
	$conn->query ( $query );
	
	if ($conn->affected_rows != 1) {
		set_error ( 4, $callback );
	}

	$query = "UPDATE booths SET idea_num = idea_num+1 WHERE id= ".$booth_id;
	$conn->query($query);

	// 5. JOSN 으로 만든다.
	$result['err'] = 0;
	//$result['content'] = $ret[0]['content'];
	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>













