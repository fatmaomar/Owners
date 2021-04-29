<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


include_once(APPPATH . 'controllers/base_controller.php');

class invoices extends Base_Controller
 {

	function __construct()
	{
        parent::__construct();
    
        $this->concept  = "invoice" ; 
        $this->controller = "mollak/invoices";    
        
        $this->class_name    = "bi_invoice" ; 
        $this->class_path =  "mollak/bi_invoice" ; 
 
        $this->view_folder = "mollak"; 
        $this->id_field  = "invoice_id"; 
        
        $this->use_lang_files = array("mollak/invoice_main") ; 
        $this->security_component = "security.general" ; 
        $this->use_master = master_type::TableMaster ;
	
	
	}
//-------------------------------------------------------------
	public function info()
	{
		$access_component_name = "security.general" ; 
		$access_verb="read" ;
		$this_view_file = "invoice_addedit"	; 
		
		
		if (!$this->_top_function($access_component_name)) return ; 
		$data = array() ;
		$data["public_data"] = $this->admin_public->DATA;  			
		if ($this->admin_public->verify_access("read",0) == false ) 
		{
			$data["access_component_name"] = $access_component_name ; 
			$data["access_verb"] = $access_verb ; 					
			$this->load->view( '_general/general/invalid_rights_message',$data);		; // takes care of login / header loading 
		}
			
		$this_view = $this->view_folder.'/'.$this_view_file ; 
		
		$incoming_id = $this->uri->segment(4, 0);//passenger id in case filters not creat new ticket
		
		$this_item = & $this->main_class;
		$this_item->clear();
		$this_item->read($incoming_id , "" ,1);
		
		$unit_id = $this_item->business_data["invoice_unit_id"];
				
		$data["public_data"] = $this->admin_public->DATA;
		
		$data["invoice_id"] = $incoming_id;
		$data["unit_id"] = $unit_id;
	
		$data["this_concept"] = $this->concept ; 
		$data["this_controller"] = $this->controller ; 
		//$data["this_lang_folder"] = "trans"	;
		$data["this_id_field"] = $this->id_field ; 
		
	
		$this->load->view( $this_view , $data );
		
	} 
//---------------------------------------------------------------
	public function ajax_table()
	{	
		$access_component_name = $this->security_component ; 
		if (!$this->_top_function($access_component_name,'yes')) return ; 
		
		$access_verb="read" ;
		$data = array() ;
		$data["public_data"] = $this->admin_public->DATA;  	
		
				
		if ($this->admin_public->verify_access($access_verb,0) == false ) 
		{
			$data["access_component_name"] = $access_component_name ; 
			$data["access_verb"] = $access_verb ;							
			$this->load->view( '_general/general/invalid_rights_message',$data);		; // takes care of login / header loading
			return ; 	
		}
			
		$this_item = & $this->main_class;
		
		//$data["list_table"] = $this_item->list_items_rtable( "all",array() ,"");
		
		$data["list_table"] = $this_item->list_items_rtable( "current_period",array("curr_period_id"=>sysDATA("current_period_id")) ,"" ,"default");
		
		$data["this_concept"] = "invoice" ;
		$data["this_controller"] = $this->controller; 

        $data["this_lang_file"] = "mollak/invoice_main" ; 	
		$data["this_id_field"] = "invoice_id" ; 
		$data["this_name_field"] = "invoice_reference" ; 
		$data["this_name_field_ar"] = "invoice_reference" ;
		
		
		$data["options"]["hide_add_button"] = true ; 
		$data["options"]["disable_line_add"] = true ; 
		$data["options"]["disable_line_edit"] = true ; 
		$data["options"]["disable_line_delete"] = true ;
		$data["options"]["hide_line_verbs"] = false ; 
		$data["options"]["disable_datatable"] = false ; 
		$data["options"]["line_verbs_colors"] = true ; 
		$data["options"]["line_verbs_buttons"] = true ;
		$data["options"]["show_csv"] = true ; 
		//$data["hscroll"] = true ; 
		
		$data["options"]["enable_open_button"] = true ; 
		$data["options"]["open_url_suffix"] = site_url("mollak/invoices/info")   ; 
		$data["options"]["open_url_field"] = "invoice_id" ; 
				 
		$this->view_data = $data ; 
      
        return parent::ajax_table(); 
     }
//---------------------------------------------------
	public function ajax_edit()
	{
		$access_component_name = "security.general" ; 
		$access_verb="read" ;
		
		if (!$this->_top_function($access_component_name)) return ; 
		$data = array() ;
		$data["public_data"] = $this->admin_public->DATA;  			
		if ($this->admin_public->verify_access("read",0) == false ) 
		{
			$data["access_component_name"] = $access_component_name ; 
			$data["access_verb"] = $access_verb ; 					
			$this->load->view( '_general/general/invalid_rights_message',$data);		; // takes care of login / header loading 
		}
	
		$this->load->library("form_validation");
		$this->load->model("mollak/bi_unit");
		$this->load->model("mollak/bi_period");
		$this->load->model("mollak/bi_invoice_detail");
		
		// load & read Existing object  ----------------------------------------------------
		$this_item = & $this->main_class; 
		$this_item->clear();

		$incoming_id = $this->uri->segment(4, 0);
	 
		if ($incoming_id !=0) 
		{
			$this_item->Read($incoming_id,"",1);
			if (!$this_item->is_published )
			{
				//redirect with error not found object  
			}
		}
		
		$unit_id = $this_item->business_data["invoice_unit_id" ] ; 
		
		$this_unit = & $this->bi_unit; 
		$this_unit->clear();
		$this_unit->Read($unit_id,"",1);
			
			
		$data["this_controller"] = $this->controller; 	
		
		//	echo $this->concept ; 
			
		$this->form_validation->set_rules("invoice_reference","invoice reference", "required") ;
		
		if ($this->form_validation->run() == FALSE )
		{
			$data["this_unit"] = $this_unit;
			$data["unit_id"] = $unit_id;	
			
			$data["this_item"] = $this_item ; 			
			$data["public_data"] = $this->admin_public->DATA;
			$data["disable_edit"] = false;						
			$this->load->view('mollak/invoice_edit',$data);	
			return ; 
		}
		
		else 
		{
			 
			if ($this_item->ID()==0) 
			{ if ($this->admin_public->verify_access("new",1) == false ) return ;}
			else { if ($this->admin_public->verify_access("edit",1) == false ) return ; }

			/*
			// ---------------------------------------------------------------------------------------------
			// this assumes that you only expose business_data from editing or filling 						/
			// you may require the input->post manually if you have additional fields , that_ 				/
			// are not in the data base or the business data 												/
			// ---------------------------------------------------------------------------------------------
			
			 */
			// just a quick fix for boolean // should find a long term solution
			//$this_item->business_data["drug_available"] = 0 ; //it's for check-box when it's unchecked return 0
			//to add new values	
			
			foreach ($this_item->business_data as $key => $value)
			{
				if (key_exists($key, $this->input->post())) // if ($this->input->post($key))
				{ 
					$this_item->business_data[$key] =$this->input->post($key);  	
				}
			}
			
			// in this moment , where would be the new value of the field before update ?
			$this_item->validate();

			if ($this_item->success==FALSE)
			{
				//goto redo; 
				
				$data["this_item"] = $this_item ; 			
				$data["public_data"] = $this->admin_public->DATA;
				$data["disable_edit"] = false;		
				
				$template_folder = "_templates/".$this->template_name."/" ;  
				$this->load->helper($this->theme_helper)	;							
				$this->load->view( $this->view_folder.'/'.$this->concept .'_edit',$data);
				
				
				echo "<b><center>This Period Name is already exist</center></b>";
				
				return ;
			}
			else
			{
					
				//$this_item->business_data["sys_account_id"] = $this->admin_public->DATA["sys_account_id"];
				
				$this_item->update();
				echo "FINE: OK :"."<a msg=record_update_success /><ID>".$this_item->ID()."</ID>" ; 
			}
								
			return;
		}		
			
	}
//---------------------------------------------------
	public function create_invoices()
	{
		$access_component_name = $this->security_component ; 
		if (!$this->_top_function($access_component_name,'yes')) return ; 
		
		$access_verb="read" ;
		$data = array() ;
		$data["public_data"] = $this->admin_public->DATA;  	
		
				
		if ($this->admin_public->verify_access($access_verb,0) == false ) 
		{
			$data["access_component_name"] = $access_component_name ; 
			$data["access_verb"] = $access_verb ;							
			$this->load->view( '_general/general/invalid_rights_message',$data);		; // takes care of login / header loading
			return ; 	
		}
			
		$this_item = & $this->main_class;
		
		$current_period_id = sysDATA("current_period_id");
		
		$this->db->Query("CALL create_invoices(".$current_period_id .")" )  ; 		
		//echo "create new invoices done" ;		
		$this->db->Query("CALL create_invoice_detail(".$current_period_id .")" )  ; 		
		//echo "<br>invoice detail insert done" ;
				
		//------------------ message to confirm complete process 
	    r_theme_box_start("انشاء فواتير",12,	array("body_id"=>"nothing",
				"box_id"=>"nothing","hide_with_menu"=>"yes",
				"tools"=>"","box_icon"=>"icon-pencil"));	
							
			echo '<div class="alexrt alexrt-error">';	
			//echo '<h4><i class="icon-warning-sign big"></i> PLease Select Clients</h4><hr/>' ; 
			echo "<h4>تم اصدار الفواتير بنجاح</h4>";
			echo "<br>" ; 
			echo '</div>';
			echo '<button class="btn blue ajax_action right master_font" caller_verb="form_cancel" caller_id="invoice_edit_form">';
			echo "أغلاق" ; 
			echo '</button>';
		r_theme_box_end();
		return ;
		
		
	}
//---------------------------------------------------
	public function confirm_create_invoice()
	{
		$access_component_name = $this->security_component ; 
		if (!$this->_top_function($access_component_name,'yes')) return ; 
		
		$access_verb="read" ;
		$data = array() ;
		$data["public_data"] = $this->admin_public->DATA;  		
				
		if ($this->admin_public->verify_access($access_verb,0) == false ) 
		{
			$data["access_component_name"] = $access_component_name ; 
			$data["access_verb"] = $access_verb ;							
			$this->load->view( '_general/general/invalid_rights_message',$data);		; // takes care of login / header loading
			return ; 	
		}
			
		$this_item = & $this->main_class;
		
		$current_period_id = sysDATA("current_period_id");
		if($current_period_id == 0)
		{
			//------------------ message to confirm no Open-Periods !!
		    r_theme_box_start("انشاء فواتير",12,	array("body_id"=>"nothing",
					"box_id"=>"nothing","hide_with_menu"=>"yes",
					"tools"=>"","box_icon"=>"icon-pencil"));	
								
				echo '<div class="alexrt alexrt-error">';	
				//echo '<h4><i class="icon-warning-sign big"></i> PLease Select Clients</h4><hr/>' ; 
				echo "<h4>لايوجد فترة مفتوحة لاصدار الفواتير !!</h4>";
				echo "<br>" ; 
				echo '</div>';
				echo '<button class="btn blue ajax_action right master_font" 
								caller_verb="form_cancel" 
								caller_id="invoice_edit_form">';
				echo "أغلاق" ; 
				echo '</button>';
			r_theme_box_end();
			return;
		}

		$data["this_item"] = $this_item ; 
		$data["this_concept"] = $this->concept ;
		$data["this_controller"] = $this->controller ;
		$data["message_title"] = "اصدار فواتير" ; 
        $data["message_header"] = "Please Confirm Convert To File" ;
        $data["message_question"] = "هل انت متأكد من اصدار فواتير للفترة الحالية؟؟" ; 
        $data["message_button"] = "نعم ,, اصدار فواتير" ;
        $data["message_caller_key"] = "create_file" ;
		
		$this->load->view( '_general/concept_delete_aj',$data);
		
	}
//---------------------------------------------------
	public function print_invoice()
	{
		$access_component_name = $this->security_component ; 
		if (!$this->_top_function($access_component_name,'yes')) return ; 
		
		$access_verb="read" ;
		$data = array() ;
		
		$data["public_data"] = $this->admin_public->DATA;  	
			
		if ($this->admin_public->verify_access($access_verb,0) == false ) 
		{
			$data["access_component_name"] = $access_component_name ; 
			$data["access_verb"] = $access_verb ;							
			$this->load->view( '_general/general/invalid_rights_message',$data);		; // takes care of login / header loading
			return ; 	
		}
		

		$this->load->model("mollak/bi_invoice_detail");
		$this->load->model("mollak/bi_unit");
		
		$this_item = & $this->main_class; 
		$this_item->clear();

		$current_period_id = sysDATA("current_period_id");		
		
		//$this_inv_detail = & $this->bi_invoice_detail; 
		//$this_inv_detail->clear();
				
		$incoming_id = $this->uri->segment(4, 0);
		
	//-------------- qry to get all invoice details which is belong to this $incoming_id --------
		
		$query = "SELECT * FROM invoice_detail_s
		        inner join calculation_method_s on calculation_method_s.calculation_method_id = invoice_detail_s.invd_method_id
				where invoice_detail_s.invd_invoice_id = $incoming_id
				order by calculation_method_order , invd_item_order";
		
		$inv_details_qry = $this->db->query($query);
		$inv_details_arr = $inv_details_qry->result_array($inv_details_qry);	
	//-----------------------------------------------------------------------------
		$query_period_maint = "select period_total_maintenance
								from period_s
								where period_id = $current_period_id";
		
		$period_maint_qry = $this->db->query($query_period_maint);
		$total_period_maint_arr = $period_maint_qry->result_array($period_maint_qry);
		
		$total_period_maint = $total_period_maint_arr[0]["period_total_maintenance"];
		
	
		if ($incoming_id !=0) 
		{
			$this_item->Read($incoming_id,"",1);
			
			if (!$this_item->is_published )
			{
				//redirect with error not found object  
			}
		}
		
		$unit_id = $this_item->business_data["invoice_unit_id" ] ; 
		
		$this_unit = & $this->bi_unit; 
		$this_unit->clear();
		$this_unit->Read($unit_id,"",1);
		
		
		$data["this_item"] = $this_item;
		$data["invoice_detail_arr"] = $inv_details_arr;
		$data["this_unit"] = $this_unit;
		$data["unit_id"] = $unit_id;
		$data["total_period_maint"] = $total_period_maint;
		 	
		
		$this->load->view( $this->view_folder.'/print_invoice_view' , $data);

	}
}//end of controller

	