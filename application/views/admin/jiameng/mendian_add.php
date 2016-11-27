<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 门店商添加页 ***

创建 2016-02-26 刘深远 

*** ***/
?>
<style>
form{margin-top:20px;}
form line>span.title{width:140px;}
btns{display:block;padding-left:148px;}
div.btn{display:inline-block;float:left;height:30px;line-height:30px;border:1px solid #ccc;background-color:#f8f8f8;padding:0 6px;margin-right:4px;margin-bottom:4px;}
div.btn.sel{border-color:#09c;background-color:#0099cc;color:#fff;}
div.btn.dis{background-color:#ccc;color:#fff;}
</style>
<body>
	<section>
		<div class="title"><span>添加门店商</span></div>
		<form action="/mendian/addMendian">
			<input type="hidden" name="CityName" id="CityName">
			<input type="hidden" name="CityCode" id="CityCode">
			<line>
				<span class="title">门店商名称：</span>
				<input data-valids="required" class="valid" name="Username" type="text" value="">
			</line>
			<line>
				<span class="title">门店商账号：</span>
				<input data-valids="required" class="valid" name="Account" type="text" value="">
			</line>
			<line>
				<span class="title">门店商密码：</span>
				<input data-valids="required" class="valid" name="Password" type="text" value="">
			</line>
			<line class="qiyong">
				<span class="title">启用中：</span>
				<input type="radio" name="IsDisable" value="0" checked="checked"><span>是</span>
				<input type="radio" name="IsDisable" value="1"><span>否</span>
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
		</form>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time.min.js"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="<?=$staticPath?>js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>

		$('form').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					location.reload();
				}
			}
		});
	</script>
</body>