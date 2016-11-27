<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 商品列表模板 ***

创建 2016-08-13 刘深远 

*** ***/

?>
<style>
td img{width:40px;height:40px;}
</style>
<body>
	<section>
		<div class="title"><span>商品列表</span></div>
		<form class="tab" action="/product/getProductList">
            <div class="filter-box">
                <line>
					<span>店铺名称：</span><input type="text" name="ShopName">
					<span>产品名称：</span><input type="text" name="ProductName">
					<span>产品类型：</span>
						<select name="ProductType">
							<option value="0">不限类型</option>
							<option value="1">普通</option>
							<option value="2">拼团</option>
							<option value='3'>免费试用</option>
							<option value='4'>一元夺宝</option>
							<option value='5'>幸运抽奖</option>
						</select>
                    <button type="button">查询</button>
                </line>
            </div>
        </form>
		<table class="orderList">
            <tfoot>
                <tr>
                    <td colspan="10">
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
							data: json.ProductList
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
				{name:"ProductId",header:"产品ID"}, 
				{value:function(mo){
					var url = '';
					if(mo.ImageMin)url = mo.ImageMin;
					var img = '<img src="'+url+'">';
					return img;
				},header:"图片"},
				{name:"ProductTypeMsg",header:"类型"},
				{name:"ProductName",header:"产品名称"},
				{name:"CityName",header:"城市"},
				{value:function(mo){
					return mo.Prices.Normal;
				},header:"价格"},
				{name:"IsForSaleMsg",header:"上架"},
				{name:"IsDisableMsg",header:"启用"},
				{name:"ShopName",header:"店铺"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.id+"'>查看</button>");
					btn.click(function(){
						var id = $(this).data('id');
						self.location= '/admin4/product/product_info/'+id;
					});
					return btn;
				},header:"操作"}
			],
        };

		$('input.dataTime').datetimepicker({language: "zh-CN",autoclose: true,minView: "month",format: "yyyy-mm-dd"});

		$('form.tab button').click(function(){
			pagejs.page = 1;
			$('form.tab').submit();
		});
		
		pagejs.submit();
		$('form.tab').submit();
		//pagejs.loadList(1);
	</script>
</body>