<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 加盟商列表模板 ***

创建 2016-02-26 刘深远

*** ***/

?>
<body>
	<section>
		<div class="title"><span>加盟商列表</span></div>
		<form class="tab" action="/jiameng/getJiamengList">
            <div class="filter-box">
                <line>
					<span>加盟商：</span><input class="short" type="text" name="Username">
					<span>状态：</span>
					<select name="IsDisable">
						<option value="-1">不限</option>
						<option value="0">启用</option>
						<option value="1">关闭</option>
					</select>
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
							data: json.AdminList
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
				{name:"Username",header:"加盟商"}, 
				{name:"Account",header:"加盟商账号"}, 
				{name:"RealName",header:"联系人"}, 
				{name:"Mobile",header:"联系电话"}, 
				{name:"CityName",header:"管辖范围"},
				{name:"IsDisableShow",header:"状态"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.id+"'>编辑</button>");
					btn.click(function(){
						self.location='/admin/jiameng/jiameng_upd/'+$(this).data('id');
					});
					return btn;
				},header:"操作"}
			],
        };

		$('form.tab button').click(function(){
			pagejs.page = 1;
			$('form.tab').submit();
		});
		
		pagejs.submit();
		$('form.tab').submit();
	</script>
</body>