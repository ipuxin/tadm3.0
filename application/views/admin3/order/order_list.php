<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 订单列表模板 ***

创建 2016-02-01 刘深远
编辑 2016-02-21 添加产品类型显示,各种筛选,模糊查询

*** ***/

?>
<style>
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;display:inline-block;color:#333;margin-left:8px;cursor:pointer;}
div.btn:hover{color: #333;border: 1px solid #ddd;background-color: #fff;}
</style>
<body>
	<section>
		<div class="title"><span>订单列表</span></div>
		<form class="tab" action="/order/getOrderList">
			<input type="hidden" name="OrderStatus" value="<?=$OrderStatus?>">
            <div class="filter-box">
                <line>
					<span>订单号：</span><input class="short" type="text" name="id">
					<span>商品名称：</span><input class="short" type="text" name="ProductName">
					<span>商品ID：</span><input class="short" type="text" name="ProductShowId">
					<span>产品总价：</span>
					<select name="ProductFee">
						<option value="-1">不限</option>
						<option value="0">等于0</option>
						<option value="1">大于0</option>
					</select>
				</line>
				<line>
					<span>商品类型：</span>
					<select id="ProductType" name="ProductType">
						<option value='-1'>不限</option>
						<option value='1'>普通商品</option>
						<option value='2'>拼团商品</option>
						<option value='3'>免费试用</option>
						<option value='4'>一元夺宝</option>
						<option value='5'>幸运抽奖</option>
					</select>
					<span>收货人：</span><input type="text" name="RealName" class="short">
					<span>手机：</span><input type="text" name="Mobile" class="short">
				</line>
				<line>
					<span>下单时间：</span>
					<input type="text" name="CreateTimeStart" class="dataTime">
					<span>至</span>
					<input type="text" name="CreateTimeEnd" class="dataTime">
                    <button type="button">查询</button>
                </line>
            </div>
        </form>
		<table class="orderList">
            <tfoot>
                <tr>
                    <td colspan="12">
                        <div class="fr page-box"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/table.js?v=<?=$version?>"></script>
    <script src="<?=$staticPath?>js/ext/page.js?v=<?=$version?>"></script>
    <script src="<?=$staticPath?>js/ext/tab.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/table.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time.min.js"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="<?=$staticPath?>js/ext/datetimepicker/css/time.css" type="text/css" />
	<script type="text/javascript">

		$('input').keyup(function(e){
			if(e.keyCode==13){
				$('form.tab').submit();
			}
		});
		var pagejs = {
			page: 1,
			perpage: 15,
			submit: function(){
				$('form.tab').FormAjax({
					datas:{
						page:function(){return pagejs.page},
						perpage:function(){return pagejs.perpage;}
					},
					success:function(json){
						if(json.ErrorCode){
							if(json.ErrorCode != 235){
								$.tip(json.ErrorMsg,2);
							}
						}
						pagejs.perpage = json.PerPage;
						$("table.orderList").bindtable({
							model: pagejs.datamodel,
							data: json.OrderList
						});
						$("tfoot .page-box").pager({
							pagesize: json.PerPage,
							records: json.Count,
							curpage: pagejs.page,
							change: function(page){
								pagejs.page = page;
								$('form.tab').submit();
							}
						});
					}
				});
			},
            datamodel: [
				{name:"OrderId",header:"订单号"}, 
				{name:"OrderTypeMsg",header:"订单类型"},
				{name:"ProductNameShow",header:"产品名称"},
				{name:"RealName",header:"收货人"},
				{name:"Mobile",header:"手机"},
				{name:"OrderStatusMsg",header:"订单状态"},
				{name:"ProductCount",header:"数量"},
				{name:"ProductFee",header:"产品总价"},
				{name:"Remark",header:"备注"},
				{name:"CreatDateShow",header:"下单时间"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.id+"'>订单详情</button>");
					btn.click(function(){
						self.location='/admin3/order/order_info/'+$(this).data('id');
					});

					var btn2 = $("<button data-id='"+mo.TeamId+"'>拼团详情</button>");
					btn2.click(function(){
						self.location='/admin3/team/team_info/'+$(this).data('id');
					});

					var btn3 = $("<button data-userid='"+mo.UserId+"'>推送优惠券</button>");
					btn3.click(function(){
						var userId = $(this).data('userid');
						window.open("/admin3/coupon/coupon_send/"+userId,'newindow','height=300,width=350,top=400,left=800,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no');
					});
					var warp = $('<btns></btns>');
					warp.append(btn);
					if(mo.TeamId)warp.append(btn2);
					warp.append(btn3);
					return warp;
				},header:"操作"}
			],
        };

		$('input.dataTime').datetimepicker({
			language: "zh-CN",
			autoclose: true,
			minView: "hour",
			minuteStep: 1,
			format: "yyyy-mm-dd hh:ii"});

		$('form.tab button').click(function(){
			pagejs.page = 1;
			$('form.tab').submit();
		});
		
		pagejs.submit();
		$('form.tab').submit();
		//pagejs.loadList(1);
	</script>
</body>