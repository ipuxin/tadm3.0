<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 管理员编辑页 ***

创建 2016-02-26 刘深远 

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
		<div class="title"><span>编辑加盟商</span></div>
		<form action="/jiameng/updJiameng">
			<input type="hidden" name="id" value="<?=$admin['id']?>">
			<input type="hidden" name="CityName" id="CityName" value="<?=$admin['CityName']?>">
			<input type="hidden" name="CityCode" id="CityCode" value="<?=$admin['CityCode']?>">
			<line>
				<span class="title">加盟商名称：</span>
				<input data-valids="required" class="valid" name="Username" type="text" value="<?=$admin['Username']?>">
			</line>
			<line>
				<span class="title">加盟商账号：</span>
				<input data-valids="required" class="valid" name="Account" type="text" value="<?=$admin['Account']?>">
			</line>
			<line>
				<span class="title">加盟商密码：</span>
				<input data-valids="required" class="" name="Password" type="text" value="">
				<span>不填则不改</span>
			</line>
			<line class="qiyong">
				<span class="title">启用中：</span>
				<input type="radio" name="IsDisable" value="0" checked="checked"><span>是</span>
				<input type="radio" name="IsDisable" value="1"><span>否</span>
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
		</form>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time.min.js"></script>
	<script src="<?=$staticPath?>js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="<?=$staticPath?>js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>
		
		var IsDisable = '<?=$admin["IsDisable"]?>';
		if(IsDisable && IsDisable!=0){
			$('line.qiyong input').eq(0).prop('checked','');
			$('line.qiyong input').eq(1).prop('checked','checked');
		}
		var Code = '<?=$admin["CityCode"]?>';
		var CodeList = '';
		if(Code){
			CodeList = Code.split(',');
		}

		var UsedCode = '<?=$usedCode?>';
		var UsedCodeList = '';
		if(UsedCode){
			UsedCodeList = UsedCode.split(',');
		}

		$.AjaxApi({
			url: "/division/getDivisionsList",
			success: function(json){
				if(json.Result){
					printCityList(json.Result);
				}else{
					$.tip('拉取地理信息失败',2);
				}
			}
		});

		function printCityList(list){
			for(var i in list){
				var pro = list[i];
				var line = $('<line><span class="title">'+pro.Name+'：</span></line>');
				var btns = $('<btns></btns>');
				var btn = '';
				for(var n in pro.Cities){
					var city = pro.Cities[n];
					btn = '<input type="hidden" name="CityNames[]">';
					btn += '<input type="hidden" name="CityCodes[]">';
					btn += '<div id="box_'+city.Id+'" data-code="'+city.Id+'" data-name="'+city.Name+'" class="btn">'+city.Name+'</div>';
					btns.append(btn);
				}
				line.append(btns);
				if(btns.html())$('line.qiyong').before(line);
			}
			setCityClick();
			if(UsedCodeList)setCityUsedData(UsedCodeList);
			if(CodeList)setCityData(CodeList);
		}

		function setCityClick(){
			$('div.btn').click(function(){
				var has = $(this).hasClass('sel');
				var dis = $(this).hasClass('dis');
				if(dis)return;
				var name = $(this).data('name');
				var code = $(this).data('code');
				if(!has){
					$('#CityCode').val(code);
					$('#CityName').val(name);
					$('div.btn').removeClass('sel');
					$(this).addClass('sel');
				}
			});
		}

		function setCityUsedData(list){
			for(var i in list){
				var code = list[i];
				var box = $('#box_'+code);
				box.addClass('dis');
			}
		}

		function setCityData(list){
			for(var i in list){
				var code = list[i];
				var box = $('#box_'+code);
				var name = box.data('name');
				var code = box.data('code');
				box.removeClass('dis');
				box.prev().prev().val(name);
				box.prev().val(code);
				box.addClass('sel');
			}
		}

		$('form').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					location.reload();
				}
			}
		});
	</script>
</body>