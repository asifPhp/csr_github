<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

	/**
     * Default constructor.
     * 
     * @access	public
     * @since	1.0.0
     */
    function __construct() {
        parent::__construct();
        if (!($this->session->userdata('loggedIN') == 3)) {
            redirect(base_url().'organisation/login');
        }		
    }
	/**
	 * admin List of plans for this controller.
	*/
	public function listing(){            //this function is common for ngo and organisation for loading view 12/12/2020 
		$this->load->model('Page_validation_model_organisation', 'obj_pvm', TRUE);
		$page='list';
		
		$staff_id = $this->session->userdata('staff_id');
		$arrayName = array(
			'module_id' => '8', 
			'submodule_id' => '11', 
			'staff_id' => $staff_id, 
			'page' => 'list', 
		);
		$data['access_data'] =$this->obj_pvm->get_user_access_data_for_menu($staff_id);
		$return_val =$this->obj_pvm->is_permitted($arrayName);
		$data['is_permitted']=$return_val;
		$organisation_id = $this->session->userdata('org_id');
		$where = '';
		$where1 = '';
	    $data['status'] = '';
	    $status='';
	    $focal_point_status='';
	    $data['sstatus'] = '';
	    $data['focal_point_status'] = '';
	    if(isset($_GET['status'])){
			$status = $_GET['status'];
			$data['status'] = $_GET['status'];
			if($status=='New'){
				if(isset($_GET['sstatus']) && isset($_GET['sstatus'])!=''){
					$data['sstatus'] = $_GET['sstatus'];
					$sstatus = $_GET['sstatus'];
					$sstatus_arr = explode(",",$sstatus);
					$where .= '  AND  (';
					$array_length = count($sstatus_arr);
					$counter = 0;
					foreach($sstatus_arr as $item) {
						$counter++;
						$where .= ' project_status="'.trim($item).'" ';
						if($array_length != $counter){
							$where .= ' or ';
						}
					}
					$where .= ') ';

				}else{
					$where .= ' AND project_status != "Completed"';
				}
				
				if(isset($_GET['focal_point_status']) && isset($_GET['focal_point_status'])!=''){
					$data['focal_point_status'] = $_GET['focal_point_status'];
					$focal_point_status = $_GET['focal_point_status'];
					
					$focal_arr = explode(",",$focal_point_status);
					$where1 .= '  AND  (';
					$array_length = count($focal_arr);
					$counter = 0;
					foreach($focal_arr as $item) {
						$counter++;
						$where1 .= ' org_assigner_mgmt.staff_id='.$item.' AND user_type="project normal"';
						if($array_length != $counter){
							$where1 .= ' or ';
						}
					}
					$where1 .= ') ';
					
					
					//var_dump($where1);
				}else{
					$where1 .= '';
				}

			}
			if($status=='Completed'){
				$data['status'] = 'Completed';
				$status = 'Completed';
				$where .= ' AND project_status = "Completed" ';
			}
		}else{
			$data['status'] = 'New';
			$where .= '  AND project_status != "Completed" ';
			$where1 .= '';
		}
		//var_dump($where);
		//var_dump($where1);
		if($where1!=''){
			$sql = "SELECT project.*,org_assigner_mgmt.* 
				FROM `project` 
				JOIN org_assigner_mgmt ON project.id=org_assigner_mgmt.project_id
				WHERE  `organisation_id` =$organisation_id ".$where." ".$where1." ORDER BY `id` DESC";
		}else{
			$sql = "SELECT * FROM `project` 
					WHERE  `organisation_id` =$organisation_id ".$where."  ORDER BY `id` DESC";
		}
		//var_dump($sql);
		$result = $this->db->query($sql);
	   	$data_temp = array();
        $data_value = array();
		$data_couter = 0;
		
		if ($result && $result->num_rows() > 0) {
			foreach ($result->result() as $row) {
				$data_couter++ ;
				$assigned_to = '';
				if($row->ngo_id){
					$sql1="SELECT legal_name FROM `ngo` WHERE `id` =  ".$row->ngo_id."";
					$db_result1 = $this->db->query($sql1)->result_array();
					if($db_result1){
						$assigned_to = $db_result1[0]['legal_name'];
					}
				}
				if($row->generated_by){
					$sql23 = "SELECT first_name,last_name FROM org_staff_account WHERE `staff_id`=$row->generated_by " ;
					$result23 = $this->db->query($sql23)->result_array();
					if($result23){
						$first_name = $result23[0]['first_name'];
						$last_name = $result23[0]['last_name'];
						$focal_point=$first_name .' '.$last_name;
						//var_dump($focal_point);
					}
				}
				if (!array_key_exists($data_couter, $data_temp)) {
					$data_temp[$data_couter] = array();
				}
				if (array_key_exists($data_couter, $data_temp)) {
					$data_temp[$data_couter] = array(
						'id' => $row->id ,
						'prop_id' => $row->prop_id ,
						'title' => $row->title,
						'superngo_id' => $row->superngo_id,
						'ngo_id' => $row->ngo_id,
						'legal_name'=>$assigned_to,
						'organisation_id' => $row->organisation_id,
						'project_status'=> $row->project_status,
						'project_start_date'=> $row->project_start_date,
						'project_end_date'=> $row->project_end_date,
						'updated_datetime'=> $row->updated_datetime,
						'focal_point'=> $focal_point,
					);

				array_push($data_value, $data_temp[$data_couter]);
				}
			}
		}
		
		$data['project_list'] = $data_value;
		$data['ngo_or_organisation'] = 'organisation';
		
		$this->load->view('organisation/components/header');
		$this->load->view('organisation/components/menu',$data);
		
		if($return_val){
			$this->load->view('organisation/pages/project_listing',$data);
		}else{
			$this->load->view('error_msg');
		}
		$this->load->view('organisation/components/footer');
	}
	
	public function detail(){         //this function is common for ngo and organisation for loading view 12/12/2020 
		//var_dump($this->session->userdata);
		$this->load->model('Page_validation_model_organisation', 'obj_pvm', TRUE);
		$page='list';
		$staff_id = $this->session->userdata('staff_id');
		//var_dump($staff_id = $this->session->userdata());
		
		$arrayName = array(
			'module_id' => '8', 
			'submodule_id' => '11', 
			'staff_id' => $staff_id, 
			'page' => 'other1', 
		);
		$data['access_data'] =$this->obj_pvm->get_user_access_data_for_menu($staff_id);
		$return_val =$this->obj_pvm->is_permitted($arrayName);
		$data['is_permitted']=$return_val;


		$organisation_id = $this->session->userdata('org_id');
		if(isset($_GET['id'])){
			$project_id = $_GET['id'];
			
		}else{
			$id = 0;
		}
		$arrayName1 = array();
		$project_cycle_data1 = array();
		$sql="SELECT * FROM `project` WHERE `id` =".$project_id." AND  `organisation_id` =".$organisation_id ;
		$data['sql_query'] = $sql;
		$result = $this->db->query($sql); 
		$data['assigned_to'] ='';	
		if ($result && $result->num_rows() > 0){
			$data['project_data'] = $result->result();
			$ngo_id = $data['project_data'][0]->ngo_id;
			$superngo_id = $data['project_data'][0]->superngo_id;
			$organisation_id = $data['project_data'][0]->organisation_id;
			if($ngo_id){
				$sql1="SELECT legal_name,id,code,registration_detail FROM `ngo` WHERE `id` =".$ngo_id ;
				$result1 = $this->db->query($sql1); 
				if ($result1 && $result1->num_rows() > 0){
					$data['project_ngo_data'] = $result1->result();
				}else{
					$data['project_ngo_data'] ='';
				}
			}
			
			if($superngo_id){
				$sql1="SELECT * FROM `superngo` WHERE `id` =  ".$superngo_id."";
				$data['project_superngo_data'] = $this->db->query($sql1)->result_array();
				
			}
			
			
			$sql4="SELECT * FROM `org_ngo_review_status` WHERE `ngo_id` =".$ngo_id;
			$result4 = $this->db->query($sql4); 
			if ($result4 && $result4->num_rows() > 0){
				$data['org_ngo_review_status'] = $result4->result();
			}else{
				$data['org_ngo_review_status'] = '';
			}
			
			$prop_id = $data['project_data'][0]->prop_id;
			if($prop_id){
				$this->load->model('Comman_model', 'obj_comman', TRUE);
				//$sql2="SELECT * FROM `proposal` WHERE `prop_id` =".$prop_id;
				$sql2="SELECT *, 
				(SELECT name FROM admin_category WHERE id=proposal.focus_category) AS 'admin_category' ,
				(SELECT name FROM admin_subcategory WHERE id=proposal.focus_subcategory) AS 'admin_subcategory',
				(SELECT registration_detail FROM ngo WHERE id=proposal.ngo_id) AS 'registration_detail',
				(SELECT category_array FROM ngo WHERE id=proposal.ngo_id) AS 'category_array' FROM proposal
				WHERE prop_id=$prop_id" ;
				//var_dump($sql2);
				$result2 = $this->db->query($sql2); 
				if ($result2 && $result2->num_rows() > 0){
					 $data['prop_data'] =$result2->result_array();
									
				}else{
					$data['prop_data'] = '';
				}
				$org_district = $this->obj_comman->get_query("select state_id, (select name from admin_states where id=ngo_district.state_id) as 'state_name' from ngo_district where prop_id=$prop_id group by state_id");
				foreach ($org_district as $key => $value) {
					$state_id=$value['state_id'];
					$sql="select district_id,(select name from admin_district where id=ngo_district.district_id) as 'district_name' from ngo_district where prop_id=$prop_id AND state_id=$state_id";

					$district = $this->obj_comman->get_query($sql);
					$org_district[$key]['district']=$district;
				}
				$data['org_geo_location_data']=$org_district;
				
			}
			
			//$data['total_budget_amount']='';
			$data['total_allocate_amount']='';
			$data_value = array();
			$data_temp = array();
			$sql3="SELECT * FROM `project_donors` WHERE `project_id` =".$project_id;
			$total_budget_amount=0;	
			$result3 = $this->db->query($sql3); 
			if ($result3 && $result3->num_rows() > 0){
				foreach ($result3->result() as $val) {
					//var_dump($val);
										
					//$total_budget_amount=((int)$val->donor_amount + (int)$total_budget_amount);
					//$data['total_budget_amount']=$total_budget_amount;
					//var_dump($total_budget_amount);
					
					$cycle_donor_amount_dis = 0;
					$allocate_cycle_donor_amount=0;
					$is_completed_cycle='';
					$cycle_donor_id=0;
					$main_donor_code='';
					$main_donor_id=$val->select_donor;
					if($main_donor_id){
							$sql_donor="SELECT legal_name,code FROM `donor` WHERE `donor_id` =".$main_donor_id;
							$db_donor_data = $this->db->query($sql_donor)->result_array();
							if($db_donor_data){
								$main_donor_code=$db_donor_data[0]['code'];
							}
					}
					$sql_cycle="SELECT * FROM `project_cycle_details` WHERE `project_id` =$project_id AND is_deleted=0";
					$db_cycle_data = $this->db->query($sql_cycle)->result_array();
					
					if($db_cycle_data){
						foreach($db_cycle_data as $cycle_val){
							
							$project_cycle_id=$cycle_val['project_cycle_id'];
							$is_payment=$cycle_val['is_payment'];
							$is_completed_cycle=$cycle_val['is_completed'];
							if($project_cycle_id and $is_payment=='yes'){
								$sql_cycle_donor="SELECT * FROM `project_cycle_donor_details` WHERE `project_id` =$project_id AND project_cycle_id=$project_cycle_id AND is_deleted=0 " ;
								$db_cycle_donor_data = $this->db->query($sql_cycle_donor)->result_array();
								if($db_cycle_donor_data){
									foreach($db_cycle_donor_data as $cycle_donor_val){
										$cycle_donor_id=$cycle_donor_val['id'];
										$cycle_donor_amt=$cycle_donor_val['cycle_donor_amount'];
										//$allocate_cycle_donor_amount=((int)$cycle_donor_amt + (int)$allocate_cycle_donor_amount);
										//var_dump($allocate_cycle_donor_amount);
										if($cycle_donor_val['donor_dropdown_id']==$val->select_donor){
											$allocate_cycle_donor_amount=((int)$cycle_donor_amt + (int)$allocate_cycle_donor_amount);
										}
										//var_dump($allocate_cycle_donor_amount);
										if($is_completed_cycle=='yes'){
											if($cycle_donor_val['donor_dropdown_id']==$val->select_donor){
												$cycle_donor_amount_dis=((int)$cycle_donor_amt + (int)$cycle_donor_amount_dis);
											}
										}
									}
								}
							}
						}
					}
					if (!array_key_exists($val->project_donor_id, $data_temp)) {
						$data_temp[$val->project_donor_id] = array();
					}
					if (array_key_exists($val->project_donor_id, $data_temp)) {
						$data_temp[$val->project_donor_id] = array(
							'vendor_code' => $val->vendor_code ,
							'donor_amount' => $val->donor_amount ,
							'donor_id' => $val->select_donor,
							'ngo_id' => $val->ngo_id,
							'project_donor_id'=> $val->project_donor_id,
							'project_id'=> $project_id,
							'main_donor_code'=> $main_donor_code,
							
							
							'cycle_donor_amount' => $allocate_cycle_donor_amount,
							'cycle_donor_amount_dis' => $cycle_donor_amount_dis,
							'cycle_donor_id' => $cycle_donor_id,
							'pending'=> $allocate_cycle_donor_amount-$cycle_donor_amount_dis,
							
							
						);
						array_push($data_value, $data_temp[$val->project_donor_id]);
					}
					
				}
			}
			//var_dump($data_value);
			$data['donor_data'] = $data_value;
			
			$data['table_type'] = 'dataTables';
			$data['project_detail_content_hide_left'] ='';
			$data['project_detail_content_hide_right'] ='';
			$data['edit_button_hide'] = 'no';
		}
		
		$project_list = $this->db->query($sql)->num_rows();
		if($project_list == 0){
			$return_val = false;
		}
		//var_dump($organisation_id);
		$sql3="SELECT * FROM `org_tasks` WHERE `org_id` = $organisation_id AND `status` != 'Completed' AND `project_id` = $project_id ORDER BY `org_tasks`.`org_task_id` DESC LIMIT 1" ;
		//var_dump($sql3);
		$result3 = $this->db->query($sql3); 
		//var_dump($result3);
		if ($result3 && $result3->num_rows() > 0){
			$data['org_tasks_data'] = $result3->result();
			$org_staff_id = $data['org_tasks_data'][0]->org_staff_id;
			
			$sql5="SELECT * FROM `org_staff_account` WHERE `staff_id` = ".$org_staff_id;
			$db_result1 = $this->db->query($sql5)->result_array();
			if($db_result1){
				$data['assigned_to'] = $db_result1[0]['first_name'] .' '.$db_result1[0]['last_name'];
			}else{
				$data['assigned_to'] ='';
			}
		}else{
			$data['org_tasks_data'] ='';
		}
		
		$sql41="SELECT * FROM `project_document` WHERE `project_id` =".$project_id." AND `document_type` LIKE 'csr_document_list'";
		$result51 = $this->db->query($sql41); 
		if ($result51 && $result51->num_rows() > 0){
			$data['csr_document_data'] = $result51->result();
			
		}else{
			$data['csr_document_data'] = '';
		}	
		//var_dump($id);
		$sql51="SELECT * FROM `project_document` WHERE `project_id` =".$project_id." AND `document_type` LIKE 'ngo_document_list'";
		$result61 = $this->db->query($sql51); 
		if ($result61 && $result61->num_rows() > 0){
			$data['ngo_document_data'] = $result61->result();
			
		}else{
			$data['ngo_document_data'] = '';
		}	
		
		$sql="SELECT * FROM `project_cycle_details` WHERE `project_id` =$project_id  AND is_deleted=0";
		$data['sql_query1'] = $sql;
		
		
		
		
		
		$data['project_id'] = $project_id;
		
		$sql_org_task2="SELECT org_tasks.org_task_id,org_tasks.status,org_tasks.org_task_label,org_tasks.task_type,org_tasks.prop_id,org_tasks.due_date,org_staff_account.first_name,org_staff_account.last_name,org_staff_account.staff_id 
						FROM org_tasks 
						INNER JOIN org_staff_account ON org_tasks.org_staff_id=org_staff_account.staff_id
						WHERE 
						org_tasks.project_id=$project_id AND org_tasks.org_id=$organisation_id AND status!='Completed' AND task_type='pmp' LIMIT 1";
		//var_dump($sql_org_task2);
		$data['current_task_data'] = $this->db->query($sql_org_task2)->result_array();
		
		
		$sql_cycle_donor="SELECT project_donors.*,donor.legal_name,donor.code FROM `project_donors`
			INNER JOIN donor ON project_donors.select_donor=donor.donor_id
			WHERE `project_id` =".$project_id ;
		$data['project_cycle_donor_data'] = $this->db->query($sql_cycle_donor)->result_array();
		
		
		$this->load->view('organisation/components/header');
		$this->load->view('organisation/components/menu',$data);
		
		if($return_val){
			$this->load->view('organisation/pages/project_detail',$data);
		}else{
			$this->load->view('error_msg');
		}
		$this->load->view('organisation/components/footer');
	}
	
	public function project_cycle_detail(){ 
		$this->load->model('Page_validation_model_organisation', 'obj_pvm', TRUE);
		$page='list';
		
		$staff_id = $this->session->userdata('staff_id');
		$arrayName = array(
			'module_id' => '8', 
			'submodule_id' => '11', 
			'staff_id' => $staff_id, 
			'page' => 'other1', 
		);
		$data['access_data'] =$this->obj_pvm->get_user_access_data_for_menu($staff_id);
		$return_val =$this->obj_pvm->is_permitted($arrayName);
		$data['is_permitted']=$return_val;
		$data['assigned_to'] ='';
		if(isset($_GET['id'])){
			$project_cycle_id = $_GET['id'];
			
			$sqll="SELECT * FROM `project_cycle_details` WHERE `project_cycle_id` =".$project_cycle_id."";
			$result = $this->db->query($sqll); 
			$project_cycle_data = array();
			
			if ($result && $result->num_rows() > 0){
				
				foreach ($result->result() as $val) {
					
					$sql="SELECT * FROM `project` WHERE `id` =".$val->project_id;
					$result1 = $this->db->query($sql); 
					if ($result1 && $result1->num_rows() > 0){
						$data['project_data'] = $result1->result();
						$ngo_id = $data['project_data'][0]->ngo_id;
						$organisation_id = $data['project_data'][0]->organisation_id;
						
						$sql2="SELECT * FROM `ngo` WHERE `id` =".$ngo_id;
						$result2 = $this->db->query($sql2); 
						if ($result2 && $result2->num_rows() > 0){
							$data['ngo_data'] = $result2->result();
						}else{
							$data['ngo_data'] = '';
						}
						
						$sql3="SELECT * FROM `organisation` WHERE `org_id` =".$organisation_id;
						$result3 = $this->db->query($sql3); 
						if ($result3 && $result3->num_rows() > 0){
							$data['org_data'] = $result3->result();
						}else{
							$data['org_data'] = '';
						}
						
						$sql4="SELECT * FROM `org_tasks` WHERE `org_id` = $organisation_id AND `status` != 'Complete' AND `project_id` = ".$val->project_id." ORDER BY `org_tasks`.`org_task_id` DESC LIMIT 1" ;
						$result4 = $this->db->query($sql4); 
						
						if ($result4 && $result4->num_rows() > 0){
							$data['org_tasks_data'] = $result4->result();
							$org_staff_id = $data['org_tasks_data'][0]->org_staff_id;
							
							$sql5="SELECT * FROM `org_staff_account` WHERE `staff_id` = ".$org_staff_id;
							$db_result1 = $this->db->query($sql5)->result_array();
							if($db_result1){
								$data['assigned_to'] = $db_result1[0]['first_name'] .' '.$db_result1[0]['last_name'];
							}else{
								$data['assigned_to'] ='';
							}
						}else{
							$data['org_tasks_data'] ='';
						}
					}
					
					$arrayName = array(
						'project_cycle_id' => $val->project_cycle_id,
						'project_id' => $val->project_id,
						'org_id' => $val->org_id,
						'superngo_id' => $val->superngo_id,
						'org_staff_id' => $val->org_staff_id,
						'cycle_name' => $val->cycle_name,
						'no_of_cycle' => $val->no_of_cycle,
						'cycle_start_date1' => $val->cycle_start_date1,
						'cycle_end_date2' => $val->cycle_end_date2,
						'is_payment' => $val->is_payment,
						'donor_dropdown' => $val->donor_dropdown,
						'donor_dropdown_id' => $val->donor_dropdown_id,
						'cycle_donor_amount' => $val->cycle_donor_amount,
						'ngo_payment' => $val->ngo_payment,
						'ngo_doc' => $val->ngo_doc,
						'csr_doc' => $val->csr_doc,
						'created_time' => $val->created_time,
						'cycle_status' => $val->cycle_status,
						'is_deleted' => $val->is_deleted,
						'is_completed' => $val->is_completed,
						'payment_date_time' => $val->payment_date_time,
						'is_payment_made' => $val->is_payment_made,
						'payment_proof' => $val->payment_proof,
					);
					array_push($project_cycle_data,$arrayName);
					
					$sql6="SELECT * FROM `project_document` WHERE `project_id` =".$val->project_id." AND project_cycle_id=$project_cycle_id AND `document_type` LIKE 'csr_document_list'";
					$result5 = $this->db->query($sql6); 
					if ($result5 && $result5->num_rows() > 0){
						$data['csr_doc_data'] = $result5->result();
						//var_dump($data['csr_doc_data']);
					}else{
						$data['csr_doc_data'] = '';
					}	
					
					$sql7="SELECT * FROM `project_document` WHERE `project_id` =".$val->project_id." AND project_cycle_id=$project_cycle_id AND `document_type` LIKE 'ngo_document_list'";
					$result6 = $this->db->query($sql7); 
					if ($result6 && $result6->num_rows() > 0){
						$data['ngo_doc_data'] = $result6->result();
					}else{
						$data['ngo_doc_data'] = '';
					}		
					
					$sql8="SELECT * FROM `project_document` WHERE `project_id` =".$val->project_id." AND project_cycle_id=$project_cycle_id AND `document_type` LIKE 'payment_processing_doc'";
					$result7 = $this->db->query($sql8); 
					if ($result7 && $result7->num_rows() > 0){
						$data['ngo_payment_doc'] = $result7->result();
					}else{
						$data['ngo_payment_doc'] = '';
					}
					
					$sql9="SELECT * FROM `project_cycle_donor_details` WHERE `project_id` =".$val->project_id." AND project_cycle_id=$project_cycle_id ";
					$result9 = $this->db->query($sql9); 
					if ($result9 && $result9->num_rows() > 0){
						$data['cycle_document_donor_data'] = $result9->result();
						//var_dump($data['cycle_document_donor_data']);
					}else{
						$data['cycle_document_donor_data'] = '';
					}		

						
				}
				$data['project_cycle_data'] = $project_cycle_data; 
			}
		}
		$this->load->view('organisation/components/header');
		$this->load->view('organisation/components/menu',$data);
		
		if($return_val){
			$this->load->view('organisation/pages/project_cycle_detail',$data);
		}else{
			$this->load->view('error_msg');
		}
		$this->load->view('organisation/components/footer');
	}
	
	public function edit_project_cycle_creation(){
		$this->load->model('Page_validation_model_organisation', 'obj_pvm', TRUE);
		$page='list';
		$this->load->model('Comman_model', 'obj_comman', TRUE);
		
		$staff_id = $this->session->userdata('staff_id');
			$arrayName = array(
			'module_id' => '6', 
			'submodule_id' => '6', 
			'staff_id' => $staff_id, 
			'page' => 'list', 
		);
		$data['access_data'] =$this->obj_pvm->get_user_access_data_for_menu($staff_id);
		$return_val =$this->obj_pvm->is_permitted($arrayName);
		$data['is_permitted']=$return_val;
		 if($return_val['is_edit']){
	    	$data['edit_url'] = base_url().'organisation/task/manage';
		}
	    if($return_val['is_add']){
	    	$data['add_url'] = base_url().'organisation/task/manage';
		}if($return_val['is_remove']){
	    	$data['is_remove'] = true;
		}
		
		$data['acces_module_list'] = $this->obj_pvm->get_module_sub_module_list_data();



		$org_id = $this->session->userdata('org_id');
		$data['heading'] = 'Task Detail:hgh ';
		
		$sql1="SELECT * FROM `org_staff_account` WHERE `staff_status` = 'Active' AND `org_id` = ".$org_id."";
		$data['org_users'] = $this->db->query($sql1)->result_array();
		
		if(isset($_GET['id'])){
			$org_task_id = $_GET['id'];
		}else{
			$org_task_id =0;
		}
		
		$sql="SELECT * FROM `org_tasks` WHERE `org_id` = ".$org_id." AND `org_task_id` = ".$org_task_id." AND `org_staff_id` = " .$staff_id."";
		$db_result = $this->db->query($sql);
		
		if($db_result->result_array()){
			$data_temp = array();
			$data_value = array();
			if ($db_result && $db_result->num_rows() > 0) {
				foreach ($db_result->result() as $row) {
					if (!array_key_exists($row->org_task_id, $data_temp)) {
						$data_temp[$row->org_task_id] = array();
					}
					$prop_name = '';
					if($row->prop_id){
						$prop_id=$row->prop_id;
						$sql1="SELECT * FROM `proposal` WHERE `prop_id` =  ".$prop_id."";
						$db_result1 = $this->db->query($sql1)->result_array();
						if($db_result1){
							$prop_name = ($db_result1[0]['title']);
							$ngo_id=($db_result1[0]['ngo_id']);
						}
						$sql="select *,(select org_name from organisation where org_id=proposal.organisation_id) as 'organisation',(select legal_name from ngo where id=proposal.ngo_id) as 'ngo' from proposal where prop_id=$prop_id AND is_deleted=0";
							$data['proposal_data'] = $this->obj_comman->get_query($sql);
							$data['is_ngo'] = 'yes';
							$data['admin_category_data'] = '';
							if($data['proposal_data'][0]['focus_category']){
								$focus_category_id=$data['proposal_data'][0]['focus_category'];
								$data['admin_category_data'] = $this->obj_comman->get_by('admin_category',array('id'=>$focus_category_id));
							}
							$data['admin_subcategory_data'] = '';
							if($data['proposal_data'][0]['focus_subcategory']){
								$focus_subcategory_id=$data['proposal_data'][0]['focus_subcategory'];
								$data['admin_subcategory_data'] = $this->obj_comman->get_by('admin_subcategory',array('id'=>$focus_subcategory_id));
							}
					}	
					//var_dump($row->prop_id);die;
					$legal_name='';					
					if($ngo_id){
						$sql2="SELECT legal_name,id FROM `ngo` WHERE `id` =  ".$ngo_id."";
						$db_result2 = $this->db->query($sql2)->result_array();
						if($db_result2){
							$legal_name = ($db_result2[0]['legal_name']);
							$ngo_id = ($db_result2[0]['id']);
						}
						
						$data['entity_data'] = $this->obj_comman->get_by('ngo',array('id'=>$ngo_id),false,true);	
						$data['edit_hide']='yes';
						$data['page_heading_disp']='no';
						$data['hide_two_row']='yes';
					}
					

					$ngo_name = '';
					if($row->superngo_id){
						$sql1="SELECT * FROM `superngo` WHERE `id` =  ".$row->superngo_id."";
						$db_result1 = $this->db->query($sql1)->result_array();
						if($db_result1){
							$ngo_name = ($db_result1[0]['brand_name']);
						}
					}
					$title='';
					if($row->project_id){
						$sql1="SELECT * FROM `project` WHERE `id` =  ".$row->project_id."";
						$db_result2 = $this->db->query($sql1)->result_array();
						if($db_result2){
							$data['project_data'] = $db_result2;
							$title = ($db_result2[0]['title']);
						}else{
							$data['project_data'] = '';
						}
						
						$this->db->limit(1);
						$db_result = $this->db->get_where('project_cycle_details',array('project_id' => $row->project_id,'is_completed'=>'no'));
						$data['project_cycle_details'] = $db_result->result();
					}
					
					if (array_key_exists($row->org_task_id, $data_temp)) {
						$data_temp[$row->org_task_id] = array(
							'org_task_id' => $row->org_task_id,
							'org_task_list_id' => $row->org_task_list_id,
							'org_task_label' => $row->org_task_label,
							'org_id'=> $row->org_id,
							'project_id'=> $row->project_id,
							'org_staff_id'=> $row->org_staff_id,
							'assigned_to'=> '',
							'superngo_id'=> $row->superngo_id,
							'ngo_name'=> $legal_name,
							'ngo_id'=> $ngo_id,
							'prop_id'=> $row->prop_id,
							'prop_name'=> $prop_name,
							'created_date'=> $row->created_date,
							'created_time'=> $row->created_time,
							'status'=> $row->status,
							'due_date'=> $row->due_date,
							'comments'=> $row->comments,
							'view_file_name'=> $row->view_file_name,
							'additional_json'=> $row->additional_json,
							'project_id'=> $row->project_id,
							'task_type'=>$row->task_type,
							'legal_name'=>$legal_name,
							'title'=>$title,

						);
						array_push($data_value, $data_temp[$row->org_task_id]);
					}
				}
			}
			$data['data_value'] = $data_value;
			$data['table_type'] = 'dataTables';
			$data['heading'] = '';
			
			$this->load->view('organisation/components/header');
			$this->load->view('organisation/components/menu',$data);
		
			$this->load->view('organisation/pages/edit_project_cycle_creation',$data);
		}else{
			
			$this->load->view('organisation/components/header');
			$this->load->view('organisation/components/menu',$data);
			$this->load->view('error_msg');
		}
		$this->load->view('organisation/components/footer');
	}
	
	public function update_cycle_by_project_detail_report_cycle_table() {
		//var_dump($_POST);die;
		//$is_payment=$_POST['is_payment'];
		$project_id=$_POST['project_id'];
		$project_cycle_id=$_POST['project_cycle_id'];
		$cycle_end_date2=$_POST['cycle_end_date2'];
		//if($is_payment=='yes'){
			if(isset($_POST['payment_list'])){
				$payment_list=$_POST['payment_list'];
				if($payment_list){
					foreach($payment_list as $val){
						$is_payment=$val['is_payment'];
						
						if(isset($val['project_cycle_donor_id'])){
							$project_cycle_donor_id=$val['project_cycle_donor_id'];
							if($is_payment=='yes'){
								$data = array(
									'cycle_donor_amount' => $val['donor_amount'],
									'cycle_name' => $_POST['cycle_name'],
									'cycle_end_date2' => $_POST['cycle_end_date2'],
									'is_payment' => $is_payment,
									'ngo_payment' => $_POST['ngo_payment'],
								);            
									$return_val = $this->db->update('project_cycle_donor_details',$data,array('project_id' => $project_id,'project_cycle_id' => $project_cycle_id,'id' => $val['project_cycle_donor_id']));
							}else{
								$data = array(
									'cycle_donor_amount' => $val['donor_amount'],
									'is_payment' => 'no',
								);            
									$return_val = $this->db->update('project_cycle_donor_details',$data,array('project_id' => $project_id,'project_cycle_id' => $project_cycle_id,'id' => $val['project_cycle_donor_id']));
							}
						}else{
							$sql_cycle="SELECT * FROM `project_cycle_details` WHERE `project_id` =".$project_id." AND  project_cycle_id=$project_cycle_id";
							$result_cycle = $this->db->query($sql_cycle)->result_array();
							if($result_cycle){
													
								$data_cycle_donor = array(
									'project_id' => $project_id,
									'project_cycle_id' => $project_cycle_id,
									'org_id' => $result_cycle[0]['org_id'],
									'superngo_id' => $result_cycle[0]['superngo_id'],
									'org_staff_id' => $result_cycle[0]['org_staff_id'],
									'no_of_cycle' => $result_cycle[0]['no_of_cycle'],
									'cycle_name' => $_POST['cycle_name'],
									'cycle_start_date1' => $result_cycle[0]['cycle_start_date1'],
									'cycle_end_date2' =>$cycle_end_date2,
									'created_time' => $result_cycle[0]['created_time'],
									'cycle_status' => $result_cycle[0]['cycle_status'],
									'is_payment' => $is_payment,
									'donor_dropdown_id' => $val['donor_dropdown_id'],
									'project_donor_id' => $val['project_donor_id'],
									'donor_dropdown' => $val['donor_code1'],
									'cycle_donor_amount' => $val['donor_amount'],
									'ngo_payment' => $val['ngo_payment'],
									
									
								);            
								$return_val = $this->db->insert('project_cycle_donor_details',$data_cycle_donor);
							}
						}
					}
				}
				
			}
		//}
		/*else{
			$data = array(
				'is_deleted' => 1,
			);
			$return_val = $this->db->update('project_cycle_donor_details',array('project_id' => $project_id,'project_cycle_id' => $project_cycle_id));
		}*/
		if($project_cycle_id){
			$data = array(
				'cycle_name' => $_POST['cycle_name'],
				'cycle_end_date2' => $_POST['cycle_end_date2'],
				'is_payment' => $is_payment,
				'ngo_payment' => $_POST['ngo_payment'],
				'ngo_doc' => $_POST['ngo_doc'],
				'csr_doc' => $_POST['csr_doc'],
			);            
			$return_val = $this->db->update('project_cycle_details',$data,array('project_cycle_id' => $_POST['project_cycle_id']));
		}
		
		if ($return_val) {
			$arr_response['status'] = 200;
			$arr_response['message'] = 'cycle updated successfully';
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	
	public function remove_project_cycle() {
		//var_dump($_POST);die;
		if(isset($_POST['remove_cycle_donor'])){
			$remove_cycle_donor=$_POST['remove_cycle_donor'];
			if($remove_cycle_donor){
				foreach($remove_cycle_donor as $del){
					/*$del_data = array(
						'is_deleted' => 1,
					); */
					$return_val = $this->db->delete('project_cycle_donor_details',array('project_cycle_id' => $_POST['project_cycle_id'],'project_id' => $_POST['project_id'] ,'id' =>$del['project_cycle_donor_id']));
				}
			}
		}
		if($_POST['project_cycle_id']){
			/*$data = array(
				'is_deleted' => 1,
			); */           
			$return_val = $this->db->delete('project_cycle_details',array('project_cycle_id' => $_POST['project_cycle_id'],'project_id' => $_POST['project_id'] ));
		}
		if ($return_val) {
			$arr_response['status'] = 200;
			$arr_response['message'] = 'Cycle Deleted successfully';
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	
	public function add_cycle() {
    // var_dump($_POST);die;
	 $staff_id = $this->session->userdata('staff_id');
	 $org_id = $this->session->userdata('org_id');
	 $org_id = $this->session->userdata('org_id');
	 $project_id=$_POST['project_id'];
		if($_POST['project_id']){
			$query = $this->db->query("SELECT project_cycle_id FROM project_cycle_details WHERE project_id=$project_id");
			$num_row= $query->num_rows();
			$no_of_cycle=$num_row+1;
			$data = array(
				'project_id' => $project_id,
				'org_staff_id' => $staff_id,
				'superngo_id' => $_POST['superngo_id'],
				'org_id' => $org_id,
				'cycle_name' => $_POST['cycle_name'],
				'cycle_end_date2' => $_POST['cycle_end_date2'],
				'is_payment' => $_POST['is_payment'],
				'ngo_payment' => $_POST['ngo_payment'],
				'ngo_doc' => $_POST['ngo_doc'],
				'csr_doc' => $_POST['csr_doc'],
				'cycle_start_date1' => date('Y-m-d'),
				'cycle_status' => 'New',
				'no_of_cycle' => $no_of_cycle,
			);  
			
			$return_val = $this->db->insert('project_cycle_details',$data);
			$project_cycle_id = $this->db->insert_id();
			if($project_cycle_id){
				if(isset($_POST['payment_list'])){
					$payment_list=$_POST['payment_list'];
					if($payment_list){
						foreach($payment_list as $val){
							$data_cycle_donor = array(
								'project_id' => $project_id,
								'superngo_id' => $_POST['superngo_id'],
								'project_cycle_id' => $project_cycle_id,
								'org_staff_id' => $staff_id,
								'org_id' => $org_id,
								'cycle_name' => $_POST['cycle_name'],
								'cycle_end_date2' => $_POST['cycle_end_date2'],
								'is_payment' => $_POST['is_payment'],
								'ngo_payment' => $_POST['ngo_payment'],
								//'ngo_doc' => $_POST['ngo_doc'],
								//'csr_doc' => $_POST['csr_doc'],
								'cycle_start_date1' => date('Y-m-d'),
								'donor_dropdown_id' => $val['donor_dropdown_id'],
								'project_donor_id' => $val['project_donor_id'],
								'donor_dropdown' => $val['donor_code1'],
								'cycle_donor_amount' => $val['donor_amount'],
								'ngo_payment' => $val['ngo_payment'],
								'no_of_cycle' => $no_of_cycle,
								'cycle_status' => 'New',
								
								
								
							); 
							$return_val = $this->db->insert('project_cycle_donor_details',$data_cycle_donor);
							
						}
					}
				}
			}
		}
		if ($project_cycle_id) {
			$arr_response['status'] = 200;
			$arr_response['message'] = 'Cycle updated successfully';
			$arr_response['project_cycle_id'] = $project_cycle_id;
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	
	public function update_cycle() {
     
		if($_POST['project_cycle_id']){
			$data = array(
				'cycle_name' => $_POST['cycle_name'],
				'cycle_start_date1' => $_POST['project_date'],
				'is_payment' => $_POST['is_payment'],
				'donor_dropdown' => $_POST['donor_dropdown'],
				'donor_dropdown_id' => $_POST['donor_dropdown_id'],
				'cycle_donor_amount' => $_POST['cycle_donor_amount'],
				'ngo_payment' => $_POST['ngo_payment'],
				'ngo_doc' => $_POST['ngo_doc'],
				'csr_doc' => $_POST['csr_doc'],
			);            
			$return_val = $this->db->update('project_cycle_details',$data,array('project_cycle_id' => $_POST['project_cycle_id']));
		}
		if ($return_val) {
			$arr_response['status'] = 200;
			$arr_response['message'] = 'Cycle updated successfully';
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	/*start project update section */
	public function update_project_details() {
		//var_dump($_POST);die;
		$project_id=$_POST['project_id'];
		
		if($project_id){
			$csr_file_value=$_POST['csr_file_value'];
			$csr_file_value_actual=$_POST['csr_file_value_actual'];
			$data = array(
				'title' => $_POST['project_update'],
				'project_start_date' => $_POST['project_start_date'],
				'project_end_date' => $_POST['project_end_date'],
				'cycle_file_upload' => $csr_file_value,
				'cycle_file_upload_actual' => $csr_file_value_actual,
				
			);            
			$return_val = $this->db->update('project',$data,array('id' => $project_id));
			if($project_id){
				$uploaded_type='mou';
				$this->project_mou_and_aproval_history($project_id,$uploaded_type,$csr_file_value,$csr_file_value_actual);
				
			}
		}
		if ($return_val) {
			$arr_response['project_id'] = $project_id;
			$arr_response['status'] = 200;
			$arr_response['message'] = 'updated successfully';
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	/*end  project update section */
	
	/*start project donor amount updatea  section */
	public function update_donor_amount() {
		//var_dump($_POST);die;
		$project_donor_id=$_POST['project_donor_id'];
		$project_id_set=$_POST['project_id_set'];
		
		if($project_donor_id){
			$data = array(
				'donor_amount' => $_POST['donor_amount'],
				'vendor_code' => $_POST['vendor_code'],
						
			);            
			$return_val = $this->db->update('project_donors',$data,array('project_donor_id' => $project_donor_id,'project_id' => $project_id_set));
		}
		if ($return_val) {
			$arr_response['project_id'] = $project_id_set;
			$arr_response['status'] = 200;
			$arr_response['message'] = 'updated successfully';
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	/*start project donor amount updatea  section */
	public function edit_project(){
		$this->load->model('Page_validation_model_organisation', 'obj_pvm', TRUE);
		$page='edit';
		
		$staff_id = $this->session->userdata('staff_id');
		$arrayName = array(
			'module_id' => '8', 
			'submodule_id' => '11', 
			'staff_id' => $staff_id, 
			'page' => 'other1', 
		);
		$data['access_data'] =$this->obj_pvm->get_user_access_data_for_menu($staff_id);
		$return_val =$this->obj_pvm->is_permitted($arrayName);
		$data['is_permitted']=$return_val;
		if(isset($_GET['id'])){
			$project_id=$_GET['id'];
			//var_dump($project_id);
		}else{
			$project_id=0;
		}
			$db_result = $this->db->get_where('project',array('id' =>$project_id));
			$data['project_data'] = $db_result->result();
		
		$this->load->view('organisation/components/header');
		$this->load->view('organisation/components/menu',$data);
		
		if($return_val){
			$this->load->view('organisation/pages/task/edit_project',$data);
		}else{
			$this->load->view('error_msg');
		}
		$this->load->view('organisation/components/footer');	
		
		
	}
	
	public function send_vandor_code_email(){
		
		$staff_id = $this->session->userdata('staff_id');
		$project_id = $_POST['project_id'];
		$ngo_id = $_POST['ngo_id'];
		
		$res_email = '';
		$first_name= '';
		$last_name= '';
		$org_id= 0;
		$superngo_id= 0;
		
		$sql_1 = 'SELECT * FROM `org_staff_account` WHERE `staff_id` = '.$staff_id ;
		$result_1 = $this->db->query($sql_1);
		if($result_1 && $result_1->num_rows()){
			$dataa = $result_1->result();
			$res_email = $dataa[0]->staff_email;
			$first_name = $dataa[0]->first_name;
			$last_name = $dataa[0]->last_name;
			$org_id = $dataa[0]->org_id;
		}
		
		$sql_2 = 'SELECT * FROM `project` WHERE `id` ='.$project_id ;
		$result_2 = $this->db->query($sql_2);
		if($result_2 && $result_2->num_rows()){
			$datas = $result_2->result();
			$superngo_id = $datas[0]->superngo_id;
			
			$sql_3 = 'SELECT * FROM `superngo` WHERE `id` ='.$superngo_id ;
			$result_3 = $this->db->query($sql_3);
			if($result_3 && $result_3->num_rows()){
				$data1 = $result_3->result();
				$brand_name = $data1[0]->brand_name;
			}
		}
		
		$sql_4 = 'SELECT * FROM `ngo` WHERE `id` ='.$ngo_id ;
		$result_4 = $this->db->query($sql_4);
		if($result_4 && $result_4->num_rows()){
			$data2 = $result_4->result();
			$legal_name = $data2[0]->legal_name;
		}

		$message ='';
		$message .= 'Dear '.$first_name.' '.$last_name.' <br>';
		$message .= '<p>Please provide a Vendor Code for '.$legal_name.'.</p><br>';
		
		$message .= '<p>Please contact '.$first_name.' '.$last_name.' for any further questions:</p><br>';
		
		$message .= '<p>Regards,</p>';
		$message .= '<span>Team Compass</span>';
		//print_r($ngo_staff_email);
		//print_r($message);
		
		$this->load->model('Email_model', 'obj_email', TRUE);
		$array = array(
			'subject' =>'Requesting Vendor Code from '.$brand_name.' for '.$legal_name,
			'msg' => $message,
			'to' => $res_email,
			'to_name' => $first_name.' '.$last_name,
		);
		
		//$this->obj_email->send_mail_in_ci($data);
		$this->obj_email->send_mail_in_sendinblue($array);
		
		$arr_response['status'] = 200;
		$arr_response['message'] = 'Request for vendor code sent successfully';
		echo json_encode($arr_response);
	}
	
	
	public function send_vandor_code_email_by_project_detail(){
		//var_dump($_POST);die;
		//if($_POST){
			$ngo_id=$_POST['ngo_id'];
			$donor_id=$_POST['donor_id'];
			$project_id=$_POST['project_id'];
			$org_id = $this->session->userdata('org_id'); 	
		
		$sql_1 = 'SELECT donor_id ,brand_name FROM `donor` WHERE `donor_id` ='.$donor_id;
		$result_1 = $this->db->query($sql_1);
		if($result_1 && $result_1->num_rows()){
			$main_donor_data = $result_1->result();
			$brand_name = $main_donor_data[0]->brand_name;
			//var_dump($brand_name);
		}else{
			$main_donor_data = '';
			$brand_name = 0;
		}
		
		$focal_point='';
		$sql23 = "SELECT staff_id FROM org_assigner_mgmt WHERE ngo_id=$ngo_id and org_id=$org_id and user_type='project normal'" ;
		$result23 = $this->db->query($sql23)->result_array();
		if($result23){
			$staff_id_data=$result23[0]['staff_id'];
			$sql23 = "SELECT first_name,last_name FROM org_staff_account WHERE `staff_id`=$staff_id_data " ;
				$result23 = $this->db->query($sql23)->result_array();
				if($result23){
					$first_name = $result23[0]['first_name'];
					$last_name = $result23[0]['last_name'];
					$focal_point=$first_name .' '.$last_name;
					//var_dump($focal_point);
				}
			$sql_5 = 'SELECT * FROM `org_staff_account` WHERE `staff_id` = '.$staff_id_data ;
			$result_5 = $this->db->query($sql_5);
			if($result_5 && $result_5->num_rows()){
				$staff_data = $result_5->result();
				$org_staff_name = $staff_data[0]->first_name.' '.$staff_data[0]->last_name;
				$org_staff_email = $staff_data[0]->staff_email;
			//var_dump($staff_data);
			}else{
				$staff_data = '';
				$staff_name = '';
				$staff_email = '';
			}	
		}
		
		$sql_6 = 'SELECT legal_name,staff_id FROM `ngo` WHERE `id` = '.$ngo_id ;
		$result_6 = $this->db->query($sql_6);
		if($result_6 && $result_6->num_rows()){
			$ngo_data = $result_6->result();
			$ngo_name = $ngo_data[0]->legal_name;
			$staff_id = $ngo_data[0]->staff_id;
			
		}else{
			$ngo_name = '';
			$staff_id = '';
			$ngo_data = '';
		}
		
		$message ='';
		$message .= 'Dear '.$org_staff_name.' <br>';
		$message .= '<p>Please provide a Vendor Code for '.$ngo_name.'</p>';
		$message .= '<div>Please contact '.$focal_point.' for any further questions:</div>';
		
		//print_r($ngo_staff_email);
		//print_r($message);
		
		//die;	
		
		$this->load->model('Email_model', 'obj_email', TRUE);
		$array = array(
			'subject' =>'Requesting Vendor Code from '.$brand_name.' for '. $ngo_name.'',
			'msg' => $message,
			'to' => $org_staff_email,
			'to_name' => $org_staff_name,
		);
		
		//$this->obj_email->send_mail_in_ci($data);
		$return_val=$this->obj_email->send_mail_in_sendinblue($array);
		
		if ($return_val) {
			$arr_response['status'] = 200;
			$arr_response['message'] = 'Vendor Code Send Successfully';
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
		
	}
	
	public function project_mou_and_aproval_history($project_id,$uploaded_type,$uploaded_file,$uploaded_file_actual){
		$data = array(
			'project_id' => $project_id ,
			'uploaded_type' => $uploaded_type,
			'uploaded_file' => $uploaded_file,
			'uploaded_file_actual' => $uploaded_file_actual,
			'uploaded_datetime' => date('Y-m-d H:i:s'),
		);
		$result2 = $this->db->insert('project_mou_and_aproval_history', $data);
	}
	
	
	public function get_view_mou_and_aproval_history() {
		//var_dump($_POST);die;
		
		$return_val=$this->db->get_where('project_mou_and_aproval_history',array('project_id' => $_POST['project_id'],'uploaded_type'=>$_POST['uploaded_type']));
		$return_data = $return_val->result_array();
		//var_dump($return_data);
		//die;
		
		
		if ($return_val) {
			$arr_response['status'] = 200;
			//$arr_response['message'] = 'Cycle Deleted successfully';
			$arr_response['return_data'] = $return_data;
		} else {
			$arr_response['status'] = 201;
			$arr_response['message'] = 'Something went wrong! Please try again';
		}
		echo json_encode($arr_response);
    }
	
}
