<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 优惠券发放页 ***

创建 2016-02-20 刘深远 

*** ***/
?>
<style>
html, body, header{min-width:350px;width:350px;}
form{margin-top:12px;}
form line>span.title{width:80px;}
</style>
<body>
	<section>
		<div class="title"><span>推送优惠券</span></div>
		<form action="/coupon/couponSend">
			<input type="hidden" name="UserId" value="<?=$UserId?>">
			<line>
				<span class="title">优惠券：</span>
				<select name="CouponId">
					<?foreach($List as $v){?>
					<option value="<?=$v['id']?>"><?=$v['CouponName']?></option>
					<?}?>
				</select>
			</line>
			<line>
				<span class="title">发放数量：</span>
				<input type="text" data-valids="natural" class="short valid" name="CouponNum">
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>发放</button>
			</line>
		</form>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script>
		$('form').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					$.tip('成功领取'+data.Num+'张',2);
				}
			}
		});
	</script>
</body>