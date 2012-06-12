<?php ?>

<div id="user_info_container">
<?if($logged_in_user){?>
	Hello, <?=$logged_in_user->username?>. You are logged in. [ <a href="/login/?logout=true">Logout</a> ]
<?}?>
</div>
<br />
<div id="update_message_container">
	<?=$update_message?>
</div>
<br /><br />