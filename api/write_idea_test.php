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

// 한국시간
date_default_timezone_set("Asia/Seoul");

// 2. DB 접속
$conn = db_connect();



// 필터링작업
function filter($nameasdferwer){
	// $nameasdferwer = htmlspecialchars($nameasdferwer);
	// $nameasdferwer = strip_tags($nameasdferwer);
	$nameasdferwer = addslashes($nameasdferwer);
	// $nameasdferwer = stripslashes($nameasdferwer);
	// $nameasdferwer = mysql_real_escape_string($nameasdferwer, $sql_con);
	return $nameasdferwer;
}

	// 4. DB 인서트
	$query = sprintf("insert into `ideas` (user_id, booth_id, content, hit, date, like_num,comment_num) values
		(" . $user_id . ", '" . $booth_id . "', '".'%s'."',0,'".date("Y-m-d H:i:s")."',0,0)"
		,filter($content)
		);
	
	$conn->query ( $query );
	
	if ($conn->affected_rows != 1) {
		set_error ( 4, $callback );
	}
	$id = mysqli_insert_id($conn);

	// 부스의 idea_num값 올리기
	$query = "UPDATE booths SET idea_num = idea_num+1 WHERE id= ".$booth_id;
	$conn->query($query);

	// 5. JOSN 으로 만든다.
	$result['err'] = 0;
	// insert한 idea_id
	$result['idea_id'] = $id;
	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>