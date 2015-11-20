<?php 
$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');
require_once ($doc_root . '/engine/SimpleImage.php');

require '/var/www/html/aws/aws-sdk-php/vendor/autoload.php';


use Aws\Common\Aws;
	use Aws\S3\Enum\CannedAcl;
	use Aws\Common\Enum\Region;


// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}
$user_id = $_REQUEST['user_id'];

$uploaddir = '/tmp/';
	$uploadfile = $uploaddir . basename($_FILES['img']['tmp_name']);

// 1.1 validation check
if(!isset($user_id)){
	set_error(1, $callback);
}

// 네트워크로 넘어온 경우는 항상 문자열이다.
// 따라서 숫자로 사용되는 변수는 캐스트를 먼저 해주자.
$user_id = (int)$user_id;


	$keyname_o = $user_id.'_profile.jpg';

	// need img src
	//

	$bucket = "comepenny";
	 try{
 		// Instantiate an S3 client
   		 $s3 = Aws::factory(array(
   		    'key'=>'AKIAJHVBHV3AHXPTPDKA',
	   	    'secret'=>'tZEWOi0PDfUPSyzaAnOWIMkLejp2bwQxNFepwF8S++Qg4kpb2Cwj79UkWviAqm',
    	    'region'=>Region::US_WEST_2
   		 ))->get('s3');

	 } catch(S3Exception $e){
		 set_error(21, $callback);
 	 }

 	  $final = $s3->putObject(array(
        'Bucket' => $bucket,
        'Key'    => $keyname_o,
        'Body'   => fopen($uploadfile, 'r'),
        'ACL'    => CannedAcl::PUBLIC_READ,
        'ContentType'=>mime_content_type($uploadfile)
    ));

// 1.2 해당 유저가 회원이냐.
// 2. DB 접속

$conn = db_connect();



	// 4. DB 인서트
	$query = "update users set `image_o` = '".$keyname_o."'  
	where id = ".$user_id ;

	
	$conn->query ( $query );
	
	if ($conn->affected_rows != 1) {
		set_error ( 4, $callback );
	}

	// 5. JOSN 으로 만든다.
	$result['err'] = 0;
	
	
	// 6. 전송
	if($callback){
		echo $callback.'('.json_encode($result).')';
	}else{
		echo json_encode($result);
	}


?>