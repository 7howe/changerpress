<?php
/**
 * The obtain view file of package module of ChanZhiEPS.
 *
 * @copyright   Copyright 2009-2013 青岛息壤网络信息有限公司 (QingDao XiRang Network Infomation Co,LTD www.xirangit.com)
 * @license     http://api.chanzhi.org/goto.php?item=license
 * @author      Chunsheng Wang <chunsheng@xirangit.com>
 * @package     package
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.admin.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div class='side'>
  <form class='side-search mgb-20' method='post' action='<?php echo inlink('obtain', 'type=bySearch');?>'>
    <div class="input-group">
      <?php echo html::input('key', $this->post->key, "class='form-control' placeholder='{$lang->package->bySearch}'");?>
      <span class="input-group-btn">
        <?php echo html::submitButton('<i class="icon-search"></i>', '', ''); ?>
      </span>
    </div>
  </form>
  <div class='list-group'>
      <?php
      echo html::a(inlink('obtain', 'type=byUpdatedTime'), $lang->package->byUpdatedTime, '', "class='list-group-item' id='byupdatedtime'");
      echo html::a(inlink('obtain', 'type=byAddedTime'),   $lang->package->byAddedTime, '', "class='list-group-item' id='byaddedtime'");
      echo html::a(inlink('obtain', 'type=byDownloads'),   $lang->package->byDownloads, '', "class='list-group-item' id='bydownloads'");
      ?>
  </div>
  <div class='panel panel-sm'>
    <div class='panel-heading'><?php echo $lang->package->byCategory;?></div>
    <div class='panel-body'>
      <?php $moduleTree ? print($moduleTree) : print($lang->package->errorGetModules);?>
    </div>
  </div>
</div>
<div class='main'>
  <?php if($packages):?>
  <div class='cards pd-0 mg-0'>
  <?php foreach($packages as $package):?>
    <?php 
    $currentRelease = $package->currentRelease;
    $latestRelease  = isset($package->latestRelease) ? $package->latestRelease : '';
    ?>
    <div class='card'>
      <div class='card-heading'>
        <small class='pull-right text-important'>
          <?php 
          if($latestRelease and $latestRelease->releaseVersion != $currentRelease->releaseVersion) 
          {
              printf($lang->package->latest, $latestRelease->viewLink, $latestRelease->releaseVersion, $latestRelease->zentaoCompatible);
          }?>
        </small>
        <h5 class='mg-0'><?php echo $package->name . "($currentRelease->releaseVersion)";?></h5>
      </div>
      <div class='card-content text-muted'>
        <?php echo $package->abstract;?>
      </div>
      <div class='card-actions'>
        <div style='margin-bottom: 10px'>
          <?php
          echo "{$lang->package->author}:     {$package->author} ";
          echo "{$lang->package->downloads}:  {$package->downloads} ";
          echo "{$lang->package->compatible}: {$lang->package->compatibleList[$currentRelease->compatible]} ";
          
          echo " {$lang->package->depends}: ";
          if(!empty($currentRelease->depends))
          {
              foreach(json_decode($currentRelease->depends) as $code => $limit)
              {
                  echo $code;
                  if($limit != 'all')
                  {
                      echo '(';
                      if(!empty($limit['min'])) echo '>= v' . $limit['min'];
                      if(!empty($limit['max'])) echo '<= v' . $limit['min'];
                      echo ')';
                  }
                  echo ' ';
              }
          }
          ?>
        </div>
        <?php
          echo "{$lang->package->grade}: ",   html::printStars($package->stars);
        ?>
        <div class='pull-right' style='margin-top: -15px'>
          <div class='btn-group'>
          <?php
          $installLink = inlink('install',  "package=$package->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5={$currentRelease->md5}&type=$package->type&overridePackage=no&ignoreCompitable=yes");
          echo html::a($package->viewLink, $lang->package->view, '', 'class="btn package"');
          if($currentRelease->public)
          {
              if($package->type != 'computer' and $package->type != 'mobile')
              {
                  if(isset($installeds[$package->code]))
                  {
                      if($installeds[$package->code]->version != $package->latestRelease->releaseVersion and $this->package->checkVersion($package->latestRelease->zentaoCompatible))
                      {
                          $upgradeLink = inlink('upgrade',  "package=$package->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5=$currentRelease->md5&type=$package->type");
                          echo html::a($upgradeLink, $lang->package->upgrade, '', 'class="iframe btn"');
                      }
                      else
                      {
                          echo html::commonButton($lang->package->installed, "disabled='disabled' style='color:gray'");
                      }
                  }
                  else
                  {
                      $label = $currentRelease->compatible ? $lang->package->installAuto : $lang->package->installForce;
                      echo html::a($installLink, $label, '', 'class="iframe btn"');
                  }
              }
          }
          echo html::a($currentRelease->downLink, $lang->package->downloadAB, '', 'class="manual btn"');
          echo html::a($package->site, $lang->package->site, '_blank', 'class=btn');
          ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach;?>
  </div>
  <?php if($pager):?>
  <div class='clearfix'>
    <?php $pager->show()?>
  </div>
  <?php endif; ?>
  <?php else:?>
  <div class='alert alert-danger'>
    <i class='icon icon-remove-sign'></i>
    <div class='content'>
      <h4><?php echo $lang->package->errorOccurs;?></h4>
      <div><?php echo $lang->package->errorGetPackages;?></div>
    </div>
  </div>
  <?php endif;?>
</div>
<script>
$('#<?php echo $type;?>').addClass('active')
$('#module<?php echo $moduleID;?>').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>