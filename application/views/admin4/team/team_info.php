<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 团购详情页 ***

创建 2016-03-02 刘深远 

*** ***/
?>
<style>
section{padding-bottom:50px;}
form line{position:relative;}
form line>span{color:#f00;float:left;}
img.user{height:40px;width:40px;margin-left:30px;}
lottery{position:absolute;left:16px;top:0;background-color:#f00;color:#fff;padding:4px;height:12px;line-height:12px;}
IsNewMember{position:absolute;left:16px;top:20px;background-color:#369;color:#fff;padding:4px;height:12px;line-height:12px;}
</style>
<body>
	<section>
		<div class="title"><span>团购详情</span></div>
			<form>
			<? 
				$pro = $team['ProductInfo'];
				$user = $team['Members'];
			?>
			<div class="tab-box" style="margin-bottom:12px;"><span class="sel">团购信息</span></div>
			<line>
				<span class="title">团购编号：</span><span><?=$team['TeamId']?></span>
				<span class="title">所需人数：</span><span><?=$team['MaxOrderCount']?></span>
				<span class="title">开团时间：</span><span><?=$team['CreatDate']?></span>
			</line>
			<line>
				<span class="title">到期时间：</span><span><?=$team['EndDate']?></span>
				<span class="title">当前状态：</span><span><?=$team['TeamStatusMsg']?></span>
			</line>
			<div class="tab-box" style="margin-bottom:12px;margin-top:0;"><span class="sel">商品信息</span></div>
			<line>
				<span class="title">商品名称：</span>
				<span><?=$pro['ProductName']?></span>
				<span class="title">团购价格：</span>
				<span><?=$pro['Prices']['Team']?></span>
			</line>
			<line>
				<span style="position:absolute;left:0;top:0;"class="title">商品描述：</span>
				<span style="height:auto;padding-left:84px;text-align:left"><?=$pro['Description']?></span>
			</line>
			<div class="tab-box" style="margin-bottom:12px;margin-top:0;"><span class="sel">团员信息</span></div>
			<?foreach($user as $k=>$v){
				$msg = '小兵';
				if($k==0)$msg = '团长';
				if($k==1)$msg = '营长';
				if($k==2)$msg = '连长';
				if($k==3)$msg = '排长';
				if($v['Lottery']){$Lottery = '<lottery>中奖</lottery>';}else{$Lottery = '';}
				if($v['IsNewMember']){$IsNewMember = '<IsNewMember>新人</IsNewMember>';}else{$IsNewMember = '';}
			?>
			<line>
				<span><img class="user" src="<?=$v['Thnumbail']?>"><?=$Lottery.$IsNewMember?></span>
				<span><?=$msg?></span>
				<span><?=$v['RealName']?></span>
				<span><?=$v['Mobile']?></span>
				<span><?=$v['OrderStatusMsg']?></span>
				<span>参团时间：<?=$v['CreatDate']?></span>
				<span><a href="/admin4/order/order_info/<?=$v['OrderRealId']?>">查看订单信息</a></span>
			</line>
			<?}?>
			<line style="display:none">
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
			</form>
			<div style="position:absolute;left:10px;bottom:0px;width:100%;height:40px;background-color:#fff;display:none">
				<button id="lottery">分配中奖</button>
				<button id="orderRefund">未中奖一键退款</button>
			</div>
	</section>
    <script src="<?=$staticPath?>js/init.js"></script>
	<script src="<?=$staticPath?>js/ext/form.js"></script>
	<script>
		var TeamId = '<?=$team["TeamId"]?>';
		$('#lottery').click(function(){
			$.AjaxApi({
				data: {TeamId:TeamId},
				url: "/team/setTeamLottery",
				success: function (d) {
					if(d.Message){
						$.tip(d.Message,2);
					}else{
						location.reload();
					}
				}
			});
		});

		$('#orderRefund').click(function(){
			$.AjaxApi({
				data: {TeamId:TeamId},
				url: "/team/setTeamLotteryRefund",
				success: function (d) {
					location.reload();
				},
				error: function(){
					location.reload();
				}
			});
		});
	</script>
</body>