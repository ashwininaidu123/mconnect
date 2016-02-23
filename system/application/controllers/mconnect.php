
<?php
class Mconnect extends controller
{
	var $data,$roleDetail;
	function Mconnect(){
		parent::controller();
		if(!$this->session->userdata('logged_in'))redirect('/site/login');
		$this->load->model('sysconfmodel');
		$this->data = $this->sysconfmodel->init();
		$this->load->model('systemmodel');
		$this->load->model('mconnectmodel');
	    $this->load->helper('mcube_helper');
	    $this->load->model('supportmodel');
		$this->load->model('auditlog');
		$this->load->model('configmodel');
		$this->roleDetail = $this->empmodel->getRoledetail($this->session->userdata('roleid'));
				$this->load->model('sysconfmodel');
		$this->data = $this->sysconfmodel->init();


	}
	public function __destruct() {
		$this->db->close();
	}
	function feature_access(){
		$show=0;
		$checklist=$this->systemmodel->checked_featuremanage();
		if(in_array(17,$checklist)){
			$show=1;
		}
		return $show;
	}

	function addsite($id=''){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="") ? $cbid : $this->session->userdata('bid');
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$roleDetail = $this->roleDetail;
		if(!$roleDetail['modules']['48']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('update_system')){
			$this->form_validation->set_rules('sitename', 'Site Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			if (!$this->form_validation->run() == FALSE){
			if($id == ""){
			$res = $this->mconnectmodel->addsite($bid);
				$this->session->set_flashdata('msgt', 'success');
					if($res == '0'){
						$this->session->set_flashdata('msg', "Site added Successfully");
						redirect('ListSite/0');
					}
		}else{
			$res = $this->mconnectmodel->editsite($bid,$id);
				$this->session->set_flashdata('msgt', 'success');
					if($res == '1'){
						$this->session->set_flashdata('msg', "Site Updated Successfully");
						redirect('ListSite/0');
					}
		}
		}
		}
		$this->sysconfmodel->data['html']['title'] .= " | Add Site";
		$data['module']['title'] = "Add Site";
		$fieldset = $this->configmodel->getFields('48',$bid);
		$itemDetail = $this->configmodel->getDetail('48',$id,'',$bid);
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && $field['fieldname']!='' && $field['fieldname']!='sitemedia'){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked && !in_array($field['fieldname'] ,array())) 
					$formFields[] = array(
									'label'=>'<label  class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' <img src="system/application/img/icons/help.png" title="'.$this->lang->line('TTmod_'.$field['modid'])->$field['fieldname'].'" >&nbsp;&nbsp: </label>',
												'field'=>($field['fieldname'] == 'tracknum') ? form_dropdown('tracknum',$this->systemmodel->getPriList(isset($itemDetail['prinumber'])?$itemDetail['prinumber']:'','1'),isset($itemDetail['tracknum']) ? $itemDetail['tracknum']:'','id="tracknum" class="form-control required" ')
												:((in_array($field['fieldname'],array('sitevideo')))? 
																	(form_input(array(
																	  'name'       => $field['fieldname']
																	  ,'id'        => $field['fieldname']
														              ,'class'	   => 'form-control'
																      ,'style'     => 'float: left;'
																      ,'value'     =>  isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname'])
																	 )))
											   :((in_array($field['fieldname'],array('siteicon')))? 
																	(form_input(array(
																	  'name'      => $field['fieldname']
																	  ,'id'       => $field['fieldname']
																      ,'type'     => 'file'
																      ,'parsley-filemaxsize' =>"Upload|1.5"
																      ,'style'     => 'float: left;'
																      ,'accept'   => 'image/gif, image/jpeg , image/png'
																      ,'value'    => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname'])
																	 ))	)
											    :((in_array($field['fieldname'],array('siteimg')))? 
																	(form_input(array(
																	  'name'      => $field['fieldname']
																	  ,'id'       => $field['fieldname']
																      ,'type'     => 'file'
																      ,'parsley-filemaxsize' =>"Upload|1.5"
																      ,'accept'   => 'image/gif, image/jpeg , image/png'
																      ,'style'    => 'float: left;'
																      ,'value'    => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname'])
																	 ))	).'<div class="input_image_wrap">
														<span id="add_image_button" class="btn btn-success fileinput-button add_image_button"><i class="glyphicon glyphicon-plus"></i><span>Add image...</span></span>
														
												</div>'
											  :((in_array($field['fieldname'],array("siteinterest_opt")))?
												form_textarea(array(
											  'name'       => $field['fieldname']
											  ,'id'        => $field['fieldname']
											  ,'parsley-trigger' => "keyup"
											  ,'parsley-rangelength'=> "[20,50]"
											  ,'class'	   => 'form-control valid'
											  ,'value'     => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname']),
											)).'<div class="input_fields_wrap">
														<span  class="btn btn-success fileinput-button add_field_button"><i class="glyphicon glyphicon-plus"></i><span>Add field...</span></span>
												</div>'
											   :((in_array($field['fieldname'],array("sitedesc")))?
												form_textarea(array(
											  'name'       => $field['fieldname']
											  ,'id'        => $field['fieldname']
											  ,'parsley-trigger' => "keyup"
											  ,'parsley-rangelength'=> "[20,160]"
											  ,'class'	   => 'form-control valid'
											  ,'value'     => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname']),
											))	
											 :((in_array($field['fieldname'],array("email")))?
												form_input(array(
											  'name'       => $field['fieldname']
											  ,'id'        => $field['fieldname']
											  ,'class'	   => 'form-control required'
											  ,'type'	   => 'email'
											  ,'value'     => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname']),
											))		
											  :form_input(array(
												'name'      => $field['fieldname'],
												'id'        => $field['fieldname'],
												'value'		=> (isset($itemDetail[$field['fieldname']])) ? $itemDetail[$field['fieldname']] : '',
												'class'		=> ($field['fieldname'] == 'sitename') ? 'form-control required' : 'form-control'))
												)))))));
			}elseif($field['type']=='c' && $field['show']){
					foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
					if($checked)$formFields[] = array(
							'label'=>'<label  class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
							'field'=>$this->configmodel->createFieldAdvance($field,isset($itemDetail[$field['fieldKey']]) ? $itemDetail[$field['fieldKey']] : '',''));
			}
		}
		$data['form'] = array(
		            'form_attr'=>array('action'=>'mconnect/addsite/'.$id,'name'=>'addsite','id'=>'addsite','enctype'=>"multipart/form-data"),
					'hidden'=>array('bid'=>$bid,'siteid'=>$id),
					'fields'=>$formFields,'parentids'=>$parentbids,
					'busid'=>$bid,
					'pid'=>$this->session->userdata('pid'),
					'close'=>form_close()
				);
		$this->sysconfmodel->viewLayout('form_view',$data);
	}
	function listSite(){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['48']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '30';
		$data['itemlist'] = $this->mconnectmodel->getlistSite($bid,$ofset,$limit);
		$this->pagination->initialize(array(
						 'base_url'=>site_url($this->uri->segment(2).'/')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>2				
				));
		$data['module']['title'] = "Sites [".$data['itemlist']['count']."]";	
		$links = array();	
		$links[] = '<li><a href="mconnect/addsite"><span title="Add Site" class="glyphicon glyphicon-plus-sign">&nbsp;Add</span></a></li>';
	    $links[] = ($roleDetail['modules']['48']['opt_delete']) ? '<li><a href="mconnect/bulkDelSite" class="blkDelsite"><span title="Bulk Delete" class="glyphicon glyphicon-trash">&nbsp;Delete</span></a></li>':'';
	    $links[] = '<li class="divider"><a>&nbsp;</a></li>';
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$fieldset = $this->configmodel->getFields('48',$bid);
		$formFields = array();
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && !in_array($field['fieldname'],array('siteicon','sitevideo','siteimg'))){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
									'label'=>'<label class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' : </label>',
									'field'=>
										($field['fieldname']=='eid')
											?form_dropdown('eid',$this->supportmodel->getEmployees(),'',"class='form-control'")
											:form_input(array(
												'name'      => $field['fieldname'],
												'id'        => $field['fieldname'],
												'class'		=>($field['fieldname']=="createdon" || $field['fieldname']=="lastmodified")?'datepicker_leads form-control':'form-control'
												))
									);
								}		
			}elseif($field['type']=='c' && $field['show']){
				foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
								'label'=>'<label class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
								'field'=>form_input(array(
													'name'      => 'custom['.$field['fieldid'].']',
													'class'      => 'form-control'
													)));
								//$advsearch['custom['.$field['fieldid'].']']=$field['customlabel'];							
							}						
			}
		}
		$data['links'] = $links;
		$data['form'] = array(
					'open'=>form_open_multipart(site_url('mconnect/listSite'),array('name'=>'listsite','class'=>'form','id'=>'listsite','method'=>'post')),
					'form_field'=>$formFields,
					'parentids'=>$parentbids,
					'adv_search'=>array(),
					'busid'=>$bid,
					'pid'=>$this->session->userdata('pid'),
					'close'=>form_close(),
					'title'=>$this->lang->line('level_search')
					);
		$data['paging'] = $this->pagination->create_links();
		$this->sysconfmodel->data['html']['title'] .= " | Sites";
		if(isset($_POST['search']) && $_POST['search'] == 'search'){
			$this->load->view('search_view',$data);
			return true;
		}
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
	function delSite($id){
		if(!$this->feature_access(17))redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;
		if(!$roleDetail['modules']['48']['opt_delete']) redirect('Employee/access_denied');
		$ret = $this->mconnectmodel->delSite($id,$bid);
		if(!$ret == '0'){
		redirect('mconnect/listSite/0');
      	}
     }
    function bulkDelSite(){
		$res=$this->mconnectmodel->bulkDelSite($_POST['siteid']);
		echo "1";
	}
	function deleteSite(){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$roleDetail = $this->roleDetail;
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$heading="Deleted Sites";
		if(!$roleDetail['modules']['48']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){	
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '20';
		$data['itemlist'] = $this->mconnectmodel->delSlist($bid,$ofset,$limit,$url='');
		$this->pagination->initialize(array(
						 'base_url'=>site_url('mconnect/deleteSite')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>3				
				));
		$links = array();
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$data['module']['title'] = "Deleted Sites";
		$fieldset = $this->configmodel->getFields('48',$bid);

		$formFields = array();
		$advsearch=array();
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && !in_array($field['fieldname'],array('siteicon','sitevideo','siteimg'))){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
									'label'=>'<label  class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' : </label>',
									'field'=>
										($field['fieldname']=='siteicon')
											?form_dropdown('gid',$this->supportmodel->getSupportGrps(),'',"class='form-control'")
											:(($field['fieldname']=='assignto')
												?form_dropdown('assignto',$this->supportmodel->getEmployees(),'',"class='form-control'")
												:(($field['fieldname']=='tkt_status') ? form_dropdown('tkt_status',$this->supportmodel->getSupStatus($bid),'',"class='form-control'")
												:(($field['fieldname']=='tkt_criticality') ? form_dropdown('tkt_criticality',$this->supportmodel->getSupTktCritic(),'',"class='form-control'")
												:form_input(array(
													'name'      => $field['fieldname'],
													'id'        => $field['fieldname'],
													'class'		=>($field['fieldname']=="createdon" || $field['fieldname']=="lastmodified")?'datepicker_leads form-control':'form-control'
													))
												)))
										);
										$advsearch[$field['fieldname']]=(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']);
								}			
			}elseif($field['type']=='c' && $field['show']){
				foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
								'label'=>'<label  class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
								'field'=>form_input(array(
													'name'      => 'custom['.$field['fieldid'].']',
													'class'     => 'form-control',
													)));
													$advsearch['custom['.$field['fieldid'].']']=$field['customlabel'];	
												}
			}
		}
		$save_cnt=save_search_count($bid,'40',$this->session->userdata('eid'));	
		$data['links'] = $links;
		$data['form'] = array(
					'open'=>form_open_multipart(site_url('mconnect/deleteSite'),array('name'=>'delsite','class'=>'form','id'=>'delsite','method'=>'post')),
					'form_field'=>$formFields,
					'adv_search'=>array(),
					'save_search'=>$save_cnt,
					'parentids'=>$parentbids,
					'busid'=>$bid,
					'pid'=>$this->session->userdata('pid'),
					'close'=>form_close(),
					'title'=>$this->lang->line('level_search')
					);
		$data['paging'] = $this->pagination->create_links();
		$this->sysconfmodel->data['html']['title'] .= " | Deleted Sites ";
		if(isset($_POST['search']) && $_POST['search'] == 'search'){
			$this->load->view('search_view',$data);
			return true;
		}
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
	function undelSite($siteid){
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$res=$this->mconnectmodel->undelSt($siteid,$bid);
		$this->session->set_flashdata('msgt', 'success');
		$this->session->set_flashdata('msg',"Deleted Record restored Successfully");
		redirect('mconnect/listSite/0');
	}
	function addlocation($id='',$locid = ''){
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="") ? $cbid : $this->session->userdata('bid');
		$roleDetail = $this->roleDetail;
		if(!$roleDetail['modules']['49']['opt_view']) redirect('Employee/access_denied');
			if($this->input->post('update_system')){
			$this->form_validation->set_rules('locname', 'Location Name', 'required');
			if(!$this->form_validation->run() == FALSE){
				  $res = $this->mconnectmodel->addnewlocation($id,$locid);
					if($res == '0'){
						$this->session->set_flashdata('msg', "Location added Successfully");
				        redirect('ListLocation/'.$id);
					}else{
						$this->session->set_flashdata('msg', "Location Updated Successfully");
						redirect('ListLocation/'.$id);
					}
			}	
		}
		$this->sysconfmodel->data['html']['title'] .= " | Add Location";
		$data['module']['title'] = "Add Location";
		$fieldset = $this->configmodel->getFields('49',$bid);
		$itemDetail = $this->configmodel->getDetail('49',$locid,'',$bid);
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && $field['fieldname']!=''){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked && !in_array($field['fieldname'] ,array())) 
					$formFields[] = array(
									'label'=>'<label  class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' <img src="system/application/img/icons/help.png" title="'.$this->lang->line('TTmod_'.$field['modid'])->$field['fieldname'].'" >&nbsp;&nbsp: </label>',
												'field'=>($field['fieldname'] == 'beaconid') ?((isset($itemDetail['beaconid'])) ?(form_input(array(
																	  'name'      => $field['fieldname']
																	  ,'id'       => $field['fieldname']
																	  ,'class'	   => 'form-control valid'
																	  ,'readonly' => 'readonly'
																      ,'value'    => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname'])
																	 ))): form_dropdown('beaconid',$this->mconnectmodel->getBeaconlist(),isset($itemDetail['beaconid']) ? $itemDetail['beaconid']:'','id="beaconid" class="form-control required" '))
											  :((in_array($field['fieldname'],array('loc_image')))? 
																	(form_input(array(
																	  'name'      => $field['fieldname']
																	  ,'id'       => $field['fieldname']
																      ,'type'     => 'file'
																      ,'parsley-filemaxsize' =>"Upload|1.5"
																      ,'accept'   => 'image/gif, image/jpeg , image/png'
																      ,'style'    => 'float: left;'
																      ,'value'    => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname'])
																	 ))).'<div class="input_img_wrap">
														<span  class="btn btn-success fileinput-button add_img_button"><i class="glyphicon glyphicon-plus"></i><span>Add image...</span></span>
												</div>'
											  :((in_array($field['fieldname'],array("loc_desc")))?
												form_textarea(array(
											  'name'       => $field['fieldname']
											  ,'id'        => $field['fieldname']
											  ,'parsley-trigger' => "keyup"
											  ,'parsley-rangelength'=> "[20,500]"
											  ,'class'	   => 'form-control valid'
											  ,'value'     => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname']),
											   ))
									     	 :((in_array($field['fieldname'],array("locname")))?
												form_input(array(
											  'name'       => $field['fieldname']
											  ,'id'        => $field['fieldname']
											  ,'parsley-trigger' => "keyup"
											  ,'parsley-rangelength'=> "[10,50]"
											  ,'class'	   => 'form-control valid'
											  ,'value'     => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname']),
											))
										    :form_input(array(
												'name'      => $field['fieldname'],
												'id'        => $field['fieldname'],
												'value'		=> (isset($itemDetail[$field['fieldname']])) ? $itemDetail[$field['fieldname']] : '',
												'class'		=> ($field['fieldname'] == 'sitename') ? 'form-control required' : 'form-control'))
												))));
			}elseif($field['type']=='c' && $field['show']){
					foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
					if($checked)$formFields[] = array(
							'label'=>'<label  class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
							'field'=>$this->configmodel->createFieldAdvance($field,isset($itemDetail[$field['fieldKey']]) ? $itemDetail[$field['fieldKey']] : '',''));
			}
		}
		$data['form'] = array(
		            'form_attr'=>array('action'=>'mconnect/addlocation/'.$id,'name'=>'addlocation','id'=>'addlocation','enctype'=>"multipart/form-data"),
					'hidden'=>array('bid'=>$bid,'siteid'=>$id, 'locid'=>$locid),
					'fields'=>$formFields,
					'busid'=>$bid,
					'pid'=>$this->session->userdata('pid'),
					'close'=>form_close()
				);
		$this->sysconfmodel->viewLayout('form_view',$data);
	}
   function listlocation($id){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['49']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '30';
		$data['itemlist'] = $this->mconnectmodel->getlistlocation($bid,$ofset,$limit,$id);
		$this->pagination->initialize(array(
						 'base_url'=>site_url($this->uri->segment(2).'/')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>2				
				));
		$data['module']['title'] = "Locations [".$data['itemlist']['count']."]";	
		$links = array();	
		$links[] = '<li><a href="mconnect/addlocation"><span title="Add Location" class="glyphicon glyphicon-plus-sign">&nbsp;Add</span></a></li>';
	    $links[] = ($roleDetail['modules']['49']['opt_delete']) ? '<li><a href="mconnect/bulkDelSite" class="blkDelsite"><span title="Bulk Delete" class="glyphicon glyphicon-trash">&nbsp;Delete</span></a></li>':'';
	    $links[] = '<li class="divider"><a>&nbsp;</a></li>';
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$fieldset = $this->configmodel->getFields('49',$bid);
		$formFields = array();
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && !in_array($field['fieldname'],array('siteicon','sitevideo','siteimg'))){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
									'label'=>'<label class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' : </label>',
									'field'=>
										($field['fieldname']=='eid')
											?form_dropdown('eid',$this->supportmodel->getEmployees(),'',"class='form-control'")
											:form_input(array(
												'name'      => $field['fieldname'],
												'id'        => $field['fieldname'],
												'class'		=>($field['fieldname']=="createdon" || $field['fieldname']=="lastmodified")?'datepicker_leads form-control':'form-control'
												))
									);
								}		
			}elseif($field['type']=='c' && $field['show']){
				foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
								'label'=>'<label class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
								'field'=>form_input(array(
													'name'      => 'custom['.$field['fieldid'].']',
													'class'      => 'form-control'
													)));
								//$advsearch['custom['.$field['fieldid'].']']=$field['customlabel'];							
							}						
			}
		}
		$data['links'] = $links;
		$data['form'] = array(
					'open'=>form_open_multipart(site_url('mconnect/listlocation'),array('name'=>'listlocation','class'=>'form','id'=>'listlocation','method'=>'post')),
					'form_field'=>$formFields,
					'parentids'=>$parentbids,
					'adv_search'=>array(),
					'busid'=>$bid,
					'pid'=>$this->session->userdata('pid'),
					'close'=>form_close(),
					'title'=>$this->lang->line('level_search')
					);
		$data['paging'] = $this->pagination->create_links();
		$this->sysconfmodel->data['html']['title'] .= " | Locations";
		if(isset($_POST['search']) && $_POST['search'] == 'search'){
			$this->load->view('search_view',$data);
			return true;
		}
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
	function deleteLocation($id=''){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;
		$itemDetail = $this->configmodel->getDetail('49',$id,'',$bid);
		$beaconid = $itemDetail['beaconid']; 
		if(!$roleDetail['modules']['49']['opt_delete']) redirect('Employee/access_denied');
		$ret = $this->mconnectmodel->deleteLocation($id,$bid,$beaconid);
		if(!$ret == '0'){
		redirect('mconnect/listlocation/0');
      	}
     }
    //~ function Imagegallery($img){ 
	   //~ $data['image'] = $img;
	   //~ $data['module']['title'] = "Site images";
	   //~ $this->load->view('imagegallery',$data);
	//~ }
   function sitevisits(){
	   		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['49']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '30';
		$data['itemlist'] = $this->mconnectmodel->sitevisits();
		$this->pagination->initialize(array(
						 'base_url'=>site_url($this->uri->segment(2).'/')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>2				
		));
		$data['module']['title'] = "Site Visit";	
		$links = array();	
		$links[] = '<li><a href="mconnect/addlocation"><span title="Add Location" class="glyphicon glyphicon-plus-sign">&nbsp;Add</span></a></li>';
	    $links[] = ($roleDetail['modules']['49']['opt_delete']) ? '<li><a href="mconnect/bulkDelSite" class="blkDelsite"><span title="Bulk Delete" class="glyphicon glyphicon-trash">&nbsp;Delete</span></a></li>':'';
	    $links[] = '<li class="divider"><a>&nbsp;</a></li>';
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
        
        function sitereferrals(){
	   		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['49']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '30';
		$data['itemlist'] = $this->mconnectmodel->sitereferrals();
		$this->pagination->initialize(array(
						 'base_url'=>site_url($this->uri->segment(2).'/')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>2				
		));
		$data['module']['title'] = "Site Referral";	
		$links = array();	
		$links[] = '<li><a href="mconnect/addlocation"><span title="Add Location" class="glyphicon glyphicon-plus-sign">&nbsp;Add</span></a></li>';
	    $links[] = ($roleDetail['modules']['49']['opt_delete']) ? '<li><a href="mconnect/bulkDelSite" class="blkDelsite"><span title="Bulk Delete" class="glyphicon glyphicon-trash">&nbsp;Delete</span></a></li>':'';
	    $links[] = '<li class="divider"><a>&nbsp;</a></li>';
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
       function siteoffers(){
	   		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['49']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '30';
		$data['itemlist'] = $this->mconnectmodel->sitevisits();
		$this->pagination->initialize(array(
						 'base_url'=>site_url($this->uri->segment(2).'/')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>2				
		));
		$data['module']['title'] = "Site Offer";	
		$links = array();	
		$links[] = '<li><a href="mconnect/addlocation"><span title="Add Location" class="glyphicon glyphicon-plus-sign">&nbsp;Add</span></a></li>';
	    $links[] = ($roleDetail['modules']['49']['opt_delete']) ? '<li><a href="mconnect/bulkDelSite" class="blkDelsite"><span title="Bulk Delete" class="glyphicon glyphicon-trash">&nbsp;Delete</span></a></li>':'';
	    $links[] = '<li class="divider"><a>&nbsp;</a></li>';
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
	
	function addoffers($offerid){
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="") ? $cbid : $this->session->userdata('bid');
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$roleDetail = $this->roleDetail;
		if(!$roleDetail['modules']['50']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('update_system')){
			$this->form_validation->set_rules('offerper', 'Offer Percentage', 'required');
			$this->form_validation->set_rules('siteid', 'Site Name', 'required');
			if (!$this->form_validation->run() == FALSE){
			$res = $this->mconnectmodel->addoffers($bid,$offerid);
				$this->session->set_flashdata('msgt', 'success');
					if($res == '0'){
						$this->session->set_flashdata('msg', "Offer added Successfully");
						redirect('ListOffers/0');
					}else{
						$this->session->set_flashdata('msg', "Offer Updated Successfully");
						redirect('ListOffers/0');
					}
			}
		}
		$this->sysconfmodel->data['html']['title'] .= " | Add Offer";
		$data['module']['title'] = "Add Offer";
		$fieldset = $this->configmodel->getFields('50',$bid);
		$itemDetail = $this->configmodel->getDetail('50',$offerid,'',$bid);
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && $field['fieldname']!='' && $field['fieldname']!=''){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked && !in_array($field['fieldname'] ,array())) 
					$formFields[] = array(
									'label'=>'<label  class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' <img src="system/application/img/icons/help.png" title="'.$this->lang->line('TTmod_'.$field['modid'])->$field['fieldname'].'" >&nbsp;&nbsp: </label>',
												'field'=>(in_array($field['fieldname'],array("siteid")) ?( form_dropdown('siteid',$this->mconnectmodel->getSitelist($bid),isset($itemDetail['siteid']) ? $itemDetail['siteid']:'','id="siteid" class="form-control required" '))
											   :((in_array($field['fieldname'],array('starttime','endtime')))?
												form_input(array(
											  'name'       => $field['fieldname']
											  ,'id'        => $field['fieldname']
											  ,'class'	   => 'datefutpicker form-control'
											  ,'value'     => isset($itemDetail[$field['fieldname']])?$itemDetail[$field['fieldname']]:$this->input->post($field['fieldname']),
											   ))
											  :form_input(array(
												'name'      => $field['fieldname'],
												'id'        => $field['fieldname'],
												'value'		=> (isset($itemDetail[$field['fieldname']])) ? $itemDetail[$field['fieldname']] : '',
												'class'		=> 'form-control ' ))
												)));
			}elseif($field['type']=='c' && $field['show']){
					foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
					if($checked)$formFields[] = array(
							'label'=>'<label  class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
							'field'=>$this->configmodel->createFieldAdvance($field,isset($itemDetail[$field['fieldKey']]) ? $itemDetail[$field['fieldKey']] : '',''));
			}
		}
		$data['form'] = array(
		            'form_attr'=>array('action'=>'mconnect/addoffers/'.$offerid,'name'=>'addoffers','id'=>'addoffers','enctype'=>"multipart/form-data"),
					'hidden'=>array('bid'=>$bid),
					'fields'=>$formFields,'parentids'=>$parentbids,
					'close'=>form_close()
				);
		$this->sysconfmodel->viewLayout('form_view',$data);
	}
	function listoffers($id){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['50']['opt_view']) redirect('Employee/access_denied');
		if($this->input->post('submit')){
			if($this->session->userdata('search')!=""){
				$s=$this->session->unset_userdata('search');
			}
		}
		
		$parentbids=array();
		if($this->session->userdata('eid')==1){
			$parentbids=$this->systemmodel->getChildBusiness();
		}
		$ofset = ($this->uri->segment(3)!=null)?$this->uri->segment(3):0;
		$limit = '30';
		$data['itemlist'] = $this->mconnectmodel->getlistoffers($bid,$ofset,$limit,$id);
		$this->pagination->initialize(array(
						 'base_url'=>site_url($this->uri->segment(2).'/')
						,'total_rows'=>$data['itemlist']['count']
						,'per_page'=>$limit		
						,'uri_segment'=>2				
				));
		$data['module']['title'] = "Offers [".$data['itemlist']['count']."]";	
		$links = array();	
		$links[] = '<li><a href="mconnect/addlocation"><span title="Add Location" class="glyphicon glyphicon-plus-sign">&nbsp;Add</span></a></li>';
	    $links[] = ($roleDetail['modules']['50']['opt_delete']) ? '<li><a href="mconnect/bulkDelSite" class="blkDelsite"><span title="Bulk Delete" class="glyphicon glyphicon-trash">&nbsp;Delete</span></a></li>':'';
	    $links[] = '<li class="divider"><a>&nbsp;</a></li>';
		$links[] = '<li><a href="'.$_SERVER['REQUEST_URI'].'" class="btn-search" data-toggle="modal" data-target="#modal-search" ><span title="Search" class="glyphicon glyphicon-search" >&nbsp;Search</span></a></li>';
		$fieldset = $this->configmodel->getFields('50',$bid);
		$formFields = array();
		foreach($fieldset as $field){
			$checked = false;
			if($field['type']=='s' && $field['show'] && !in_array($field['fieldname'],array())){
				foreach($roleDetail['system'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
									'label'=>'<label class="col-sm-4 text-right" for="'.$field['fieldname'].'">'.(($field['customlabel']!="")
											 ?$field['customlabel']:$this->lang->line('mod_'.$field['modid'])->$field['fieldname']).' : </label>',
									'field'=>
										($field['fieldname']=='eid')
											?form_dropdown('eid',$this->supportmodel->getEmployees(),'',"class='form-control'")
											:form_input(array(
												'name'      => $field['fieldname'],
												'id'        => $field['fieldname'],
												'class'		=>($field['fieldname']=="createdon" || $field['fieldname']=="lastmodified")?'datepicker_leads form-control':'form-control'
												))
									);
								}		
			}elseif($field['type']=='c' && $field['show']){
				foreach($roleDetail['custom'] as $ret){if($ret['fieldid']==$field['fieldid'])$checked = true;}
				if($checked) { $formFields[] = array(
								'label'=>'<label class="col-sm-4 text-right" for="custom_'.$field['fieldid'].'">'.$field['customlabel'].' : </label>',
								'field'=>form_input(array(
													'name'      => 'custom['.$field['fieldid'].']',
													'class'      => 'form-control'
													)));
								//$advsearch['custom['.$field['fieldid'].']']=$field['customlabel'];							
							}						
			}
		}
		$data['links'] = $links;
		$data['form'] = array(
					'open'=>form_open_multipart(site_url('mconnect/listoffers'),array('name'=>'listoffers','class'=>'form','id'=>'listoffers','method'=>'post')),
					'form_field'=>$formFields,
					'parentids'=>$parentbids,
					'adv_search'=>array(),
					'busid'=>$bid,
					'pid'=>$this->session->userdata('pid'),
					'close'=>form_close(),
					'title'=>$this->lang->line('level_search')
					);
		$data['paging'] = $this->pagination->create_links();
		$this->sysconfmodel->data['html']['title'] .= " | Offers";
		if(isset($_POST['search']) && $_POST['search'] == 'search'){
			$this->load->view('search_view',$data);
			return true;
		}
		$this->sysconfmodel->viewLayout('list_view',$data);
	}
    function deleteoffer($offerid=''){
		if(!$this->feature_access())redirect('Employee/access_denied');
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
		$roleDetail = $this->roleDetail;	
		if(!$roleDetail['modules']['50']['opt_delete']) redirect('Employee/access_denied');
		$ret = $this->mconnectmodel->deleteoffer($offerid,$bid);
		if(!$ret == '1'){
		redirect('mconnect/listoffers/0');
      	}
     }
	
}
?>
