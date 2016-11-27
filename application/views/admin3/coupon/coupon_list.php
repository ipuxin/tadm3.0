<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 优惠券列表模板 ***

创建 2016-02-20 刘深远 

*** ***/

?>
<style>
td{vertical-align:middle;}
td img{height:60px;width:60px;}
</style>
<body>
	<section>
		<div class="title"><span>优惠券列表</span></div>
		<form class="tab" action="/coupon/getCouponList">
            <div class="filter-box">
                <line>
					<span>优惠券名称：</span><input type="text" name="CouponName">
                    <button type="button">查询</button>
                </line>
            </div>
        </form>
		<table class="orderList">
            <tfoot>
                <tr>
                    <td colspan="9">
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
							data: json.CouponList
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
				//{name:"ProductId",header:"产品ID"}, 
				{value:function(mo){
					var btn = $('<a href="/admin3/coupon/coupon_upd/'+mo.id+'">'+mo.CouponName+'</a>');
					return btn;
				},header:"优惠券名称"},
				{name:"CouponAmount",header:"优惠卷金额"},
				{name:"CountGived",header:"发放数量"},
				{name:"ShareCount",header:"分享次数"},
				//{name:"ShareUrl",header:"分享链接"},
				{name:"ShareImage",header:"分享二维码"},
				{name:"IsDisableMsg",header:"状态"},
				{name:"SendDateStartShow",header:"开始时间"},
				{name:"SendDateEndShow",header:"结束时间"},
				{value:function(mo){
					var btn = $("<button data-id='"+mo.id+"'>删除</button>");
					btn.click(function(){
						var id = $(this).data('id');
						var obj = $(this);
						$.AjaxApi({
							data: {id:id},
							url: "/coupon/couponHide",
							success: function (d) {
								if(d.Num){
									var tr = obj.parent().parent();
									tr.remove();
								}
							}
						});
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