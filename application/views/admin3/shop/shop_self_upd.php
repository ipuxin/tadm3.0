<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 管理员店铺信息编辑页 ***

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
		<div class="title"><span>我的店铺</span></div>
		<form class="tab" action="/shop/updSelf">
			<line>
				<span class="title">余额：</span>
				<span><?=$shop['Balance']?></span>
				<span class="title">所属城市：</span>
				<span><?=$shop['CityName']?></span>
				<span class="title">关注度：</span>
				<span><?=$shop['Fans']?></span>
			</line>
			<line>
				<span class="title">店铺名称：</span>
				<span><?=$shop['ShopName']?></span>
			</line>
			<line style="height:52px;">
				<input type="hidden" id="ImageInput" name="ShopLogo" value='<?=$shop['ShopLogo']?>'>
				<span class="title">店铺LOGO：</span>
				<span><img id="Image" class="min" src="<?=$shop['ShopLogo']?>"></span>
				<label for="ImageButton"><div class="btn">上传</div></label>
				<span style="margin-top:12px;">(100KB以内,宽320*320高)</span>
			</line>
			<line>
				<span class="title">联系人：</span>
				<input type="text" name="ShopOwnerName" value="<?=$shop['ShopOwnerName']?>">
			</line>
			<line>
				<span class="title">联系人手机：</span>
				<input type="text" name="ShopOwnerMobile" value="<?=$shop['ShopOwnerMobile']?>">
			</line>
			<line>
				<span class="title">店铺简介：</span>
				<input type="text" name="ShopDescription" value="<?=$shop['ShopDescription']?>">
			</line>
			<line>
				<span class="title">店铺地址：</span>
				<input type="text" name="ShopAddress" value="<?=$shop['ShopAddress']?>">
			</line>
			<line>
				<span class="title">发货地址：</span>
				<input type="text" name="DeliverAddress" value="<?=$shop['DeliverAddress']?>">
			</line>
			<line>
				<span class="title">退货地址：</span>
				<input type="text" name="ReturnAddress" value="<?=$shop['ReturnAddress']?>">
			</line>
			<line>
				<span class="title">默认运费：</span>
				<input type="text" name="FreightAmout" value="<?=$shop['FreightAmout']?>">
			</line>
			<line>
				<span class="title">包邮金额：</span>
				<input type="text" name="FreightFreeAmout" value="<?=$shop['FreightFreeAmout']?>">
			</line>
			<line>
				<span class="title">支付宝开户名称：</span>
				<input type="text" name="ZhifubaoKaihu" value="<?=$shop['ZhifubaoKaihu']?>">
			</line>
			<line>
				<span class="title">支付宝账号：</span>
				<input type="text" name="ZhifubaoAccount" value="<?=$shop['ZhifubaoAccount']?>">
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
		</form>
		<form method="POST" action="/upload/shopImage" accept-charset="UTF-8" id="uploadImgForm" enctype="multipart/form-data">
		<input name="ImageUpload" id="ImageButton" type="file" class="manual-file-chooser js-manual-file-chooser js-avatar-field" style="display:none">
		</form>
		<script type="text/javascript">
		 $(document).ready(function(){
				var options = {
					beforeSubmit:  showRequest,
					success:       showResponse,
					dataType: 'json'
				};
				$('#ImageButton').on('change', function(){
					$('#uploadImgForm').ajaxForm(options).submit();
				});
			});
			
		function showRequest() {return true;}
		function showResponse(response)  {
			if(response.error){
				alert(response.error.error);
			}else{
				var imgUrl = response.imageUrl;
				$('#Image').attr('src',imgUrl);
				$('#ImageInput').val(imgUrl);
			}
		}
		</script>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script src="/data/file/js/ext/datetimepicker/time.min.js"></script>
	<script src="/data/file/js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="/data/file/js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>
		
		$('form.tab').FormAjax({
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