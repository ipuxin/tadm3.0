<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 焦点图添加页 ***

创建 2016-09-12 刘深远 

*** ***/
?>
<style>
tit{margin-bottom:20px;}
tit button{margin-left:12px;}
itemList item.green{background-color:#009a61;color:#fff}
#Image{width:320px;height:160px;}
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;}
div.btn:hover{background-color:#fff;}
</style>
<body class="bg-grey">
	<warpbox>
		<tit><span>添加</span> 焦点图</tit>

		<form class="tab" action="/banner/addBanner">
			<line style="height:180px;">
				<input type="hidden" id="ImageInput" name="Url" value=''>
				<span class="title">图片上传：</span>
				<span><img id="Image" class="min" src=""></span>
				<label for="ImageButton"><div class="btn">上传</div></label>
				<span>(150KB以内,宽640*320高)</span>
			</line>
			<line>
				<span class="title">图片名称：</span>
				<input type="text" data-valids="required" class="valid" name="Name" value="">
			</line>
			<line>
				<span class="title">图片排序：</span>
				<input type="text" data-valids="number" class="valid" name="Paixu" value="0">
				<span>(数字越大越靠前)</span>
			</line>
			<line>
				<span class="title">是否禁用：</span>
				<input type="radio" name="IsDisable" value="0" checked="checked"><span>启用</span>
				<input type="radio" name="IsDisable" value="1"><span>禁用</span>
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
		</form>
		<form method="POST" action="/upload/bannerImage" accept-charset="UTF-8" id="uploadImgForm" enctype="multipart/form-data">
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
		
		$('form.tab').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					//self.location= '/admin/setting/setting_banner';
				}else{
					self.location= '/admin/setting/setting_banner';
				}
			}
		});

	</script>
</body>