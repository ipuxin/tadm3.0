<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 加盟商页 ***

创建 2016-02-26 刘深远 

*** ***/

?><body>
	<header>
		<div class="fl">
			<span class="logo"><i class="icon logo"></i></span>
			<span><?=$title?></span>
		</div>
		<div class="fr">
			<span><?=$username?></span>
			<span class="menu">
				<?=$account?><s>▼</s>
				<ul>
					<li><a href="/main/loginout">登出</a></li>
				</ul>
			</span>
		</div>
	</header>
	<div class="loadingLine"></div>
	<div class="admin_warp">
		<aside class="main">
			<?
				foreach($PyxMenu as $MenuName => $MainMenu){
					echo '<div class="menu hide">'.$MenuName.'</div>';
					echo '<ul>';
					foreach($MainMenu as $MName => $Menu){
						echo '<li><a href="'.$Menu['MenuUrl'].'" target="main">'.$MName.'</a></li>';
					}
					echo '</ul>';
				}
			?>
		</aside>
		<iframe id="iframe" name="main" src="<?=$PyxMenuMain?>" frameborder="0" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
	</div>
	<script type="text/javascript" src="<?=$staticPath?>js/menu.js?v=<?=$version?>"></script>
    <script src="<?=$staticPath?>js/init.js?v=<?=$version?>"></script>
	<script src="<?=$staticPath?>js/ext/form.js"></script>
	<script type="text/javascript">
		$(function(){
			Menu.init_page();
		});
	</script>
</body>