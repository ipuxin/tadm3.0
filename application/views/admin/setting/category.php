<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 产品标签设置页 ***

创建 2016-01-29 刘深远 

*** ***/

?>
<style>
tit button{margin-left:12px;}
/*itemList item{padding:6px;line-height:0;}*/
itemList item{text-align:center;}
itemList item.green{background-color:#009a61;color:#fff}
itemList item.org{background-color:#f93;color:#fff}
img{width:80px;height:80px;margin:auto;display:block;}
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;display:inline-block;margin-left:20px;}
div.btn:hover{background-color:#fff;}
</style>
<body class="bg-grey">
	<warpbox>
		<tit><span>使用中</span> 的分类<button class="addBtn">添加</button></tit>

		<?foreach($list as $k=>$c){?>
			<tit class="s">
				<?=$c['CateName']?>
				<div class="btn" data-id="<?=$c['id']?>">编辑</div>
			</tit>
			<itemlist>
			<?if($c['Child']){foreach($c['Child'] as $v){if($v['IsDisable']){
				$class="org";}else{$class="green";}
			?>
			<item class="<?=$class?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ImgUrl']?>"><br>
				<?=$v['CateName']?>
			</item>
			<?}}?>
			</itemlist>
		<?}?>
	</warpbox>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/modal.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$('button.addBtn').click(function(){
			self.location= '/admin/setting/category_add';
		});

		$('item,div.btn').click(function(){
			var id = $(this).data('id');
			self.location= '/admin/setting/category_upd/'+id;
		});
	</script>
</body>