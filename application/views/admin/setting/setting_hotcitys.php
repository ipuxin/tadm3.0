<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 热门城市设置页 ***

创建 2016-07-11 刘深远 

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
			
		</form>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<link rel="stylesheet" href="<?=$staticPath?>js/ext/datetimepicker/css/time.css" type="text/css" />
	<script>

		$.AjaxApi({
			url: "/division/getDivisionsList",
			success: function(json){
				if(json.Result){
					printCityList(json.Result);
					getHotCitys();
				}else{
					$.tip('拉取地理信息失败',2);
				}
			}
		});

		function getHotCitys(){
			$.AjaxApi({
				url: "/division/getHotCitys",
				success: function(json){
					if(json.List){
						printHotCity(json.List);
					}
				}
			});
		}

		function printCityList(list){
			for(var i in list){
				var pro = list[i];
				var line = $('<line><span class="title">'+pro.Name+'：</span></line>');
				var btns = $('<btns></btns>');
				var btn = '';
				for(var n in pro.Cities){
					var city = pro.Cities[n];
					btn = '<div id="box_'+city.Id+'" data-pcode="'+pro.Id+'" data-pname="'+pro.Name+'" data-code="'+city.Id+'" data-name="'+city.Name+'" class="btn">'+city.Name+'</div>';
					btns.append(btn);
				}
				line.append(btns);
				if(btns.html())$('form').append(line);
			}
			setCityClick();
		}

		function printHotCity(list){
			for(var i in list){
				var city = list[i];
				$('#box_'+city.CityCode).addClass('sel');
			}
		}

		function setCityClick(){
			$('div.btn').click(function(){
				var code = $(this).data('code');
				var name = $(this).data('name');
				var pcode = $(this).data('pcode');
				var pname = $(this).data('pname');
				var has = $(this).hasClass('sel');
				var index = $(this).index();
				var hot = 1;
				var obj = $(this);
				if(has){hot = 0;}

				$.AjaxApi({
					url: "/division/setCitysHot",
					data: {code:code,name:name,pcode:pcode,pname:pname,index:index,hot:hot},
					success: function(json){
						if(json.Code==0){
							if(has){
								obj.removeClass('sel');
							}else{
								obj.addClass('sel');
							}
						}else{
							$.tip('设置热门城市失败',2);
						}
					}
				});
			});
		}
	</script>
</body>