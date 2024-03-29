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
$idea_id = $_REQUEST['idea_id'];
$is_like = $_REQUEST['is_like'];
$is_like = (int)$is_like;

// 1.1 validation check
if(!isset($user_id) ||!isset($idea_id) || !isset($is_like)){
	set_error(1, $callback);
}

// 1.2 해당 유저가 회원이냐.
// 2. DB 접속

$conn = db_connect();

// 한국시간
date_default_timezone_set("Asia/Seoul");

//좋아요 삭제
if($is_like==1)
{
	$query = "DELETE FROM likes where user_id=".$user_id." AND idea_id =".$idea_id;
}else{ //좋아요 추가
	$query = "insert into `likes` (user_id, idea_id, like_date) 
	values (" . $user_id . "," .$idea_id.",'".date("Y-m-d H:i:s")."')";
}

	// 4. DB 인서트
	$conn->query ( $query );

// 좋아요 삭제하면 like_num 감소
if($is_like==1)
{
	$query = "UPDATE ideas SET like_num = like_num-1 WHERE id= ".$idea_id;
}else{ //좋아요 추가하면 like_num 증가
	$query = "UPDATE ideas SET like_num = like_num+1 WHERE id= ".$idea_id;
}

$conn->query($query);

//  아이디어의 like수 받아오기
$cursor = $conn->query(
		"SELECT like_num
		FROM ideas
		WHERE id = ".$idea_id);

if(!$cursor){
	set_error(4, $callback);
}

$ret = db_result_to_array($cursor);

	// 5. JOSN 으로 만든다.
	
	$result['err'] = 0;
	$result['like_num'] = $ret[0]['like_num'];

	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>