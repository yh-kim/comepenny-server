<?php


// db 연동
function db_connect(){
//mysqli는 db연결에 관한 정보를 가져온다
$result = new mysqli('rds.clhoiotgubj7.ap-northeast-1.rds.amazonaws.com', 'greentea', 'frappuccino6100', 'comepenny');

if(!$result){
throw new Exception('Could noto connect to database server');//throw->이건 에러정의라는것을 알림 exceptiondp 넣는 인자 첫번째는 에러 메세지, 두번째는 에러코드
} else {
mysqli_query($result, 'set names utf8');//mysql과 연동 
$result->set_charset('utf8'); //클래스에 의해 생성된 객체에 접근하기 위한 기호
return $result;
}
}

function db_result_to_array($result){
$res_array = array();

for($count = 0; $row = $result->fetch_assoc(); $count++){
$res_array[$count] = $row;
}
return $res_array;
}

function db_result($result) {
}

?>