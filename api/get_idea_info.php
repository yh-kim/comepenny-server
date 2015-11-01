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

// 4. DB에 저장된, 특정부스 리스트를 불러온다.
$cursor = $conn->query(
		"SELECT booths.name, ideas.content, ideas.hit, ideas.like_num
		FROM ideas
		INNER JOIN booths 
		ON ideas.id = ".$idea_id ." 
		WHERE booths.id = ideas.booth_id");

if(!$cursor){
	set_error(4, $callback);
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
$result['name'] = $ret[0]['name'];
$result['content'] =  $ret[0]['content'];
$result['hit'] = $ret[0]['hit'];
$result['like_num'] = $ret[0]['like_num'];


// 6. 전송
if($callback){
	echo $callback.'('.json_encode($result).')';
}else{
	echo json_encode($result);
}

?>
















