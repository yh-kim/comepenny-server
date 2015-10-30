<?php

$doc_root = $_SERVER ['DOCUMENT_ROOT'];

$passwd = $_REQUEST ['_passwd'];
?>

<html>
<head>
	<title></title>
</head>
<body>
	<?php
	if($passwd == "alswnwndml" || $passwd == "민주주의"){
	?>

	<script type="text/javascript">
	window.open("api.php","_self");
	</script>

	<?php
	}
	else{
	?>

	<script type="text/javascript">
	window.open("api_main.html","_self");
	</script>
	<?php	
	}
	?>

</body>
</html>