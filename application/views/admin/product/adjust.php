<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 分类编辑页 ***

创建 2016-09-12 刘深远 

*** ***/
?>
<style>
tit{margin-bottom:20px;}
tit button{margin-left:12px;}
itemList item.green{background-color:#009a61;color:#fff}
</style>
<body class="bg-grey">
	<warpbox>
		<tit><span>设置</span> 销量</tit>

		<form action="/admin/Adjust" method='post'>
			<input type="hidden" name="id" value="<?=$info['id']?>">
			<line>
				<span class="title">设置销量：</span>
				<i>展示销量 = 实际销量 + 未成团数量 + 设置销量</i>
				<input type="text" data-valids="required" class="valid" name="Adjust" value="<?= $_GET['Adjust']?>">
				<input type="hidden" name="Waiting" value="<?=$_GET['Waiting']?>">
				<input type="hidden" name="Real" value="<?=$_GET['Real']?>">
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
		</form>
	</warpbox>
    <!-- <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script> -->
	<!-- <script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script> -->
	<<!-- script>
		
		$('form').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					self.location= '/admin/setting/category';
				}else{
					location.reload();
				}
			}
		});

	</script> -->
</body>