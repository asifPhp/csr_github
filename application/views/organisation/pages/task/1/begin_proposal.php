<style>
#headerMsg{	margin:20px 0px;}
.dataTables-example th{	text-align:center;}
.ibox-content{padding: 10px;}
[data-toggle="collapse"] .fa:before {  
  content: "\f139";
}ev

[data-toggle="collapse"].collapsed .fa:before {
  content: "\f13a";
}
.input_description{font-weight: 400;}
.actual_disp{
 background-color: white;
    opacity: 1;
    margin-top: 10px;
    border: none;
    color: blue;
}
.approval_sheet{
	cursor:pointer;
}
.modal-backdrop.in {
    filter: alpha(opacity=50);
    opacity: -0.5;
    display: none;
	
	
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php
	if($data_value){
		//var_dump($data_value);
		$superngo_id = $data_value[0]['superngo_id']; 
		$comments = $data_value[0]['comments'];
		$org_task_id = $data_value[0]['org_task_id']; 
		$project_id = $data_value[0]['project_id'];  
		$new_prop_id = $data_value[0]['new_prop_id'];  
		$superngo_details = $this->db->get_where('superngo',array('id' =>$superngo_id))->result();
		$org_task_list_id=$data_value[0]['org_task_list_id'];
		$org_id=$data_value[0]['org_id'];
		$prop_id=$data_value[0]['prop_id'];
		$org_staff_id=$data_value[0]['org_staff_id'];
		$status = $data_value[0]['status'];
		//var_dump($status);
		$ngo_id = $data_value[0]['ngo_id'];
		$prop_name = $data_value[0]['prop_name'];
		$task_type = $data_value[0]['task_type'];
		//var_dump($task_type);
		$additional_json = '';
		$was_review_radion='';
		$my_final='';
		$comments_no ='';
		$comments_approve ='';
		$comments_request ='';
		$donor_dropdown_text1='';
		$donor_dropdown_id1='';
		$donor_dropdown_text2='';
		$donor_dropdown_id2='';
		$donor_dropdown_text3='';
		$donor_dropdown_id3='';
		
		$comments_recommend_yes='';
		$comments_my_reject_yes1='';
		$comments_my_reject_yes2='';
		$prop_update='';
		//$new_focal_point_data='';
		
		if(isset($new_focal_point_data)){
			if($new_focal_point_data){
				//var_dump($new_focal_point_data);
				$user_type=$new_focal_point_data[0]['user_type'];
				if($user_type=='normal'){
					$staff_id=$new_focal_point_data[0]['staff_id'];
					$donor_dropdown_id1=$staff_id;
					$donor_dropdown_id2=$staff_id;
					$donor_dropdown_id3=$staff_id;
				}
			}
		}else{
			$donor_dropdown_id1='';
			$donor_dropdown_id2='';
			$donor_dropdown_id3='';
		}
		
		
		if(isset($data_value[0]['additional_json'])){
			$additional_json = json_decode($data_value[0]['additional_json']);
			//var_dump($additional_json);
			if(isset($additional_json[0]->was_review_radion)){
				$was_review_radion = $additional_json[0]->was_review_radion;
			}
			if(isset($additional_json[0]->my_final)){
				$my_final = $additional_json[0]->my_final;	
			}
			if(isset($additional_json[0]->comments_no)){
				$comments_no = $additional_json[0]->comments_no;
			}
			if(isset($additional_json[0]->comments_approve)){
				$comments_approve = $additional_json[0]->comments_approve;
			}
			if(isset($additional_json[0]->comments_request)){
				$comments_request = $additional_json[0]->comments_request;
			}
			if(isset($additional_json[0]->donor_dropdown_text1)){
				$donor_dropdown_text1 = $additional_json[0]->donor_dropdown_text1;
			}
			if(isset($additional_json[0]->donor_dropdown_id1)){
				$donor_dropdown_id1 = $additional_json[0]->donor_dropdown_id1;
			}
			
			if(isset($additional_json[0]->donor_dropdown_text2)){
				$donor_dropdown_text2 = $additional_json[0]->donor_dropdown_text2;
			}
			if(isset($additional_json[0]->donor_dropdown_id2)){
				$donor_dropdown_id2 = $additional_json[0]->donor_dropdown_id2;
			}
			if(isset($additional_json[0]->donor_dropdown_text3)){
				$donor_dropdown_text3 = $additional_json[0]->donor_dropdown_text3;
			}
			if(isset($additional_json[0]->donor_dropdown_id3)){
				$donor_dropdown_id3 = $additional_json[0]->donor_dropdown_id3;
			}
			if(isset($additional_json[0]->comments_recommend_yes)){
				$comments_recommend_yes = $additional_json[0]->comments_recommend_yes;
			}
			if(isset($additional_json[0]->comments_my_reject_yes1)){
				$comments_my_reject_yes1 = $additional_json[0]->comments_my_reject_yes1;
			}
			if(isset($additional_json[0]->comments_my_reject_yes2)){
				$comments_my_reject_yes2 = $additional_json[0]->comments_my_reject_yes2;
			}
			if(isset($additional_json[0]->prop_update)){
				$prop_name = $additional_json[0]->prop_update;
			}
			//var_dump($donor_dropdown_id1);
		}else{
			$was_review_radion='';
			$my_final='';
			$comments_no ='';
			$comments_approve ='';
			$comments_request ='';
			$donor_dropdown_text1='';
			$donor_dropdown_id1=$org_staff_id;
			$donor_dropdown_text2='';
			$donor_dropdown_id2='';
			$donor_dropdown_text3='';
			$donor_dropdown_id3='';
			$comments_recommend_yes='';
			$comments_my_reject_yes1='';
			$comments_my_reject_yes2='';
			$prop_update='';
			
		}
	}else{
		
		
		$prop_update =''; 
		$new_focal_point_data =''; 
		$superngo_id =0; 
		$comments = '';
		$org_task_id = 0; 
		$project_id =0;
		$org_task_list_id=0;
		$org_id=0;
		$prop_id=0;
		$org_staff_id=0;
		$status = '';
		$ngo_id =0;
		$prop_name ='';
				
		$was_review_radion='';
		$my_final='';
		$comments_no ='';
		$comments_approve ='';
		$comments_request ='';
		$donor_dropdown_text1='';
		$donor_dropdown_id1='';
		$donor_dropdown_text2='';
		$donor_dropdown_id2='';
		$donor_dropdown_text3='';
		$donor_dropdown_id3='';
		$comments_recommend_yes='';
		$comments_my_reject_yes1='';
		$comments_my_reject_yes2='';
	}
?>
<?php
$data['sub_folder_name']="";
$data['image_cat']="entity";
$this->load->view('file_upload',$data);
?>
<div class="animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div id="head_ngo_review"></div>
					<input type="hidden" class="form-control" id="org_staff_id" name="org_staff_id" value="<?php echo $org_staff_id; ?>">		
					<input type="hidden" class="form-control" id="org_task_id" name="org_task_id" value="<?php echo $org_task_id; ?>">
					<input type="hidden" class="form-control" id="org_task_list_id" name="org_task_list_id" value="<?php echo $org_task_list_id; ?>">
					<input type="hidden" class="form-control" id="org_id" name="org_id" value="<?php echo $org_id; ?>">
					<input type="hidden" class="form-control" id="prop_id" name="prop_id" value="<?php echo $prop_id; ?>">
					<input type="hidden" class="form-control" id="superngo_id" name="superngo_id" value="<?php echo $superngo_id;?>">
					<input type="hidden" class="form-control" id="ngo_id" name="ngo_id" value="<?php echo $ngo_id;?>">
					<input type="hidden" class="form-control" id="project_id" name="project_id" value="<?php echo $project_id;?>">
					<input type="hidden" class="form-control" id="status" name="status" value="<?php echo $status;?>">
					<div class="row">
						<div class="col-lg-6">
							
							<?php 
								if($task_type=='prp'){
									$sql3="SELECT additional_json FROM `org_tasks` WHERE org_id=$org_id AND prop_id=$prop_id AND org_task_list_id=3";
									$task_data_fetch = $this->db->query($sql3)->result_array();
									if($task_data_fetch){
									$data['proposal_desk_review_data']=$task_data_fetch[0]['additional_json'];
										if($data['proposal_desk_review_data']!=''){
											$this->load->view('organisation/pages/task/1/view_proposal_desk_review',$data);
										}
									}
								}
								if($prop_data){
									$this->load->view('organisation/pages/view_proposal_detail_page/view_proposal_right_1',$data);
								}
								
								$this->load->view('organisation/pages/task/ngo_information_collapse'); 
								
							?>
						</div>
						<div class="col-lg-6">
							<?php $this->load->view('organisation/pages/task/Task_details_collapse'); ?>
							<div class="box box-primary ">
								<div class="box-header with-border " data-widget="collapse">
									<i class="fa btn btn-box-tool fa_plus fa-minus"></i>
									<h3 class="box-title"><?php echo $data_value[0]['org_task_label']; ?></h3>
								</div>
								<div class="box-body" >
									<form id="product" method="post" role="form">
										<div class="form-group row ">
											<div>
												<label for="was_review" class="col-md-12 desk-review" id="was_review">Was the Proposal Desk Review done satisfactorily?<span class="required">*</span></label>
											</div>
											<div class="col-md-12">
												<label style="font-weight: 400;">
													<input type="radio" class="was_review_radion" name="was_review_radion" value="Yes" id="approved_radio" <?php if($was_review_radion == 'Yes'){echo 'checked';}?>>&nbsp;
													<span>Yes</span> 
												</label>
												<label style="font-weight: 400;">
													<input type="radio"  class="was_review_radion"  name="was_review_radion" value="No" id="rejection_radio" <?php if($was_review_radion == 'No'){echo 'checked';}?>>
													<span>No</span> 
												</label> 
												<div class=" was_review_radion_error"></div>
											</div>
										</div>
										<div class="form-group row was_review_radion_no <?php if($was_review_radion == 'No'){}else{ echo 'display_none'; }?>">
											<div class="col-md-12"> 
												<label>What needs to be changed/improved?</label><br>
												<label class="input_description">This will be shared with the desk reviewer to help them make the necessary improvements.</label>
												<textarea id="comments_no" name="comments_no" class="form-control"  rows="3" placeholder="Details/Comments on above"><?php echo $comments_no;?></textarea>
											</div>
										</div>
										
										<div class="form-group row was_review_radion_yes <?php if($was_review_radion == 'Yes'){}else{ echo 'display_none'; }?>">
											<div class="col-md-12">
												<div>
													<label for="my_final">Select the next step for processing this proposal<span class="required">*</span></label>	
													<div >
														<label style="font-weight: 400;display: block;">
															<input type="radio" name="my_final" class="my_final"  value="my_approve" <?php if($my_final == 'my_approve'){echo 'checked';}?> >
															<span><b>Approve proposal for further review </b>(and initiate field visit).</span> &nbsp;
														</label>
														<label style="font-weight: 400;display: block;">
															<input type="radio" name="my_final" class="my_final"  value="my_request" <?php if($my_final == 'my_request'){echo 'checked';}?> >
															<span><b>Request revision of Proposal</b> details from the NGO (NGO will be notified and further review of the proposal will be paused). 
															</span> &nbsp;
														</label>
														<label style="font-weight: 400;display: block;">
															<input type="radio" name="my_final" class="my_final"  value="my_recommend" <?php if($my_final == 'my_recommend'){echo 'checked';}?> >
															<span><b>Recommend the Proposal for rejection </b>(further review of the proposal will be skipped and proposal will be directly sent for Board Review).</span> 
														</label> 
													</div>
												</div>
												<div class="my_final_error"></div>    
											</div>  
										</div> 
										 
										<div class="form-group  my_approve_yes <?php if($my_final == 'my_approve'){}else{ echo 'display_none'; }?>">
											<div> 
												<div>
												   <label>Update proposal title (if needed)<span class="required">*</span> </label>
												</div>
												<div class="my_internal_error"></div>
												<input type="text" class="form-control" name="prop_update" id="prop_update" value="<?php echo $prop_name;?>">
											</div>
											<div>    
												<label >Assign a Focal Point for this Proposal<span class="required">*</span></label><br/>
												<label class ="input_description">This person will be the primary contact for this proposal going forward.</label>
											</div>
											<div class="my_ngo1_error"></div>
											
											<div>  
												<select name="my_ngo1" class="form-control donor_dropdown1 js-example-basic-single" id="donor_dropdown1" name="donor_dropdown1">
													<option value="">Select</option>
													<?php if($focal_point_data){
													foreach($focal_point_data as $val){
														$is_selected='';
														$is_role='';
														if($val['staff_id']==$donor_dropdown_id1){
															$is_selected='selected';
														}
														if($val['first_name']!=''){
													?>
														<option <?php echo $is_selected; ?> value="<?php echo $val['staff_id'] ;?>" ><?php echo  $val['first_name'].' '. $val['last_name'];?></option>
																										
													<?php } if($val['is_deleted'] == '0'){ 
														echo '<option disabled value="" > '.$val['role_name'].' </option>';
													}}}?>		
												</select>
											</div> 
											<div>    
												<label >Assign an Approver for this Proposal<span class="required">*</span></label><br/>
												<label class="input_description">This person is in charge of handling approval of various tasks in the proposal review process (PRP)</label>
											</div>
											<div class="my_ngo2_error"></div>
											<div>  
												<select name="my_ngo1" class="form-control donor_dropdown2 js-example-basic-single" id="donor_dropdown2" name="donor_dropdown2">
													<option value="">Select</option>
													<?php if($focal_point_data){
													foreach($focal_point_data as $val){
														$is_selected='';
														$is_role='';
														if($val['staff_id']==$donor_dropdown_id2){
															$is_selected='selected';
														}
														if($val['first_name']!=''){
													?>
														<option <?php echo $is_selected; ?> value="<?php echo $val['staff_id'] ;?>" ><?php echo  $val['first_name'].' '. $val['last_name'];?></option>
																										
													<?php } if($val['is_deleted'] == '0'){ 
														echo '<option disabled value="" > '.$val['role_name'].' </option>';
													}}}?>		
												</select>
											</div>
											
											<div>    
												<label >Assign a field agent to conduct the field visit for this Proposal<span class="required">*</span></label>
											</div>
											<div class="my_ngo3_error"></div>
											<div>  
												<select name="my_ngo3" class="form-control donor_dropdown3 js-example-basic-single" id="donor_dropdown3" name="donor_dropdown3">
													<option value="">Select</option>
													<?php if($focal_point_data){
													foreach($focal_point_data as $val){
														$is_selected='';
														$is_role='';
														if($val['staff_id']==$donor_dropdown_id3){
															$is_selected='selected';
														}
														if($val['first_name']!=''){
													?>
														<option <?php echo $is_selected; ?> value="<?php echo $val['staff_id'] ;?>" ><?php echo  $val['first_name'].' '. $val['last_name'];?></option>
																										
													<?php } if($val['is_deleted'] == '0'){ 
														echo '<option disabled value="" > '.$val['role_name'].' </option>';
													}}}?>	
												</select>
											</div>
											
											<div > 
												<div>
													<label name="my_revise">Enter any special questions (that are not already present in the field visit form) that you want the field agent to ask during their visit (optional).</label>
													<div class="my_revise_error"></div>
												</div>
												<textarea id="comments_approve" name="comments_approve" class="form-control "  rows="3" placeholder="Enter all questions here"><?php echo $comments_approve;?></textarea>
											</div>	
										</div> 
											 
										<div class="form-group my_request_yes <?php if($my_final == 'my_request'){}else{ echo 'display_none'; }?>">
											<div > 
												<div>
													<label name="my_revise">Enter details of what the NGO needs to revise in this proposal.<span class="required">*</span> </label><br/>
													<label class="input_description">The details entered here will be sent to the NGO when this form is submitted.</label>
													<div class="my_revise_error"></div>
												</div>
												<textarea id="comments_request" name="comments_request" class="form-control "  rows="3" placeholder="Enter details here..."><?php echo $comments_request;?></textarea>
											</div>	
										</div>
									
										<div class="form-group  my_recommend_yes  <?php if($my_final == 'my_recommend'){}else{ echo 'display_none'; }?>">
											<div> 
												<div>
												   <label>Enter reasons for this rejection recommendation.<span class="required">*</span> </label><br/>
												   <div class="input_description">These details are for internal use only</div>
												</div>
												<div class="my_internal_error"></div>
												<textarea id="comments_recommend_yes" name="comments_recommend_yes"  class="form-control"  rows="3" placeholder="Enter details here..."><?php echo $comments_recommend_yes;?></textarea>
											</div>
										</div>
										
										<div class="form-group  my_recommend_yes">
											<div> 
												<div>
												    <label>Approval sheet available for download</label><br/>
												    <a proposal_id="<?php echo $prop_id;?>" class="approval_sheet">Approval sheet</a>
													<div class="download_sheet1"></div>
												</div>
											</div>
										</div>
										
										<div class="form-group my_request_yes_no <?php if($was_review_radion == 'Yes' || $was_review_radion=='No'){}else{ echo 'display_none'; }?>">
											<div class="row">
												<div>
													<div>&nbsp;</div>
													<div class="button " style="margin-left: 17px;">
														<button type="button" <?php if($status == 'Completed'){echo 'disabled';}?>  id="save<?php if($status == 'Completed'){echo 'disabled';}?>" class="btn btn-primary">Save</button>
														<button type="submit" <?php if($status == 'Completed'){echo 'disabled';}?>  id="submit<?php if($status == 'Completed'){echo 'disabled';}?>" class="btn btn-success">Submit</button>
														<button type="button" class="btn btn-primary ngo_send_notification" style="float: right;display: none;">Notify</button>
													</div>
												</div>
											</div>
										</div>
									</form>	
								</div>			
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	.select2-container--default .select2-selection--single {
		padding: 4px !important;
	}
	.select2-container--default .select2-results__option[aria-disabled=true] {
		color: #000;
		font-weight: 600;
	}
</style>

<script>
$('document').ready(function(){
	$('body').on('click','.approval_sheet', function () {
		var proposal_id = $(this).attr('proposal_id');
		$.post(APP_URL + 'xls/generateXls',
			{proposal_id : proposal_id}, 
			function (response) {
            if (response.status == 200) {
				$('.download_sheet1').html("<a class='download_sheet' style='visibility:hidden' href='"+response.message+"'>"+response.message+"</a>");
                setTimeout( function(){					
				$(".download_sheet").trigger('click');
				}  , 1000 );
			} else {
                $('#headerMsg').html("<div class='alert alert-danger fade in'><button class='close' type='button' data-dismiss='alert'>x</button><strong>" + response.message + "</strong></div>");
				$("#headerMsg").fadeTo(1000, 500).slideUp(500, function(){
					$('#headerMsg').empty();
				});
			}
        }, 'json');		
	});
	
    $('body').on('click','.download_sheet', function () {
	    var href = $('.download_sheet').attr('href');
        window.location.href = href; 
	});
	
	
	
	$(".download_date").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		minDate: 'dateToday',
		yearRange : 'c-75:c+75',
	});
	
	
	$('.js-example-basic-single').select2();
	
	$('body').on('click','.was_review_radion', function () {
		var radio_value = $('input[name="was_review_radion"]:radio:checked').next().html();
  
	   console.log(radio_value);
	   if(radio_value == 'Yes'){
		    $('.my_request_yes_no').removeClass('display_none')
		    $('.was_review_radion_yes').removeClass('display_none')
		    $('.was_review_radion_no').addClass('display_none')
			
	   }else{
			$('.my_final').prop('checked', false);
		    $('.my_request_yes_no').removeClass('display_none')
		    $('.was_review_radion_no').removeClass('display_none')
		    $('.was_review_radion_yes').addClass('display_none')
			
			$('.my_approve_yes').addClass('display_none')
			$('.my_request_yes').addClass('display_none')
			$('.my_recommend_yes').addClass('display_none')
			
			
		   
	   }
	});
	
	$('body').on('click','.my_final', function () {
		var radio_value = $('input[name="my_final"]:radio:checked').val();
  
	   console.log(radio_value);
	   if(radio_value == 'my_approve'){
			$('.my_approve_yes').removeClass('display_none')
			$('.my_request_yes').addClass('display_none')
			$('.my_recommend_yes').addClass('display_none')
			$('.my_reject_yes').addClass('display_none')
		}
	    else if (radio_value=='my_request'){
		   	$('.my_request_yes').removeClass('display_none')
			$('.my_approve_yes').addClass('display_none')
			$('.my_recommend_yes').addClass('display_none')
			$('.my_reject_yes').addClass('display_none')
		 
	   }else{
			$('.my_recommend_yes').removeClass('display_none')
			$('.my_approve_yes').addClass('display_none')
			$('.my_request_yes').addClass('display_none')
			$('.my_reject_yes').addClass('display_none')
	   }
	});
	
});

	$('body').on('click','#save', function () {
				
		var org_task_id=$('#org_task_id').val();
		var org_task_list_id=$('#org_task_list_id').val();
		var org_id=$('#org_id').val();
		var ngo_id=$('#ngo_id').val();
		var prop_id=$('#prop_id').val();
		var superngo_id=$('#superngo_id').val();
		var org_staff_id=$('#org_staff_id').val();
		var status=$('#status').val();
		var prop_update='';
		var additional_json = [];	
		var was_review_radion=$('input[name="was_review_radion"]:radio:checked').val();
			console.log(was_review_radion);
		if(was_review_radion=='Yes'){
		
			var my_final=$('input[name="my_final"]:radio:checked').val();
				console.log(my_final);
				if(my_final){
					if(my_final=='my_approve'){
						 prop_update=$('#prop_update').val();
						var donor_dropdown_text1 = $('.donor_dropdown1 option:selected').text();
						var donor_dropdown_text2 = $('.donor_dropdown2 option:selected').text();
						var donor_dropdown_text3 = $('.donor_dropdown3 option:selected').text();
						var donor_dropdown_id1 = $('.donor_dropdown1').val();
						var donor_dropdown_id2 = $('.donor_dropdown2').val();
						var donor_dropdown_id3 = $('.donor_dropdown3').val();
						var comments_approve = $('#comments_approve').val();
						
						additional_json.push({
							'was_review_radion':was_review_radion,
							'my_final':my_final,
							'donor_dropdown_text1':donor_dropdown_text1,
							'donor_dropdown_text2':donor_dropdown_text2,
							'donor_dropdown_text3':donor_dropdown_text3,
							'donor_dropdown_id1':donor_dropdown_id1,
							'donor_dropdown_id2':donor_dropdown_id2,
							'donor_dropdown_id3':donor_dropdown_id3,
							'prop_update':prop_update,
						});
						
					}else if(my_final=='my_request'){
						var comments_request = $('#comments_request').val();
						
						
						additional_json.push({
							'was_review_radion':was_review_radion,
							'my_final':my_final,
							'comments_request':comments_request,
							
						});
					}else {
						var comments_recommend_yes = $('#comments_recommend_yes').val();
						
						additional_json.push({
							'was_review_radion':was_review_radion,
							'my_final':my_final,
							'comments_recommend_yes':comments_recommend_yes,
							
						});
					
					}
				}
				
				
		}else{
			
			var comments_no = $('#comments_no').val();
			additional_json.push({
				'was_review_radion':was_review_radion,
				'comments_no':comments_no,
			});
		}   
		
			console.log(additional_json);
			$.blockUI({ message: '<h1><img src="'+APP_URL + 'assets/img/loading.gif" /></h1>' });
	
			$.post(APP_URL + 'organisation/tasks/save_org1_normal_all_pages', {
				additional_json:additional_json,
				org_task_id:org_task_id, 
				org_task_list_id:org_task_list_id,
				org_id:org_id,
				ngo_id:ngo_id,
				prop_id:prop_id,
				superngo_id:superngo_id,
				org_staff_id:org_staff_id,
			},
			function (response) {
				$.unblockUI();
				$('#head_ngo_review').empty();
				if (response.status == 200) {
					var message = response.message; 
					$.toaster({ priority :'success', message :'Saved'});
					//$('#head_ngo_review').html("<div class='alert alert-success fade in'><button class='close' type='button' data-dismiss='alert'>x</button><strong>" + response.message + "</strong></div>");
					//$('.remove_image[name=' + contact_id + ']').closest("tr").remove();
					//$("#head_ngo_review").fadeTo(2000, 500).slideUp(500, function(){
						//$('#head_ngo_review').empty();
						//window.location.reload();
						//window.location.href=APP_URL+"organisation/tasks/mytasks";
					//});
				} else {
					$('#head_ngo_review').html("<div class='alert alert-danger fade in'><button class='close' type='button' data-dismiss='alert'>x</button><strong>" + response.message + "</strong></div>");
					$("#head_ngo_review").fadeTo(2000, 500).slideUp(500, function(){
						$('#head_ngo_review').empty();
					});
				}	
			}, 'json');
		
		
		
	});	


	
	$('#product').validate({
		
		//ignore: [],
        rules: {
			was_review_radion: {
                required: true,
            },	
			my_final : {
			   required: true,
			},
			
			donor_dropdown1: {
			   required: true,
			},
			donor_dropdown2 : {
			   required: true,
			},
			donor_dropdown3 : {
			   required: true,
			},
			prop_update : {
			   required: true,
			},
			comments_request : {
			   required: true,
			},
			
			comments_recommend_yes :{
			   required: true,
			},
			
			
		},
		messages: {
			was_review_radion: {
                required: "Was review is required.",
            },
			my_final: {
                required: "Select the next step is required.",
            },
			
			donor_dropdown1 : {
			   required: "Focal Point is required.",
			},
			donor_dropdown2 : {
			   required: "final evalution is required.",
			},
			donor_dropdown3 : {
			   required: "final evalution is required.",
			},
			comments_approve  : {
			   required: "any special questions is required.",
			},
			prop_update  : {
			   required: "Update proposl is required.",
			},
					
			comments_recommend_yes :{
			   required: "reasons for this rejection is required.",
			},
			
			
			
		},
		
		errorPlacement: function(error, element) {
            if (element.hasClass('was_review_radion')) {
				error.insertAfter(element.closest('div.form-group').find('.was_review_radion_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
            }
			else if (element.hasClass('my_final')) {
				error.insertAfter(element.closest('div.form-group').find('.my_final_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
			
			}else if (element.hasClass('donor_dropdown1')) {
				error.insertAfter(element.closest('div.form-group').find('.my_ngo1_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
			}else if (element.hasClass('my_internal')) {
				error.insertAfter(element.closest('div.form-group').find('.my_internal_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
			}else if (element.hasClass('my_submission')) {
				error.insertAfter(element.closest('div.form-group').find('.my_submission_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
			}else if (element.hasClass('my_revise')) {
				error.insertAfter(element.closest('div.form-group').find('.my_revise_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
			}else if (element.hasClass('my_ngo1')) {
				error.insertAfter(element.closest('div.form-group').find('.my_ngo1_error'));
            }else if (element.hasClass('is_valid_tax_exemption')) {
				error.insertAfter(element.closest('div.form-group').find('.valid_tax_exemption'));
       
			}
            else{	
				error.insertAfter(element);
			}

         },

	  
		
		submitHandler: function (form) {
			//$.blockUI()
		//var post_two='';	
		console.log('submit');
		var org_task_id=$('#org_task_id').val();
		var org_task_list_id=$('#org_task_list_id').val();
		var org_id=$('#org_id').val();
		var ngo_id=$('#ngo_id').val();
		var prop_id=$('#prop_id').val();
		var superngo_id=$('#superngo_id').val();
		var org_staff_id=$('#org_staff_id').val();
		var prop_update='';
		var status=$('#status').val();
			console.log(status);
		var additional_json = [];	
		var was_review_radion=$('input[name="was_review_radion"]:radio:checked').val();
		var my_final=$('input[name="my_final"]:radio:checked').val();
			console.log(was_review_radion);
			var is_erroor='no';
		if(was_review_radion=='Yes'){
		
			var my_final=$('input[name="my_final"]:radio:checked').val();
				console.log(my_final);
				if(my_final){
					if(my_final=='my_approve'){
						 prop_update=$('#prop_update').val();
						var donor_dropdown_text1 = $('.donor_dropdown1 option:selected').text();
						var donor_dropdown_text2 = $('.donor_dropdown2 option:selected').text();
						var donor_dropdown_text3 = $('.donor_dropdown3 option:selected').text();
						var donor_dropdown_id1 = $('.donor_dropdown1').val();
						var donor_dropdown_id2 = $('.donor_dropdown2').val();
						var donor_dropdown_id3 = $('.donor_dropdown3').val();
						var comments_approve = $('#comments_approve').val();
							if(donor_dropdown_id1){
								$('.my_ngo1_error').empty();
							}else{
								is_erroor='yes';
								$('.my_ngo1_error').append('<label class="error">Assign a Focal Point is required.</label>');
							}
							if(donor_dropdown_id2){
								$('.my_ngo2_error').empty();
							}else{
								is_erroor='yes';
								$('.my_ngo2_error').append('<label class="error">Assign an Approver is required.</label>');
							}
							if(donor_dropdown_id3){
								$('.my_ngo3_error').empty();
							}else{
								is_erroor='yes';
								$('.my_ngo3_error').append('<label class="error">Assign a field is required.</label>');
							}
						
						if(is_erroor=='no'){
							additional_json.push({
								'was_review_radion':was_review_radion,
								'my_final':my_final,
								'donor_dropdown_text1':donor_dropdown_text1,
								'donor_dropdown_text2':donor_dropdown_text2,
								'donor_dropdown_text3':donor_dropdown_text3,
								'donor_dropdown_id1':donor_dropdown_id1,
								'donor_dropdown_id2':donor_dropdown_id2,
								'donor_dropdown_id3':donor_dropdown_id3,
								'comments_approve':comments_approve,
								'prop_update':prop_update,
							});
						}
					}else if(my_final=='my_request'){
						var comments_request = $('#comments_request').val();
							additional_json.push({
							'was_review_radion':was_review_radion,
							'my_final':my_final,
							'comments_request':comments_request,
							
						});
					}else {
						var comments_recommend_yes = $('#comments_recommend_yes').val();
						
						additional_json.push({
							'was_review_radion':was_review_radion,
							'my_final':my_final,
							'comments_recommend_yes':comments_recommend_yes,
							
						});
					
					}
				}
				
				
		}else{
			//post_two='two';
			var comments_no = $('#comments_no').val();
			additional_json.push({
				'was_review_radion':was_review_radion,
				'comments_no':comments_no,
				'my_final':my_final,
				
			});
		}   
		var url_type= 'proposal';
		var note_title= 'visit_approval';
		var proposal_status= 'Approved';
		//var post_two='two';
			console.log(additional_json);
			if(is_erroor=='no'){
				$.blockUI({ message: '<h1><img src="'+APP_URL + 'assets/img/loading.gif" /></h1>' });
				$.post(APP_URL + 'organisation/tasks/change_status_onsubmit_by_begin_proposal', {
						additional_json:additional_json,
						org_task_id:org_task_id, 
						org_task_list_id:org_task_list_id,
						org_id:org_id,
						ngo_id:ngo_id,
						prop_id:prop_id,
						superngo_id:superngo_id,
						org_staff_id:org_staff_id,
						my_final:my_final,
						was_review_radion:was_review_radion,
					//	prop_update:prop_update,
						url_type:url_type,
						status:status,
						
				},
				function (response) {
					$.unblockUI();
					$("html, body").animate({scrollTop: 0}, "slow");
					$('#head_ngo_review').empty();
					if (response.status ==200) {
					var message = response.message;
						
						$('#head_ngo_review').html("<div class='alert alert-success fade show in'><button class='close' type='button' data-dismiss='alert'>x</button><strong>" + message + "</strong></div>");
						$("#head_ngo_review").fadeTo(2000, 500).slideUp(500, function(){
							$('#head_ngo_review').remove();
							window.location.href=APP_URL+"organisation/tasks/mytasks";
						})
					}else {
						$('#head_ngo_review').html("<div class='alert alert-danger fade show'><button class='close' type='button' data-dismiss='alert'>x</button><strong>" + response.message + "</strong></div>");
						$("#head_ngo_review").fadeTo(2000, 500).slideUp(500, function(){
							$('#head_ngo_review').empty();
						});
					}					
					
				}, 'json');
			}
			return false;
		},
	
	
	
	
	});
	</script>

	