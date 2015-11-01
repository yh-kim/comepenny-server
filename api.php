
<?php

$doc_root = $_SERVER ['DOCUMENT_ROOT'];


require_once($doc_root.'/engine/db.php'); 

// DB 접속
$conn = db_connect();

$cursor = $conn->query ( "select * from apis");

if (! $cursor) {
	set_error ( 2, $callback );//db 연결 안됐을때
}
//-------------------------------------------------
if($cursor->num_rows == 0){
// 3-1.없으면 에러 코드
	$cursor->close();
	set_error(3, $callback);//row값이 없을떄
}

?>

<html>
<head>
 <link rel="stylesheet" href="css.css" />
	<title>API 명세서</title>
</head>
<body>
	<div id="api_body">
		<p><h1>ComePenny API 명세서</h1></p>
		<br><br><br>
		
		<table border="0px" cellpadding="20px" cellspacing="0px">

			<colgroup>
				<col width="140px"/>
				<col width="360px"/>
				<col width="140px"/>
				<col width="360px"/>
			</colgroup>
			<tbody>
				<?php 
				for($count = 0; $row = $cursor->fetch_assoc(); $count++){ 
					if($count %2 == 0){
						$row2 = $row;
					}else{
				?>
				<tr>
					<td><a href=api_explanation.php?id="<?php echo $row2['id'] ?>"><?php echo $row2['name'] ?></a></td>
					<td><?php echo $row2['explanation'] ?></td>
					<td><a href=api_explanation.php?id="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
					<td><?php echo $row['explanation'] ?></td>
				</tr>
				<?php	$row2="";} }
					if($row2 != ""){
				?>
					<tr>
						<td><a href=api_explanation.php?id="<?php echo $row2['id'] ?>"><?php echo $row2['name'] ?></a></td>
						<td><?php echo $row2['explanation'] ?></td>
					</tr>
					<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>