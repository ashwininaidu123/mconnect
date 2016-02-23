<?php
   include_once 'open_flash_chart_object.php';
   $url=site_url('dashboard/priweekly/');
   $url1=site_url('system/application/');
   ?>
<div id="main-content" class="dashboard">
   <div class="page-title">
      <i class="icon-custom-left"></i>
      <h3><strong>Dashboard</strong></h3>
   </div>

               <!--- ---------- Dashboard tab starts here --------------- --->
                    <table>
                     <tr>
                        <td>
	<?php $j=0; ?>
                           <ul>
		 <div class="row">
                          <div class="col-md-12">
                              <li>
                                 <div class="panel no-bd bg-red ">
									 	<div class="panel-heading clearfix pos-rel">
                                    <div class="panel-heading clearfix pos-rel text-center p-10 p-b-0">
                                       <h2 class="panel-title c-white headingclass"><?php echo "Site";?></h2>
                                    </div>
                                    </div>
                                   <div class="panel-body bg-red p-t-0 p-b-10">
									  <div class="row">
										<div class="col-md-12">
										  <div class="row m-b-10">
                                             <div class="withScroll">
                                                <table class="table tabdes table-striped table-hover" cellpadding="0" cellspacing="0" border="0">
                                                   <tr class="sortable  coldes bd-3 bg-opacity-20  fade in ui-sortable">
                                                      <th><?php echo "Site Name";?></th>
                                                      <th><?php echo "Visits";?></th>
                                                      <th><?php echo "Likes";?></th>
                                                    <!--
                                                      <th><?php //echo "Dislikes ";?></th>
-->
                                                      <th><?php echo "Referrals ";?></th>
                                                   </tr>
                                     <?php
                                                     for($i=0;$i<sizeof($visits);$i++)
                                                      {
                                                       
                                                      echo "<tr class='sortable  coldes bd-3 bg-opacity-20  fade in ui-sortable'>
                                                      			<td>".$visits[$i]['sitename']."</td>
                                                      			<td>".$visits[$i]['visits']."</td>
                                                      			<td>".$visits[$i]['likes']."</td>
                                                      			<td>".$visits[$i]['referrals']."</td>
                                                     
                                                      	    </tr>";
													  }
                                                      ?>	
                                                </table>
                                             </div>
                                          </div>
                                         </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              </div>
						</div>
					 <div class="row">
						  <div class="col-md-12">
                              <li>
                                 <div class="panel no-bd bg-green " >
									 	<div class="panel-heading padddef clearfix pos-rel">
                                    <div class="panel-heading  text-center p-10 p-b-0">
                                       <h2 class="panel-title c-white headingclass"><?php echo "Offers";?></h2>
                                    </div>
                                    </div>
                                           <div class="panel-body bg-green p-t-0 p-b-10">
									  <div class="row">
										<div class="col-md-12">
										  <div class="row m-b-10">
                                             <div class="withScroll">
                                               <table  class="table tabdes table-striped table-hover" cellpadding="0" cellspacing="0" border="0">
                                                   <tr class="sortable  coldes bd-3 bg-opacity-20  fade in ui-sortable">
													  <th><?php echo "Site Name";?></th>
                                                      <th><?php echo "Visit requests";?></th>
                                                      <th><?php echo "Likes";?></th>
<!--
                                                      <th><?php //echo "Dislikes ";?></th>
-->
                                                      <th><?php echo "Referrals ";?></th>
                                                   </tr>
                                                   <?php
                                                     for($i=0;$i<sizeof($visits);$i++)
                                                      {
                                                       
                                                      echo "<tr class='sortable  coldes bd-3 bg-opacity-20  fade in ui-sortable'>
                                                      			<td>".$visits[$i]['sitename']."</td>
                                                      			<td>".$visits[$i]['visits']."</td>
                                                      			<td>".$visits[$i]['likes']."</td>
                                                      			<td>".$visits[$i]['referrals']."</td>
                                                      	    </tr>";
													  }
                                                      ?>		
                                                </table>
                                             </div>
                                          </div>
                                         </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              </div>
						</div>
						</div>
                             </div>
                           </ul>
                        </td>
                     </tr>
                  </table>
               <!-- ---------- Dashboard tab Ends here --------------- --->
            </div>
         <div class="modal fade" id="modal-responsive" aria-hidden="true"></div>
      </div>
   </div>
</div>
</div>
