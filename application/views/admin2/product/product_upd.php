<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 产品添加页 ***

创建 2016-01-25 刘深远 

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
		<div class="title"><span>发布商品</span></div>
		<div class="tab-box" style="margin-bottom:12px;">
            <span class="sel">基本信息</span>
			<span class="">拼团设置</span>
			<span class="">商品详情</span>
        </div>
		<form action="/product/updProduct">
			<input type='hidden' name='id' value='<?=$info['id']?>'>
			<tabarea>
			<line>
				<span class="title">商品名称：</span>
				<span><?=$info['ProductName']?></span>
			</line>
			<line>
				<span class="title">商品类型：</span>
				<span><?=$info['ProductTypeMsg']?></span>
			</line>
			<line>
				<span class="title">商品分类：</span>
				<span><?=$info['Category1Name']?> - <?=$info['Category2Name']?></span>
			</line>
			<line style="height:52px;">
				<span class="title">商品小图：</span>
				<a target="_blank" href="<?=$info['ImageMin']?>"><img style="height:60px;width:60px;" src="<?=$info['ImageMin']?>"></a>
			</line>
			<line style="height:52px;">
				<span class="title">商品大图：</span>
				<a target="_blank" href="<?=$info['ImageMin']?>"><img style="height:60px;width:120px;" src="<?=$info['ImageBig']?>"></a>
			</line>
			<line style="height:52px;">
				<span class="title">商品相册：</span>
				<?if($info['ImageList']){foreach($info['ImageList'] as $img){?>
				<a target="_blank" href="<?=$img?>"><img style="height:60px;width:120px;" src="<?=$img?>"></a>
				<?}}?>
			</line>
			<line>
				<span class="title">商品库存：</span>
				<span><?=$info['StorageCount']?>份</span>
			</line>
			<line>
				<span class="title">购买价格：</span>
				<span><?=$info['Prices']['Normal']?>元</span>
				<span class="title">市场价格：</span>
				<span><?=$info['Prices']['Market']?>元</span>
			</line>
			<line>
				<span class="title">发货地址：</span>
				<span><?=$info['DeliverAddress']?></span>
			</line>
			<line>
				<span class="title">运费：</span>
				<span><?=$info['freightAmout']?>元</span>
			</line>
			<line>
				<span class="title">实际销量：</span>
				<span><?=$info['SalesCount']['Real']?></span>
				<span class="title">未成团数：</span>
				<span><?=$info['SalesCount']['Waiting']?></span>
				<span class="title">设置销量：</span>
				<span><?php if($info['SalesCount']['Adjust']){echo $info['SalesCount']['Adjust'];}else{echo 0;}?></span>
				<a href="/admin2/product/Adjust/<?=$info['id']?>?Real=<?=$info['SalesCount']['Real']?>&Waiting=<?=$info['SalesCount']['Waiting']?>&Adjust=<?=$info['SalesCount']['Adjust']?>">设置销量</a>
			</line>
			<line>
				<span class="title">商品描述：</span>
				<span><?=$info['Description']?></span>
			</line>
			<line>
				<span class="title">是否上架：</span>
				<input type="radio" name="IsForSale" value="1" checked="checked"><span>上架</span>
				<input type="radio" name="IsForSale" value="0"><span>下架</span>
			</line>
			<line>
				<span class="title">是否启用：</span>
				<input type="radio" name="IsDisable" value="0" checked="checked"><span>启用</span>
				<input type="radio" name="IsDisable" value="1"><span>禁用</span>
			</line>
			</tabarea>
			<tabarea>
			<line>
				<span class="title">开团人数：</span>
				<span><?=$info['TeamMemberLimit']?>人</span>
			</line>
			<line>
				<span class="title">团购限时：</span>
				<span><?=$info['Alive']?>小时</span>
			</line>
			<line>
				<span class="title">抽奖人数：</span>
				<span><?=$info['LotteryCount']?>人</span>
			</line>
			</tabarea>
			<tabarea>
			<line>
				<span class="title" onclick="getContent()">产品详情：</span>
				<span><?=$info['Content']?></span>
			</line>
			</tabarea>
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
		
		$('tabarea').hide();
		$('tabarea').eq(0).show();

		$('.tab-box span').click(function(){
			$('.tab-box span').removeClass('sel');
			$(this).addClass('sel');
			var index = $(this).index();
			$('tabarea').hide();
			$('tabarea').eq(index).show();
		});
		

		var IsDisable = '<?=$info["IsDisable"]?>';
		var IsForSale = '<?=$info["IsForSale"]?>';
		$('input[name="IsDisable"]').attr("checked",false);
		$('input[name="IsDisable"][value="'+IsDisable+'"]').prop("checked",true);
		$('input[name="IsForSale"]').attr("checked",false);
		$('input[name="IsForSale"][value="'+IsForSale+'"]').prop("checked",true);

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