<?php
include('dbinfo.php');

$date1 = new DateTime($_POST['date-from_submit']);
$date2 = new DateTime($_POST['date-to_submit']);

$diff = $date2->diff($date1)->format("%a");

if($diff <= 30){
	$sql_ext_1 = "%d";
	$sql_ext_2 = "%d";
	$dstart = date_format($date1,"d");
	$dend = date_format($date2,"d");
}
else if($diff <= 365){
	$sql_ext_1 = "%M";
	$sql_ext_2 = "%m";
	$dstart = date_format($date1,"m");
	$dend = date_format($date2,"m");
}
else if($diff > 365){
	$sql_ext_1 = "%Y";
	$sql_ext_2 = "%Y";
	$dstart = date_format($date1,"Y");
	$dend = date_format($date2,"Y");
}



if(isset($_POST["franchise"])){
	if( count($_POST["franchise"]) > 0){
		$fran_arr = $_POST["franchise"];
		array_splice($fran_arr, 0, 1);
		$franchises_sel = implode("','",$fran_arr);
		$franchises_sel = "AND franchise_name IN ('" . $franchises_sel . "')";
		if($franchises_sel == "AND franchise_name IN ('')"){
			$franchises_sel = '';
		}
	}
	else{
		$franchises_sel = '';	
	}	
}
else{
	$franchises_sel = '';	
}

$counts = array();
$counts1 = array();
$franchises = array();
$count_franchises = array();
$franchises_counts = array();
$franchises_slots = array();

$franchises_str = array();
$franchises_counts_arr = array();

$count = 0;

$colors = array(
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
           "#565cf9",
           "#ff365a",
		);



$time_slots = array("09:00:00","10:00:00","11:00:00","12:00:00","13:00:00","14:00:00","15:00:00","16:00:00","17:00:00","18:00:00","19:00:00","20:00:00","21:00:00","22:00:00","23:00:00","24:00:00");

$time_slots_str = array("9 - 10 AM","10 - 11 AM","11 - 12 AM","12 - 1 PM","1 - 2 PM","2 - 3 PM","3 - 4 PM","4 - 5 PM","5 - 6 PM","6 - 7 PM","7 - 8 PM","8 - 9 PM","9 - 10 PM","10 - 11 PM","11 - 12 PM");

$i = 0;

$sql1 = "SELECT COUNT(DATE_FORMAT(appointment_date,'%T')) AS 'count', DATE_FORMAT(appointment_date,'%T') AS 'time', franchise_name FROM appointment_summary WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') AND status='CANCELLED' " . $franchises_sel . " GROUP BY DATE_FORMAT(appointment_date,'%T'), franchise_name;";

$sql2 = "SELECT COUNT(franchise_name) AS 'count', franchise_name FROM appointment_summary WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') AND status='CANCELLED' " . $franchises_sel . " GROUP BY franchise_name;";

$sql3 = "SELECT COUNT(DATE_FORMAT(appointment_date,'" . $sql_ext_1 . "')) AS 'count', DATE_FORMAT(appointment_date,'" . $sql_ext_1 . "') AS 'time' FROM appointment_summary WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') AND status='CANCELLED' " . $franchises_sel . " GROUP BY DATE_FORMAT(appointment_date,'" . $sql_ext_2 . "');";

$dates = array();
$totalcounts = array();
$franchise_names = array();
$franchise_names_distinct = array();

$j = 0;

$last = -1;

$data = '';

$result3 = $con->query($sql3);

while($row3 = $result3->fetch_assoc()){

	// if (!in_array($row3["franchise_name"], $franchise_names)){
	// 	$franchise_names[$j] = $row3["franchise_name"];
	// 	$j++;
	// }

	// if (array_key_exists($row3["franchise_name"], $franchise_names_distinct)) {
		
	// 	if ( (int)($last+1) != (int)$row3["time"] ) {
	// 		$data .=  $last+1 . " == " . (int)$row3["time"] . "----";
	// 		for($i= $last; $i < (int)$row3["time"]; $i++){
	// 			$franchise_names_distinct[$row3["franchise_name"]] += array($i => 0);
	// 		}
	// 		$franchise_names_distinct[$row3["franchise_name"]] += array($row3["time"] => $row3["count"]);
	// 	}
	// 	else{
	// 		$franchise_names_distinct[$row3["franchise_name"]] += array($row3["time"] => $row3["count"]);
	// 	}
		
	// 	$j++;
	// }
	// else{
	// 	$franchise_names_distinct[$row3["franchise_name"]] = array($row3["time"] => $row3["count"]);
	// 	$j++;
	// }

	// $last = (int)$row3["time"];

	$dates[$i] = $row3["time"];
	$totalcounts[$i] = $row3["count"];

	$i++;

}

$i =0;
$j = 0;
$last = 0;

$franchise_names_distinct_new = array();

// foreach($franchise_names_distinct as $franchise_name_str => $franchise_name_arr) {

// 	foreach($franchise_name_arr as $franchise_time => $franchise_count) {

		

// 		// 	if (array_key_exists($franchise_name_str, $franchise_names_distinct_new)) {
// 		// 		if($last+1 == $franchise_time){
// 		// 			$franchise_names_distinct_new[$franchise_name_str] += array($franchise_time => $franchise_count);
// 		// 		}
// 		// 		else{
// 		// 			$franchise_names_distinct_new[$franchise_name_str] = array(($last+1) => 0);
// 		// 		}
// 		// 		// $j++;
// 		// 	}
// 		// 	else{
// 		// 		if($last+1 == $franchise_time){
// 		// 			$franchise_names_distinct_new[$franchise_name_str] = array($franchise_time => $franchise_count);
// 		// 		}
// 		// 		else{
// 		// 			$franchise_names_distinct_new[$franchise_name_str] = array(($last+1) => 0);
// 		// 		}
// 		// 		// $j++;
// 		// 	}

		

// 		// $last = $franchise_time;

// 		// if(current($franchise_time) != next($franchise_time)-1){
// 		// 	for($j = current($franchise_time); $j < next($franchise_time); $j++){
// 		// 		if (array_key_exists($franchise_time, $franchise_name_arr)) {
// 		// 			$franchise_names_distinct_new[$franchise_name_str] += array($j => 0);
// 		// 		}
// 		// 		else{

// 		// 		}
// 		// 	}
// 		// }

// 	}

// }

foreach($franchise_names_distinct as $franchise_name_str => $franchise_name_arr) {
	shuffle($colors);
	$rand_color_key = array_rand($colors);
	$rand_color = $colors[$rand_color_key];
	$franchise_arr_final[] = array(
							            'label'	=>	$franchise_name_str,
							            'data'	=>	array_values($franchise_name_arr),
							            'backgroundColor'	=>	$rand_color
							       		);
}

// for($i = 0; $i < count($franchise_names); $i++){
// 	$result3 = $con->query($sql3);
// 	while($row3 = $result3->fetch_assoc()){

// 		if (array_key_exists($row3["franchise_name"], $franchise_names_distinct)) {
// 			$franchise_names_distinct[$row3["franchise_name"]] += array($row3["time"] => $row3["count"]);
// 			$j++;
// 		}
// 		else{
// 			$franchise_names_distinct[$row3["franchise_name"]] = array($row3["time"] => $row3["count"]);
// 			$j++;
// 		}

// 	}
// }

// for($i = 0; $i < count($dates); $i++){
// 	shuffle($colors);
// 	$rand_color_key = array_rand($colors);
// 	$rand_color = $colors[$rand_color_key];
// 	$totalcounts_arr_final[] = array(
// 							            'label'	=>	$dates[$i],
// 							            'data'	=>	array_values($franchise_name_arr),
// 							            'backgroundColor'	=>	$rand_color
// 							       		);
// }

$i = 0;
$j = 0;

$result1 = $con->query($sql1);
while($row1 = $result1->fetch_assoc()){
	if (!in_array($row1["franchise_name"], $franchises_str)){
		$franchises_str[$i] = $row1["franchise_name"];
		$i++;
	}
}

for( $i = 0; $i < count($franchises_str); $i++ ){
	$result1 = $con->query($sql1);
	while($row1 = $result1->fetch_assoc()){
		if($franchises_str[$i] == $row1["franchise_name"]){
			if (array_key_exists($franchises_str[$i], $franchises_counts_arr)) {
				$franchises_counts_arr[$franchises_str[$i]] += array($row1["time"] => $row1["count"]);
			}
			else{
				$franchises_counts_arr[$franchises_str[$i]] = array($row1["time"] => $row1["count"]);
			}
		}
	}
}

$i = 0;

for($j=0; $j < count($time_slots)-1 ; $j++){

	$result1 = $con->query($sql1);

	while($row1 = $result1->fetch_assoc()){

		$start_time_diff = strtotime($row1["time"]) - strtotime($time_slots[$j]);
		$end_time_diff = strtotime($time_slots[$j+1]) - strtotime($row1["time"]);

		if( ( $start_time_diff > 0 && $start_time_diff <= 3600 ) && ( $end_time_diff >= 0 && $end_time_diff <= 3600 ) ){
			$count += $row1["count"];
		}
	}

	$counts[$i] = $count;
	$i++;
	$count = 0;
}

$count = 0;
$i = 0;
$counts_franchises = array();

foreach($franchises_counts_arr as $franchise_name_str => $franchise_name_arr) {

    for($j=0; $j < count($time_slots)-1 ; $j++){

		foreach($franchise_name_arr as $franchise_name_arr_time => $franchise_name_arr_count) {

			$start_time_diff = strtotime($franchise_name_arr_time) - strtotime($time_slots[$j]);
			$end_time_diff = strtotime($time_slots[$j+1]) - strtotime($franchise_name_arr_time);

			if( ( $start_time_diff > 0 && $start_time_diff <= 3600 ) && ( $end_time_diff >= 0 && $end_time_diff <= 3600 ) ){
				
				$count += $franchise_name_arr_count;
				
			}
		}

		if (array_key_exists($franchise_name_str, $counts_franchises)) {
			$counts_franchises[$franchise_name_str] += array($i => $count);
			$i++;
		}
		else{
			$counts_franchises[$franchise_name_str] = array($i => $count);
			$i++;
		}
		
		$count = 0;

	}
}

$counts_arr_final = array();

foreach($counts_franchises as $franchise_name_str => $franchise_name_arr) {
	shuffle($colors);
	$rand_color_key = array_rand($colors);
	$rand_color = $colors[$rand_color_key];
	$counts_arr_final[] = array(
							            'label'	=>	$franchise_name_str,
							            'data'	=>	array_values($franchise_name_arr),
							            'backgroundColor'	=>	$rand_color
							       		);
}



$time_slots_str_copy = $time_slots_str;
$counts_copy = $counts;

for($k = 0; $k < count($counts_copy); $k++){
	if($counts_copy[$k] == 0){
		array_splice($counts_copy, $k, 1);
		array_splice($time_slots_str_copy, $k, 1);
		$k--;
	}
}

$i = 0;
$result2 = $con->query($sql2);

while($row2 = $result2->fetch_assoc()){
	$count_franchises[$i] = $row2["count"];
	$franchises[$i] = $row2["franchise_name"];
	$i++;
}

$select_str = '<option value="" disabled selected>Franchises</option>';

for( $l = 0; $l < count($franchises); $l++ ){
	$select_str .= '<option value="' . $franchises[$l] . '">' . $franchises[$l] . '</option>';
}

$con->close();

$json[] = 	array( 
				array( 	'type'	=> 'bar',
						'data'	=>	array(
										'labels'	=>	$time_slots_str,
								        'datasets'	=>	$counts_arr_final
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
										'labels'	=>	$time_slots_str_copy,
								        'datasets'	=>	array( array(
								            'data'	=>	$counts_copy,
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
				array( 	'type'	=> 'bar',
						'data'	=>	array(
										'labels'	=>	$franchises,
								        'datasets'	=>	array( array(
								            'label'	=>	'Highest Unassigned Franchises',
								            'data'	=>	$count_franchises,
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
				array( $select_str ),
				array( 	'type'	=> 'bar',
						'data'	=>	array(
										'labels'	=>	$dates,
								        'datasets'	=>	array( array(
								            'label'	=>	'Unassigned Appointments (Daily/Monthly/Yearly)',
								            'data'	=>	$totalcounts,
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
				// array( 	'type'	=> 'bar',
				// 		'data'	=>	array(
				// 						'labels'	=>	$time_slots_str,
				// 				        'datasets'	=>	$counts_arr_final
				// 					),
				// 					'options'	=>	array(
				// 				        'scales'	=>	array(
				// 				        	'xAxes'	=>	array( 
				// 				            	array( 
				// 				            	'stacked'	=> true,
				// 				                'ticks'		=>	array(
				// 				                    'beginAtZero'	=>	true
				// 				                				)
				// 				            	) 
				// 				            ),
				// 				            'yAxes'	=>	array( 
				// 				            	array( 
				// 				            	'stacked'	=> true,
				// 				                'ticks'		=>	array(
				// 				                    'beginAtZero'	=>	true
				// 				                				)
				// 				            	) 
				// 				            )
				// 				        )
				// 				    )
				// ),
				array( $sql1 ),
				array( $sql3 )

			);

$jsonstring = json_encode($json);
echo $jsonstring;

?>