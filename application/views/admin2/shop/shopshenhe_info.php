<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 管理员店铺信息查看页 ***

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
		<div class="title"><span>店铺申请信息</span></div>
		<form action="/shop/setShopShenhe">
			<input type="hidden" name="ShopId" value="<?=$shop['id']?>">
			<line>
				<span class="title">所属城市：</span>
				<span><?=$shop['CityName']?></span>
			</line>
			<line>
				<span class="title">店铺类型：</span>
				<span><?=$shop['ShopType']?></span>
			</line>
			<line>
				<span class="title">店铺名称：</span>
				<span><?=$shop['shopname']?></span>
			</line>
			<line style="height:52px;">
				<span class="title">店铺logo：</span>
				<span class="ShopMinImage"><a target="_blank" href="<?=$shop['shoplogo']?>"><img src="<?=$shop['shoplogo']?>"></a></span>
			</line>
			<line>
				<span class="title">联系人：</span>
				<span><?=$shop['realname']?></span>
			</line>
			<line>
				<span class="title">联系人手机：</span>
				<span><?=$shop['mobile']?></span>
			</line>
			<line>
				<span class="title">预设密码：</span>
				<span><?=$shop['password']?></span>
			</line>
			<line>
				<span class="title">支付宝账号：</span>
				<span><?=$shop['zhifubaoaccount']?></span>
			</line>
			<line>
				<span class="title">店铺地址：</span>
				<span><?=$shop['address']?></span>
			</line>
			<line>
				<span class="title">店铺简介：</span>
				<span><?=$shop['shopinfo']?></span>
			</line>
			<line>
				<span class="title">身份证：</span>
				<span><?=$shop['personid']?></span>
			</line>
			<line>
				<span class="title"></span>
				<span><?=$shop['person_id_timestar']?> 到 <?=$shop['person_id_timeend']?></span>
			</line>
			<line>
				<span class="title" style="height:52px;">身份证照片：</span>
				<span class="ShopMinImage">
					<a target="_blank" href="<?=$shop['personpica']?>"><img src="<?=$shop['personpica']?>"></a>
					<a target="_blank" href="<?=$shop['personpicb']?>"><img src="<?=$shop['personpicb']?>"></a>
				</span>
			</line>
			<line>
				<span class="title" style="height:130px;">其他照片：</span>
				<span class="ShopMinImage">
					<?if($shop['company_business_license']){?><a target="_blank" href="<?=$shop['company_business_license']?>"><img src="<?=$shop['company_business_license']?>"></a><?}?>
					<?if($shop['organization_certificate']){?><a target="_blank" href="<?=$shop['organization_certificate']?>"><img src="<?=$shop['organization_certificate']?>"></a><?}?>
					<?if($shop['qc_other']){?><a target="_blank" href="<?=$shop['qc_other']?>"><img src="<?=$shop['qc_other']?>"></a><?}?>
					<?if($shop['tax_registration_certificate']){?><a target="_blank" href="<?=$shop['tax_registration_certificate']?>"><img src="<?=$shop['tax_registration_certificate']?>"></a><?}?>
					<br>


					<?if($shop['brand_certificate']){?><a target="_blank" href="<?=$shop['brand_certificate']?>"><img src="<?=$shop['brand_certificate']?>"></a><?}?>
					<?if($shop['person_halfbody']){?><a target="_blank" href="<?=$shop['person_halfbody']?>"><img src="<?=$shop['person_halfbody']?>"></a><?}?>
					<?if($shop['qc_report']){?><a target="_blank" href="<?=$shop['qc_report']?>"><img src="<?=$shop['qc_report']?>"></a><?}?>
					<?if($shop['trademark']){?><a target="_blank" href="<?=$shop['trademark']?>"><img src="<?=$shop['trademark']?>"></a><?}?>
				</span>
			</line>
			<line>
				<span class="title">审核：</span>
				<input type="radio" name="open" value="1"><span>通过</span>
				<input type="radio" name="open" value="2"><span>不通过</span>
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
					window.location.href="/admin2/shop/shop_check";
				}
			}
		});

	</script>
</body>