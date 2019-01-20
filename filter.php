<?php
include('dbinfo.php');

// print_r($_POST);

$chart_heads = array();
$chart_nos = array();

$i = 0;

$sql1 = "SELECT status, COUNT(*) AS 'total_appointments' FROM appointment_summary WHERE (`booking_date` BETWEEN '" . $_POST['date-from_submit'] . " 00:00:00" . "' AND '" . $_POST['date-to_submit'] . " 00:00:00" ."') GROUP BY status;";
// print_r($sql1);
$result1 = $con->query($sql1);

while($row1 = $result1->fetch_assoc()){

	$chart_heads[$i] = '"' . $row1["status"] . '"';
	$chart_nos[$i] = $row1["total_appointments"];	
	
	$i++;
}

// print_r($chart_heads);
// print_r($chart_nos);

$con->close();

$json[] = array( 	'type'	=> 'bar',
					'data'	=>	array(
									'labels'	=>	$chart_heads,
							        'datasets'	=>	array( array(
							            'label'	=>	'# of Votes',
							            'data'	=>	$chart_nos,
							            'backgroundColor'	=>	array(
							                'rgba(255, 99, 132, 0.2)',
							                'rgba(54, 162, 235, 0.2)',
							                'rgba(255, 206, 86, 0.2)',
							                'rgba(75, 192, 192, 0.2)',
							                'rgba(153, 102, 255, 0.2)',
							                'rgba(255, 159, 64, 0.2)'
							            ),
							            'borderColor'	=>	array(
							                'rgba(255,99,132,1)',
							                'rgba(54, 162, 235, 1)',
							                'rgba(255, 206, 86, 1)',
							                'rgba(75, 192, 192, 1)',
							                'rgba(153, 102, 255, 1)',
							                'rgba(255, 159, 64, 1)'
							            ),
							            'borderWidth'	=>	1
							        ) )
								),
								'options'	=>	array(
							        'scales'	=>	array(
							            'yAxes'	=>	array( array( 
							                'ticks'	=>	array(
							                    'beginAtZero'	=>	true
							                )
							            ) )
							        )
							    )
					);

$jsonstring = json_encode($json);
echo $jsonstring;

?>