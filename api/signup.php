<?php


// 회원가입
$doc_root = $_SERVER ['DOCUMENT_ROOT'];


require_once($doc_root.'/engine/db.php'); 
require_once($doc_root.'/util/util.php');

// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}

$email = $_POST ['email'];
$passwd = $_POST ['passwd'];

if (!isset( $email ) || !isset( $passwd )) {
	set_error ( 1, $callback );
}



// 2.DB 접속
$conn = db_connect();
// 3.이름이 DB 에 있는지 없는지

$cursor = $conn->query ( "select * from `users` where email = '" . $email . "' " );

if (! $cursor) {
	set_error ( 2, $callback );//db error
}
// 3-1.있으면 에러 코드
if($cursor->num_rows != 0){
	$cursor->close();
	set_error(3, $callback);
}

// 한국시간
date_default_timezone_set("Asia/Seoul");

// 4.DB에 INSERT

$query = "insert into `users` (email,passwd,reg_date) values 
		( '".$email. "', 
		'" .sha1($passwd.$salt1)."',
		'".date("Y-m-d H:i:s")."')";

$conn->query($query);
if($conn->affected_rows != 1){//insert로 영향받은 행수
	set_error(4, $callback);
}

// 5.JSON으로 만든다.
$result['err'] = 0;
$result['user_id'] = $conn->insert_id; //회원가입되면 아이디 값을 가져온다

// 6.전송
if($callback){
	echo $callback.'('.json_encode($result).')';
}else{
	echo json_encode($result);
}
?>