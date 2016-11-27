<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 提现明细 ***

创建 2016-09-17 刘深远 

*** ***/

?>
<style>
td img{width:40px;height:40px;}
</style>
<body>
	<section>
		<div class="title"><span>提现明细</span></div>
		<form class="tab" action="/tixian/getList">
            <div class="filter-box">
                <line>
					<span>用户类型：</span>
					<select name="UserType">
						<option value="">不限</option>
						<option value="合伙人">合伙人</option>
						<option value="店铺">店铺</option>
					</select>
					<span>申请时间：</span>
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
							data: json.TXList
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
				{name:"UserType",header:"用户类型"}, 
				{name:"UserRealName",header:"真实姓名"},
				{name:"UserShowName",header:"用户名称"},
				{name:"UserMobile",header:"手机"},
				{name:"Type",header:"提现状态"},
				{name:"AccountName",header:"开户名称"},
				{name:"Account",header:"提现账号"},
				{name:"AccountType",header:"账号类型"},
				{name:"Amount",header:"申请金额"},
				{name:"AmountReal",header:"已转转账"},
				{name:"CreatTimeDate",header:"申请时间"},
				{name:"OrderId",header:"转账单号"},
				{name:"UpdateTimeDate",header:"转账时间"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.id+"'>编辑</button>");
					btn.click(function(){
						var id = $(this).data('id');
						self.location= '/admin/tixian/tixian_upd/'+id;
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