 
<?php
///*
$servername = '127.0.0.1';
$username = 'bengalfishcom_user2020';
$password = ']v1AxO?Az~%q';
$dbname = 'bengalfishcom_main';
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");
//*/
$requestData= $_REQUEST;
$columns = array( 
// datatable column index  => database column name
	0 =>'orders_id', 
	1 => 'customers_name',
	2=> 'order_price',
	3=> 'date_purchased',
	4=> 'orders_id',
	5=> 'orders_id'
);

$data = array();

$sql="select * from orders ";
$query=mysqli_query($conn, $sql) ;
$totalData = mysqli_num_rows($query);
$totalFiltered=$totalData;

if( !empty($requestData['search']['value']) ) {
	$sql="select * from orders";
	 
	$sql.=" where  orders_id LIKE '%".$requestData['search']['value']."%' or customers_name LIKE '%".$requestData['search']['value']."%' or date_purchased LIKE '%".$requestData['search']['value']."%' or order_price LIKE '%".$requestData['search']['value']."%' or shipping_cost LIKE '%".$requestData['search']['value']."%' ";
	$query=mysqli_query($conn, $sql) ;
$totalFiltered = mysqli_num_rows($query);

	//$sql.=" where  orders_id =1";
	 $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
else{
	$sql="select * from orders ";
	 $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	 $query=mysqli_query($conn, $sql) ;	
	}
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array();
	$orders_id=$row["orders_id"];
	$customers_name=$row["customers_name"];
	$date_purchased=$row["date_purchased"];
	
	$order_price=$row["order_price"];
	$shipping_cost=$row["shipping_cost"]; 
	$OrderTotal=$order_price+$shipping_cost;
	
	$testql=mysqli_query($conn, "select  orders_status_id from orders_status_history where orders_id='$orders_id'  order by date_added DESC") ;
	
	$fe=mysqli_fetch_array($testql);
	$orders_status_id=$fe["orders_status_id"];
	
	$testql=mysqli_query($conn, "select  orders_status_name from orders_status_description where orders_status_id='$orders_status_id'") ;
	$fe=mysqli_fetch_array($testql);
	
	$orders_status_name=$fe["orders_status_name"];
	
	
	$nestedData[] ="$orders_id";//."( $sql )"
	$nestedData[] ="$customers_name";
	$nestedData[] ="$OrderTotal";
	$nestedData[] ="$date_purchased";
	$nestedData[] ="$orders_status_name";
	$nestedData[] ="<a href='https://bengalfish.com.bd/admin/orders/vieworderedit/"."$orders_id' target='_blank'> Update</a>";
	$data[] = $nestedData; 
}


$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
