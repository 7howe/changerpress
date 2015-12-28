<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php $mainMenu = commonModel::createMainMenu($this->moduleName);?>
<?php include 'header.lite.html.php';?>
<nav id='primaryNavbar'>
  <ul class='nav nav-stacked'>
  <?php foreach ($lang->groups as $group => $setting):?>
  <?php list($module, $method, $params) = explode('|', $setting['link'])?>
  <li <?php if($group == $this->session->currentGroup) echo "class='active'";?> data-id='<?php echo $group ?>'><a data-toggle='tooltip' href='<?php echo helper::createLink($module, $method, $params);?>' title='<?php echo $setting['title'] ?>'><i class='icon icon-<?php echo $setting['icon'] ?>'></i></a></li>
  <?php endforeach;?>
  </ul>
  <?php echo commonModel::createManagerMenu('nav nav-stacked fixed-bottom');?>
</nav>
<nav class='navbar navbar-fixed-top' role='navigation' id='mainNavbar'>
  <div class='navbar-header'>
    <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#mainNavbarCollapse'>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
    </button>
    <?php echo html::a($this->createLink($this->config->default->module), $lang->chanzhiEPSx, "class='navbar-brand'");?>
  </div>
  <div class='collapse navbar-collapse' id='mainNavbarCollapse'>
    <?php echo $mainMenu;?>
    <ul class='nav navbar-nav' id='navbarSwitcher'>
      <li><a href='###'><i class='icon-chevron-sign-right icon-large'></i></a></li>
    </ul>
    <ul class='nav navbar-nav navbar-right'>
      <li><?php echo html::a($config->homeRoot, '<i class="icon-home icon-large"></i> ' . $lang->frontHome, "target='_blank' class='navbar-link'");?></li>
      <li class='dropdown'><?php include 'selectlang.html.php';?></li>
    </ul>
  </div>
</nav>
<div class="clearfix row-main">
  <?php $moduleName = $this->moduleName; ?>
  <?php $menuGroup  = zget($lang->menuGroups, $moduleName);?>
  <?php if($moduleName != 'ui' && $menuGroup != 'ui'): ?>
  <?php $moduleMenu = commonModel::createModuleMenu($this->moduleName);?>
  <?php if($moduleMenu or !empty($treeModuleMenu)):?>
  <div class='col-md-2'>
    <div class="leftmenu affix hiddden-xs hidden-sm">
      <?php if($moduleMenu) echo $moduleMenu;?>
      <?php if(!empty($treeModuleMenu)):?>
      <div class='panel category-nav'>
        <div class='panel-body'>
          <?php echo $treeModuleMenu;?>
          <?php if(!empty($treeManageLink)):?>
          <div class='text-right'><?php if(commonModel::hasPriv('tree', 'browse')) echo $treeManageLink;?></div>
          <?php endif;?>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
  <div class='col-md-10'>
  <?php endif;?>
  <?php else:?>
  <?php include '../../ui/view/header.html.php';?>
  <?php if(!empty($treeModuleMenu)):?>
  <div class='col-md-2'>
    <div class="leftmenu affix hiddden-xs hidden-sm">
      <div class='panel category-nav'>
        <div class='panel-body'>
          <?php echo $treeModuleMenu;?>
          <?php if(!empty($treeManageLink)):?>
          <div class='text-right'><?php if(commonModel::hasPriv('tree', 'browse')) echo $treeManageLink;?></div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <div class='col-md-10'>
  <?php endif;?>
  <?php endif;?>

<?php if($this->session->currentGroup == 'design'):?>
<?php $templates       = $this->loadModel('ui')->getTemplates(); ?>
<?php $currentTemplate = $this->config->template->{$this->device}->name; ?>
<?php $currentTheme    = $this->config->template->{$this->device}->theme; ?>
<?php $currentDevice   = $this->session->device ? $this->session->device : 'desktop';?>
<ul class='hide'>
  <li id='deviceNav' class='nav-item-primary'>
    <?php $mobileTemplate = isset($this->config->site->mobileTemplate) ? $this->config->site->mobileTemplate : 'close';?>
    <?php if($mobileTemplate == 'close'):?>
    <?php echo html::a('javascript:;', $lang->ui->deviceList->desktop);?>
    <?php else:?>
    <a href='javascript:;' data-toggle='dropdown'>
      <?php echo $lang->ui->deviceList->{$currentDevice};?> <i class='icon-caret-down'></i>
    </a>
    <ul id='deviceMenu' class='dropdown-menu'>
      <?php foreach($lang->ui->deviceList as $device => $name):?>
      <?php $class = $device == $currentDevice ? "class='active'" : '';?>
      <li <?php echo $class;?>><a href='<?php echo helper::createLink('ui', 'setdevice', "device={$device}")?>'><?php echo $name;?><i class='icon-ok'></i></a></li>
      <?php endforeach;?>
    </ul>
    <?php endif;?>
  </li>
</ul>
<?php endif;?>
