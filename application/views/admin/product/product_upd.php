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

tabarea.city line>span.title{width:140px;}
tabarea.city btns{display:block;padding-left:148px;}
tabarea.city div.btn{display:inline-block;float:left;height:30px;line-height:30px;border:1px solid #ccc;background-color:#f8f8f8;padding:0 6px;margin-right:4px;margin-bottom:4px;margin-top:0;}
tabarea.city div.btn.sel{border-color:#09c;background-color:#0099cc;color:#fff;}
tabarea.city div.btn.dis{background-color:#ccc;color:#fff;}
</style>
<body>
	<section>
		<div class="title"><span>发布商品</span></div>
		<div class="tab-box" style="margin-bottom:12px;">
            <span class="sel">基本信息</span>
			<span class="">拼团设置</span>
			<span class="">商品详情</span>
			<span class="">展示城市</span>
        </div>
		<form class="tab" action="/product/updProduct">
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
				<a href="/admin/product/Adjust/<?=$info['id']?>?Real=<?=$info['SalesCount']['Real']?>&Waiting=<?=$info['SalesCount']['Waiting']?>&Adjust=<?=$info['SalesCount']['Adjust']?>">设置销量</a>
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
			<!--<line>
				<span class="title">展示到全国：</span>
				<input type="radio" name="IsAllCity" value="0" checked="checked"><span>否</span>
				<input type="radio" name="IsAllCity" value="1"><span>是</span>
			</line>-->
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
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
			</form>
			<tabarea class="city">
			<form>
				<line class="bbb">
					<span class="title"></span>
					<div class="btn quanxuan">全选</div>
					<div class="btn buxuan">不选</div>
					<div class="saveCity" style="cursor:pointer;">保存</div>

				</line>
			</form>
			</tabarea>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script src="/data/file/js/ext/datetimepicker/time.min.js"></script>
	<script src="/data/file/js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="/data/file/js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>

		var productId = "<?=$info['id']?>";
		var showCityCode = new Array;

		<?if($info['CityCode'] && is_array($info['CityCode'])){foreach($info['CityCode'] as $v){?>
			showCityCode.push("<?=$v?>");
		<?}}?>
		
		$('tabarea').hide();
		$('tabarea').eq(0).show();

		$('.tab-box span').click(function(){
			$('.tab-box span').removeClass('sel');
			$(this).addClass('sel');
			var index = $(this).index();
			$('tabarea').hide();
			$('tabarea').eq(index).show();
		});
		$('.bbb .quanxuan').click(function(){
			$('.btn').addClass('sel');
		});
		$('.bbb .buxuan').click(function(){
			$('.btn').removeClass('sel');
		});

		

		var IsDisable = '<?=$info["IsDisable"]?>';
		var IsForSale = '<?=$info["IsForSale"]?>';
		var IsAllCity = '<?=$info["IsAllCity"]?>';
		$('input[name="IsDisable"]').attr("checked",false);
		$('input[name="IsDisable"][value="'+IsDisable+'"]').prop("checked",true);
		$('input[name="IsForSale"]').attr("checked",false);
		$('input[name="IsForSale"][value="'+IsForSale+'"]').prop("checked",true);
		$('input[name="IsAllCity"]').attr("checked",false);
		$('input[name="IsAllCity"][value="'+IsAllCity+'"]').prop("checked",true);

		$('form.tab').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					location.reload();
				}
			}
		});

		$('div.saveCity').click(function(){
			saveCityData();
		});

		$.AjaxApi({
			url: "/division/getDivisionsList",
			success: function(json){
				if(json.Result){
					printCityList(json.Result);
				}else{
					$.tip('拉取地理信息失败',2);
				}
			}
		});

		function printCityList(list){
			for(var i in list){
				var pro = list[i];
				var line = $('<line><span class="title">'+pro.Name+'：</span></line>');
				var btns = $('<btns></btns>');
				var btn = '';
				for(var n in pro.Cities){
					var city = pro.Cities[n];
					btn = '<div id="box_'+city.Id+'" data-code="'+city.Id+'" data-name="'+city.Name+'" class="btn">'+city.Name+'</div>';
					btns.append(btn);
				}
				line.append(btns);
				if(btns.html())$('tabarea.city line.bbb').before(line);
			}
			setCityClick();
			setCityUsedData();
		}

		function setCityUsedData(){
			for(var i in showCityCode){
				var id = showCityCode[i];
				$('#box_'+id).addClass('sel');
			}
		}

		function setCityClick(){
			$('div.btn').click(function(){
				var has = $(this).hasClass('sel');
				var dis = $(this).hasClass('dis');
				if(dis)return;
				var name = $(this).data('name');
				var code = $(this).data('code');
				if(!has){
					$(this).addClass('sel');
				}else{
					$(this).removeClass('sel');
				}
			});
		}

		function saveCityData(){
			var codeArr = new Array();
			$('div.btn.sel').each(function(){
				var obj = $(this);
				var code = $(this).data('code');
				codeArr.push(code);
			});
			$.AjaxApi({
				url: "/product/updProductCitys",
				data : {id:productId,Code:codeArr},
				success: function(json){
					if(json.Num){
						$.tip('更新成功');
					}else{
						//$.tip('拉取地理信息失败',2);
					}
				}
			});
		}
	</script>
</body>