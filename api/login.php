<?php


// 로그인
$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once($doc_root.'/engine/db.php'); 
require_once($doc_root.'/util/util.php');

//---------------------------------------------------------------------
// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}
//---------------------------------------------------------------------
$email = $_REQUEST ['email'];
$passwd = $_REQUEST ['passwd'];

if (!isset( $email ) || !isset( $passwd )) {
	set_error ( 1, $callback );
}
//---------------------------------------------------------------------
// 2.DB 접속
$conn = db_connect();
//---------------------------------------------------------------------
// 3.이름이 DB 에 있는지 없는지

$cursor = $conn->query ( "select * from `users` where `email` = '" . $email . "' " );

if (! $cursor) {
	set_error ( 2, $callback );//db 연결 안됐을때
}
//-------------------------------------------------
if($cursor->num_rows == 0){
// 3-1.없으면 에러 코드
	$cursor->close();
	set_error(3, $callback);//row값이 없을떄
}
//---------------------------------------------------------------------
// 패스워드 확인.
$ret = db_result_to_array($cursor);//ret에 받아온 row값들 저장

$cursor->close();//받아오면 연결 종료

// 암호화 했을 때
if($ret[0]['passwd'] != sha1($passwd.$salt1)){
	set_error(4, $callback);
}


// 5.JSON으로 만든다.
$result['err'] = 0;
$result['user_id'] = $ret[0]['id'];
$result['user_email'] = $ret[0]['email'];
// 6.전송
if($callback){
	echo $callback.'('.json_encode($result).')';
}else{
	echo json_encode($result);
}
?>