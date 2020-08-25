<!DOCTYPE html>
<html>
	<title>Orders Display</title>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
     <?php


	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    echo"<link rel='stylesheet' type='text/css' href='css/jquery.dataTables.css'>
		<script type='text/javascript' language='javascript' src='js/jquery.js'></script>
		<script type='text/javascript' language='javascript' src='js/jquery.dataTables.js'></script>
		
		<script type='text/javascript' language='javascript' >
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					'processing': true,
					'serverSide': true,
					'ajax':{
						url :'orders_display_ajax.php', // json datasource
						type: 'post',  // method  , by default get
						error: function(){  // error handling
							$('.employee-grid-error').html('');
							$('#employee-grid').append('<tbody class=employee-grid-error><tr><th colspan=3>No data found in the server</th></tr></tbody>');
							$('#employee-grid_processing').css('display','none');
							
						}
					}
				} );
			} );
		</script>
		
			<table id='employee-grid'  cellpadding='0' cellspacing='0' border='0' class='display' width='100%'>
					<thead>
						<tr>
                        	<th>Order Id</th>
							<th>Customer Name</th>
							<th>Order Total</th>
							<th>Date Purchased</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
			</table>
			
			
			";
            ?>
		
			
	</body>
</html>
