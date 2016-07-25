<?php /* Smarty version Smarty-3.1.16, created on 2016-07-25 05:31:22
         compiled from "view\default\imnicore\install.html" */ ?>
<?php /*%%SmartyHeaderCode:175645795880a6d83c5-57180512%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '001a50d9827b3f639d5b054d581ce3c4fbe5b724' => 
    array (
      0 => 'view\\default\\imnicore\\install.html',
      1 => 1469211476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '175645795880a6d83c5-57180512',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'errorMsg' => 0,
    'step' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5795880a8882f1_07174116',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5795880a8882f1_07174116')) {function content_5795880a8882f1_07174116($_smarty_tpl) {?><!DOCTYPE html>
<html lang="<?php echo Imnicore::getLang();?>
">
	<head>
		<meta charset="UTF-8" />
		<title>Imnicore: <?php echo Lang::get('setup');?>
</title>
		<link rel="stylesheet" href="getCss/install.css" />
	</head>
	<body>
		<div class="logo"></div>
		<div class="content">
			<div id="container">
				<?php if (isset($_smarty_tpl->tpl_vars['errorMsg']->value)) {?>
					<div id="error"><?php echo $_smarty_tpl->tpl_vars['errorMsg']->value;?>
</div>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['step']->value==0) {?>
					<h3 class="container title"><?php echo Lang::get('install.welcome.title','2.0');?>
</h3>
					<div class="container body">
						<p><?php echo Lang::get('install.welcome.text1');?>
<br>
						<?php echo Lang::get('install.welcome.text2');?>
</p>
						<p><?php echo Lang::get('install.welcome.text3');?>
</p>
					</div>
				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value==1) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value==2) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value==3) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value==4) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } else { ?>
					<?php echo Lang::get('install.error.unknown.step');?>
: <?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php }?>
			</div>
		</div>
	</body>
</html><?php }} ?>
