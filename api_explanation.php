<?php

$doc_root = $_SERVER ['DOCUMENT_ROOT'];


require_once($doc_root.'/engine/db.php'); 
$api_id = $_REQUEST ['id'];

// DB 접속
$conn = db_connect();

$cursor = $conn->query ( "select * from apis where id = ".$api_id);

$row = $cursor->fetch_assoc();

?>
<html>
<head>
 <link rel="stylesheet" href="css.css" />
	<title><?php echo $row['name'] ?></title>
</head>
<body>
	<div id="right_back">
		<h5><a href="">추가하기</a></h5>
		<h5><a href="api.php">목록보기</a></h5>
	</div>
	<div>
		<h1><?php echo $row['name'] ?></h1>

		<h5>*<?php echo $row['explanation'] ?></h5>
		<br><br>
		<h3>1. 요청 URL (request url)</h3>
		<h5><?php echo $row['request_url'] ?></h5>
	</div>
	<div>
		<br><br>
		<h3>2. 요청 변수 (request parameter)</h3>
		<table  border="0px" cellpadding="0" cellspacing="0">
	
			<colgroup>
				<col width="150px"/>
				<col width="200px"/>
				<col width="650px"/>
			</colgroup>
			<thead>
				<th scope="col">요청 변수</th>
				<th scope="col">값</th>
				<th scope="col">설명</th>
			</thead>
			<tbody>
				<?php 
				$cursor = $conn->query ( "select * from requests where api_id = ".$api_id);

				for($count = 0; $row = $cursor->fetch_assoc(); $count++){
				?>
				<tr>
					<td><?php echo $row['parameter'] ?></td>
					<td><?php echo $row['value'] ?></td>
					<td><?php echo $row['explanation'] ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<div>
		<br><br>
		<h3>3. 출력 결과 필드 (response field)</h3>
		<table border="0px" cellpadding="0" cellspacing="0">
	
			<colgroup>
				<col width="150px"/>
				<col width="130px"/>
				<col width="720px"/>
			</colgroup>
			<thead>
				<th scope="col">필드</th>
				<th scope="col">값</th>
				<th scope="col">설명</th>
			</thead>
			<tbody>
				<?php 
				$cursor = $conn->query ( "select * from respones where api_id = ".$api_id);

				for($count = 0; $row = $cursor->fetch_assoc(); $count++){
				?>
				<tr>
					<td><?php echo $row['field'] ?></td>
					<td><?php echo $row['value'] ?></td>
					<td><?php echo $row['explanation'] ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>


</body>
</html>