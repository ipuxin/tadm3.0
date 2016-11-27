<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 2015-12-12 刘深远 ***

appservice系统首页外页模板

*** ***/

?><body class="bg-grey">
	<section>
		<?=$cityId?>
	</section>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		/*$.AjaxApi({
			url: "/api/orderInput",
			type: "POST",
			data: {AppId:'gxk',OrderId:'1509001834'},
			success: function(json){
				if(json.ErrorCode){
					$.tip(json.ErrorMsg,2);
				}else{

				}
			}
		});*/
		/*$.AjaxApi({
			url: "/api/orderUpdate",
			type: "POST",
			data: {OrderId:'1509001834'},
			success: function(json){
				if(json.ErrorCode){
					$.tip(json.ErrorMsg,2);
				}else{

				}
			}
		});*/
	</script>
</body>