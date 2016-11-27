<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 门店员个人信息编辑页 ***

创建 2016-08-28 刘深远 

*** ***/
?>
<style>
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;cursor:pointer;color:#666;float:left;margin-top:14px;}
div.btn:hover{background-color:#fff;}
span{position:relative;}
span img{height:60px;display:inline-block;}
span a{display:inline-block;float:left;margin-right:10px;}
span i{display:block;height:24px;width:24px;position:absolute;line-height:24px;border-radius:50%;border:1px solid #888;background-color:rgba(0,0,0,0.5);color:#fff;font-size:18px;text-align:center;right:-2px;top:-12px;cursor:pointer;}
line textarea{float:left;}
tabarea{display:block;margin-bottom:12px;}

areaList line>span.title{width:140px;}
areaList btns{display:block;padding-left:148px;}
areaList div.btn{display:inline-block;float:left;height:30px;line-height:30px;border:1px solid #ccc;background-color:#f8f8f8;padding:0 6px;margin-right:4px;margin-bottom:4px;margin-top:0;}
areaList div.btn.sel{border-color:#09c;background-color:#0099cc;color:#fff;}
</style>
<body>
	<section>
		<div class="title"><span>我的资料</span></div>
		<form action="/user/updSelf">
			<line>
				<span class="title">账号：</span>
				<span><?=$user['Account']?></span>
				<span class="title">余额：</span>
				<span><?=$user['Balance']?></span>
			</line>
			<line>
				<span class="title">用户名：</span>
				<span><?=$user['Username']?></span>
			</line>
			<line>
				<span class="title">密码：</span>
				<input type="text" name="Password"> （不填则不修改）
			</line>
			<line>
				<span class="title">联系人：</span>
				<input type="text" name="RealName" value="<?=$user['RealName']?>">
			</line>
			<line>
				<span class="title">联系电话：</span>
				<input type="text" name="Mobile" value="<?=$user['Mobile']?>">
			</line>
			<line>
				<span class="title">联系地址：</span>
				<input type="text" name="Address" class="long" value="<?=$user['Address']?>">
			</line>
			<line>
				<span class="title">支付宝开户名称：</span>
				<input type="text" name="ZhifubaoKaihu" class="long" value="<?=$user['ZhifubaoKaihu']?>">
			</line>
			<line>
				<span class="title">支付宝账号：</span>
				<input type="text" name="ZhifubaoAccount" class="long" value="<?=$user['ZhifubaoAccount']?>">
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
	<script src="/data/file/js/ext/datetimepicker/time.min.js"></script>
	<script src="/data/file/js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="/data/file/js/ext/datetimepicker/css/time.css" type="text/css" />
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