<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 焦点图设置页 ***

创建 2016-01-29 刘深远 

*** ***/

?>
<style>
tit button{margin-left:12px;}
itemList item{padding:6px;line-height:0;}
itemList item.green{background-color:#009a61;color:#fff}
itemList item.org{background-color:#f93;color:#fff}
img{width:160px;height:80px;}
</style>
<body class="bg-grey">
	<warpbox>
		<tit><span>全部</span> 图片<button class="addBtn">添加</button></tit>
		<itemlist>
		<?foreach($list as $v){
			if($v['IsDisable']){$class="org";}else{$class="green";}
		?>
			<item class="<?=$class?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['Url']?>">
			</item>
		<?}?>
		</itemlist>
	</warpbox>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/modal.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$('button.addBtn').click(function(){
			self.location= '/admin/setting/banner_add';
		});

		$('item').click(function(){
			var id = $(this).data('id');
			self.location= '/admin/setting/banner_upd/'+id;
		});
	</script>
</body>