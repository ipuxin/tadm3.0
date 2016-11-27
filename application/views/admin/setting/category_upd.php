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
#Image{width:160px;height:200px;}
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;}
div.btn:hover{background-color:#fff;}
</style>
<body class="bg-grey">
	<warpbox>
		<tit><span>编辑</span> 分类</tit>

		<form class="tab" action="/category/updCate">
			<input type="hidden" name="id" value="<?=$cate['id']?>">
			<?if($cate['CateLevel'] != 1){?>
			<line>
				<span class="title">父级分类：</span>
				<select name="CateParent">
					<?foreach($list as $v){?>
					<option value="<?=$v['id']?>" <?if($cate['CateParent']==$v['id']){?>selected<?}?>><?=$v['CateName']?></option>
					<?}?>
				</select>
			</line>
			<line style="height:200px;">
				<input type="hidden" id="ImageInput" name="ImgUrl" value='<?=$cate['ImgUrl']?>'>
				<span class="title">图片上传：</span>
				<span><img id="Image" class="min" src="<?=$cate['ImgUrl']?>"></span>
				<label for="ImageButton"><div class="btn">上传</div></label>
				<span>(50KB以内,宽160*200高)</span>
			</line>
			<?}?>
			<line>
				<span class="title">分类名称：</span>
				<input type="text" data-valids="required" class="valid" name="CateName" value="<?=$cate['CateName']?>">
			</line>
			<line>
				<span class="title">分类排序：</span>
				<input type="text" data-valids="number" class="valid" name="CateSorting" value="<?=$cate['CateSorting']?>">
				<span>(数字越大越靠前)</span>
			</line>
			<?if($cate['CateLevel'] != 1){?>
			<line>
				<span class="title">是否禁用：</span>
				<input type="radio" name="IsDisable" value="0" checked="checked"><span>启用</span>
				<input type="radio" name="IsDisable" value="1"><span>禁用</span>
			</line>
			<?}?>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
		</form>
		<form method="POST" action="/upload/cateImage" accept-charset="UTF-8" id="uploadImgForm" enctype="multipart/form-data">
		<input name="ImageUpload" id="ImageButton" type="file" class="manual-file-chooser js-manual-file-chooser js-avatar-field" style="display:none">
		</form>
		<script type="text/javascript">
		 $(document).ready(function(){
				var options = {
					beforeSubmit:  showRequest,
					success:       showResponse,
					dataType: 'json'
				};
				$('#ImageButton').on('change', function(){
					$('#uploadImgForm').ajaxForm(options).submit();
				});
			});
			
		function showRequest() {return true;}
		function showResponse(response)  {
			if(response.error){
				alert(response.error.error);
			}else{
				var imgUrl = response.imageUrl;
				$('#Image').attr('src',imgUrl);
				$('#ImageInput').val(imgUrl);
			}
		}
		</script>
	</warpbox>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script>

		var IsDisable = '<?=$cate["IsDisable"]?>';
		$('input[name="IsDisable"]').attr("checked",false);
		$('input[name="IsDisable"][value="'+IsDisable+'"]').prop("checked",true);
		
		$('form.tab').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					self.location= '/admin/setting/category';
				}else{
					location.reload();
				}
			}
		});

	</script>
</body>