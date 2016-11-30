<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 订单详情页 ***

创建 2016-02-26 刘深远 

*** ***/
?>
<style>
section{padding-bottom:100px;}
form line>span{color:#f00;float:left;}
td{width:50%;}
</style>
<body>
	<section>
		<div class="title"><span>订单详情</span></div>
		<div class="tab-box" style="margin-bottom:12px;">
            <span class="sel">订单信息</span>
			<span class="">商品信息</span>
			<span class="">店铺信息</span>
			<span class="">发货信息</span>
			<span class="">快递信息</span>
        </div>
		<form class="refund" action="/order/Refundorder">
		<input type="hidden" name="id" value="<?=$order['id']?>">
		<tabarea>
			<div class="title2">订单信息</div>
			<table class="table">
				<tr>	
					<td>订单编号：<?=$order['OrderId']?></td>
					<td>订单类型：<?=$order['OrderTypeMsg']?></td>
				</tr>
				<tr>	
					<td>创建时间：<?=$order['CreatDateShow']?></td>
					<td>订单状态：<?=$order['OrderStatusMsg']?></td>
				</tr>
				<tr>	
					<td>购买数量：<?=$order['ProductCount']?></td>
					<td>订单总价：<?=$order['ProductFee']?></td>
				</tr>
				<tr>	
					<td>应付总价：<?=$order['OrderFee']?> (运费：<?=$order['freightFee']?>)</td>
					<td>实际支付：<?=$order['PayAmount']?></td>
				</tr>
			</table>
			<div class="title2">优惠信息</div>
			<table class="table">
				<tr>	
					<td>优惠券：<?=$order['Coupons']['CouponName']?></td>
					<td>优惠券面额：<?=$order['Coupons']['CouponAmount']?></td>
				</tr>
			</table>
			<div class="title2">支付信息</div>
			<table class="table">
				<tr>	
					<td>支付金额：<?=$order['PayAmount']?></td>
					<td>支付状态：<?=$order['PayStatus']?></td>
				</tr>
				<tr>	
					<td>支付方式：<?=$order['PayType']?></td>
					<td>支付单号：<?=$order['PayTradeNo']?></td>
				</tr>
			</table>
			<div class="title2">收货信息</div>
			<table class="table">
				<tr>	
					<td>收货人：<?=$order['DeliveryInfo']['RealName']?></td>
					<td>收货电话：<?=$order['DeliveryInfo']['Mobile']?></td>
				</tr>
				<tr>	
					<td colspan=2>收货地址：<?=$order['DeliveryInfo']['RealAddress']?></td>
				</tr>
				<tr>	
					<td>快递商：<?=$order['Logistics']['LogisticsName']?></td>
					<td>快递单号：<?=$order['Logistics']['LogisticsNum']?></td>
				</tr>
			</table>
			<div class="title2">退款信息</div>
			<table class="table">
				<tr>	
					<td>已退金额：<?=$order['RefundFee']?></td>
					<td>退款时间：<?=$order['RefundDate']?></td>
				</tr>
				<tr>	
					<td colspan=2>
						<span class="title">退款金额：</span>
						<input type="text" id="refundFee" name="refundFee" placeholder="不填则全退">
						<button>退款</button>
					</td>
				</tr>
			</table>
		</tabarea>
		</form>
		<form class="info" action="/order/Updorder">
		<input type="hidden" name="id" value="<?=$order['id']?>">
		<tabarea>
			<?if($order['ProductList']){foreach($order['ProductList'] as $k=>$pro){?>
			<div class="title2"><?=$pro['ProductName']?></div>
			<table class="table">
				<tr>
					<td>商品数量：<?=$pro['ProductCount']?></td>
					<td>商品价格：<?=$pro['Prices']?></td>
				</tr>
				<tr>	
					<td colspan=2>
						<img style="width:40px;height:40px;" src="<?=$pro['ImageMin']?>">
						<?=$pro['Description']?>
					</td>
				</tr>
			</table>
			<?}}else{?>
			<div class="title2"><?=$order['ProductInfo']['ProductName']?></div>
			<table class="table">
				<tr>
					<td>商品数量：<?=$order['ProductCount']?></td>
					<td>商品价格：<?=$order['ProductInfo']['Prices']?></td>
				</tr>
				<tr>	
					<td colspan=2>
						<img style="width:40px;height:40px;" src="<?=$order['ProductInfo']['ImageMin']?>">
						<?=$order['ProductInfo']['Description']?>
					</td>
				</tr>
			</table>
			<?}?>
		</tabarea>
		<tabarea>
			<div class="title2">店铺信息</div>
			<table class="table">
				<tr>	
					<td>店铺名称：<?=$shop['ShopName']?></td>
					<td>所属城市：<?=$shop['CityName']?></td>
				</tr>
				<tr>	
					<td colspan=2>店铺介绍：<?=$shop['ShopDescription']?></td>
				</tr>
				<tr>	
					<td colspan=2>店铺地址：<?=$shop['ShopAddress']?></td>
				</tr>
				<tr>	
					<td colspan=2>发货地址：<?=$shop['DeliverAddress']?></td>
				</tr>
				<tr>	
					<td colspan=2>退货地址：<?=$shop['ReturnAddress']?></td>
				</tr>
			</table>
			<div class="title2">店铺联系人信息</div>
			<table class="table">
				<tr>	
					<td>联系人：<?=$shop['ShopOwnerName']?></td>
					<td>联系电话：<?=$shop['ShopOwnerMobile']?></td>
				</tr>
			</table>
		</tabarea>
		<tabarea>
			<line>
				<span class="title">收货人：</span>
				<span>
					<input type="text" name="DeliveryInfo[RealName]" value="<?=$order['DeliveryInfo']['RealName']?>">
					<input type="text" name="DeliveryInfo[Mobile]" value="<?=$order['DeliveryInfo']['Mobile']?>">
				</span>
			</line>
			<line>
				<span class="title">收货地址：</span>
				<span>
					<input id="ProviceName" type="hidden" name="DeliveryInfo[ProviceName]" value="<?=$order['DeliveryInfo']['ProviceName']?>">
					<select id="ProviceCode" name="DeliveryInfo[ProviceCode]"><option value="110000">北京市</option><option value="120000">天津市</option><option value="130000">河北省</option><option value="140000">山西省</option><option value="150000">内蒙古自治区</option><option value="210000">辽宁省</option><option value="220000">吉林省</option><option value="230000">黑龙江省</option><option value="310000">上海市</option><option value="320000">江苏省</option><option value="330000">浙江省</option><option value="340000">安徽省</option><option value="350000">福建省</option><option value="360000">江西省</option><option value="370000">山东省</option><option value="410000">河南省</option><option value="420000">湖北省</option><option value="430000">湖南省</option><option value="440000">广东省</option><option value="450000">广西壮族自治区</option><option value="460000">海南省</option><option value="500000">重庆市</option><option value="510000">四川省</option><option value="520000">贵州省</option><option value="530000">云南省</option><option value="540000">西藏自治区</option><option value="610000">陕西省</option><option value="620000">甘肃省</option><option value="630000">青海省</option><option value="640000">宁夏回族自治区</option><option value="650000">新疆维吾尔自治区</option><option value="710000">台湾省</option><option value="810000">香港特别行政区</option><option value="820000">澳门特别行政区</option></select>	
					<input id="CityName" type="hidden" name="DeliveryInfo[CityName]" value="<?=$order['DeliveryInfo']['CityName']?>">
					<select id="CityCode" name="DeliveryInfo[CityCode]"></select>
					<input id="DistrictName" type="hidden" name="DeliveryInfo[DistrictName]" value="<?=$order['DeliveryInfo']['DistrictName']?>">
					<select id="DistrictCode" name="DeliveryInfo[DistrictCode]"></select>
					<input type="text" name="DeliveryInfo[Address]" value="<?=$order['DeliveryInfo']['Address']?>">
				</span>
			</line>
			<line>
				<span class="title">快递单号</span>
				<input type="text" name="Logistics[LogisticsNum]" value="<?=$order['Logistics']['LogisticsNum']?>">
				<span class="title">快递商</span>
				<input id="sel_kuaidi_name" type="hidden" name="Logistics[LogisticsName]" value="<?=$order['Logistics']['LogisticsName']?>">
				<select id="sel_kuaidi" name="Logistics[LogisticsCode]">
					<option value="">--未发货--</option>
					<?foreach($kuaidi as $v){
						$sel = '';
						if($v['Code']==$order['Logistics']['LogisticsCode']){$sel = 'selected="selected"';}
					?>
					<option value="<?=$v['Code']?>" <?=$sel?>><?=$v['Name']?></option>
					<?}?>
				</select>
				<span class="title">订单状态</span>
				<select name="OrderStatus">
					<?foreach($status as $k=>$v){
						$sel = '';
						if($k==$order['OrderStatus']){$sel = 'selected="selected"';}
					?>
					<option value="<?=$k?>" <?=$sel?>><?=$v?></option>
					<?}?>
				</select>
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>确定发货</button>
			</line>
			<div class="kuaidiInfo tab-box" style="margin-bottom:12px;margin-top:0;display:none"><span class="sel">快递信息</span></div>
			<line class="kuaidiClu"></line>
		</tabarea>
		<tabarea>
			<div class="title2">快递信息</div>
			<table class="table">
				<?if($order['KuaidiInfo']['Reason']){?>
				<tr>	
					<td><?=$order['KuaidiInfo']['Reason']?></td>
				</tr>
				<?}elseif($order['KuaidiInfo']['Traces']){foreach($order['KuaidiInfo']['Traces'] as $v){?>
				<tr>	
					<td style="width:120px"><?=$v['AcceptTime']?></td>
					<td style="width:auto"><?=$v['AcceptStation']?></td>
				</tr>
				<?}}?>
			</table>
		</tabarea>
		<div style="position:absolute;left:10px;bottom:0px;width:100%;height:40px;background-color:#fff;display:none">
			<button id="kuaidi">查看快递信息</button>
			<button id="returnAll">全部退款</button>
			<button style="position:absolute;top:-54px;left:194px;height:50px;">金额：<input style="boredr:0;margin-right:6px;width:80px;height:30px;padding-left:8px;font-size:24px;line-height:30px;" id="returnMoney" value=""></button>
			<button id="returnNum">部分退款</button>
		</div>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script>
		
		//标签页切换效果
		$('tabarea').hide();
		$('tabarea').eq(0).show();

		$('.tab-box span').click(function(){
			$('.tab-box span').removeClass('sel');
			$(this).addClass('sel');
			var index = $(this).index();
			$('tabarea').hide();
			$('tabarea').eq(index).show();
		});

		var id = "<?=$order['id']?>";
		var OrderStatus = "<?=$order['OrderStatus']?>";


		var pro_code = "<?=$order['DeliveryInfo']['ProviceCode']?>";
		var city_code = "<?=$order['DeliveryInfo']['CityCode']?>";
		var dis_code = "<?=$order['DeliveryInfo']['DistrictCode']?>";
		if(pro_code){$('#ProviceCode').val(pro_code);getCityList(pro_code,1);}
		$('#ProviceCode').change(function(){
			var code = $(this).val();
			var text = $(this).find('option:selected').text();
			$('#ProviceName').val(text);
			getCityList(code);
		});

		function getCityList(code,first){
			$.AjaxApi({
				url: "/division/getCityList/"+code,
				success: function (d) {
					if(d.ErrorCode==0){
						printCityList(d.Result,first);
					}else{
						$.tip('城市拉取失败',2);
					}
				}
			});
		}

		function getDistrictList(code,first){
			$.AjaxApi({
				url: "/division/getDistrictList/"+code,
				success: function (d) {
					if(d.ErrorCode==0){
						printDistrictList(d.Result,first);
					}else{
						$.tip('区域拉取失败',2);
					}
				}
			});
		}

		function printDistrictList(list,first){
			$('#DistrictCode').html('');
			for(var i in list){
				var dis = list[i];
				var sel = '';
				if(first && dis_code==dis.Code)sel = 'selected="selected"';
				var opt = '<option value="'+dis.Code+'" '+sel+'>'+dis.Name+'</option>';
				$('#DistrictCode').append(opt);
			}
			if(!first){
				var option = $('#DistrictCode option').eq(0);
				$('#DistrictName').val(option.text());
			}
		}

		function printCityList(list,first){
			$('#CityCode').html('');
			for(var i in list){
				var city = list[i];
				var sel = '';
				if(first && city_code==city.Code)sel = 'selected="selected"';
				var opt = '<option value="'+city.Code+'" '+sel+'>'+city.Name+'</option>';
				$('#CityCode').append(opt);
			}
			if(!first){
				var option = $('#CityCode option').eq(0);
				$('#CityName').val(option.text());
				getDistrictList(option.val());
			}else{
				if(dis_code && city_code){
					getDistrictList(city_code,1);
				}else{
					getDistrictList(city_code);
				}
			}
		}

		$('#CityCode').change(function(){
			var code = $(this).val();
			var text = $(this).find('option:selected').text();
			$('#CityName').val(text);
			getDistrictList(code);
		});

		$('#DistrictCode').change(function(){
			var text = $(this).find('option:selected').text();
			$('#DistrictName').val(text);
		});

		$('#kuaidi').click(function(){
			$.AjaxApi({
				data: {id:id},
				url: "/order/getKuaidiInfo",
				success: function (d) {
					if(d.Code==0){
						printKuaidi(d.Result.Traces);
					}else{
						$.tip(d.Message,2);
					}
				}
			});
		});

		$('#returnAll').click(function(){
			$.AjaxApi({
				data: {id:id},
				url: "/order/orderRefund",
				success: function (d) {
					if(d.ErrorCode==0 && d.Code==0){
						location.reload();
					}else{
						if(d.Code==0){
							$.tip(d.ErrorMsg,2);
						}else{
							$.tip(d.Message,2);
						}
					}
				}
			});
		});

		$('#returnNum').click(function(){
			var fee = $('#returnMoney').val();
			if(fee==0 || !fee){alert('请输入退款金额！');return;}
			$.AjaxApi({
				data: {id:id,OrderStatus:OrderStatus,fee:fee},
				url: "/order/orderRefund",
				success: function (d) {
					if(d.ErrorCode==0 && d.Code==0){
						location.reload();
					}else{
						if(d.Code==0){
							$.tip(d.ErrorMsg,2);
						}else{
							$.tip(d.Message,2);
						}
					}
				}
			});
		});

		$('#sel_kuaidi').change(function(){
			var code = $(this).val();
			var name = $(this).find('option:selected').text();
			$('#sel_kuaidi_name').val(name);
		});

		function printKuaidi(list){
			$('.kuaidiInfo').show();
			$('.kuaidiClu').show();
			$('.kuaidiClu').html('');
			for(var i in list){
				var msg = list[i];
				var line = '<line><span class="title" style="width:130px;">'+msg.AcceptTime+'</span></span>'+msg.AcceptStation+'</span></line>';
				$('.kuaidiClu').append(line);
			}
		}

		$('form.refund').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					location.reload();
				}
			}
		});

		$('form.info').FormAjax({
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