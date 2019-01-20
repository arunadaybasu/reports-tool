<?php
include('dbinfo.php');

$date1 = new DateTime($_POST['date-from_submit']);
$date2 = new DateTime($_POST['date-to_submit']);

$diff = $date2->diff($date1)->format("%a");

if($diff <= 30){
	$sql_ext_1 = "%d";
	$sql_ext_2 = "%d";
}
else if($diff <= 365){
	$sql_ext_1 = "%M";
	$sql_ext_2 = "%m";
}
else if($diff > 365){
	$sql_ext_1 = "%Y";
	$sql_ext_2 = "%Y";
}

// if(isset($_POST["category"])){
// 	if( count($_POST["category"]) > 0){
// 		$fran_arr = $_POST["category"];
// 		array_splice($fran_arr, 0, 1);
// 		$franchises_sel = implode("','",$fran_arr);
// 		$franchises_sel = "AND category.category_name IN ('" . $franchises_sel . "')";
// 		if($franchises_sel == "AND category.category_name IN ('')"){
// 			$franchises_sel = '';
// 		}
// 	}
// 	else{
// 		$franchises_sel = '';	
// 	}	
// }
// else{
// 	$franchises_sel = '';	
// }

// if(isset($_POST["status"])){
// 	if( count($_POST["status"]) > 0){
// 		$status_arr = $_POST["status"];
// 		array_splice($status_arr, 0, 1);
// 		$status_sel = implode("','",$status_arr);
// 		$status_sel = "AND appointment_summary.status IN ('" . $status_sel . "')";
// 		if($status_sel == "AND appointment_summary.status IN ('')"){
// 			$status_sel = "AND appointment_summary.status IN ('CANCELLED','REJECTED')";
// 		}
// 	}
// 	else{
// 		$status_sel = "AND appointment_summary.status IN ('CANCELLED','REJECTED')";
// 	}	
// }
// else{
// 	$status_sel = "AND appointment_summary.status IN ('CANCELLED','REJECTED')";
// }

$counts = array();
$count_reasons = array();
$categories = array();
$reasons = array();

$count = 0;

// $time_slots = array("09:00:00","10:00:00","11:00:00","12:00:00","13:00:00","14:00:00","15:00:00","16:00:00","17:00:00","18:00:00","19:00:00","20:00:00","21:00:00","22:00:00","23:00:00","24:00:00");

// $time_slots_str = array("9 - 10 AM","10 - 11 AM","11 - 12 AM","12 - 1 PM","1 - 2 PM","2 - 3 PM","3 - 4 PM","4 - 5 PM","5 - 6 PM","6 - 7 PM","7 - 8 PM","8 - 9 PM","9 10 PM","10 - 11 PM","11 - 12 PM");

$i = 0;

$sql1 = "SELECT CEILING(SUM(discount)) as 'total_discount' ,CEILING(SUM(rad)) as 'total_rad',CEILING(SUM(rbd)) as 'total_rbd', DATE_FORMAT(appointment_summary.booking_date,'" . $sql_ext_1 . "') as 'monday' 
FROM appointment_summary 
WHERE (appointment_summary.booking_date BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') 
GROUP BY DATE_FORMAT(appointment_summary.booking_date,'" . $sql_ext_2 . "');";

// $sql2 = "SELECT count(reason.reason) as 'count', reason.reason
// FROM appointment
// LEFT JOIN appointment_summary 
// ON appointment.appointment_id=appointment_summary.appointment_id 
// LEFT JOIN reason 
// ON reason.reason_id=appointment.reason_id  
// WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') 
// " .  $status_sel . " 
// AND reason.reason != ''
// GROUP BY reason.reason;";

// print_r($sql1);
// echo count($time_slots)-1;

// for($j=0; $j < count($time_slots)-1 ; $j++){

// 	$result1 = $con->query($sql1);

// 	while($row1 = $result1->fetch_assoc()){

// 		$start_time_diff = strtotime($row1["time"]) - strtotime($time_slots[$j]);
// 		$end_time_diff = strtotime($time_slots[$j+1]) - strtotime($row1["time"]);

// 		// echo strtotime($row1["time"]) - strtotime($time_slots[$j]) . " - " . strtotime($time_slots[$j+1]) - strtotime($row1["time"]) . "<br>";
// 		if( ( $start_time_diff > 0 && $start_time_diff <= 3600 ) && ( $end_time_diff >= 0 && $end_time_diff <= 3600 ) ){
// 			// echo $start_time_diff . " - " . $end_time_diff . "\n";
// 			// echo $row1["time"] . "\n";
// 			$count += $row1["count"];
// 			// echo $count . "\n";
// 		}
// 	}

// 	$counts[$i] = $count;
// 	$i++;
// 	$count = 0;

// 	// $hex = substr(str_shuffle('ABCDEF0123456789'), 0, 6);
//  //   	$rand_colors[$i] = '#' . $hex;
//    	// $rand_colors[$i] = hex2rgba($rand_colors[$i], 0.5);
// }

$dates = array();
$discounts = array();
$rads = array();

$total_discounts = 0;
$total_rad = 0;

$result1 = $con->query($sql1);

while($row1 = $result1->fetch_assoc()){

	$dates[$i] = $row1["monday"];
	$discounts[$i] = $row1["total_discount"];
	$rads[$i] = $row1["total_rad"];

	$total_discounts += $row1["total_discount"];
	$total_rad += $row1["total_rad"];

	$i++;

}

$i = 0;

// $result2 = $con->query($sql2);

// while($row2 = $result2->fetch_assoc()){

// 	$count_reasons[$i] = $row2["count"];
// 	$reasons[$i] = $row2["reason"];
// 	$i++;

// }


// $time_slots_str_copy = $time_slots_str;
// $counts_copy = $counts;

// // echo count($counts_copy);

// for($k = 0; $k < count($counts_copy); $k++){
// 	if($counts_copy[$k] == 0){
// 		array_splice($counts_copy, $k, 1);
// 		// print_r($counts_copy);
// 		array_splice($time_slots_str_copy, $k, 1);
// 		// print_r($time_slots_str_copy);
// 		$k--;
// 	}
// }

// $i = 0;
// $result2 = $con->query($sql2);

// while($row2 = $result2->fetch_assoc()){
// 	$count_franchises[$i] = $row2["count"];
// 	$franchises[$i] = $row2["franchise_name"];
// 	$i++;
// }

// $select_str = '<option value="" disabled selected>Categories</option>';

// for( $l = 0; $l < count($categories); $l++ ){
// 	$select_str .= '<option value="' . $categories[$l] . '">' . $categories[$l] . '</option>';
// }

// $status_str = '<option value="" disabled selected>Status</option>';
// $status_str .= '<option value="CANCELLED">Cancelled</option>';
// $status_str .= '<option value="REJECTED">Rejected</option>';
	
// print_r($counts);
// print_r($chart_nos);

$con->close();

$json[] = 	array( 
				array( 	'type'	=> 'bar',
						'data'	=>	array(
										'labels'	=>	$dates,
								        'datasets'	=>	array( 
								        		array(
									            'label'	=>	'Total Revenue After Discount',
									            'data'	=>	$rads,
									            'backgroundColor'	=>	"#34a3fa"
									        	),
									        	array(
									            'label'	=>	'Total Discount',
									            'data'	=>	$discounts,
									            'backgroundColor'	=>	"#ff365a"
									        	)  
								        	)
									),
									'options'	=>	array(
								        'scales'	=>	array(
								        	'xAxes'	=>	array( 
								            	array( 
								            	'stacked'	=> true,
								                'ticks'		=>	array(
								                    'beginAtZero'	=>	true
								                				)
								            	) 
								            ),
								            'yAxes'	=>	array( 
								            	array( 
								            	'stacked'	=> true,
								                'ticks'		=>	array(
								                    'beginAtZero'	=>	true
								                				)
								            	) 
								            )
								        )
								    )
				),
				array( 	'type'	=> 'doughnut',
						'data'	=>	array(
										'labels'	=>	array( 'Discount', 'Revenue'),
								        'datasets'	=>	array( array(
								            'data'	=>	array( $total_discounts, $total_rad),
								            'backgroundColor'	=>	array(
								                "#ff365a",
							                                               "#34a3fa",
							                                               "#ffba00",
							                                               "#00d481",
							                                               "#50e3c2",
							                                               "#9013fe",
							                                               "#8d4234",
							                                               "#6a6a81",
							                                               "#f8970c",
							                                               "#a18d2c",
							                                               "#dfdfdf",
							                                               "#f8970c",
							                                               "#aec9a2",
							                                               "#f9565c",
							                                               "#504616",
							                                               "#70621e",
							                                               "#d278ea",
							                                               "#d787ec",
							                                               "#dc96ee",
							                                               "#e1a5f1",
							                                               "#e6b4f3",
							                                               "#ebc3f5",
							                                               "#f0d2f8",
							                                               "#cd6ae8",
							                                               "#e86ac4",
							                                               "#8e6ae8",
							                                               "#f9565c",
							                                               "#f9a256",
							                                               "#f956ae",
							                                               "#f9565c",
							                                               "#5cf956",
							                                               "#565cf9"
								            )
								        ) )
									)
				),
				// array( $select_str ),
				// array( $status_str ),
				// array( 	'type'	=> 'horizontalBar',
				// 		'data'	=>	array(
				// 						'labels'	=>	$reasons,
				// 				        'datasets'	=>	array( array(
				// 				            'label'	=>	'Cancelled/Rejected Reasons',
				// 				            'data'	=>	$count_reasons,
				// 				            'backgroundColor'	=>	array(
				// 									                   "#ff365a",
				// 		                                               "#34a3fa",
				// 		                                               "#ffba00",
				// 		                                               "#00d481",
				// 		                                               "#50e3c2",
				// 		                                               "#9013fe",
				// 		                                               "#8d4234",
				// 		                                               "#6a6a81",
				// 		                                               "#f8970c",
				// 		                                               "#a18d2c",
				// 		                                               "#dfdfdf",
				// 		                                               "#f8970c",
				// 		                                               "#aec9a2",
				// 		                                               "#f9565c",
				// 		                                               "#504616",
				// 		                                               "#70621e",
				// 		                                               "#d278ea",
				// 		                                               "#d787ec",
				// 		                                               "#dc96ee",
				// 		                                               "#e1a5f1",
				// 		                                               "#e6b4f3",
				// 		                                               "#ebc3f5",
				// 		                                               "#f0d2f8",
				// 		                                               "#cd6ae8",
				// 		                                               "#e86ac4",
				// 		                                               "#8e6ae8",
				// 		                                               "#f9565c",
				// 		                                               "#f9a256",
				// 		                                               "#f956ae",
				// 		                                               "#f9565c",
				// 		                                               "#5cf956",
				// 		                                               "#565cf9"
				// 				            )
				// 				        ) )
				// 					),
				// 					'options'	=>	array(
				// 				        'scales'	=>	array(
				// 				            'xAxes'	=>	array( array( 
				// 				                'ticks'	=>	array(
				// 				                    'beginAtZero'	=>	true
				// 				                )
				// 				            ) )
				// 				        )
				// 				    )
				// ),
				// array( $count_reasons ),
				array( $diff ),
				array( $sql1 )

			);

$jsonstring = json_encode($json);
echo $jsonstring;

?>