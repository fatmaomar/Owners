<?php
//foreach($total as $mytotal)
//{
//  foreach ($mytotal as $final)
//  {
//     // print_r($final=>system_id);
//      
//  }
//  
//}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">

    <head>
<!--    <img src=<?php //echo base_url("lahacienda_logo.png"); ?> alt="Lahacienda Logo" 
         align="left"	style="width:200px;height:100px;">-->
    <style>
        header{
            margin-top: 1px;
        }
        @font-face
        {
            font-family:'rArabic';
            src:url('<?php echo base_url("fonts/DroidKufi-Regular.eot"); ?>');
            src:url('<?php echo base_url("fonts/DroidKufi-Regular.eot?#iefix"); ?>') format('embedded-opentype'),	
                url('<?php echo base_url("fonts/DroidKufi-Regular.ttf"); ?>') format('woff');
        }
        body 
        {
            /* size of A4 */
            height: 842px;
            width: 595px;
            /* to centre page on screen*/
            margin-left: auto;
            margin-right: auto;
        }

        p
        {
            font-family:'rArabic';
            font-size : 12px;

            margin: 0;
            padding: 0;

        }

        table {
            border-collapse: collapse;	
            font-family:'rArabic';
            font-size : 12px;		
        }

        table.calc td,th
        {
            border: 1px solid;
        }

        table.calc td
        {
            height: 20px;
            padding-right: 5px;
        }
    </style>
</head>

<body>
<!--    <header>

    </header> -->
    <?php
    foreach ($mybus_data as $key => $value) {
        $main_invoice = $value;
        $this_unit_arr = $my_units[$key];
        $total_garden_maint_price = $priod_maintenance[$key] * 0.1;
        // The general maintenance cost charged to the buildings
        $total_building_maint_price = $priod_maintenance[$key] * 0.9;

        $maint_ratio = $main_invoice["inv_building_maintenance_ratio"] + $main_invoice["inv_garden_maintenance_ratio"];

        // Building's share of maintenance
        $build_maint_price = $this_unit_arr["unit_build_area"] * $main_invoice["inv_build_meter_price"];

        // The park's share of maintenance
        $garden_maint_price = $this_unit_arr["unit_garden_area"] * $main_invoice["inv_garden_meter_price"];

        // Total general maintenance
        $sum_maint = $build_maint_price + $garden_maint_price;

        // Total net discounts and revenue of an unit share
        $total_maint_and_unit_income = $main_invoice["inv_unit_income_value"] + $main_invoice["invoice_total_interest"];

        // Total Consumption
        $total_reading_price = 0;

        // Net maintenance insurance = total deposits - arrears
        $final_maint_insurance = $main_invoice["invoice_total_insurance"] - $main_invoice["inv_unit_last_balance"];

        //اtotal cost
        $total_reading_and_maint_price = $main_invoice["invoice_total_gross"] + $main_invoice["invoice_total_interest"];




//-------------------------------------- Main Invoice ------------------------------------
        //------------------------ header ---------------------
        ?>
        <img src="<?php echo base_url("lahacienda_logo.png"); ?> "alt="Lahacienda Logo" 
         align="left"	style="width:200px;height:100px"> 
        <?php  
        echo"
		<table width=95% >
			<tr>
				<td><b>قريـــــــــــــة لاسيـــــــــــــــــــــــــــاندا راس ســـــــــــــــدر</b></td>
				<td>التاريخ : " . $main_invoice["invoice_date"] . "</td>
			</tr>
			
			<tr>
				<td>مطالبة فروق تكاليف الإدارة والتشغيل عن  " . $main_invoice["period_name"] . "</td>
				<td>كود العميل : ________</td>
			</tr>
		
		</table>		
	";

        //------------------------ main info ---------------------
        echo"
		<table width=95% >
			<tr>
				<td>السيد/السيدة : " . $main_invoice["invoice_client_name"] . "</td>
			</tr>
			
			<tr>
				<td>رقم الوحدة :" . $main_invoice["unit_number"] . "</td>
				<td>الدور : " . $this_unit_arr["unit_floor"] . "</td>
				<td>المرحلة : " . $this_unit_arr["unit_stage"] . "</td>
				<td>المصطبة : " . $this_unit_arr["unit_terrace"] . "</td>
			</tr>
			
			<tr>
				<td>تاريخ الاستلام : " . $this_unit_arr["unit_begin_date"] . "</td>
				
				<td>مدة الحساب : " . $main_invoice["inv_no_of_month_calc"] . " شهور </td>
			</tr>
		
		</table>		
	";

        //------------------------ main table info ---------------------
        echo"	
	<table class='calc' width=97% >
	<tr>
		<td>اجمالى مساحات المبانى بالقرية</td>
	    <td>" . $main_invoice["inv_total_building_area"] . "</td>
	    <td>مساحة مبانى الوحدة</td>
	    <td>" . $this_unit_arr["unit_build_area"] . "</td>
	    <td>نسبة التحميل للمبنى</td>
	    <td>" . ($main_invoice["inv_building_maintenance_ratio"]) . "</td>	
	</tr>
	
	<tr>
		<td>اجمالى مساحة الحدائق الخاصة</td>
		<td>" . $main_invoice["inv_total_garden_area"] . "</td>
		<td>مساحة الحديقة</td>
		<td>" . $this_unit_arr["unit_garden_area"] . "</td>
		<td>نسبة التحميل للحديقة</td>
		<td>" . ($main_invoice["inv_garden_maintenance_ratio"]) . "</td>
	</tr>

	</table>

		
	";

        echo"<br><hr>";

//------------ paragraph ---------------------
        echo "<p style='font-size:13px'>
بناء علي المصروفات من مراجع الحسابات فإن تكلفة الصيانة العامة المحملة على المبانى قد بلغت مبلغاً وقدره $total_building_maint_price 
, وتكلفة الصيانة العامة المحملة على الحدائق قد بلغت مبلغاً وقدره $total_garden_maint_price
<br>
<p>
 وللتوضيح ان الصيانة العامة المحملة على المتر المربع للمبانى = " . round($main_invoice["inv_build_meter_price"], 2) . " جنيه مصري ,
والصيانة العامة المحملة على المتر المربع للحديقة = " . round($main_invoice["inv_garden_meter_price"], 2) . " جنيه مصري.</p>
<p><b>* وفيما يلي بيان بالمصروفات الإجمالية المستحقة عنكم فى الفترة محل المطالبة:</b></p>
";
//-------------------------------------- Invoice Details ------------------------------------
        echo"	
	<table  class='calc'  width=100% >	
	<tr>
		<th>البيـــــــــــان</th>
	    <th>المســاحة</th>
	    <th colspan=3>السعر</th>
	    <th>المبلغ</th>
	    <th>المجموع</th>
	</tr>
	
	<tr>
		<td>نصيب المبانى من الصيانة العامة</td>
		<td>" . $this_unit_arr["unit_build_area"] . "</td>
		<td colspan=3>" . $main_invoice["inv_build_meter_price"] . "</td>
		<td>" . round($build_maint_price, 2) . "</td>
		<td>	</td>
	</tr>
	
	<tr>
		<td>نصيب الحديقة من الصيانة العامة</td>
		<td>" . $this_unit_arr["unit_garden_area"] . "</td>
		<td colspan=3>" . $main_invoice["inv_garden_meter_price"] . "</td>
		<td>" . round($garden_maint_price, 2) . "</td>
		<td>	</td>		
	</tr>


";

//___________________________________________________________________________________________________
        //------------------------------- maintenance ------------------------------------------------------
        $invoice_detail_arr = $my_details[$key];

        foreach ($invoice_detail_arr as $key => $value) {

            if ($invoice_detail_arr[$key]["calculation_method_order"] == 4) {
                echo"			
				<tr>
					<td>اجمالى الصيانة العامة</td>
					<td>	</td>
					<td colspan=3>	</td>
					<td>	</td>
					<td>" . round($sum_maint, 2) . "</td>
				</tr>	
			";
            }
        }

//___________________________________________________________________________________________________	
        //----------------------------- fixed value ----------------------------
        $check_iteration = 0;
        $total_fixed_items_value = 0;

        foreach ($invoice_detail_arr as $key => $value) {
            //------------- لاضافة كلمة "يضاف" اول مرة فقط --------------------
            $check_iteration++;

            if ($check_iteration == 1 && $invoice_detail_arr[$key]["calculation_method_order"] == 1) {
                $total_fixed_items_value = $total_fixed_items_value + $invoice_detail_arr[$key]["invoice_detail_amount"];
                echo"
				
				<tr>
					<td><u>يضــــــــــــــاف</u><br>" . $invoice_detail_arr[$key]["invd_item_name"] . "</td>
					<td>	</td>
					<td colspan=3>	</td>
					<td>	</td>
					<td valign=bottom>" . $invoice_detail_arr[$key]["invoice_detail_amount"] . "</td>
				</tr>
			
			";
            } else if ($invoice_detail_arr[$key]["calculation_method_order"] == 1) {
                $total_fixed_items_value = $total_fixed_items_value + $invoice_detail_arr[$key]["invoice_detail_amount"];
                echo"			
				<tr>
					<td>" . $invoice_detail_arr[$key]["invd_item_name"] . "</td>
					<td>	</td>
					<td colspan=3>	</td>
					<td>	</td>
					<td>" . $invoice_detail_arr[$key]["invoice_detail_amount"] . "</td>
				</tr>			
			";
            }
        }

        //----------------------------- إجمالى مصروفات الصيانة ----------------------------
        //إجمالى مبلغ فروق الصيانة العامة
        $total_maint_amount = $total_fixed_items_value + $sum_maint - $total_maint_and_unit_income + $main_invoice["invoice_vat_amount"];

        echo"			
		<tr>
			<td>إجمالى مصروفات الصيانة</td>
			<td>	</td>
			<td colspan=3>	</td>
			<td>	</td>
			<td>" . round(($total_fixed_items_value + $sum_maint), 2) . "</td>
		</tr>			
			
		<tr>
			<td><u>يطرح منه</u><br>صافى خصومات التأمين (12%)</td>
			<td>	</td>
			<td colspan=3>	</td>
			<td>" . $main_invoice["invoice_total_interest"] . "</td>
			<td>	</td>
			
		</tr>	
		
		<tr>
			<td>نصيب الوحدة من ايرادات اخرى</td>
			<td>	</td>
			<td colspan=3>	</td>
			<td>" . $main_invoice["inv_unit_income_value"] . "</td>
			<td>	</td>
			
		</tr>
		
		<tr>
			<td>إجمالى صافى الخصومات ونصيب الوحدة من الايرادات</td>
			<td>	</td>
			<td colspan=3>	</td>
			<td>	</td>
			<td>" . $total_maint_and_unit_income . "</td>
			
		</tr>
		
		<tr>
			<td>ضريبة القيمة المضافة عن فرق الصيانة والستالايت</td>
			<td>	</td>
			<td colspan=3>	</td>
			<td>	</td>
			<td>" . $main_invoice["invoice_vat_amount"] . "</td>
		</tr>
		
		<tr bgcolor=#D3D3D3>
			<td>إجمالى مبلغ فروق الصيانة العامة</td>
			<td>	</td>
			<td colspan=3>	</td>
			<td>	</td>
			<td>" . round($total_maint_amount, 2) . "</td>
		</tr>
			
			
	";
        //--------------------------reading values headers ---------------------------

        echo"
		<tr>
			<th>القراءات والاستهلاكات</th>
		    <th>السابقة</th>
		    <th>الحالية</th>
		    <th>الاستهلاك</th>
		    <th>السعر</th>
		    <td>	</td>
		    <td>	</td>
		</tr>
	
	";

        foreach ($invoice_detail_arr as $key => $value) {
            //----------------------------- reading value ----------------------------
            if ($invoice_detail_arr[$key]["calculation_method_order"] == 2) {
                $total_reading_price = $total_reading_price + $invoice_detail_arr[$key]["invoice_detail_amount"];
                echo"
				<tr>
					<td>" . $invoice_detail_arr[$key]["invd_item_name"] . "</td>
					<td>" . $invoice_detail_arr[$key]["invd_pre_reading"] . "</td>
					<td>" . $invoice_detail_arr[$key]["invd_cur_reading"] . "</td>
					<td>" . $invoice_detail_arr[$key]["invd_diff_reading"] . "</td>
					<td>" . $invoice_detail_arr[$key]["invd_item_unit_price"] . "</td>
					<td>" . $invoice_detail_arr[$key]["invoice_detail_amount"] . "</td>
				    <td>	</td>
				</tr>			
			";
            }
        }


        echo"


<!-------------------------------------- readings items ------------------------------------------------->
	
	<tr bgcolor=#D3D3D3>
		<td>إجمالى الإستهلاكات</td>
		<td>	</td>
		<td>	</td>
		<td>	</td>
		<td>	</td>
		<td>	</td>
	    <td>" . $total_reading_price . "</td>
	</tr>
	
	<tr>
		<td><b>صـــــافى قيمة المطالبــــــة المستحق</b></td>
		<td>	</td>
		<td>	</td>
		<td>	</td>
		<td>	</td>
		<td>	</td>
		<td bgcolor=#D3D3D3>" . round($main_invoice["invoice_total_amount"], 2) . "</td>
	</tr>

</table>

<p style='page-break-after: always;'>&nbsp;</p>

<table style=width:100% >
	<tr align=left>
		<td><img src= " . base_url('lahacienda_logo.png') . " alt= 'Lahacienda Logo'
				style='width:200px;height:100px;'>
		</td>
	</tr>
</table>	
		
<br>	
	<p><u>تحريراً في&ensp;" . date("Y/m/d") . "</u></p>
	<p>يعد استلامكم لهذا الكشف المؤيد بالمستندات هو اطلاعكم علي كافة المستندات المؤيدة له والماماً بها
	ومراجعتها ولا يجوز لكم الاعتراض عليها بعد مرور خمسة ايام من تاريخ الاستلام ويعد استلامكم هو مصادقة علي كشف الحساب.</p>	
	<br>
	<p><u>ويتم السداد كالأتى</u>:-</p>
	<p>-&emsp; إجمالى المتأخرات السابقة تستحق السداد فورا.</p>
	<p>-&emsp; تمنح فنرة سماح لسداد المبلغ المستحق وقدرها خمسة عشر يوما من تاريخ إستحقاق المطالبة.</p>
	<p>-&emsp; يرجى مراجعة الإدارة المالية بالشركة في حالة وجود أي خطأ في البيانات الخاصة بسيادتكم.</p>
	<p>-&emsp; للاستفسار / الشكاوي / المقترحات يرجي الاتصال علي  <span align= right dir=ltr>( 01000018294&emsp;or&emsp;16334 )</span> او مراسالاتنا علي
	  <u>customer.service@lahaciendarassudr.com</u>
	  أو موقع الشركة <u>www.lahaciendarassudr.com</u></p>
	
	<br>
	<p>- <u><b>طـــــــرق الســـــداد:</b></u></p>
	<p>- يتم السداد نقدا أو بشيك بإسم &quot; شركة البحراوى للإستثمار السياحي &quot; أو كروت ائتمان في جميع فروع الشركة
	  (الفرع الرئيسي: 20 أ شارع منشية الطيران - منشية البكرى - مصر الجديدة - القاهرة , أو القرية براس سدر)</p>	
	
	<p>- كما يمكن السداد عن طريق التحويل البنكي علي أحد حساباتنا التالية:</p>
	<table class='calc' width=95% >
		
		<tr>
			<th>البنـــــــــــك</th>
			<th>الفــــــــــرع</th>
			<th>رقم الحساب</th>
			<th>سويفت كود</th>
		</tr>
		
		<tr>
			<td>البنك العربي الإفريقى</td>
			<td>ارض الجولف</td>
			<td align=center>515463</td>
			<td align=center>araiegcxlf</td>
		</tr>
		
		<tr>
			<td>البنك التجارى الدولى</td>
			<td>الخليفة المأمون</td>
			<td align=center>100030352437</td>
			<td align=center>CIBEEGCX</td>
		</tr>
		
		<tr>
			<td>بنك الإمارات دبى الوطنى</td>
			<td>النزهة الجديدة</td>
			<td align=center>1019227263006</td>
			<td align=center>EBILEGCXXX</td>
		</tr>
	</table>
	
	<br>
	<p>- يمكن التحصيل عن طريق مندوبينا مع مراعاة دفع مصاريف تحصيل وقدرها مائة جنيه للمرة الواحدة.</p>
	<p>- فى حالة التأخير فى السداد يستحق غرامة تأخير شهرية بنسبة 2% طبقا للتعاقد بعد إستهلاك قيمة تأمين الصيانة.</p>
	<p>- يرجي التكرم بالتأكيد من أخذ إيصالات جميع الخدمات المقدمة اليكم.</p>
	<p>- برجاء تحديث البيانات الخاصة بكم لدى الشركة (التليفون - البريد الالكترونى - عنوان المراسلات).</p>
	<p>- يتم ارسال المطالبات بالبريد المسجل ونسخة بالبريد الالكترونى (ان وجد) كما تسلم نسخة باليد للمقيمين بالقرية.</p>
	<p>- هذا الكشف غير مقترن به مصاريف التحصيل سواء العادية او القضائية.</p>
	<p>- هذه البيانات حتى&ensp;" . $main_invoice["invoice_date"] . " .</p>	
	<p>وتهيب إدارة الشركة بضرورة سداد هذه المصروفات في مواعيديها حتي نتمكن من الوفاء بكافة الخدمات المطلوبة ومراعاة المساواة في تحمل الاعباء بين السادة الملاك/الشاغلين.</p>
	
	
	
	
	";

        echo '<p style="page-break-after: always;">&nbsp;</p>';
      
    } 
// <footer>
//        <p align= left>قرية لاسياندا راس سدر السياحية.</p>
//    </footer>
    //نسبة التحميل
    ?> 
    
    <script>
        window.print();
    </script> 
</body>
</html>