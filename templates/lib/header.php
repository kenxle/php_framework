<?php ?>

<div id="user_info_container">
<?if($logged_in_user){?>
	Welcome <?=$logged_in_user->username?>. You are logged in. [ <a href="?logout=true">Logout</a> ]
<?}?>
</div>