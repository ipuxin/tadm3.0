<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 会员列表模板 ***

创建 2016-08-13 刘深远

*** ***/

?>
<style>
td img{height:40px;width:40px;}
</style>
<body>
	<section>
		<div class="title"><span>会员列表</span></div>
		<form class="tab" action="/user/getUserList">
			<input type="hidden" name="OrderStatus" value="<?=$OrderStatus?>">
            <div class="filter-box">
                <line>
					<span>昵称：</span><input class="short" type="text" name="NickName">
					<span>姓名：</span><input class="short" type="text" name="RealName">
					<span>区：</span><input class="short" type="text" name="DistrictName">
					<span>地址：</span><input class="short" type="text" name="Address">
                </line>
				<line>
					<span>APP登录：</span>
					<select name="AppLogined">
						<option value="0">不限</option>
						<option value="1">登录过</option>
					</select>
					<!--<span>上次关注时间：</span>
					<input type="text" name="CreateTimeStart" class="dataTime">
					<span>至</span>
					<input type="text" name="CreateTimeEnd" class="dataTime">-->
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
							data: json.UserList
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
				{name:"SubscribeShow",header:"已关注"},
				{name:"AppLoginedShow",header:"登录APP"},
				{name:"NickName",header:"昵称"}, 
				{value:function(mo){
					var btn = $("<img src='"+mo.Thumbnail+"'>");
					if(mo.Thumbnail)return btn;
				},header:"头像"},
				{name:"SexShow",header:"性别"},
				{name:"RealName",header:"姓名"},
				{name:"Mobile",header:"手机"},
				{name:"ProviceName",header:"省"},
				{name:"CityName",header:"市"},
				{name:"DistrictName",header:"区"},
				{name:"Address",header:"地址"},
				{name:"SubscribeDate",header:"关注时间"}
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