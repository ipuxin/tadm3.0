<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 订单批量处理页 ***

创建 2016-02-29 刘深远 

*** ***/
?>
<style>
form{margin-top:12px;}
input.button{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;}
tabarea{display:none}
</style>
<body>
	<section>
		<div class="title"><span>订单批量处理</span></div>
		<div class="tab-box" style="margin-bottom:12px;">
            <span class="sel">订单导出</span>
        </div>
		<tabarea>
		<form class="tab" action="/excelPrint/outputListSel" method="post">
			<line>
				<span class="title">城市：</span><input type="text" name="CityName">
				<span class="title">店铺名称：</span><input type="text" name="ShopName">
			</line>
			 <line>
				<span class="title">订单号：</span><input class="short" type="text" name="id">
				<span>商品名称：</span><input class="short" type="text" name="ProductName">
				<span>商品ID：</span><input class="short" type="text" name="ProductId">
			</line>
			<line>
				<span class="title">产品类型：</span>
				<select name="ProductType">
					<option value="0">不限</option>
					<option value='1'>普通商品</option>
					<option value='2'>拼团商品</option>
					<option value='3'>免费试用</option>
					<option value='4'>一元夺宝</option>
					<option value='5'>幸运抽奖</option>
				</select>
				<span>产品总价：</span>
				<select name="ProductFee">
					<option value="-1">不限</option>
					<option value="0">等于0</option>
					<option value="1">大于0</option>
				</select>
				<span>订单状态：</span>
				<select name="OrderStatus">
					<option value="-1">不限</option>
					<option value="1">待付款</option>
					<option value="2">已付款</option>
					<option value="3">待发货</option>
					<option value="4">已发货</option>
					<option value="5">已签收</option>
					<option value="6">已取消</option>
					<option value="7">已退款</option>
				</select>
			</line>
			<line>
				<span class="title">收货人：</span><input type="text" name="RealName" class="short">
				<span>手机：</span><input type="text" name="Mobile" class="short">
			</line>
			<line>
				<span class="title">下单时间：</span>
				<input type="text" name="CreateTimeStart" class="short dataTime">
				<span>至</span>
				<input type="text" name="CreateTimeEnd" class="short dataTime">
			</line>
			<line>
				<span class="title"></span><input class="button" type="submit" value="导出">
			</line>
		</form>
		</tabarea>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time.min.js"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="<?=$staticPath?>js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>
		$('input.dataTime').datetimepicker({language: "zh-CN",autoclose: true,minView: "month",format: "yyyy-mm-dd"});

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
	</script>
</body>