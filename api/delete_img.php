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

// 1.1 validation check
if(!isset($user_id)){
	set_error(1, $callback);
}

// 1.2 해당 유저가 회원이냐.
// 2. DB 접속

$conn = db_connect();

	// 4. DB 인서트
	$query = "UPDATE users SET image_o = null, image_t = null WHERE id=".$user_id;
	$conn->query($query);

	// 5. JOSN 으로 만든다.
	$result['err'] = 0;

	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>