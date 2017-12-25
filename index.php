<?php
	session_start();

	include "functions.php";
	include "wuapi_caller.php";

	if( !isset( $_SESSION['block_count'] ) ){
		$_SESSION['block_count'] = 1;
	}

	$delete_index = "-1";
	$run = false;
	//turn input to item array
	$array = filter_input_array(INPUT_POST);
	$item = array();

	if( !empty( $array['item'] ) ){
		foreach ( array_keys( $array['item'] ) as $fieldKey ) {
			foreach ( array_keys( $array['item'][$fieldKey] ) as $index ) {
				$item[$index][$fieldKey] = $array['item'][$fieldKey][$index];
			}
		}
	}

	if( !empty( $_POST['block'] ) ){
		if( $_POST['block'] == "add" ){
		$_SESSION['block_count'] += 1;
		}
	}
	if( isset( $_POST['delete_index'] ) ){
		$_SESSION['block_count'] = ( $_SESSION['block_count'] > 0 )? $_SESSION['block_count'] - 1 : 0;
		$delete_index = $_POST['delete_index'];
		//delete
		delete_in_array( $item, $delete_index );
	}

	if( isset ( $_POST['run'] ) ){
		$run = true;
		//check for non full inputed blocks
		$result = check_array( $item );
	}

	$_POST['block'] = "";
	$_POST['delete_index'] = "";

	echo '
		<!DOCTYPE html>
		<html>
			<head>
				<style>
					table {
						border-collapse: collapse;
					}
					table, th, td {
						border: 1px solid black;
					}
				</style>
			</head>
			<body>
	';
	if( $run && !$result[0] ){
		$message = $result[1];
		$output_message = "The following input is not filled\\n";
		foreach( $message as $info ){
			$output_message = $output_message . $info . "\\n";
		}
		echo '
				<script>
					alert( "' . $output_message . '" );
				</script>
		';
	}
	echo '
				<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
					<table>
	';

	for( $i = 0; $i < $_SESSION['block_count'] ; $i++ ){
		echo'
						<tr>
							<td>
								' . ( $i + 1 ) . '
							</td>
							<td>
                                <input type="date" name="item[date_start][]" placeholder="departure date"';
                                    if ( isset( $item[$i]['date_start'] ) ){
                                        echo ' value="' . $item[$i]['date_start'] . '"';
                                    }
                                echo '>
								<input type="text" name="item[country_start][]" placeholder="departure country"';
									if ( isset( $item[$i]['country_start'] ) ){
										echo ' value="' . $item[$i]['country_start'] . '"';
									}
								echo '>
								<input type="text" name="item[city_start][]" placeholder="departure city"';
									if ( isset( $item[$i]['city_start'] ) ){
										echo ' value="' . $item[$i]['city_start'] . '"';
									}
								echo '>
								<br>
                                <input type="date" name="item[date_end][]" placeholder="arrival date"';
                                    if ( isset( $item[$i]['date_end'] ) ){
                                        echo ' value="' . $item[$i]['date_end'] . '"';
                                    }
                                echo '>
								<input type="text" name="item[country_end][]" placeholder="arrival country"';
									if ( isset( $item[$i]['country_end'] ) ){
										echo ' value="' . $item[$i]['country_end'] . '"';
									}
								echo '>
								<input type="text" name="item[city_end][]" placeholder="arrival city"';
									if ( isset( $item[$i]['city_end'] ) ){
										echo ' value="' . $item[$i]['city_end'] . '"';
									}
								echo '>
							</td>
							<td>
								<button type="submit" name="delete_index" value="' . $i . '">Delete this schedule</button>
							</td>
						</tr>
		';
	}
	echo'
						<tr>
							<td>
								<input type="submit" name="block" value="add"/>
								<input type="submit" name="run" value="run"/>
							</td>
							<td>
								total blocks : ' . $_SESSION['block_count'] . ';  just deleted : ' . ( $delete_index + 1 ) . '
							</td>
						</tr>
					</table>
				</form>
			</body>
		</html>
	';
?>
