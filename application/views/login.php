<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 登陆页模板 ***

创建 2016-01-21 刘深远 

*** ***/

?><body class="login">
	
	<loginBox>
		<tit><?=$title?></tit>
		<content>
			<line><input placeholder="用户名" id="userName" type="text"></line>
			<line><input placeholder="密码" id="passWord" type="password"></line>
			<line><button class="org">登 陆</button></line>
		</content>
	</loginBox>
	<script>
		$('button').click(function(){
			var userName = $('#userName').val();
			var passWord = $('#passWord').val();
			if(!userName){$.tip('请输入用户名',2);$('#userName').focus();return;}
			if(!passWord){$.tip('请输入密码',2);$('passWord').focus();return;}

			$.AjaxApi({
				url: "/main/login",
				data: {Account:userName,Password:passWord},
				success: function(json){
					if(json.ErrorCode){
						$.tip(json.ErrorMsg,2);
					}else{
						location.reload();
					}
				}
			});

		});
	</script>
	<script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
</body>