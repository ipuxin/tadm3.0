<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 优惠券添加页 ***

创建 2016-02-20 刘深远 

*** ***/
?>
<style>
form line>span.title{width:110px;}
</style>
<body>
	<section>
		<div class="title"><span>发布优惠券</span></div>
		<info class="success" style="display:none"><span></span><p></p></info>
		<info class="warning" ><p>谨慎操作</p></info>
		<form action="/coupon/Addcoupon">
			<line>
				<span class="title">优惠券名称：</span>
				<input data-valids="required" class="long valid" name="CouponName" type="text" value="">
			</line>
			<line>
				<span class="title">满减额度：</span>
				<input data-valids="number" class="short valid" name="CouponLimits" type="text" value="0"><span>超过则可使用，0为不限</span>
			</line>
			<line>
				<span class="title">支付方式限制：</span>
				<select name="OrderType">
					<option value='0'>不限</option>
					<option value='1'>单买</option>
					<option value='2'>开团</option>
					<option value='3'>参团</option>
				</select>
			</line>
			<line>
				<span class="title">限制商品id：</span>
				<input class="long valid" name="ProductId" type="text" value="">
			</line>
			<line>
				<span class="title">推荐商品id：</span>
				<input data-valids="required" class="long valid" name="ProductTuijian" type="text" value="">
			</line>
			<line>
				<span class="title">发放数量：</span>
				<input data-valids="number" class="short valid" name="CountLimit" type="text" value="0"><span>0为不限</span>
			</line>
			<line>
				<span class="title">优惠金额：</span>
				<input data-valids="number" class="short valid" name="CouponAmount" type="text" value="">
			</line>
			<line>
				<span class="title">发放方式：</span>
				<select name="SendType">
					<option value='1'>无限制</option>
					<option value='2'>限一张</option>
					<option value='3'>限每天一张</option>
				</select>
			</line>
			<line>
				<span class="title">发放时间：</span>
				<input type="text" data-valids="required" name="SendDateStart" class="short valid dataTime">
				<span>至</span>
				<input type="text" data-valids="required" name="SendDateEnd" class="short valid dataTime">
			</line>
			<line>
				<span class="title">是否绝对时间：</span>
				<input type="radio" name="IsUsedDate" value="1" checked="checked"><span>是</span>
				<input type="radio" name="IsUsedDate" value="0"><span>否</span>
			</line>
			<line class="useDate">
				<span class="title">使用时间：</span>
				<input type="text" data-valids="required" name="StartDate" class="short valid dataTime">
				<span>至</span>
				<input type="text" data-valids="required" name="ExpiryDate" class="short valid dataTime">
			</line>
			<line class="useDays">
				<span class="title">有效天数：</span>
				<input data-valids="natural number" class="short valid" name="UseableDays" type="text" value="0">
			</line>
			<line>
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
		
		$('input.dataTime').datetimepicker({language: "zh-CN",autoclose: true,minView: "month",format: "yyyy-mm-dd"});

		$('line.useDays').hide();
		$('line.useDays input').removeClass('valid');
		$('input[name=IsUsedDate]').click(function(){
			var val = $(this).val();
			if(val==0){
				$('line.useDate').hide();$('line.useDays').show();
				$('line.useDate input').removeClass('valid');
				$('line.useDays input').addClass('valid');
			}else{
				$('line.useDate').show();$('line.useDays').hide();
				$('line.useDays input').removeClass('valid');
				$('line.useDate input').addClass('valid');
			}
		});

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