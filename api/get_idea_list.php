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

// 부스 눌렀을 때 아이디어 받아오기
if(isset($_REQUEST ['booth_id'])){
$booth_id = $_REQUEST ['booth_id']; //사용자가 넘겨준거

$query ="SELECT ideas.id, content, hit, like_num, email
      FROM ideas
      INNER JOIN users 
      ON ideas.user_id = users.id
      WHERE booth_id= ".$booth_id." 
      ORDER BY ideas.id DESC
      LIMIT ".$offset.",6";
  }

// 내 정보 눌렀을 때 like한 아이디어 받아오기
else if(isset($_REQUEST ['user_id'])){
$user_id = $_REQUEST ['user_id'];

$query = "SELECT ideas.id, content, hit, like_num, email
      FROM ideas, users
      INNER JOIN likes
      ON likes.user_id=".$user_id."
      WHERE ideas.id = likes.idea_id AND users.id = ideas.user_id
      LIMIT ".$offset.",6";
}else{
// 메인에서 인기순으로 아이디어 받아오기
  /*
$query ="SELECT ideas.id, content, hit, like_num, email
      FROM ideas
      INNER JOIN users 
      ON ideas.user_id = users.id
      ORDER BY like_num DESC 
      limit ".$offset.",6";
  }
  */
//메인에서 관리자가 선택한 아이디어 받아오기
$query = "SELECT ideas.id, content, hit, like_num, email
      FROM ideas, users
      INNER JOIN likes
      ON likes.user_id=0
      WHERE ideas.id = likes.idea_id AND users.id = ideas.user_id
      LIMIT ".$offset.",6";
  }

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