<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 店铺列表模板 ***

创建 2016-08-14 刘深远

*** ***/

?>
<style>
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;display:inline-block;color:#333;margin-left:8px;cursor:pointer;}
div.btn:hover{color: #333;border: 1px solid #ddd;background-color: #fff;}
td img{height:40px;width:40px;}
</style>
<body>
	<section>
		<div class="title"><span>店铺列表</span></div>
		<form class="tab" action="/shop/getShopList">
			<input type="hidden" name="IsOpenAdminAccount" value="1">
            <div class="filter-box">
                <line>
					<span>店铺名称：</span><input class="short" type="text" name="ShopName">
					<span>创建时间：</span>
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
                    <td colspan="7">
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
							data: json.ShopList
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
				{name:"ShopId",header:"店铺编号"}, 
				{value:function(mo){
					var img = '<img src="'+mo.ShopLogo+'">';
					return img;
				},header:"店铺图标"},
				{name:"ShopName",header:"店铺名称"},
				{name:"CityName",header:"所在城市"},
				{name:"Fans",header:"关注度"},
				{name:"CreatTimeDate",header:"创建时间"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.id+"'>详情</button>");
					btn.click(function(){
						self.location='/admin4/shop/shop_info/'+$(this).data('id');
					});
					var btn2 = $("<button data-id='"+mo.id+"'>禁用</button>");
					btn2.click(function(){
						var id = $(this).data('id');
						var obj = $(this);
						$.AjaxApi({
							data: {ShopId:id,Disable:1},
							url: "/shop/setShopDisable",
							success: function (d) {
								if(d.ErrorCode==0){
									$('form.tab').submit();
								}else{
									alert(d.Message);
								}
							}
						});
					});
					var warp = $('<btns></btns>');
					warp.append(btn);
					//warp.append(btn2);
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