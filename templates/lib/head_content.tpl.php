<?php ?>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title><?=$metaTitle?></title>
<meta name="title" content="<?=$metaTitle?>" />
<meta name="description" content="<?=$metaDesc?>" />
<meta name="keywords" content="<?=$metaKeywords?>" />
<link rel="shortcut icon" type="image/ico" href="<?=($favico ? $favico : "favicon.ico")?>" />

<? if($ad_group_template){
	include ($ad_group_template);
}?>