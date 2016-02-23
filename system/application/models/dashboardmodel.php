<?php
   Class Dashboardmodel extends Model {
	   
    function Dashboard(){
		parent::Model();
		$this->load->model('configmodel','CM');
		$this->load->model('empmodel','EMPM');
		$this->load->model('systemmodel','SYS');
	}
   	function sitevist(){
		$cbid=$this->session->userdata('cbid');
		$bid=(isset($cbid) && $cbid!="")?$cbid:$this->session->userdata('bid');
         $sql     = "SELECT v.sitename,COUNT(DISTINCT(ul.authkey)) as likes,COUNT(DISTINCT(vh.authkey)) as visits,COUNT(DISTINCT(r.referedtonum)) as referrals FROM  ".$bid."_site v
	     LEFT JOIN  user_likes ul ON ul.siteid = v.siteid
	     LEFT JOIN  visited_history vh ON vh.siteid = v.siteid
	     LEFT JOIN referrals r ON r.siteid = v.siteid
	     GROUP BY  v.siteid ORDER BY v.time ASC";
        $rest    = $this->db->query($sql)->result_array();
              return $rest;
	}
              
 }
   ?>

