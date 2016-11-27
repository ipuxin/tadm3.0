<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 快递商设置页 ***

创建 2016-09-13 刘深远 

*** ***/
?>
<style>
form{margin-top:20px;}
form line>span.title{width:140px;}
btns{display:block;padding-left:148px;}
div.btn{display:inline-block;float:left;height:30px;line-height:30px;border:1px solid #ccc;background-color:#f8f8f8;padding:0 6px;margin-right:4px;margin-bottom:4px;}
div.btn.sel{border-color:#09c;background-color:#0099cc;color:#fff;}
div.btn.dis{background-color:#ccc;color:#fff;}
</style>
<body>
	<section>
		<div class="title"><span>设置热门城市</span></div>
		<form action="/">
			<?foreach($list as $v){
				if($shopKuaidi[$v['Code']]){$sel = 'sel';}else{$sel = '';}
			?>
				<div class="btn <?=$sel?>" data-code="<?=$v['Code']?>" data-name="<?=$v['Name']?>"><span class="title"><?=$v['Name']?></span></div>
			<?}?>
		</form>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<link rel="stylesheet" href="<?=$staticPath?>js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>
		$('div.btn').click(function(){
			var code = $(this).data('code');
			var name = $(this).data('name');
			var has = $(this).hasClass('sel');
			var obj = $(this);
			if(has){
				$.AjaxApi({
					url: "/ajax/delKuaidi",
					data: {code:code,name:name},
					success: function(json){
						obj.removeClass('sel');
					}
				});
			}else{
				$.AjaxApi({
					url: "/ajax/addKuaidi",
					data: {code:code,name:name},
					success: function(json){
						obj.addClass('sel');
					}
				});
			}
		});
	</script>
</body>