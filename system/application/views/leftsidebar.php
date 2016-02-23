<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$empDetail = $this->configmodel->getDetail('2',$this->session->userdata('eid'),'',$this->session->userdata('bid'));
$empName = $empDetail['empname'];

?>
   <!-- BEGIN TOP MENU -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a id="menu-medium" class="sidebar-toggle tooltips">
                    <i class="fa fa-outdent"></i>
                </a>
                <a class="navbar-brand" href="Home">
                    <img src="system/application/img/mcube-logo.png" alt="logo">
                </a>
            </div>
            <div class="navbar-center"><?php echo $html['title'];?></div>
            <div class="navbar-collapse collapse">
                <!-- BEGIN TOP NAVIGATION MENU -->
                <ul class="nav navbar-nav pull-right header-menu">
                    <!-- BEGIN USER DROPDOWN -->
                    <li class="dropdown" id="user-header">
                        <a href="#" class="dropdown-toggle c-white" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span class="username"><?=ucfirst($empName)?></span>
                            <i class="fa fa-angle-down p-r-10"></i>
                        </a>
                        <ul class="dropdown-menu">
                          <li class="dropdown-footer clearfix">
							<a href="user/changepassword" class="toggle_fullscreen" title="ChangePassword">
								<i class="glyphicon glyphicon-wrench"></i>
							</a>
							<?php 
								if($selfdis == 0)
									echo '<a href="user/selfdisable/'.$selfdis.'" title="Offline"><i class="glyphicon glyphicon-eye-open"></i></a>';
								else
									echo '<a href="user/selfdisable/'.$selfdis.'" title="Online"><i class="glyphicon glyphicon-eye-close"></i></a>';
							?>
							<a href="user/logout" title="Logout"><i class="glyphicon glyphicon-off"></i></a>
							</li>
						</ul>
                    </li>
                    <!-- END USER DROPDOWN -->
                </ul>
                <!-- END TOP NAVIGATION MENU -->
            </div>
        </div>
    </nav>
    <!-- END TOP MENU -->
    <!-- BEGIN WRAPPER -->
    <div id="wrapper">
        <!-- BEGIN MAIN SIDEBAR -->
        <?php
		$menubar = $this->lang->language['menu'];
		$URI = $this->uri->segments[1];
		$key = array_search($URI, $menubar);
		//~ print_r($menubar);
		//~ echo $URI; 
		//~ echo $key; exit;
		for($k=0;$k<count($menubar);$k++){
			if($k == $key){
				$cls1[$k] = "current active hasSub";
				$cls2[$k] = "current";
			}else{
				$cls1[$k] = '';
				$cls2[$k] = '';
			}
		}
		?>
		<nav id="sidebar">
            <div id="main-menu">
                <ul class="sidebar-nav">
                    <li class="<?=$cls1[0]?>">
                        <a href="#" title="Dashboard"><i class="fa fa-plus-square"></i><span class="sidebar-text">Dashboard</span><span class="fa arrow"></span></a>
						<ul class="submenu collapse" >				
				     		<li class="<?=$cls2[0];?>"><a href="<?php echo site_url($menubar[0]);?>"><span class="sidebar-text"><?php echo "Dashboard"; ?></span></a></li>
						</ul>
                    </li>   
				<li class="<?=$cls1[1].$cls1[2].$cls1[3].$cls1[4].$cls1[5].$cls1[6].$cls1[7].$cls1[8];?>">
					<a href="#" title="Mcube Connect"><i class="fa fa-plus-square"></i><span class="sidebar-text"><?php echo "Mcube Connect";?></span><span class="fa arrow"></span></a>
						<ul class="submenu collapse">
							<li><a href="#"><span class="sidebar-text"><b><?php echo $this->lang->line('label_lsite');?></b></span><span class="fa arrow"></span></a>
								<ul class="menu2 submenu collapse">
									<li class="<?=$cls2[1];?>"><a href="<?php echo site_url($menubar[1]);?>"><span class="sidebar-text"><?php echo $this->lang->line('label_addsite');?></span></a></li>
									<li class="<?=$cls2[2]?>"><a href="<?php echo site_url($menubar[2].'/0');?>"><span class="sidebar-text"><?php echo $this->lang->line('label_listsite');?></span></a></li>
									<li class="<?=$cls2[3];?>"><a href="<?php echo site_url($menubar[3].'/0');?>"><span class="sidebar-text"><?php echo $this->lang->line('label_delsite');?></span></a></li>
								</ul>
							</li>
							<li><a href="#"><span class="sidebar-text"><b><?php echo $this->lang->line('label_siterep');?></b></span><span class="fa arrow"></span></a>
								<ul class="menu2 submenu collapse">
									<li class="<?=$cls2[4];?>"><a href="<?php echo site_url($menubar[4].'/0');?>"><span class="sidebar-text"><?php echo $this->lang->line('label_sitevis');?></span></a></li>
									<li class="<?=$cls2[5]?>"><a href="<?php echo site_url($menubar[5].'/0');?>"><span class="sidebar-text"><?php echo $this->lang->line('label_siteref');?></span></a></li>
								</ul>
							</li>	
						<?php if($this->session->userdata('roleid')==1){?>
							<li><a href="#"><span class="sidebar-text"><b><?php echo $this->lang->line('label_siteoff');?></b></span><span class="fa arrow"></span></a>
								<ul class="menu2 submenu collapse">
									<li class="<?=$cls2[7];?>"><a href="<?php echo site_url($menubar[7].'/0');?>"><span class="sidebar-text"><?php echo $this->lang->line('label_addsiteoff');?></span></a></li>
									<li class="<?=$cls2[8]?>"><a href="<?php echo site_url($menubar[8].'/0');?>"><span class="sidebar-text"><?php echo $this->lang->line('label_listsiteoff');?></span></a></li>
								</ul>
							</li>	
							<?php } ?>
					</ul>
					</li>
				<li>
                     	<a href="user/logout" title="Logout"><i class="fa fa-power-off"></i><span>Logout</span></a>
							
							
                    </li>   
				<!-- for spacing -->
				<li>&nbsp;</li>
				<li>&nbsp;</li>
				<li>&nbsp;</li>
				<li>&nbsp;</li>
                </ul>
            </div>
        </nav>
        <!-- END MAIN SIDEBAR -->
