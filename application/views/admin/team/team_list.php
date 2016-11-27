<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 拼团列表模板 ***

创建 2016-02-21 刘深远

*** ***/

?>
<body>
	<section>
		<div class="title"><span>拼团列表</span></div>
		<form class="tab" action="/team/getTeamList">
			<input type="hidden" name="TeamStatus" value="<?=$TeamStatus?>">
            <div class="filter-box">
                <line>
					<span>城市：</span><input class="short" type="text" name="CityName">
					<span>店铺名称：</span><input class="short" type="text" name="ShopName">
					<span>团购编号：</span><input class="short" type="text" name="TeamId">
				</line>
				<line>
					<span>商品名称：</span><input class="short" type="text" name="ProductName">
					<span>商品ID：</span><input class="short" type="text" name="ProductId">
					<span>商品类型：</span>
					<select id="ProductType" name="ProductType">
						<option value='-1'>不限</option>
						<option value='2'>拼团商品</option>
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
							data: json.TeamList
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
				{value:function(mo){
					var a = $("<a href='/admin/team/team_info/"+mo.TeamId+"'>"+mo.TeamId+"</button>");
					return a;
				},header:"团购编号"},
				{name:"CityName",header:"城市"},
				{name:"ProductName",header:"商品名称"},
				{name:"TeamStatusMsg",header:"开团状态"},
				{value:function(mo){
					return mo.Members[0].NickName;
				},header:"团长"},
				{name:"MaxOrderCount",header:"所需人数"},
				{value:function(mo){
					return mo.MaxOrderCount-mo.Members.length;
				},header:"缺少人数"},
				{name:"CreatDate",header:"开团时间"},
				{name:"EndDate",header:"到期时间"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.TeamId+"'>点击成团</button>");
					btn.click(function(){
						var id = $(this).data('id');
						var obj = $(this).parent().parent();
						$.AjaxApi({
							data: {TeamId:id},
							url: "/team/setTeamSuccess",
							success: function (d) {
								if(d.Code==0){
									obj.find('td').eq(3).html('拼团完成');
									obj.find('td').eq(6).html('0');
									obj.find('button').remove();
								}else{
									alert(d.Message);
								}
							}
						});
					});
					//if(mo.TeamStatus==2)return btn;
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