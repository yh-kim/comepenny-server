<?php


// 에러 체크 + 암호화
$salt1 = 'polic)210&304(17-m2';
$salt2 = '+30417m2^3607(polic';


function set_error($err_code, $callback){
$result['err'] = $err_code; 
if($callback){
echo $callback.'('.json_encode($result).')';
}else{
echo json_encode($result);
}
exit;
}

// 필터링작업
function filter($nameasdferwer){
	// $nameasdferwer = htmlspecialchars($nameasdferwer);
	// $nameasdferwer = strip_tags($nameasdferwer);
	$nameasdferwer = addslashes($nameasdferwer);
	// $nameasdferwer = stripslashes($nameasdferwer);
	// $nameasdferwer = mysql_real_escape_string($nameasdferwer, $sql_con);
	return $nameasdferwer;
}



?>
