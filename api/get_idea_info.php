<?php 

$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');

// 1. 파리미터 받아오기
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}

$idea_id = $_REQUEST ['idea_id']; //사용자가 넘겨준거
$user_id = $_REQUEST ['user_id'];

// 2. DB 접속

$conn = db_connect();

// 아이디어 디테일 누르면 hit 증가
$query = "UPDATE ideas SET hit = hit+1 WHERE id= ".$idea_id;
$conn->query($query);

// 4. DB에 저장된, 특정부스 리스트를 불러온다.
$cursor = $conn->query(
		"SELECT booths.name,ideas.user_id, ideas.booth_id, ideas.content, ideas.hit, ideas.like_num, ideas.date, ideas.comment_num
		FROM ideas
		INNER JOIN booths 
		ON ideas.id = ".$idea_id ." 
		WHERE booths.id = ideas.booth_id");

if(!$cursor){
	set_error(4, $callback);
}

// 4-1. 없으면 에러코드.
if($cursor->num_rows == 0){
	$cursor->close();
	set_error(5, $callback);
}

$ret = db_result_to_array($cursor);

$query ="SELECT *
		FROM likes 
		WHERE user_id = ".$user_id." AND idea_id=".$idea_id;

$cursor = $conn -> query($query);

if($cursor->fetch_assoc() != 0){
	$like =1;
}
else{
	$like =0;
}





// JSON 객체 만들자.

$result['err'] = 0;
$result['like'] = $like;
$result['booth_id'] = $ret[0]['booth_id'];
$result['name'] = $ret[0]['name'];
$result['user_id'] = $ret[0]['user_id'];
$result['content'] =  $ret[0]['content'];
$result['hit'] = $ret[0]['hit'];
$result['like_num'] = $ret[0]['like_num'];
$result['date'] = $ret[0]['date'];
$result['comment_num'] = $ret[0]['comment_num'];


// 6. 전송
if($callback){
	echo $callback.'('.json_encode($result).')';
}else{
	echo json_encode($result);
}

?>
















