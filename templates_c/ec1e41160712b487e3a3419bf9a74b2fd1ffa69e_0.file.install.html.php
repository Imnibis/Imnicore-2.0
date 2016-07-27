<?php
/* Smarty version 3.1.30-dev/71, created on 2016-07-27 03:31:40
  from "D:\Dev\PHP\IMNICORE\view\default\imnicore\install.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30-dev/71',
  'unifunc' => 'content_57980efcb598d7_11903347',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec1e41160712b487e3a3419bf9a74b2fd1ffa69e' => 
    array (
      0 => 'D:\\Dev\\PHP\\IMNICORE\\view\\default\\imnicore\\install.html',
      1 => 1469582930,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57980efcb598d7_11903347 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="<?php echo Imnicore::getLang();?>
">
	<head>
		<meta charset="UTF-8" />
		<title>Imnicore: <?php echo Lang::get('setup');?>
</title>
		<link rel="stylesheet" href="<?php echo Imnicore::getRelativePath();?>
/getCss/imnicore/install.css" />
	</head>
	<body>
		<div class="content">
			<div id="container">
				<?php if ($_smarty_tpl->tpl_vars['step']->value == 0) {?>
					<h3 class="container title"><?php echo Lang::get('install.welcome.title','2.0');?>
</h3>
					<?php if (isset($_smarty_tpl->tpl_vars['errorMsg']->value)) {?>
						<div id="error"><?php echo $_smarty_tpl->tpl_vars['errorMsg']->value;?>
</div>
					<?php }?>
					<div class="container body">
						<p><?php echo Lang::get('install.welcome.text1');?>
<br>
						<?php echo Lang::get('install.welcome.text2');?>
</p>
						<p><?php echo Lang::get('install.welcome.text3');?>
</p>
						<div id="button-container">
							<a id="button" href="<?php echo Imnicore::getPath();?>
/imnicore/install/step0/check"><?php echo Lang::get('begin');?>
</a>
						</div>
					</div>
				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 1) {?>
					<h3 class="container title"><?php echo Lang::get('install.database.title');?>
</h3>
					<?php if (isset($_smarty_tpl->tpl_vars['errorMsg']->value)) {?>
						<div id="error"><?php echo $_smarty_tpl->tpl_vars['errorMsg']->value;?>
</div>
					<?php }?>
					<div class="container body">
						<p><?php echo Lang::get('install.database.text');?>
</p>
						<form action="<?php echo Imnicore::getPath();?>
/imnicore/install/step1/check" method="POST">
							<ul id="inputs">
								<li><label for="host"><?php echo Lang::get('install.database.host');?>
: </label><input type="text" name="host" /></li>
								<li><label for="user"><?php echo Lang::get('install.database.user');?>
: </label><input type="text" name="user" /></li>
								<li><label for="password"><?php echo Lang::get('install.database.password');?>
: </label><input type="password" name="password" /></li>
								<li><label for="dbname"><?php echo Lang::get('install.database.name');?>
: </label><input type="text" name="dbname" /></li>
							</ul>
							<div id="button-container">
								<input id="button" type="submit" value="<?php echo Lang::get('done');?>
" />
							</div>
						</form>
					</div>
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
