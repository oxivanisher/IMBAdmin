<?php /* Smarty version Smarty-3.0.7, created on 2011-03-07 02:32:29
         compiled from "Templates\ImbaTopNavigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:298274d7435adc0fd66-44361562%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad747e52823cbfbc711dd97240124ad1e56415db' => 
    array (
      0 => 'Templates\\ImbaTopNavigation.tpl',
      1 => 1299461476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '298274d7435adc0fd66-44361562',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id='imbaMenu'> \
    <ul class='topnav'> \
        <li><a href='http://alptroeim.ch/site/viewnews.php'>News/Blog</a></li> \
        <li><a href='http://alptroeim.ch/site/wrapper.php?id=board'>Forum</a></li> \
        <li><a href='#'>Games / Module</a></li> \
        <li> \
            <a id='imbaMenuImbAdmin' href='#'>Auf zum Atem</a> \
            <ul class='subnav'> \
                <!-- FIXME: add imbaadmin open function  --> \
                <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('navs')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value){
?> \
                <li><a href='<?php echo $_smarty_tpl->tpl_vars['nav']->value['url'];?>
'><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></li> \
                <?php }} ?> \
            </ul> \
        </li> \
    </ul> \
</div> \