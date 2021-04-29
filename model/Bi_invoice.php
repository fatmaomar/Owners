<?php

Class bi_invoice extends Simple_business implements iSimple_Business 
{
		
	/* Important Class Behavior Definition -- THIS FUNCTION MUST BE EDITED */ 
function __construct() 
		{
		  
		  	$this->bi_config(); 			
			// setup all variables 
			$this->clear();
			
		}
	
	/* config & clear business_data  Array of the object */ 
public function clear()
	{
		// create array containing all data of the business object not including linked tables//
		$this->business_data  = 
			array(
			"invoice_id"=>0,
			"invoice_reference"=>"",
			"invoice_period_id"=>0,
			"invoice_unit_id"=>0,
			
			"period_name"=>"",
			"unit_number"=>"",
			
			"invoice_client_name"=>"",
			"invoice_vat_amount"=>0,
			"invoice_total_amount"=>0,
			"invoice_date"=>0,
			"invoice_index"=>0,
			"inv_building_maintenance_ratio"=>0,
			"inv_garden_maintenance_ratio"=>0,
			"inv_no_of_units"=>0,
			"inv_posted"=>0,
			"inv_no_of_month_calc"=>0,
			"inv_total_building_area"=>0,
			"inv_total_garden_area"=>0,
			"inv_unit_initial_maintenance"=>0,
			"inv_unit_last_balance"=>0,			
			"inv_unit_income_value"=>0,// Unit value of revenue
			"inv_unit_income_ratio"=>0,// Unit percentage of revenue
			"invoice_total_add_insurance"=>0,
			"invoice_total_insurance"=>0,
			"invoice_total_interest"=>0,
			"invoice_total_income"=>0,//Total revenue
			"invoice_total_gross"=>0,
			"inv_build_meter_price"=>0,
			"inv_garden_meter_price"=>0,
			//"maint"=>""
			);
	}  

//-------------------------------------------------
	// class configuration 

	public function bi_config()
	{
		
			$CI =& get_instance();
			$lang = $CI->admin_public->DATA["system_lang"] ; 
			$CI->lang->load("mollak/invoice_main",$lang);
		
		//create the class stamp -------------------------------------------
		
			$this->class_name="bi_invoice";
			$this->table_name="invoice_s";
			
			$this->concept_key="invoice." ; 
			
			$this->id_field_name="invoice_id" ; 
			
			$this->name_field_name=r_langcase("invoice_reference","invoice_reference");
			$this->name_field_name="invoice_reference" ;
				 
			$this->list_title = r_langline(".list_title",$this->concept_key);
			$this->editing_title = r_langline(".editing_title",$this->concept_key); 
			$this->creating_title = r_langline(".creating_title",$this->concept_key);
			
			// create array containing fields to show in the table , with listoption =""
		 
			$this->list_view_edit_icon["default"] = 1 ; 				
			// create array containing fields to show in the table , with listoption ="" 
			
			$this->list_views["default"] = Array (
				
				"invoice_id"=>'|hide|', 
				"invoice_reference"=>'|hide|',
				"period_name"=>'|hide|',
				"unit_number"=>"وحدة",				
				"invoice_client_name"=>"عميل",
				"invoice_total_amount"=>"المطالبة المستحق",
				//"invoice_total_gross"=>"الفاتورة الكلية",
				//"inv_unit_income_ratio"=>"ايرادات%",
				//"invoice_total_income"=>"ايرادات",
				"inv_unit_income_value"=>"ايرادات الوحدة",
				//"inv_building_maintenance_ratio"=>"نسبة مبنى مالصيانة",
				//"inv_garden_maintenance_ratio"=>"نسبة حديقة مالصيانة",
								
				//"invoice_date"=>r_langline("default.list.invoice_date",$this->concept_key),				

				//"inv_no_of_units"=>"invoice_no_of_units",
				//"inv_posted"=>"inv_posted",
				//"inv_no_of_month_calc"=>"inv_no_of_month_calc",

				//"inv_unit_initial_maintenance"=>"وديعة اساسية",
				//"invoice_total_add_insurance"=>"ودائع اخرى",
				"invoice_total_insurance"=>"اجمالى ودائع",
				"inv_unit_last_balance"=>"متأخرات",				
		
				"invoice_total_interest"=>"فوائد",
				"invoice_vat_amount"=>"القيمة المضافة",
				//"inv_total_building_area"=>"مساحات مبانى",
				//"inv_total_garden_area"=>"مساحات حدائق",
				"_DETAILS"=>"|hide|",
		
				);

		//------------- last 4 invoice comparisons needed columns
		$this->list_views["neededcolsinvoice"] = Array(
           	"invoice_id" => '|hide|',
            "invoice_total_amount" => r_langline("default.list.invoice_total_amount", $this->concept_key),
           	"invoice_unit_id" => r_langline("default.list.invoice_unit_id", $this->concept_key),
        	"_DETAILS"=>"|hide|",
			);
		
		//---------------------------------------------------------- ---------------------------
		
			// to be used in reading simple & exteded Modes 
			$this->read_select = Array("invoice_s.*");		
			$this->read_select_extended=Array("invoice_s.*" , "unit_number" , "unit_floor","unit_stage",
								"unit_terrace","unit_begin_date" , "unit_build_area","unit_garden_area"
								,"period_name");
			$this->read_join_extended=Array(
						Array(
							"1"=>"unit_s" , 
							"2"=>"unit_s.unit_id = invoice_s.invoice_unit_id",
							"3"=>"left"
						 	),
						Array(
							"1"=>"period_s" , 
							"2"=>"period_s.period_id = invoice_s.invoice_period_id",
							"3"=>"left"
						 	),
						 
						 );

			$this->list_join = $this->read_join_extended ;
			
			$this->list_edit_Col =2 ; 
			
			$this->list_items_where["all"] = array();
			$this->list_items_where["current_period"] = array("curr_period_id"=>"invoice_s.invoice_period_id"); 
			$this->list_items_where["fullname"] = array(); 
				
	}

//////////////////////////////////////////////////////////////
	public function more_config_cols(rTable $irTable,$view_name="")
	{
		//$irTable->AddCol("maint");
		//$irTable->Cols["period_apply_late_penalty"]->Type = rColumnType::ColTypeBoolean;
		//$irTable->Cols["period_is_closed"]->Type = rColumnType::ColTypeBoolean;			  
	}
	/* further configure single table row */ 
	public function more_config_row(rTableRow $itable_row,Array $data_row,$view_name)
	{
		/*$invoice_id = $data_row["invoice_id"];
		
		$qry_state = "select invd_maintenance_amount 
				from invoice_detail_s
				where invoice_detail_s.invd_invoice_id = $invoice_id and invd_maintenance_amount <>0";
				
		$my_qry = $this->db->query($qry_state);
		$my_array = $my_qry->result_array($my_qry);	
		//echo"<pre>";print_r($my_array);
		$maint = $my_array[0]["invd_maintenance_amount"];
		$itable_row->Cells["maint"]->Value = $maint;*/
	}
		
//----------------End OF The Class---------------------------------
}