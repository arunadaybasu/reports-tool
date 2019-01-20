<?php
include('dbinfo.php');

// function hex2rgba($color, $opacity = false) {
 
// 	$default = 'rgb(0,0,0)';
 
// 	//Return default if no color provided
// 	if(empty($color))
//           return $default; 
 
// 	//Sanitize $color if "#" is provided 
//         if ($color[0] == '#' ) {
//         	$color = substr( $color, 1 );
//         }
 
//         //Check if color has 6 or 3 characters and get values
//         if (strlen($color) == 6) {
//                 $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
//         } elseif ( strlen( $color ) == 3 ) {
//                 $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
//         } else {
//                 return $default;
//         }
 
//         //Convert hexadec to rgb
//         $rgb =  array_map('hexdec', $hex);
 
//         //Check if opacity is set(rgba or rgb)
//         if($opacity){
//         	if(abs($opacity) > 1)
//         		$opacity = 1.0;
//         	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
//         } else {
//         	$output = 'rgb('.implode(",",$rgb).')';
//         }
 
//         //Return rgb(a) color string
//         return $output;
// }

// print_r($_POST);

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
$franchises = array();
$count_franchises = array();

$count = 0;

$time_slots = array("09:00:00","10:00:00","11:00:00","12:00:00","13:00:00","14:00:00","15:00:00","16:00:00","17:00:00","18:00:00","19:00:00","20:00:00","21:00:00","22:00:00","23:00:00","24:00:00");

$time_slots_str = array("9 - 10 AM","10 - 11 AM","11 - 12 AM","12 - 1 PM","1 - 2 PM","2 - 3 PM","3 - 4 PM","4 - 5 PM","5 - 6 PM","6 - 7 PM","7 - 8 PM","8 - 9 PM","9 10 PM","10 - 11 PM","11 - 12 PM");

$i = 0;

$sql1 = "SELECT COUNT(DATE_FORMAT(appointment_date,'%T')) AS 'count', DATE_FORMAT(appointment_date,'%T') AS 'time' FROM appointment_summary WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') AND status='UNASSIGNED' " . $franchises_sel . " GROUP BY DATE_FORMAT(appointment_date,'%T');";

$sql2 = "SELECT COUNT(franchise_name) AS 'count', franchise_name FROM appointment_summary WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') AND status='UNASSIGNED' " . $franchises_sel . " GROUP BY franchise_name;";

// print_r($sql1);
// echo count($time_slots)-1;

for($j=0; $j < count($time_slots)-1 ; $j++){

	$result1 = $con->query($sql1);

	while($row1 = $result1->fetch_assoc()){

		$start_time_diff = strtotime($row1["time"]) - strtotime($time_slots[$j]);
		$end_time_diff = strtotime($time_slots[$j+1]) - strtotime($row1["time"]);

		// echo strtotime($row1["time"]) - strtotime($time_slots[$j]) . " - " . strtotime($time_slots[$j+1]) - strtotime($row1["time"]) . "<br>";
		if( ( $start_time_diff > 0 && $start_time_diff <= 3600 ) && ( $end_time_diff >= 0 && $end_time_diff <= 3600 ) ){
			// echo $start_time_diff . " - " . $end_time_diff . "\n";
			// echo $row1["time"] . "\n";
			$count += $row1["count"];
			// echo $count . "\n";
		}
	}

	$counts[$i] = $count;
	$i++;
	$count = 0;

	// $hex = substr(str_shuffle('ABCDEF0123456789'), 0, 6);
 //   	$rand_colors[$i] = '#' . $hex;
   	// $rand_colors[$i] = hex2rgba($rand_colors[$i], 0.5);
}


$time_slots_str_copy = $time_slots_str;
$counts_copy = $counts;

// echo count($counts_copy);

for($k = 0; $k < count($counts_copy); $k++){
	if($counts_copy[$k] == 0){
		array_splice($counts_copy, $k, 1);
		// print_r($counts_copy);
		array_splice($time_slots_str_copy, $k, 1);
		// print_r($time_slots_str_copy);
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

// $select_str .= '<option value="Franchises 1">Franchises 1</option>';
// $select_str .= '<option value="Franchises 2">Franchises 2</option>';
	
// print_r($counts);
// print_r($chart_nos);

$con->close();

$json[] = 	array( 
				array( 	'type'	=> 'bar',
						'data'	=>	array(
										'labels'	=>	$time_slots_str,
								        'datasets'	=>	array( array(
								            'label'	=>	'Unassigned Time Slots',
								            'data'	=>	$counts,
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
				array( $_POST["franchise"] )

			);

$jsonstring = json_encode($json);
echo $jsonstring;

?>