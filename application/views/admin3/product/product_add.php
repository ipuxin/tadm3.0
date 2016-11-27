<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 产品添加页 ***

创建 2016-01-25 刘深远 

*** ***/
?>
<style>
div.btn{font-size: 12px;border-radius: 0px;padding: 8px 16px;height: 32px;line-height: 14px;border: 1px solid #ddd;background-color: #f7f7f7;box-sizing: border-box;cursor:pointer;color:#666;float:left;margin-top:14px;}
div.btn:hover{background-color:#fff;}
span{position:relative;}
span img{height:60px;display:inline-block;}
span a{display:inline-block;float:left;margin-right:10px;}
span i{display:block;height:24px;width:24px;position:absolute;line-height:24px;border-radius:50%;border:1px solid #888;background-color:rgba(0,0,0,0.5);color:#fff;font-size:18px;text-align:center;right:-2px;top:-12px;cursor:pointer;}
line textarea{float:left;}
tabarea{display:block;margin-bottom:12px;}

img.min{height:60px;width:60px;}
img.big{height:60px;width:120px;}

span.imgList ar{position:relative;display:inline-block;margin-right:10px;}
span.imgList ar a{margin:0;}
span.imgList ar i{position:absolute;left:-5px;top:-10px;height:18px;line-height:18px;width:18px;display:block;background-color:rgba(0,0,0,0.5);color:#fff;font-size:16px;text-align:center;}

areaList line>span.title{width:140px;}
areaList btns{display:block;padding-left:148px;}
areaList div.btn{display:inline-block;float:left;height:30px;line-height:30px;border:1px solid #ccc;background-color:#f8f8f8;padding:0 6px;margin-right:4px;margin-bottom:4px;margin-top:0;}
areaList div.btn.sel{border-color:#09c;background-color:#0099cc;color:#fff;}
tabarea.city line>span.title{width:140px;}
tabarea.city btns{display:block;padding-left:148px;}
tabarea.city div.btn{display:inline-block;float:left;height:30px;line-height:30px;border:1px solid #ccc;background-color:#f8f8f8;padding:0 6px;margin-right:4px;margin-bottom:4px;margin-top:0;}
tabarea.city div.btn.sel{border-color:#09c;background-color:#0099cc;color:#fff;}
tabarea.city div.btn.dis{background-color:#ccc;color:#fff;}
</style>
<body>
	<section>
		<div class="title"><span>发布商品</span></div>
		<div class="tab-box" style="margin-bottom:12px;">
            <span class="sel">基本信息</span>
			<span class="" style="display:none">拼团设置</span>
			<span class="">商品详情</span>
			<span class="">展示城市</span>
        </div>
		<form class="main" action="/product/addProduct">
			<tabarea>
			<line>
				<span class="title">商品类型：</span>
				<select id="ProductType" name="ProductType">
					<option value='1'>普通商品</option>
					<option value='2'>拼团商品</option>
					<option value='3'>免费试用</option>
					<option value='4'>一元夺宝</option>
					<option value='5'>幸运抽奖</option>
				</select>
			</line>
			<script>
				$('#ProductType').change(function(){
					var v = $(this).val();
					if(v==1){
						$('div.tab-box span').eq(1).hide();
						$('#freightAmout').prop('readonly',false);
					}else{
						$('div.tab-box span').eq(1).show();
						$('#freightAmout').prop('readonly',true);
						$('#freightAmout').val(0);
					}

					if(v>2){
						$('#LotteryCount').val(1);
						$('#LotteryCount').parent().show();
					}else{
						$('#LotteryCount').val(0);
						$('#LotteryCount').parent().hide();
					}

					if(v==3){
						$('#Prices').val(0);
						$('#Prices').prop('readonly',true);
						$('#Prices').data('valids','number');
					}else if(v==4){
						$('#Prices').val(1);
						$('#Prices').prop('readonly',true);
					}else{
						$('#Prices').data('valids','bigzero number');
						$('#Prices').prop('readonly',false);
					}
				});
			</script>
			<line>
				<span class="title">商品分类：</span>
				<select id="Category1" name="Category1">
					<?foreach($cate as $v){?>
					<option value='<?=$v['id']?>'><?=$v['CateName']?></option>
					<?}?>
				</select>
				<select id="Category2" name="Category2">
					<?foreach($cate2 as $v){if($v['CateParent']!=$cate[0]['id']){continue;}?>
					<option value='<?=$v['id']?>'><?=$v['CateName']?></option>
					<?}?>
				</select>
				<script>
					$('#Category1').change(function(){
						var v = $(this).val();
						$.AjaxApi({
							data: {CateParent:v,CateLevel:2},
							url: "/ajax/getCateList",
							success: function (d) {
								if(d.ErrorCode==0){
									if(d.list){PrintCateChild(d.list);}
								}else{
									alert(d.Message);
								}
							}
						});
					});

					function PrintCateChild(list){
						$('#Category2').html('');
						for(var i in list){
							var cate = list[i];
							var opt = '<option value="'+cate.id+'">'+cate.CateName+'</option>';
							$('#Category2').append(opt);
						}
					}
				</script>
			</line>
			<line>
				<span class="title">商品名称：</span>
				<input data-valids="required" class="long valid" name="ProductName" type="text" value="">
			</line>
			<line style="height:52px;">
				<input type="hidden" id="ImageMinBox" name="ImageMin" value=''>
				<span class="title">商品小图：</span>
				<span><img id="ImageMinImg" class="min" src=""></span>
				<label for="ImageMinUpload"><div class="btn">上传</div></label>
				<span style="margin-top:12px;">（32KB以内，高160*160宽）</span>
			</line>
			<line style="height:52px;">
				<input type="hidden" id="ImageBigBox" name="ImageBig" value=''>
				<span class="title">商品大图：</span>
				<span><img id="ImageBigImg" class="big" src=""></span>
				<label for="ImageBigUpload"><div class="btn">上传</div></label>
				<span style="margin-top:12px;">（128KB以内，高540*1080宽）</span>
			</line>
			<line style="height:52px;">
				<span class="title">商品相册：</span>
				<span class="imgList"></span>
				<label for="ImageListUpload"><div class="btn">添加图片</div></label>
				<span style="margin-top:12px;">（128KB以内，高470*540宽）</span>
			</line>
			<line class="tags" style="display:none">
				<span class="title">分类标签：</span>
			</line>
			<line>
				<span class="title">商品库存：</span>
				<input data-valids="bigzero number" class="short valid" name="StorageCount" type="text" value=""><span>份</span>
			</line>
			<line>
				<span class="title">购买价格：</span>
				<input data-valids="bigzero number" class="short valid" id="Prices" name="Prices[Normal]" maxlength="6" type="text" value=""><span>元</span>
				<span class="title">市场价格：</span>
				<input data-valids="bigzero number" class="short valid" name="Prices[Market]" maxlength="6" type="text" value=""><span>元</span>
			</line>
			<line>
				<span class="title">发货地址：</span>
				<input data-valids="required" class="long valid" name="DeliverAddress" type="text" value="<?=$Shop['DeliverAddress']?>">
			</line>
			<line>
				<span class="title">运费：</span>
				<input data-valids="number" class="short valid" id="freightAmout" name="freightAmout" type="text" value="<?=$Shop['FreightAmout']?>"><span>元</span>
			</line>
			<line>
				<span class="title">商品描述：</span>
				<textarea data-valids="required" class="valid" name="Description"></textarea>
			</line>
			<line>
				<span class="title">是否上架：</span>
				<input type="radio" name="IsForSale" value="1" checked="checked"><span>上架</span>
				<input type="radio" name="IsForSale" value="0"><span>下架</span>
			</line>
			</tabarea>
			<tabarea>
			<line>
				<span class="title">开团人数：</span>
				<input data-valids="natural bigone" class="short valid" name="TeamMemberLimit" type="text" value="2"><span>人</span>
			</line>
			<line>
				<span class="title">团购限时：</span>
				<input data-valids="number" class="short valid" name="Alive" type="text" value="24"><span>单位小时</span>
			</line>
			<line>
				<span class="title">抽奖人数：</span>
				<input data-valids="number" class="short valid" id="LotteryCount" name="LotteryCount" type="text" value="0"><span>人 (若大于0，则开启抽奖功能/应小于开团人数)</span>
			</line>
			</tabarea>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
			<tabarea>
			<line>
				<span class="title" onclick="getContent()">产品详情：</span>

				<script type="text/javascript" charset="utf-8" src="<?=$staticPath?>js/editor/ueditor.config.js"></script>
				<script type="text/javascript" charset="utf-8" src="<?=$staticPath?>js/editor/ueditor.all.min.js"> </script>
				<script type="text/javascript" charset="utf-8" src="<?=$staticPath?>js/editor/lang/zh-cn/zh-cn.js"></script>
				<textarea id="editor" name="Content" style="margin-left:90px;width:1024px;height:500px;"></textarea>
				<script type="text/javascript">
					//实例化编辑器
					//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
					var ue = UE.getEditor('editor');
					function getContent(){
						var content = UE.getEditor('editor').getContent();
						console.log(content);
					}
				</script>
			</line>
			<line>
				<span class="title"></span>
				<input type="reset">
				<button>保存</button>
			</line>
			</tabarea>

		</form>
		<!--图片上传-->
		<form method="POST" action="/upload/productImage/Min" accept-charset="UTF-8" id="uploadFromMin" enctype="multipart/form-data">
		<input name="ImageUpload" id="ImageMinUpload" type="file" class="manual-file-chooser js-manual-file-chooser js-avatar-field" style="display:none">
		</form>
		<form method="POST" action="/upload/productImage/Big" accept-charset="UTF-8" id="uploadFromBig" enctype="multipart/form-data">
		<input name="ImageUpload" id="ImageBigUpload" type="file" class="manual-file-chooser js-manual-file-chooser js-avatar-field" style="display:none">
		</form>
		<form method="POST" action="/upload/productImage/List" accept-charset="UTF-8" id="uploadFromList" enctype="multipart/form-data">
		<input name="ImageUpload" id="ImageListUpload" type="file" class="manual-file-chooser js-manual-file-chooser js-avatar-field" style="display:none">
		</form>
		<tabarea class="city">
			<form>
				<line class="bbb">
					<span class="title"></span>
					<div class="btn quanxuan">全选</div>
					<div class="btn buxuan">不选</div>
					<div class="saveCity" style="cursor:pointer;">保存</div>
				</line>
			</form>
		</tabarea>
		<script type="text/javascript">
		 $(document).ready(function(){
				var optionsMin = {
					beforeSubmit:  showRequest,
					success:       showResponseMin,
					dataType: 'json'
				};
				$('#ImageMinUpload').on('change', function(){
					$('#uploadFromMin').ajaxForm(optionsMin).submit();
				});
				var optionsBig = {
					beforeSubmit:  showRequest,
					success:       showResponseBig,
					dataType: 'json'
				};
				$('#ImageBigUpload').on('change', function(){
					$('#uploadFromBig').ajaxForm(optionsBig).submit();
				});
				var optionsList = {
					beforeSubmit:  showRequest,
					success:       showResponseList,
					dataType: 'json'
				};
				$('#ImageListUpload').on('change', function(){
					$('#uploadFromList').ajaxForm(optionsList).submit();
				});
			});
			
		function showRequest() {return true;}
		function showResponseMin(response)  {
			if(response.error){
				alert(response.error.error);
			}else{
				var imgUrl = response.imageUrl;
				$('#ImageMinImg').attr('src',imgUrl);
				$('#ImageMinBox').val(imgUrl);
			}
		}
		function showResponseBig(response)  {
			if(response.error){
				alert(response.error.error);
			}else{
				var imgUrl = response.imageUrl;
				$('#ImageBigImg').attr('src',imgUrl);
				$('#ImageBigBox').val(imgUrl);
			}
		}
		function showResponseList(response)  {
			if(response.error){
				alert(response.error.error);
			}else{
				var imgUrl = response.imageUrl;
				addImage(imgUrl);
			}
		}

		function addImage(url){
			var ar = $('<ar></ar>');
			var a = $('<a target="_blank" href="'+url+'"></a>');
			var img = '<img class="big" src="'+url+'">';
			var del = '<i>x</i>';
			var input = '<input type="hidden" value="'+url+'" name="ImageList[]">';
			a.append(img);
			a.append(input);
			ar.append(a);
			ar.append(del);
			$('span.imgList').append(ar);
			imageListClick();
		}

		function imageListClick(){
			$('span.imgList ar i').unbind('click');
			$('span.imgList ar i').click(function(){
				$(this).parent().remove();
			});
		}
		</script>
		<!--图片上传-->
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js?v=<?=$version?>"></script>
	<script src="/data/file/js/ext/datetimepicker/time.min.js"></script>
	<script src="/data/file/js/ext/datetimepicker/time-cn.js"></script>
	<link rel="stylesheet" href="/data/file/js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>

		$('input.dataTime').datetimepicker({
				language: "zh-CN",
				autoclose: true,
				minView: "hour",
				minuteStep: 1,
				format: "yyyy-mm-dd hh:ii:ss"});
		
		$('tabarea').hide();
		$('tabarea').eq(0).show();

		$('.tab-box span').click(function(){
			$('.tab-box span').removeClass('sel');
			$(this).addClass('sel');
			var index = $(this).index();
			$('tabarea').hide();
			$('tabarea').eq(index).show();
		});

		$('form.main').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					location.reload();
				}
			}
		});
	</script>
	<script>
		var productId = "<?=$info['id']?>";
		var showCityCode = new Array;

		<?if($info['CityCode'] && is_array($info['CityCode'])){foreach($info['CityCode'] as $v){?>
			showCityCode.push("<?=$v?>");
		<?}}?>
		
		$('tabarea').hide();
		$('tabarea').eq(0).show();

		$('.tab-box span').click(function(){
			$('.tab-box span').removeClass('sel');
			$(this).addClass('sel');
			var index = $(this).index();
			$('tabarea').hide();
			$('tabarea').eq(index).show();
		});
		$('.bbb .quanxuan').click(function(){
			$('.btn').addClass('sel');
		});
		$('.bbb .buxuan').click(function(){
			$('.btn').removeClass('sel');
		});

		

		var IsDisable = '<?=$info["IsDisable"]?>';
		var IsForSale = '<?=$info["IsForSale"]?>';
		var IsAllCity = '<?=$info["IsAllCity"]?>';
		$('input[name="IsDisable"]').attr("checked",false);
		$('input[name="IsDisable"][value="'+IsDisable+'"]').prop("checked",true);
		$('input[name="IsForSale"]').attr("checked",false);
		$('input[name="IsForSale"][value="'+IsForSale+'"]').prop("checked",true);
		$('input[name="IsAllCity"]').attr("checked",false);
		$('input[name="IsAllCity"][value="'+IsAllCity+'"]').prop("checked",true);

		$('form.tab').FormAjax({
			success:function(data){
				if(data.ErrorCode>0){
					$.tip(data.ErrorMsg,2);
				}else{
					location.reload();
				}
			}
		});

		$('div.saveCity').click(function(){
			saveCityData();
		});

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
					btn = '<div id="box_'+city.Id+'" data-code="'+city.Id+'" data-name="'+city.Name+'" class="btn">'+city.Name+'</div>';
					btns.append(btn);
				}
				line.append(btns);
				if(btns.html())$('tabarea.city line.bbb').before(line);
			}
			setCityClick();
			setCityUsedData();
		}

		function setCityUsedData(){
			for(var i in showCityCode){
				var id = showCityCode[i];
				$('#box_'+id).addClass('sel');
			}
		}

		function setCityClick(){
			$('div.btn').click(function(){
				var has = $(this).hasClass('sel');
				var dis = $(this).hasClass('dis');
				if(dis)return;
				var name = $(this).data('name');
				var code = $(this).data('code');
				if(!has){
					$(this).addClass('sel');
				}else{
					$(this).removeClass('sel');
				}
			});
		}

		function saveCityData(){
			var codeArr = new Array();
			$('div.btn.sel').each(function(){
				var obj = $(this);
				var code = $(this).data('code');
				codeArr.push(code);
			});
			console.log(codeArr);
			$.AjaxApi({
				url: "/product/updProductCitys",
				data : {id:productId,Code:codeArr},
				success: function(json){
					console.log(json);
					if(json.Num){
						$.tip('更新成功');
					}else{
						//$.tip('失败');
					}
				}
			});
		}
	</script>
</body>