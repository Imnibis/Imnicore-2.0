<?php
/* Smarty version 3.1.30-dev/71, created on 2016-07-22 20:21:38
  from "D:\Dev\PHP\IMNICORE\view\default\imnicore\install.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30-dev/71',
  'unifunc' => 'content_579264326236b9_86137226',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec1e41160712b487e3a3419bf9a74b2fd1ffa69e' => 
    array (
      0 => 'D:\\Dev\\PHP\\IMNICORE\\view\\default\\imnicore\\install.html',
      1 => 1469211476,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_579264326236b9_86137226 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
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
				<?php if ($_smarty_tpl->tpl_vars['step']->value == 0) {?>
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
				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 1) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 2) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 3) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 4) {?>
					<?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php } else { ?>
					<?php echo Lang::get('install.error.unknown.step');?>
: <?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php }?>
			</div>
		</div>
	</body>
</html><?php }
}
