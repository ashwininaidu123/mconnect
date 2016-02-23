<?php
   Class Mconnectmodel extends Model
   {
   	function Mconnectmodel(){
   		 parent::Model();
   	     $this->load->model('auditlog');
   		 $this->load->model('configmodel');
   	}
   	function getFreePri(){
		$sql = "SELECT * FROM dummynumber WHERE status='0' AND bid='0' LIMIT 0,1";
		$rst = $this->db->query($sql);
		$rec = $rst->result_array();
		return $rec[0]['landingnumber'];
	}
   	function addsite($bid){
	    $arr = array_keys($_POST);
	    $_POST['tracknum'] = ($_POST['tracknum']!='0') ? $_POST['tracknum']:$this->getFreePri();
	    	for($i=0;$i<sizeof($arr);$i++){
				    if(!in_array($arr[$i],array("update_system","bid","siteid","id","siteicon","sitevideo","siteimg","intrestopt"))){
						if(is_array($_POST[$arr[$i]]))
							$val = @implode(',',$_POST[$arr[$i]]);
						elseif($_POST[$arr[$i]]!="")
					        $val=$_POST[$arr[$i]];
						else
							$val='';
						$this->db->set($arr[$i],$val);
					}
				}
	    $sql=$this->db->query("SELECT * FROM ".$bid."_site");
	    if($sql->num_rows()==0){
	                $siteid = $this->db->query("SELECT COALESCE(MAX(`siteid`),0)+1 as id FROM ".$bid."_site")->row()->id;
	                $id = $bid.$siteid;
	                $this->db->set('siteid',$id);
   					$this->db->set('bid',$bid);
   					$this->db->set('status','1');
                 
      
	   }else{
	                $id = $this->db->query("SELECT COALESCE(MAX(`siteid`),0)+1 as id FROM ".$bid."_site")->row()->id;
   					$this->db->set('bid',$bid);
   					$this->db->set('status','1');
   					$this->db->set('siteid',$id);  
	   }
			     
			     $intrestopt = array();
   				 $capture_field_vals ="";
   				 $intrestopt = $_POST["intrestopt"];
					if(isset($intrestopt)){    
						foreach($intrestopt as $key => $text_field){
							print_r($text_field);
						   unset ($intrestopt[$key]);
						   $intrestopt[0] = 'intrestopt';
						   $intrestopt[1] = 'intrestopt1';
						   $intrestopt[2] = 'intrestopt2';
						   $intrestopt[3] = 'intrestopt3';
						   $this->db->set($intrestopt[$key],$text_field);
						}
					}
			    $this->db->set('sitename',$_POST['sitename']);
				$this->db->set('siteinterest_opt',$_POST['siteinterest_opt']);
				$this->db->set('sitedesc',$_POST['sitedesc']);
				$this->db->set('tracknum',$_POST['tracknum']);
				$this->db->set('email',$_POST['email']);
				if(isset($_POST["sitevideo"])){ 
				$this->db->set('sitemedia',$_POST['sitevideo']);	
		     	}	
   			    if(isset($_FILES['siteicon']) && $_FILES['siteicon']['error']==0){
							$ext=pathinfo($_FILES['siteicon']['name'],PATHINFO_EXTENSION);
					     	$newName = "siteicon".date('YmdHis').".".$ext;
							move_uploaded_file($_FILES['siteicon']['tmp_name'],"./uploads/".$newName);
							$this->db->set('siteicon',$newName);
				  }
		  
				if(isset($_FILES['siteimg'])){
							$ext=pathinfo($_FILES['siteimg']['name'],PATHINFO_EXTENSION);
					     	$newName = "siteimg".date('YmdHis').".".$ext;
							move_uploaded_file($_FILES['siteimg']['tmp_name'],"./uploads/".$newName);
							 $this->db->set('sitemedia',$newName);
				  }	
			    $this->db->insert($bid."_site");
			    	if(isset($_FILES['siteimage'])){
			 	   	$imgid = $this->db->query("SELECT COALESCE(MAX(`imgid`),0)+1 as id FROM ".$bid."_site_image")->row()->id;
			 	    $this->db->set('imgid',$imgid);
			 	    $this->db->set('siteid',$id);
			 	    $this->db->set('bid',$bid);
	                 $images_arr = array();
					foreach($_FILES['siteimage']['name'] as $key=>$val){
						$target_dir = "./uploads/";
						$target_file = $target_dir.$_FILES['siteimage']['name'][$key];
						$target_file1 = $_FILES['siteimage']['name'][$key];
						if(move_uploaded_file($_FILES['siteimage']['tmp_name'][$key],$target_file)){
							$images_arr[] = $target_file1;
						}
					 }
						foreach($images_arr as $key => $val ){
						   unset ($images_arr[$key]);
						   $images_arr[0] = 'site_image';
						   $images_arr[1] = 'site_image1';
						   $images_arr[2] = 'site_image2';
						   $this->db->set($images_arr[$key],$val);
					   }
   					$this->db->insert($bid."_site_image");
				}
				return '0';

}
   	function editsite($bid,$id){
			//print_r($_POST);exit;
	    $arr = array_keys($_POST);
	    $_POST['tracknum'] = ($_POST['tracknum']!='0') ? $_POST['tracknum']:$this->getFreePri();
		for($i=0;$i<sizeof($arr);$i++){
   					   if(!in_array($arr[$i],array("update_system","bid","siteid","id","siteicon","sitevideo","siteimg","intrestopt"))){
   							/* Changed for custom fields */
   							if(is_array($_POST[$arr[$i]]))
   								$val = @implode(',',$_POST[$arr[$i]]);
   							elseif($_POST[$arr[$i]]!="")
   								$val=$_POST[$arr[$i]];
   							else
   								$val='';
   							$this->db->set($arr[$i],$val);
   						}
   					}
   				 $intrestopt = array();
   				 $capture_field_vals ="";
   				 $intrestopt = $_POST["intrestopt"];
					if(isset($intrestopt)){    
						foreach($intrestopt as $key => $text_field){
						   unset ($intrestopt[$key]);
						   $intrestopt[0] = 'intrestopt';
						   $intrestopt[1] = 'intrestopt1';
						   $intrestopt[2] = 'intrestopt2';
						   $intrestopt[3] = 'intrestopt3';
						   $this->db->set($intrestopt[$key],$text_field);
						}
					}

			     
				  if(isset($_FILES['siteicon']) && $_FILES['siteicon']['error']==0){
							$ext=pathinfo($_FILES['siteicon']['name'],PATHINFO_EXTENSION);
							$newName = "siteicon".date('YmdHis').".".$ext;
							move_uploaded_file($_FILES['siteicon']['tmp_name'],"./uploads/".$newName);
							$this->db->set('siteicon',$newName);
				  }

				  if(isset($_FILES['siteimg']) && $_FILES['siteimg']['error']==0){
							$ext=pathinfo($_FILES['siteimg']['name'],PATHINFO_EXTENSION);
							$newimg= "siteimg".date('YmdHis').".".$ext;
							move_uploaded_file($_FILES['siteimg']['tmp_name'],"./uploads/".$newimg);
							 $this->db->set('sitemedia',$newimg);
				  }
				
				   	if(isset($_POST["sitevideo"])){ 
				    $this->db->set('sitemedia',$_POST['sitevideo']);	
			        }
   				
   					$this->db->where('siteid',$id);
   					$this->db->update($bid."_site");
   				    $s=$this->db->query("SELECT siteid FROM ".$bid."_site_image WHERE siteid='".$id."'");
   		            if($s->num_rows()>0){
   				    $images_arr = array();
					foreach($_FILES['siteimage']['name'] as $key=>$val){
						$target_dir = "./uploads/";
						$target_file = $target_dir.$_FILES['siteimage']['name'][$key];
						$target_file1 = $_FILES['siteimage']['name'][$key];
						if(move_uploaded_file($_FILES['siteimage']['tmp_name'][$key],$target_file)){
							$images_arr[] = $target_file1;
						}
					}
					if(is_array($images_arr)){
						foreach($images_arr as $key => $val ){
						   unset ($images_arr[$key]);
						   $images_arr[0] = 'site_image';
						   $images_arr[1] = 'site_image1';
						   $images_arr[2] = 'site_image2';
						$sql = "UPDATE `".$bid."_site_image` SET `".$images_arr[$key]."`=('".$val."') WHERE `siteid`=$id";
					   	$rst = $this->db->query($sql);
					   }
					}
				}else{
			 	   	$imgid = $this->db->query("SELECT COALESCE(MAX(`imgid`),0)+1 as id FROM ".$bid."_site_image")->row()->id;
			 	    $this->db->set('imgid',$imgid);
			 	    $this->db->set('siteid',$id);
			 	    $this->db->set('bid',$bid);
	                 $images_arr = array();
					foreach($_FILES['siteimage']['name'] as $key=>$val){
						$target_dir = "./uploads/";
						$target_file = $target_dir.$_FILES['siteimage']['name'][$key];
						$target_file1 = $_FILES['siteimage']['name'][$key];
						if(move_uploaded_file($_FILES['siteimage']['tmp_name'][$key],$target_file)){
							$images_arr[] = $target_file1;
						}
					 }
						foreach($images_arr as $key => $val ){
						   unset ($images_arr[$key]);
						   $images_arr[0] = 'site_image';
						   $images_arr[1] = 'site_image1';
						   $images_arr[2] = 'site_image2';
						   $this->db->set($images_arr[$key],$val);
					   }
   					$this->db->insert($bid."_site_image");
				}
   				return '1';
 			}			
   	function getlistSite($bid,$ofset,$limit){
   		$q= '';
   		if(isset($_POST['submit'])){
   			$this->session->set_userdata('search',$_POST);
   		}
   		if($this->session->userdata('search')){
   			$s = $this->session->userdata('search');
   		}
   		
   		$q .=(isset($s['sitename']) && $s['sitename']!='')?" AND a.sitename LIKE '%".$s['sitename']."%'":"";
   		$q.=(isset($s['email']) && $s['email']!='')?" AND a.email LIKE '%".$s['email']."%'":"";
   		$roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
   		$limit = ($roleDetail['role']['recordlimit']>($ofset+$limit))?$limit
   					:((($roleDetail['role']['recordlimit'] - $ofset)>0)?($roleDetail['role']['recordlimit'] - $ofset):0);
   		$sql = "SELECT SQL_CALC_FOUND_ROWS a.sitename,a.siteicon,a.sitedesc,a.sitemedia,a.email,CONCAT_WS('\r,',a.intrestopt, a.intrestopt1, a.intrestopt2,a.intrestopt3) as opt,a.sitemedia,a.siteid,n.landingnumber as tracknum FROM ".$bid."_site a
   			    LEFT JOIN  prinumbers n ON n.number = a.tracknum
   				WHERE a.status =1 ORDER BY a.siteid  DESC 
   		        LIMIT $ofset,$limit";  
   		$rst = $this->db->query($sql)->result_array();
   		$rst1 = $this->db->query("SELECT FOUND_ROWS() as cnt");
   		$ret['count'] = $rst1->row()->cnt;
   		foreach($roleDetail['modules'] as $mod){
   			if($mod['modid']=='48'){
   				$opt_add 	= $mod['opt_add'];
   				$opt_view 	= $mod['opt_view'];
   				$opt_delete = $mod['opt_delete'];
   			}
   		}
  
   		$fieldset = $this->configmodel->getFields('48',$bid);
   		$keys = array();
   		$header = array('#',"<a href='javascript://'><span id='c_all' class='glyphicon glyphicon-gok'></span></a>");
   			if($opt_add || $opt_view || $opt_delete)
   			array_push($header,$this->lang->line('level_Action'));
   		foreach($fieldset as $field){
   			$checked = false;
   			if($field['type']=='s' && !$field['is_hidden'] && $field['show'] && $field['listing'] ){
   				foreach($roleDetail['system'] as $f){
   					if($f['fieldid']==$field['fieldid'])$checked = true;
   				}
   				if($checked){
					if(!in_array($field['fieldname'],array('siteimg','sitevideo'))){
   					array_push($keys,$field['fieldname']);
   					array_push($header,(($field['customlabel']!="")
   										?$field['customlabel']
   										:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']));
   				}
			}
   			}elseif($field['type']=='c' && $field['show'] && $field['listing']){
   				foreach($roleDetail['custom'] as $f){if($f['fieldid']==$field['fieldid'])$checked = true;}
   				if($checked){
   					array_push($keys,$field['fieldKey']);
   					array_push($header,$field['customlabel']);
   				}
   			}
   		}
   	   array_push($keys,'intrestopt');
   	   array_push($header,'Options');
   		$ret['header'] = $header;
   		$list = array();
   		$i = $ofset+1;
   		foreach($rst as $rec){
   			$data = array($i);
   			$v = '<input type="checkbox" class="blk_check" name="blk[]" value="'.$rec['siteid'].'"/>';	
   			array_push($data,$v);
   			if($opt_add || $opt_view || $opt_delete){
   				$act = '';
   				   $act .= '<div class="btn-group">&nbsp;&nbsp;
							<a class="dropdown-toggle" data-toggle="dropdown" style=";font-weight:bold;"> Action <span class="caret"></span></a>
							<ul class="dropdown-menu" style="text-align:left;">';
   					$act .= ($opt_add) ?'<li><a href="mconnect/addsite/'.$rec['siteid'].'"><span title="Edit" class="fa fa-edit">&nbsp;&nbsp;Edit</span></a></li>':'';
   					$act .= ($opt_delete) ? '<li><a href="'.base_url().'mconnect/delSite/'.$rec['siteid'].'" class="deleteClass"><span title="Delete" class="glyphicon glyphicon-trash">&nbsp;&nbsp;Delete</span></a></li>':'';
   				    $act .= ($opt_add)? '<li><a href="mconnect/addlocation/'.$rec['siteid'].'"  ><span class="fa fa-plus" title="Add Location" >&nbsp;&nbsp;Add Location</span></a><li>':'';
   					$act .= '<li><a href="ListLocation/'.$rec['siteid'].'" ><span title="List Locations" class="fa fa-list-ul">&nbsp;&nbsp;List Locations</span></a></li>';
   					$act .= '</ul></div>';
   					$data['action'] = $act;
   			}
   			$r = $this->configmodel->getDetail('48',$rec['siteid'],'',$bid);
   		   foreach($keys as $k){
			   if($k == 'intrestopt'){
                 $v = $rec['opt'];
                }elseif($k == 'siteicon'){
                 $v = "<img alt='uploaded image'  height=\"75\" width=\"75\"  src='".base_url().'/uploads/'.$r['siteicon']."'>";
                }elseif($k == 'sitemedia') {
				 $rest = substr($r['sitemedia'], 0, 4);
	            if($rest == 'site'){
                $img  = "<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['sitemedia']."'>";
                $img .= ($r['site_image'] != '')?"<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['site_image']."'>":'';
                $img .= ($r['site_image1'] != '')?"<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['site_image1']."'>":'';
                $img .= ($r['site_image2'] != '')?"<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['site_image2']."'>":'';
                $v = $img;
                }else{
               $v = "<iframe width=\"200\" height=\"120\"  frameborder=\"0\" allowfullscreen src='".$r['sitemedia']."'></iframe>";
			    }
                //~ $v = "<div class='thumbnail'>
                              //~ ".$img."
                                       	   //~ <div class='overlay'>
                                            //~ <div class='thumbnail-actions'>
                                            //~ <a href='mconnect/Imagegallery/".$img."'  class='btn-danger btn btn-default btn-icon btn-rounded magnific' data-toggle='modal' data-target='#modal-responsive'><i class='fa fa-search'></i></a>
                                            //~ </div>
                                           //~ </div> 
                                          //~ </div>";
                }else{
   					$v = isset($r[$k])?nl2br(wordwrap($r[$k],80,"\n")):"";	
   				}
         
   				array_push($data,$v);
   			}
   			$i++;
   			array_push($list,$data);
   		}
   		$ret['rec'] = $list;
   		return $ret;
   	}
   	function delSite($id,$bid){
   		$this->db->set('status','2');
   		$this->db->where('siteid',$id);
   		$this->db->update($bid."_site");
   		return '1';
   	}
   	function bulkDelSite($arr){
   		$cbid = $this->session->userdata('cbid');
   		$bid = (isset($cbid) && $cbid!="") ? $cbid : $this->session->userdata('bid');
   		$sql="UPDATE ".$bid."_site SET status=2 WHERE siteid IN(".$arr.")";
   		$this->db->query($sql);
   		$s=$this->db->query("SELECT sitename FROM ".$bid."_site WHERE siteid IN(".$arr.")");
   		if($s->num_rows()>0){
   			foreach($s->result_array() as $row){
   				$this->auditlog->auditlog_info('Site',$row['sitename']. " Deleted By ".$this->session->userdata('username'));
   			}	
   		}
   		return 1;
   	}
    function delSlist($bid,$ofset='0',$limit='20',$type='a'){//echo $limit;
   		$q= '';
   		if(isset($_POST['submit'])){
   			$this->session->set_userdata('search',$_POST);
   		}
   		if($this->session->userdata('search')){
   			$s = $this->session->userdata('search');
   		}
   		$q.=(isset($s['siteid']) && $s['siteid']!='')?" AND a.siteid = '".$s['siteid']."'":	"";
   		$q.=(isset($s['sitename']) && $s['sitename']!='')?" AND a.sitename = '".$s['sitename']."'":"";
   		$q.=(isset($s['siteinterest_opt']) && $s['siteinterest_opt']!='')?" AND a.enteredby = '".$s['siteinterest_opt']."'":"";
   		$q.=(isset($s['sitedesc']) && $s['sitedesc']!='')?" AND a.sitedesc LIKE '%".$s['sitedesc']."%'":"";
   		$q.=(isset($s['email']) && $s['email']!='')?" AND a.email LIKE '%".$s['email']."%'":"";
   		$q.=(isset($s['tracknum']) && $s['tracknum']!='')?" AND a.tracknum)>= '".$s['tracknum']."'":"";
   		$roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
   		$limit = ($roleDetail['role']['recordlimit']>($ofset+$limit))?$limit
   					:((($roleDetail['role']['recordlimit'] - $ofset)>0)?($roleDetail['role']['recordlimit'] - $ofset):0);
   		$sql = "SELECT a.* FROM ".$bid."_site a 
   				WHERE a.status='2' $q";
   		$rst = $this->db->query($sql)->result_array();
   		$rst1 = $this->db->query("SELECT FOUND_ROWS() as cnt");
   		$ret['count'] = $rst1->row()->cnt;
   		foreach($roleDetail['modules'] as $mod){
   			if($mod['modid']=='48'){
   				$opt_add 	= $mod['opt_add'];
   				$opt_view 	= $mod['opt_view'];
   				$opt_delete = $mod['opt_delete'];
   			}
   		}
   		$fieldset = $this->configmodel->getFields('48',$bid);

   		$keys = array();
   		$header = array('#');
   		if($opt_add || $opt_view || $opt_delete)
   			array_push($header,$this->lang->line('level_Action'));
   	
   		foreach($fieldset as $field){
   			$checked = false;
   			if($field['type']=='s' && !$field['is_hidden'] && $field['show'] && $field['listing'] && !in_array($field['fieldname'],array('siteicon','sitevideo','siteimg'))){
   				foreach($roleDetail['system'] as $f){if($f['fieldid']==$field['fieldid'])$checked = true;}
   				if($checked){
   					array_push($keys,$field['fieldname']);
   					array_push($header,(($field['customlabel']!="")
   										?$field['customlabel']
   										:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']));
   				}
   			}elseif($field['type']=='c' && $field['show'] && $field['listing']){
   				foreach($roleDetail['custom'] as $f){if($f['fieldid']==$field['fieldid'])$checked = true;}
   				if($checked){
   					array_push($keys,$field['fieldKey']);
   					array_push($header,$field['customlabel']);
   				}
   			}
   		}
   		$ret['header'] = $header;
   		$list = array();
   		$i = $ofset+1;
   		foreach($rst as $rec){
   			$r = $this->configmodel->getDetail('48',$rec['siteid'],'',$bid);
   			$data = array($i);
   			if($opt_add || $opt_view || $opt_delete){
				$act = '<div class="btn-group">&nbsp;&nbsp;
							<a class="dropdown-toggle" data-toggle="dropdown" style=";font-weight:bold;"> Action <span class="caret"></span></a>
							<ul class="dropdown-menu" style="text-align:left;">';
   				$act .= '<li><a href="'.base_url().'mconnect/undelSite/'.$r['siteid'].'"><span title="Delete" class="glyphicon glyphicon-refresh">&nbsp;UnDelete</span></a></a></li>';
   				$act .= '</ul></div>';
				$data['action'] = $act;
 
   			}
   			foreach($keys as $k){
   					$v = isset($r[$k])?nl2br(wordwrap($r[$k],80,"\n")):"";	
   				array_push($data,$v);
   			}
   			
   			$i++;
   			array_push($list,$data);
   		}
   		$ret['rec'] = $list;
   		return $ret;
   	}
   	function undelSt($siteid,$bid){
   		$cbid=$this->session->userdata('cbid');
   		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
   		$this->db->set('status','1');
   		$this->db->where('siteid',$siteid);
   		$this->db->update($bid."_site");
   		return true;
   	}
   	
	function getBeaconlist(){
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$res=array();	
		$sql = $this->db->query("SELECT * FROM beacon WHERE business = ".$bid." AND usedinbusiness != '1'");
     
		if($sql->num_rows()>0){
 			$ress=$sql->result_array();	
 		    $res['']=$this->lang->line('level_select');
			foreach($ress as $rec)
				$res[$rec['beacon_id']] = $rec['beacon_id'];
	    }				
	    return $res;
	}
	function addnewlocation($id,$locid){
        $arr = array_keys($_POST);
		$bid = $_POST['bid'];
        $sql=$this->db->query("SELECT locid FROM ".$bid."_mc_location WHERE beaconid='".$_POST['beaconid']."'");
			if($sql->num_rows()==0){ 
			for($i=0;$i<count($arr);$i++){
				if(isset($_POST[$arr[$i]]) && $_POST[$arr[$i]] != '' && !in_array($arr[$i],array('update_system','locid',"visited"))){
					$this->db->set($arr[$i],$_POST[$arr[$i]]);
			
				}
			}
			$ext = pathinfo($_FILES['loc_image']['name'],PATHINFO_EXTENSION);
			$newName = "Location".date('YmdHis').".".$ext;
			   if(@move_uploaded_file($_FILES['loc_image']['tmp_name'],"./uploads/".$newName)){
						$locid = $this->db->query("SELECT COALESCE(MAX(`locid`),0)+1 as locid FROM ".$bid."_mc_location")->row()->locid;
						$this->db->set('locid',$locid);
						$this->db->set('status','1');
						$this->db->set('loc_image',$newName);
						$this->db->insert($bid."_mc_location");
						
						$this->db->set('usedinbusiness','1');
						$this->db->set('siteid',$id);
						$this->db->where('beacon_id',$_POST['beaconid']);
						$this->db->update("beacon");
						
					$imgid = $this->db->query("SELECT COALESCE(MAX(`imgid`),0)+1 as id FROM ".$bid."_loc_image")->row()->id;
						$this->db->set('imgid',$imgid);
						$this->db->set('locid',$locid);
						$this->db->set('bid',$bid);
                        $images_locarr = array();
					foreach($_FILES['locimage']['name'] as $key=>$val){
						$target_dir = "./uploads/";
						$target_file = $target_dir.$_FILES['locimage']['name'][$key];
						$target_file1 = $_FILES['locimage']['name'][$key];
						if(move_uploaded_file($_FILES['locimage']['tmp_name'][$key],$target_file)){
							$images_locarr[] = $target_file1;
						}
					}
					if(is_array($images_locarr)){
						foreach($images_locarr as $key => $val ){
						   unset ($images_locarr[$key]);
						   $images_locarr[0] = 'loc_image1';
						   $images_locarr[1] = 'loc_image2';
						   $images_locarr[2] = 'loc_image3';
						   $this->db->set($images_locarr[$key],$val);
					   }
					   	$this->db->insert($bid."_loc_image");
					}
			
				return '0';
                }
                 
				}else{
   					for($i=0;$i<sizeof($arr);$i++){
   					   if(!in_array($arr[$i],array('update_system','locid',"visited"))){
   							/* Changed for custom fields */
   							if(is_array($_POST[$arr[$i]]))
   								$val = @implode(',',$_POST[$arr[$i]]);
   							elseif($_POST[$arr[$i]]!="")
   								$val=$_POST[$arr[$i]];
   							else
   								$val='';
   							$this->db->set($arr[$i],$val);
   						}
   					}
				  if(isset($_FILES['loc_image']) && $_FILES['loc_image']['error']==0){
							$ext=pathinfo($_FILES['loc_image']['name'],PATHINFO_EXTENSION);
							$newName = "Location".date('YmdHis').".".$ext;
							move_uploaded_file($_FILES['loc_image']['tmp_name'],"./uploads/".$newName);
							$this->db->set('loc_image',$newName);
				  } 
				
   					$this->db->where('locid',$locid);
   					$this->db->update($bid."_mc_location");
   			        $images_locarr = array();
					foreach($_FILES['locimage']['name'] as $key=>$val){
						$target_dir = "./uploads/";
						$target_file = $target_dir.$_FILES['locimage']['name'][$key];
						$target_file1 = $_FILES['locimage']['name'][$key];
						if(move_uploaded_file($_FILES['locimage']['tmp_name'][$key],$target_file)){
							$images_locarr[] = $target_file1;
						}
					}
					if(is_array($images_locarr)){
						foreach($images_locarr as $key => $val ){
						   unset ($images_locarr[$key]);
						   $images_locarr[0] = 'loc_image1';
						   $images_locarr[1] = 'loc_image2';
						   $images_locarr[2] = 'loc_image3';
						$sql = "UPDATE `".$bid."_loc_image` SET `".$images_locarr[$key]."`=('".$val."') WHERE `locid`='".$locid."'";
					   	$rst = $this->db->query($sql);
					   }
					}	
   				return '1';
   				}			
		}

	function getlistlocation($bid,$ofset,$limit,$id){
   		$q= '';
   		if(isset($_POST['submit'])){
   			$this->session->set_userdata('search',$_POST);
   		}
   		if($this->session->userdata('search')){
   			$s = $this->session->userdata('search');
   		}
   		$q .=(isset($s['sitename']) && $s['sitename']!='')?" AND a.locmane LIKE '%".$s['locname']."%'":"";
   		$roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
   		$limit = ($roleDetail['role']['recordlimit']>($ofset+$limit))?$limit
   					:((($roleDetail['role']['recordlimit'] - $ofset)>0)?($roleDetail['role']['recordlimit'] - $ofset):0);
   		$sql = "SELECT SQL_CALC_FOUND_ROWS a.* FROM ".$bid."_mc_location a
   				WHERE siteid='".$id."' AND a.status = '1' ORDER BY a.locid DESC 
   		        LIMIT $ofset,$limit";  
   		$rst = $this->db->query($sql)->result_array();
   		$rst1 = $this->db->query("SELECT FOUND_ROWS() as cnt");
   		$ret['count'] = $rst1->row()->cnt;
   		foreach($roleDetail['modules'] as $mod){
   			if($mod['modid']=='49'){
   				$opt_add 	= $mod['opt_add'];
   				$opt_view 	= $mod['opt_view'];
   				$opt_delete = $mod['opt_delete'];
   			}
   		}
 
   		$fieldset = $this->configmodel->getFields('49',$bid);
   		$keys = array();
   		$header = array('#',"<a href='javascript://'><span id='c_all' class='glyphicon glyphicon-gok'></span></a>");
   			if($opt_add || $opt_view || $opt_delete)
   			array_push($header,$this->lang->line('level_Action'));
   		foreach($fieldset as $field){
   			$checked = false;
   			if($field['type']=='s' && !$field['is_hidden'] && $field['show'] && $field['listing']){
   				foreach($roleDetail['system'] as $f){
   					if($f['fieldid']==$field['fieldid'])$checked = true;
   				}
   				if($checked){
   					array_push($keys,$field['fieldname']);
   					array_push($header,(($field['customlabel']!="")
   										?$field['customlabel']
   										:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']));
   				}
   			}elseif($field['type']=='c' && $field['show'] && $field['listing']){
   				foreach($roleDetail['custom'] as $f){if($f['fieldid']==$field['fieldid'])$checked = true;}
   				if($checked){
   					array_push($keys,$field['fieldKey']);
   					array_push($header,$field['customlabel']);
   				}
   			}
   		}
  
   		$ret['header'] = $header;
   		$list = array();
   		$i = $ofset+1;
   		foreach($rst as $rec){
   			$data = array($i);
   			$v = '<input type="checkbox" class="blk_check" name="blk[]" value="'.$rec['locid'].'"/>';	
   			array_push($data,$v);
   			$act = '';
			if($opt_add || $opt_view || $opt_delete){
				$act .= '<div class="btn-group">&nbsp;&nbsp;
							<a class="dropdown-toggle" data-toggle="dropdown" style=";font-weight:bold;"> Action <span class="caret"></span></a>
							<ul class="dropdown-menu" style="text-align:left;">';
			    $act .= ($opt_add) ?'<li><a href="mconnect/addlocation/'.$rec['siteid'].'/'.$rec['locid'].'"><span title="Edit" class="fa fa-edit">&nbsp;&nbsp;Edit</span></a></li>':'';
   				$act .= ($opt_delete) ? '<li><a href="mconnect/deleteLocation/'.$rec['locid'].'" class="deleteClass"><span title="Delete" class="glyphicon glyphicon-trash">&nbsp;&nbsp;Delete</span></a></li>':'';
				$act .= '</ul></div>';
				$data['action'] = $act;
			}
   			$r = $this->configmodel->getDetail('49',$rec['locid'],'',$bid);
   			foreach($keys as $k){
			    if($k == 'loc_image'){
				$img  = "<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['loc_image']."'>";
                $img .= ($r['loc_image1'] != '')?"<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['loc_image1']."'>":'';
                $img .= ($r['loc_image2'] != '')?"<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['loc_image2']."'>":'';
                $img .= ($r['loc_image3'] != '')?"<img alt='uploaded image'  height=\"50\" width=\"50\"  src='".base_url().'/uploads/'.$r['loc_image3']."'>":'';
                $v = $img;
                }else{
   					$v = isset($r[$k])?nl2br(wordwrap($r[$k],80,"\n")):"";	
				}
				  array_push($data,$v);
   			}

   			$i++;
   			array_push($list,$data);
   		}
   		$ret['rec'] = $list;
   		return $ret;
   	}
   	 function deleteLocation($id,$bid,$beaconid){
   		$sql=$this->db->query("SELECT beaconid FROM ".$bid."_mc_location WHERE locid='".$id."'");
		if($sql->num_rows()>0){ 
		$ress=$sql->result_array();	
   		$this->db->where('locid',$id);
   		$this->db->delete($bid."_mc_location");
   		$this->db->set('usedinbusiness','0');
   		$this->db->set('siteid','0');
		$this->db->where('beacon_id',$beaconid);
		$this->db->update("beacon");
		$sql1=$this->db->query("SELECT * FROM ".$bid."_loc_image WHERE locid='".$id."'");
		if($sql1->num_rows()>0){ 
		$ress=$sql1->result_array();	
   		$this->db->where('locid',$id);
   		$this->db->delete($bid."_loc_image");
        }
   		return '1';
   	} 
   }

	function sitevisits(){
	    $cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
		$q = '';
		$sql="SELECT r.usrname as name,r.usremail as email,r.uid,ul.`liked`,r.usrnumber as number,s.sitename,s.siteid,COUNT(Distinct v.authKey) as sitecount,CAST(visit_time AS DATE) as date,CAST(visit_time as time) as time,GROUP_CONCAT(l.locname)locationname,GROUP_CONCAT(v.query)query,GROUP_CONCAT(v.Intrestedin)Intrestedin FROM visited_history v
		                       LEFT JOIN ".$bid."_site s on s.siteid = v.siteid
		                       LEFT JOIN mconnect_register r on r.authKey = v.authKey
		                       LEFT JOIN user_likes ul on ul.siteid = v.siteid
		                       LEFT JOIN ".$bid."_mc_location l on l.beaconid = v.beaconid
		                       WHERE v.bid='".$bid."'  GROUP BY v.siteid,v.authKey ASC";							 
		$rst = $this->db->query($sql)->result_array();
		$rst1 = $this->db->query("SELECT FOUND_ROWS() as cnt");
		$ret['count'] = $rst1->row()->cnt;
		foreach($roleDetail['modules'] as $mod){
			if($mod['modid']=='48'){
				$opt_add 	= $mod['opt_add'];
				$opt_view 	= $mod['opt_view'];
				$opt_delete     = $mod['opt_delete'];
			}
		}
		$header = array('#'
			            ,'Action'
						,'Sitename'
						,'Visit date'
						,'Visit Time'
						,'Name'
						,'Email'
						,'Intrested In'
                        ,'Query'
						,'Like'
						,'Location visited'
						);
		$ret['header'] = $header;
		$list = array();
		$i = 1;
		foreach($rst as $rec){
			$act = '';
			if($opt_add || $opt_view || $opt_delete){
				$act .= '<div class="btn-group">&nbsp;&nbsp;
							<a class="dropdown-toggle" data-toggle="dropdown" style=";font-weight:bold;"> Action <span class="caret"></span></a>
							<ul class="dropdown-menu" style="text-align:left;">';
				$act .= '<li><a href="Report/followup/'.$rec['siteid'].'/0/sitevisit" class="btn-followup" data-toggle="modal" data-target="#modal-followup"><span title="Followups" class="glyphicon glyphicon-book">&nbsp;Followups</span></a></li>';	
				$act .= "<li><a href=\"Javascript:void(null)\" onClick=\"window.open('".base_url()."Email/compose/".$rec['uid']."', 'Counter', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=950,height=480,resizable=1')\"><span title='Send Mail' class='glyphicon glyphicon-envelope'>&nbsp;SendMail</span></a></li>";
				$act .= "<li>".anchor("Report/sendSms/".$rec['number']."/sitevisit", '<span title="Click to send SMS" class="glyphicon glyphicon-comment">&nbsp;SendSMS</span>','class="clickToSMS" data-toggle="modal" data-target="#modal-empl"')."</li>";
				$act .= "<li>".anchor("Report/converttolead/".$rec['siteid'], '<span title="Convert to lead" class="glyphicon glyphicon-share">&nbsp;Convert&nbsp;to&nbsp;lead</span>','class="clickToSMS" data-toggle="modal" data-target="#modal-empl"')."</li>";
				$act .= "<li>".anchor("Report/mcubecalls/".$rec['number']."/48", '<span title="Mcube calls" class="fa fa-phone">&nbsp;Click  to connect</span>','class="mcubecalls" data-toggle="modal" data-target="#modal-empl"')."</li>";
				$act .= '</ul></div>';
				$ret['action'] = $act;
			}
                 
			$data = array($i
			              ,$ret['action']
						  ,$rec['sitename']
						  ,$rec['date']
						  ,$rec['time']
						  ,$rec['name']
						  ,$rec['email']
                          ,(($rec['Intrestedin'])? ($rec['Intrestedin']):'NA')
                          ,(($rec['query'])? ($rec['query']):'NA')
                          ,(($rec['liked'] == '1')? 'Yes':(($rec['liked'] == '0')? 'No':'NA'))
						  ,$rec['locationname']
						  );
			$i++;
			array_push($list,$data);
		}
		$ret['rec'] = $list;
		return $ret;
	}
	function getSitelist($bid){
		$res=array(''=>"Select");
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$rst=$this->db->query("SELECT sitename,siteid FROM ".$bid."_site
		      WHERE bid='".$bid."' AND status = 1")->result_array();
		if(count($rst)>0){
			foreach($rst as $re){
					$res[$re['siteid']]=$re['sitename'];
			}
		}
		return $res;
	}
   function addoffers($bid,$offerid){
        $arr = array_keys($_POST);
        $starttime =  $_POST['starttime'];
        $endtime =  $_POST['endtime'];
        $offerper =  $_POST['offerper'];
		$bid = $_POST['bid'];
		$id = $_POST['siteid'];
        $sql=$this->db->query("SELECT * FROM offers WHERE siteid='".$id."'");
			if($sql->num_rows()==0){ 
			for($i=0;$i<count($arr);$i++){
				if(isset($_POST[$arr[$i]]) && $_POST[$arr[$i]] != '' && !in_array($arr[$i],array('update_system','offerid'))){
					$this->db->set($arr[$i],$_POST[$arr[$i]]);
				}
		 }    
			            $this->db->set('status',1);
   				     	$this->db->set('siteid',$id);
					   	$this->db->insert("offers");
			
				return '0';
                }else{
   					for($i=0;$i<sizeof($arr);$i++){
   					   if(!in_array($arr[$i],array('update_system','offerid'))){
   							/* Changed for custom fields */
   							if(is_array($_POST[$arr[$i]]))
   								echo $val = @implode(',',$_POST[$arr[$i]]);
   							elseif($_POST[$arr[$i]]!="")
   								echo $val=$_POST[$arr[$i]];
   							else
   								$val='';
   							$this->db->set($arr[$i],$val);
   						}
   					}

   					$this->db->set('offerper',$_POST['offerper']);
   					$this->db->set('offer_desc',$_POST['offer_desc']);
   					$this->db->set('starttime',$_POST['starttime']);
   					$this->db->set('endtime',$_POST['endtime']);
   					$this->db->where('offerid',$offerid);
   					$this->db->where('siteid',$id);
   					$this->db->update("offers");
   				return '1';
   				}			
		}
   function getlistoffers($bid,$ofset,$limit,$id){
   		$q= '';
   		if(isset($_POST['submit'])){
   			$this->session->set_userdata('search',$_POST);
   		}
   		if($this->session->userdata('search')){
   			$s = $this->session->userdata('search');
   		}
   		$q .=(isset($s['sitename']) && $s['sitename']!='')?" AND a.locmane LIKE '%".$s['locname']."%'":"";
   		$roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
   		$limit = ($roleDetail['role']['recordlimit']>($ofset+$limit))?$limit
   					:((($roleDetail['role']['recordlimit'] - $ofset)>0)?($roleDetail['role']['recordlimit'] - $ofset):0);
   		$sql = "SELECT SQL_CALC_FOUND_ROWS a.* FROM offers a
   				WHERE  a.status = '1' AND endtime >= curdate() GROUP BY a.siteid
   		        LIMIT $ofset,$limit";  
   		$rst = $this->db->query($sql)->result_array();
   		$rst1 = $this->db->query("SELECT FOUND_ROWS() as cnt");
   		$ret['count'] = $rst1->row()->cnt;
   		foreach($roleDetail['modules'] as $mod){
   			if($mod['modid']=='50'){
   				$opt_add 	= $mod['opt_add'];
   				$opt_view 	= $mod['opt_view'];
   				$opt_delete = $mod['opt_delete'];
   			}
   		}
 
   		$fieldset = $this->configmodel->getFields('50',$bid);
   		$keys = array();
   		$header = array('#',"<a href='javascript://'><span id='c_all' class='glyphicon glyphicon-gok'></span></a>");
   			if($opt_add || $opt_view || $opt_delete)
   			array_push($header,$this->lang->line('level_Action'));
   		foreach($fieldset as $field){
   			$checked = false;
   			if($field['type']=='s' && !$field['is_hidden'] && $field['show'] && $field['listing']){
   				foreach($roleDetail['system'] as $f){
   					if($f['fieldid']==$field['fieldid'])$checked = true;
   				}
   				if($checked){
   					array_push($keys,$field['fieldname']);
   					array_push($header,(($field['customlabel']!="")
   										?$field['customlabel']
   										:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']));
   				}
   			}elseif($field['type']=='c' && $field['show'] && $field['listing']){
   				foreach($roleDetail['custom'] as $f){if($f['fieldid']==$field['fieldid'])$checked = true;}
   				if($checked){
   					array_push($keys,$field['fieldKey']);
   					array_push($header,$field['customlabel']);
   				}
   			}
   		}
  
   		$ret['header'] = $header;
   		$list = array();
   		$i = $ofset+1;
   		foreach($rst as $rec){
   			$data = array($i);
   			$v = '<input type="checkbox" class="blk_check" name="blk[]" value="'.$rec['offerid'].'"/>';	
   			array_push($data,$v);
   			$act = '';
			if($opt_add || $opt_view || $opt_delete){
				$act .= '<div class="btn-group">&nbsp;&nbsp;
							<a class="dropdown-toggle" data-toggle="dropdown" style=";font-weight:bold;"> Action <span class="caret"></span></a>
							<ul class="dropdown-menu" style="text-align:left;">';
			    $act .= ($opt_add) ?'<li><a href="mconnect/addoffers/'.$rec['offerid'].'"><span title="Edit" class="fa fa-edit">&nbsp;&nbsp;Edit</span></a></li>':'';
   				$act .= ($opt_delete) ? '<li><a href="mconnect/deleteoffer/'.$rec['offerid'].'" class="deleteClass"><span title="Delete" class="glyphicon glyphicon-trash">&nbsp;&nbsp;Delete</span></a></li>':'';
				$act .= '</ul></div>';
				$data['action'] = $act;
			}
   			$r = $this->configmodel->getDetail('50',$rec['offerid'],'',$bid);
   			foreach($keys as $k){
				if($k  == 'siteid'){
                    $v = $r['sitename'];
				}else{
   					$v = isset($r[$k])?nl2br(wordwrap($r[$k],80,"\n")):"";	
				}
				  array_push($data,$v);
   			}

   			$i++;
   			array_push($list,$data);
   		}
   		$ret['rec'] = $list;
   		return $ret;
   	}

   	function deleteoffer($bid,$offerid){
   		$sql=$this->db->query("SELECT offerid FROM offers WHERE offerid='".$offerid."'");
		if($sql->num_rows()>0){ 
		$ress=$sql->result_array();	
   		$this->db->where('offerid',$offerid);
   		$this->db->delete('offers');
   		return '1';
   	} 
   }
   	function sitereferrals(){
	    $cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
		$q = '';
		$sql="SELECT m.usrname as name,m.usremail as email,m.usrnumber as number,m.uid,s.sitename,s.siteid,r.referedto as rname,r.referedtoemail as remail,r.referedtonum as rnumber,r.comment,CAST(date AS DATE) as date,CAST(date as time) as time FROM referrals r
		                       LEFT JOIN ".$bid."_site s on s.siteid = r.siteid
		                       LEFT JOIN mconnect_register m on r.authKey = m.authKey";							 
		$rst = $this->db->query($sql)->result_array();
	    $rst1 = $this->db->query("SELECT FOUND_ROWS() as cnt");
		$ret['count'] = $rst1->row()->cnt;
		$header = array('#'
			            ,'Action'
			            ,'Sitename'
						,'Referred By'
						,'Email'
						,'Number'
						,'Referred To'
						,'Email'
						,'Number'
                        ,'Data'
						,'Time'
						,'Comment'
						);
		$ret['header'] = $header;
		$list = array();
		$i = 1;
		foreach($rst as $rec){
			$act = '';

				$act .= '<div class="btn-group">&nbsp;&nbsp;
							<a class="dropdown-toggle" data-toggle="dropdown" style=";font-weight:bold;"> Action <span class="caret"></span></a>
							<ul class="dropdown-menu" style="text-align:left;">';
				$act .= '<li><a href="Report/followup/'.$rec['siteid'].'/0/sitevisit" class="btn-followup" data-toggle="modal" data-target="#modal-followup"><span title="Followups" class="glyphicon glyphicon-book">&nbsp;Followups</span></a></li>';	
				$act .= "<li><a href=\"Javascript:void(null)\" onClick=\"window.open('".base_url()."Email/compose/".$rec['uid']."', 'Counter', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=950,height=480,resizable=1')\"><span title='Send Mail' class='glyphicon glyphicon-envelope'>&nbsp;SendMail</span></a></li>";
				$act .= "<li>".anchor("Report/sendSms/".$rec['number']."/referrals", '<span title="Click to send SMS" class="glyphicon glyphicon-comment">&nbsp;SendSMS</span>','class="clickToSMS" data-toggle="modal" data-target="#modal-empl"')."</li>";
				$act .= "<li>".anchor("Report/converttolead/".$rec['siteid'], '<span title="Convert to lead" class="glyphicon glyphicon-share">&nbsp;Convert&nbsp;to&nbsp;lead</span>','class="clickToSMS" data-toggle="modal" data-target="#modal-empl"')."</li>";
				$act .= "<li>".anchor("Report/mcubecalls/".$rec['number']."/48", '<span title="Mcube calls" class="fa fa-phone">&nbsp;Click to connect</span>','class="mcubecalls" data-toggle="modal" data-target="#modal-empl"')."</li>";
				$act .= '</ul></div>';
				$ret['action'] = $act;
   
			$data = array($i
			              ,$ret['action']
						  ,$rec['sitename']
						  ,$rec['name']
						  ,$rec['email']
						  ,$rec['number']
						  ,$rec['rname']
						  ,$rec['remail']
						  ,$rec['rnumber']
						  ,$rec['date']
						  ,$rec['time']
						  ,$rec['comment']
						  );
			$i++;
			array_push($list,$data);
		}
		$ret['rec'] = $list;
		return $ret;
	}
	function getuserdetail($id){
		$sql="SELECT  usrname as name,usremail as email,usrnumber as number FROM mconnect_register where uid='".$id."'";
		$rst = (array)$this->db->query($sql)->row();
		return $rst;
	}
   }
   ?>

