<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title><?=$title?></title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<link rel="stylesheet" href="<?=$staticPath?>css/reset.css" type="text/css" />
	<link rel="stylesheet" href="<?=$staticPath?>css/main.css" type="text/css" />
	<link rel="stylesheet" href="<?=$staticPath?>css/transition.css" type="text/css" />
	<script type="text/javascript" src="<?=$staticPath?>js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/jquery.form.js"></script>
	<!--ajax请求封装类-->
	<script src="<?=$staticPath?>js/ext/ajax.js"></script>
	<!--表单提交封装类-->
	<script src="<?=$staticPath?>js/ext/form.js"></script>
</head>
<script>
	function GetRand(){
		var time = new Date().getTime();
		var rand = parseInt(100000*(1+Math.random()));
		return time+"_"+rand;
	}
</script>
<!--主体部分-->