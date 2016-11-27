<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 交易明细 ***

创建 2016-09-17 刘深远 

*** ***/

?>
<style>
td img{width:40px;height:40px;}
</style>
<body>
	<section>
		<div class="title"><span>交易明细</span></div>
		<form class="tab" action="/product/getProductSaleList">
            <div class="filter-box">
                <line>
					<span>交易时间：</span>
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
							data: json.ProductSaleList
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
				{name:"OrderId",header:"订单ID"},
				{name:"ProductTypeDate",header:"产品类型"},
				{name:"SalesCount",header:"销售数量"},
				{name:"CreatTimeDate",header:"交易时间"}
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