<?php
/* Smarty version 3.1.30-dev/71, created on 2016-07-19 16:55:46
  from "D:\Dev\PHP\IMNICORE\view\default\imnicore\install.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30-dev/71',
  'unifunc' => 'content_578e3f72c4f416_29850860',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec1e41160712b487e3a3419bf9a74b2fd1ffa69e' => 
    array (
      0 => 'D:\\Dev\\PHP\\IMNICORE\\view\\default\\imnicore\\install.html',
      1 => 1468938756,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_578e3f72c4f416_29850860 (Smarty_Internal_Template $_smarty_tpl) {
?>
test: <?php echo Lang::get("register.error.username.length",$_smarty_tpl->tpl_vars['params']->value);?>
<br>
test2: <?php echo Lang::get("begin");?>
<br>
test3: <?php echo Lang::get("install.welcome.title","2.0");?>

<br>
<br>
<br>
<?php echo print_r(Lang::debug());
}
}
