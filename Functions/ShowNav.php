<?php
$Nav_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE FatherID=0",[],[]);
$TotalFr=sizeof($Nav_rs[0]);
$AllPurv=isset($_SESSION[Prefix."AllPurv"])?$_SESSION[Prefix."AllPurv"]:goIndex();

$ShowMenuFile=array();
$ShowMenuDOS=array();
$ShowMenuName=array();
$ShowMenuIcon=array();

function goIndex(){
  die('<script>window.location.href="index.php";</script>');
}
?>

<nav class="navbar navbar-default navbar-fixed-top"> 
<div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span> 
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php">东风东游泳队管理系统</a>
  </div>
  
  <div class="collapse navbar-collapse" ID="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
  <?php
    for($i=0;$i<$TotalFr;$i++){
      $FatherNavID=$Nav_rs[0][$i]['MenuID'];
      $FatherName=$Nav_rs[0][$i]['Menuname'];
      $FatherIcon=$Nav_rs[0][$i]['MenuIcon'];

      // 如果有该父菜单的权限
      if(in_array($FatherNavID,$AllPurv)){
        $Child_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE FatherID=?",[$FatherNavID],[PDO::PARAM_INT]);
        
        // 有子菜单
        if($Child_rs[1]>0){
          // 子菜单的总个数
          $Totalchd=count($Child_rs[0]);
          // 是否有子菜单权限
          $isHaveChdPurv=0;
          // 有子菜单权限的总个数
          $TotalChdPurv=0;
          
          for($j=0;$j<$Totalchd;$j++){
            // 如果有该子菜单的权限
            if(in_array($Child_rs[0][$j]['MenuID'],$AllPurv)){
              $isHaveChdPurv=1;
              $ShowMenuFile[$i][$TotalChdPurv]=$Child_rs[0][$j]['PageFile'];
              $ShowMenuDOS[$i][$TotalChdPurv]=$Child_rs[0][$j]['PageDOS'];
              $ShowMenuName[$i][$TotalChdPurv]=$Child_rs[0][$j]['Menuname'];
              $ShowMenuIcon[$i][$TotalChdPurv]=$Child_rs[0][$j]['MenuIcon'];
              $TotalChdPurv++;
            }
          }
        }else{
          // 没有子菜单
          $isHaveChdPurv=0;
        }
      }else{
        // 没有该父菜单的权限
        $isHaveChdPurv=-1;
      }
      
      // 有该父菜单的权限
      if($isHaveChdPurv>=0){
        // 没有子菜单
        if($isHaveChdPurv==0){
          $NavFile=$Nav_rs[0][$i]['PageFile'];
          $NavAction=$Nav_rs[0][$i]['PageDOS'];
          $NavIcon=$Nav_rs[0][$i]['MenuIcon'];
  ?>
  <li><a href="index.php<?php echo '?file='.$NavFile.'&action='.$NavAction; ?>"><i class="fa fa-<?php echo $NavIcon; ?>" aria-hidden="true"></i> <?php echo $FatherName; ?></a></li>
  <?php }else{ ?>
  <li class="dropdown">
    <a href="" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-<?php echo $FatherIcon; ?>" aria-hidden="true"></i> <?php echo $FatherName; ?><b class="caret"></b></a>
    <ul class="dropdown-menu">
    <?php
    for($k=0;$k<$TotalChdPurv;$k++){
      $nowfile=$ShowMenuFile[$i][$k];
      $nowdos=$ShowMenuDOS[$i][$k];
      $nowicon=$ShowMenuIcon[$i][$k];
    ?>
      <li><a href="index.php<?php echo '?file='.$nowfile.'&action='.$nowdos; ?>"><i class="fa fa-<?php echo $nowicon; ?>" aria-hidden="true"></i> <?php echo $ShowMenuName[$i][$k]; ?></a></li>
    <?php } ?>
    </ul>
  </li>
  <?php } } } ?>
  <li><a href="index.php?file=View&action=ContactAdmin.php"><i class="fa fa-comments" aria-hidden="true"></i> 联系管理员</a></li>
  </ul>
  
  <ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
      <a href="" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><img style="width:22px;border-radius:9px;" src="res/img/user.png"></a>
      <ul class="dropdown-menu">
        <li><a href="javascript:void(0)">
          <b><font color="green"><?php echo $RealName; ?></font></b>，欢迎回来！
        </a></li>
        <li class="divider"></li>
        <li><a href="javascript:void(0)">
          角色：<font color="#F57C00"><?php echo $RoleName; ?></font>
        </a></li>
        <li class="divider"></li>
        <?php
        if(GetSess(Prefix."isAthlete")==1){
        ?>
        <li><a href="index.php?file=Athlete&action=EditAthProfile.php">修改运动员资料</a></li>       
        <?php } ?>      
        <li><a href="index.php?file=User&action=UpdatePersonalProfile.php">修改用户名/密码</a></li>
        <li><a href="User/Logout.php">安全退出系统</a></li>
      </ul>
    </li>
  </ul>
</div>
</div>
</nav>