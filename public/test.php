<?php 

// require_once __DIR__.'/../app/vendor/autoload.php';

// var_dump( getenv('TEMP') );
// var_dump( new Dotenv\Dotenv(__DIR__) );

// $product = '[{"pid":"5","price":"25","qty":11340,"unit":"bulk","total_price":"283500.00","discount":""},{"pid":"27","price":"21","qty":8100,"unit":"bulk","total_price":"170100.00","discount":""},{"pid":"20","price":"19","qty":10000,"unit":"bulk","total_price":"190000.00","discount":""}]';
// print_r( json_decode( $product ) );

$date = '27-04-1988';
$date_array = explode('/', $date);
$d = new DateTime($date);
// var_dump($d->format(DATE_W3C) );

// function genStaffCode( $id, $threshold = 1000 ){

// 	function multiples($value, $threshold, $floor = false)
// 	{
// 		$greater = $value / $threshold;
// 		if ($floor)
// 			return floor( $greater );
// 		return ceil( $greater );
// 	}

// 	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
// 	$alphas = str_split($chars);
// 	$limit = 26 * $threshold;	
// 	// $multiples = multiples( $id, $threshold );

// 	$limit_m = multiples( $id, $limit);
// 	// echo $limit_m;

// 	$prefix = '';
// 	$val = ($id % $limit);
// 	// echo $val;
// 	// $multiples = multiples( $val, $limit );

// 	// echo $multiples;
// 	// echo $alphas[ $multiples - 1];
// 	// $val = $id % $limit;

// 	for ($i=0; $i < $limit_m; $i++) { 
// 		// $val = $id % ($limit / ($threshold) * ($i + 1) );
// 		// $val = ($limit / ($i + 1) - $id);
// 		// echo $alphas[ $val];
// 		// echo $val;
// 		// $multiples = multiples( $id, ($threshold * ($i + 1)), true );
// 		$multiples = multiples( $id, $threshold, true );
// 		$prefix .= $alphas[ $multiples - 1];
// 		echo $multiples;
// 		$id = $id - $limit;
// 		// echo $id, '......<br>';
		

// 	}
// 	// echo $prefix;
// 	return;
// 	echo $id % $threshold;
// }

function genStaffCode( $id, $threshold = 10000 ){
	
		function multiples($value, $threshold, $floor = false)
		{
			$greater = $value / $threshold;
			if ($floor)
				return floor( $greater );
			return ceil( $greater );
		}
	
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$alphas = str_split($chars);
		// $limit = 26 * $threshold;
		$multiples = multiples( $id, $threshold );
		$val = $id % $threshold;
		$prefix = $alphas[ $multiples - 1 ];

		// if ($val == 0){
		// 	$val++;
		// 	$prefix = $alphas[ $multiples ];
		// 	// $multiples = multiples( $id + 2, $threshold );
		// }

		// $prefix = $alphas[ $multiples - 1 ];
	
		return $prefix.($val + 1);
	}

// echo genStaffCode(1);

// $date = "2017-09-23 15:40:30";
// $d = new DateTime($date);
// // var_dump(date('j/m/Y', $date));
// var_dump($d);

// $id = 'A1048577';
// preg_match('/[0-9]+/', $id, $match);

// // var_dump( $match);

// $d = '33%2C%20Igando%20road';
// echo basename(__DIR__); var_dump( pathinfo(__DIR__) );

$file = 'C:/WTServer/conf/test3.php';
// $file = __DIR__.'/test3.php';
// $content = file_get_contents(__DIR__.'/test.php');
// file_put_contents($file, $content);
var_dump($_SERVER);
echo dirname($_SERVER[''])



// Generate an md5 hash of the current time
		// This will be used to create sub folders for image upload on the server
		// In theory, it should help with file searching when dealing with a huge amount of files.

		


// var_dump(imagecreatefromstring($img_b));
// $key = \Defuse\Crypto\Key::createNewRandomKey();
// var_dump( $key->saveToAsciiSafeString() );

//allow remote access to this script, replace the * to your domain e.g http://www.example.com if you wish to recieve requests only from your server
// header("Access-Control-Allow-Origin: *");
//rebuild form data
// $postdata = http_build_query(
//     array(
//         'username' => 'abeebola@yahoo.com',
//         'password' => 'FeyM6DTyEAxkWRFk43r6',
//   'message' => 'This is a test message',
//   'mobiles' => '2348083845557',
//   'sender' => 'UFRANK Ltd',
//     )
// );
// //prepare a http post request
// $opts = array('http' =>
//     array(
//         'method'  => 'POST',
//         'header'  => 'Content-type: application/x-www-form-urlencoded',
//         'content' => $postdata
//     )
// );
// //craete a stream to communicate with betasms api
// $context  = stream_context_create($opts);
// //get result from communication
// $result = file_get_contents('http://login.betasms.com/customer/api/', false, $context);
// //return result to client, this will return the appropriate respond code
// echo $result;

// $arr = array(

// 	'404',
// 	'ajax',
// 	'clients',
// 	'dashboard',
// 	'login',
// 	'logout',
// 	'inventory',
// 	'invoice',
// 	'orders',
// 	'products',
// 	'profile',
// 	'purchases',
// 	'sales',
// 	'suppliers',
// 	'users'

// 	);

// var_dump( $_SERVER );

// function formatPhoneNumbers( $numbers )
// {
// 	$count = count( $numbers );
// 	for ($i=0; $i < $count; $i++) { 
		
// 		$num_arr = explode( '', $numbers[ $i ] );
// 		$numbers[$i] = $num_arr;
// 		if ( $num_arr[0] == '0' ) $num_arr[0] = '234';
// 		$numbers[ $i ] = implode( '', $num_arr );
// 	}

// 	return $numbers;
	
// 	return implode( ',', $numbers );
// }

function formatPhoneNumbers( $numbers )
{
	$count = count( $numbers );
	for ($i=0; $i < $count; $i++) { 

		$num = $numbers[ $i ];
		$len = strlen( $num );
		$first = substr($num, 0, 1);
		$remainder = substr($num, 1, ( $len - 1 ) );

		if ( $first == '0' ) $numbers[ $i ] = '234'.$remainder;
	}
	
	return implode( ',', $numbers );
}

// $numbers = ['08083845557', '07065512885', '09053004018', '23488223355'];

// var_dump( formatPhoneNumbers( $numbers ) );

// var_dump( substr('08083845557', 0, 1));

	// echo json_encode( $arr );

// $composer = file_get_contents( __DIR__.'/../app/controllers.json' );
// // $composer = preg_replace('/\s/', '', $composer);

// // var_dump( $composer );

// var_dump( json_decode( $composer, true ) );

// echo ( (int) '' );

// $arr = [
		
// 		'pid'			=> 11,
// 		'price'			=> 50,
// 		'qty'			=> 5000,
// 		'unit'			=> 'bulk',
// 		'total_price'	=> 250000,
// 		'discount'		=> '',

// 		];

// $arr2 = [
		
// 		'pid'			=> 19,
// 		'price'			=> 20,
// 		'qty'			=> 6000,
// 		'unit'			=> 'bulk',
// 		'total_price'	=> 120000,
// 		'discount'		=> '',

// 		];

// 		$product_data = [ $arr, $arr2 ];

// 		echo json_encode( $product_data );

?>
