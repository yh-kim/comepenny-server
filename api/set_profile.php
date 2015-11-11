<?php 
$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once ($doc_root . '/engine/db.php');
require_once ($doc_root . '/util/util.php');
require_once ($doc_root . '/engine/SimpleImage.php');

require '/var/www/html/aws/aws-sdk-php/vendor/autoload.php';


use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


// 1.쿼리 파라미터 (리퀘스트) 처리
$callback = "";
if(isset($_REQUEST ['callback'])){
	$callback = $_REQUEST ['callback'];
}
$user_id = $_REQUEST['user_id'];

// 1.1 validation check
if(!isset($user_id)){
	set_error(1, $callback);
}

// 네트워크로 넘어온 경우는 항상 문자열이다.
// 따라서 숫자로 사용되는 변수는 캐스트를 먼저 해주자.
$user_id = (int)$user_id;


// 1.2 해당 유저가 회원이냐.
// 2. DB 접속

$conn = db_connect();



// 3. 이름이 DB 있는지 없는지.
$cursor = $conn->query(
		"select * from users where id = '".$user_id."' ");

if(!$cursor){
	set_error(2, $callback);
}

// 3-1. 없으면 에러코드.
if($cursor->num_rows == 0){
	$cursor->close();
	set_error(3, $callback);
}



// 2.  S3 접속
$bucket = "comepenny";
try{
	// Instantiate an S3 client
	$s3 = S3Client::factory(array(
			'key'=>'AKIAJHVBHV3AHXPTPDKA',
			'secret'=>'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',
	));
	
	
	// 버킷 생성할 때만 사용. 이미 웹 콘솔에서 생성한 경우는 사용할 필요 없다.
	//$result_s3 = $s3->createBucket(array('Bucket' => $bucket));	
	//$s3->waitUntil('BucketExists', array('Bucket' => $bucket));

} catch(S3Exception $e){
	//echo $e->getMessage() . "\n";
	set_error(21, $callback);
}

/******************** 이미지 처리 ***************/

$current_time = time();

if($_FILES['img']['tmp_name']){

	// size 검사
	if($_FILES['img']['size'] == 0){
		set_error(31, $callback);
	}
	// 이미지 파일인지 검사
	if(!getimagesize($_FILES['img']['tmp_name'])){

	}


	$image = new SimpleImage();
	$image->load($_FILES['img']['tmp_name']);

	// 원본 저장
	$des = $_FILES['img']['tmp_name'];
	$image->save($des);


	$uploadfile = '/tmp/'.basename($des);
	// 원본 이미지 저장.



	try{

		$keyname_o = $user_id.'_'.$current_time.'_o.jpg';

		// Upload a publicly accessible file. File size, file type, and md5 hash are automatically calculated by the SDK

		$uploader = UploadBuilder::newInstance()
		->setClient($s3)
		->setSource(fopen($uploadfile, 'r'))
		->setBucket($bucket)
		->setKey($keyname_o)
		->setConcurrency(3)
		->setOption('ACL', 'public-read')
		->setOption('ContentType', mime_content_type($uploadfile))
		->build();

		$uploader->upload();


		//$list = $s3->listObjects(array(
		// 'Bucket' => $bucket
		//));



	} catch(MultipartUploadException $e){
		//print_r($e);
		set_error(22, $callback);
	}

	// 썸네일 저장
	//****** 300 size *************//
	
	// 가로가 긴 사진인 경우.
	if($image->getWidth() >= $image->getHeight()){
	
		// 원본이 400 보다 작으면 그냥 통과
		if($image->getWidth() > 300){
			$image->resizeToWidth(300);
		}
	}else{
		// 세로가 긴 사진인 경우.
		if($image->getHeight() > 300){
			$image->resizeToHeight(300);
		}
	}
	
	
	$des = $_FILES['img']['tmp_name'];
	$image->save($des);
	
	$img_data = file_get_contents($des);
	
	$uploadfile = '/tmp/'.basename($des);
	// 바뀐 이미지 저장.
	try{
	
		$keyname_t = $user_id.'_'.$current_time.'_t.jpg';
	
		// Upload a publicly accessible file. File size, file type, and md5 hash are automatically calculated by the SDK
		$uploader = UploadBuilder::newInstance()
		->setClient($s3)
		->setSource(fopen($uploadfile, 'r'))
		->setBucket($bucket)
		->setKey($keyname_t)
		->setConcurrency(3)
		->setOption('ACL', 'public-read')
		->setOption('ContentType', mime_content_type($uploadfile))
		->build();
	
		$uploader->upload();
	
		//$list = $s3->listObjects(array(
		// 'Bucket' => $bucket
		//));
	} catch(MultipartUploadException $e){
		//print_r($e);
		set_error(23, $callback);
	}
	
	}
	/***********************************************/
	
	// DB 에 정보 저장.
	
	// 4. DB 인서트
	$query = "insert into users (image_o, image_t) values
		(".$keyname_o."', '".$keyname_t."')";
	
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













