<?php
/* Smarty version 3.1.30-dev/71, created on 2016-07-28 13:39:41
  from "D:\Dev\PHP\IMNICORE\view\default\imnicore\install.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30-dev/71',
  'unifunc' => 'content_5799eefde80fc6_13821561',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec1e41160712b487e3a3419bf9a74b2fd1ffa69e' => 
    array (
      0 => 'D:\\Dev\\PHP\\IMNICORE\\view\\default\\imnicore\\install.html',
      1 => 1469705972,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5799eefde80fc6_13821561 (Smarty_Internal_Template $_smarty_tpl) {
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
		<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"><?php echo '</script'; ?>
>
		<?php if ($_smarty_tpl->tpl_vars['step']->value == 0) {?>
			<?php echo '<script'; ?>
>
				$(document).ready(function() {
					$('select').change(function(){
						window.location.href = "<?php echo Imnicore::getPath();?>
/imnicore/install?lang=" + $(this).val();
					});
				});
			<?php echo '</script'; ?>
>
		<?php }?>
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
						<p><?php echo Lang::get('install.welcome.change.language');?>
:&nbsp;
						<select>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, Imnicore::getLangs(), 'lang');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->value) {
?>
								<option <?php if ($_smarty_tpl->tpl_vars['lang']->value == Lang::getLang()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

						</select></p>
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
					<h3 class="container title"><?php echo Lang::get('install.default.title');?>
</h3>
					<?php if (isset($_smarty_tpl->tpl_vars['errorMsg']->value)) {?>
						<div id="error"><?php echo $_smarty_tpl->tpl_vars['errorMsg']->value;?>
</div>
					<?php }?>
					<div class="container body">
						<p><?php echo Lang::get('install.default.text');?>
</p>
						<form action="<?php echo Imnicore::getPath();?>
/imnicore/install/step2/check" method="POST">
							<ul id="inputs">
								<li><label for="URL"><?php echo Lang::get('install.default.url');?>
: </label><input type="text" name="URL" value="<?php echo Imnicore::getDefaultPath();?>
" /></li>
								<li><label for="name"><?php echo Lang::get('install.default.sitename');?>
: </label><input type="text" name="name" value="Imnicore" /></li>
								<li><label for="defaultLang"><?php echo Lang::get('install.default.lang');?>
: </label>
									<select name="defaultLang">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['langs']->value, 'lang');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->value) {
?>
											<option <?php if ($_smarty_tpl->tpl_vars['lang']->value == Lang::getLang()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</option>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

									</select></li>
								<li><label for="usersTable"><?php echo Lang::get('install.default.users.table');?>
: </label><input type="text" name="usersTable" value="ic_users" />
									<input type="checkbox" name="tableExists" /> <label for="tableExists"><?php echo Lang::get('install.default.table.exists');?>
</label></li>
							</ul>
							<div id="button-container">
								<input id="button" type="submit" value="<?php echo Lang::get('done');?>
" />
							</div>
						</form>
					</div>
				<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 3) {?>
					<h3 class="container title"><?php echo Lang::get('install.done.title');?>
</h3>
					<?php if (isset($_smarty_tpl->tpl_vars['errorMsg']->value)) {?>
						<div id="error"><?php echo $_smarty_tpl->tpl_vars['errorMsg']->value;?>
</div>
					<?php }?>
					<div class="container body">
						<p><?php echo Lang::get('install.done.text1');?>
<br><?php echo Lang::get('install.done.text2');?>
</p>
						<p><?php echo Lang::get('install.done.text3');?>
</p>
						<div id="button-container">
							<a id="button" href="<?php echo Imnicore::getPath();?>
/imnicore/install/step3/check"><?php echo Lang::get('install.done');?>
</a>
						</div>
					</div>
				<?php } else { ?>
					<?php echo Lang::get('install.error.unknown.step');?>
: <?php echo $_smarty_tpl->tpl_vars['step']->value;?>

				<?php }?>
			</div>
		</div>
	</body>
</html><?php }
}
