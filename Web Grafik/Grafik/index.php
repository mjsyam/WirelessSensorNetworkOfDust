<html>
	<head>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/highcharts.js" type="text/javascript"></script>
<script type="text/javascript">
	var chart; // globally available
$(document).ready(function() {
      chart = new Highcharts.Chart({
         chart: {
            renderTo: 'container',
            type: 'column'
			//marginRight: 130,
            //marginBottom: 25
         },   
         title: {
            text: 'Grafik Data Sensor Debu '
         },
         xAxis: {
            categories: ['Concentration']
         },
         yAxis: {
            title: {
               text: 'Low Pulse Occupancy Time '
            }
         },
              series:             
            [
            <?php 
        	include('config.php');
           $sql   = "SELECT id  FROM data_sensor";
            $query = mysql_query( $sql )  or die(mysql_error());
            while( $ret = mysql_fetch_array( $query ) ){
            	$id=$ret['id'];                     
                 $sql_c   = "SELECT c FROM data_sensor WHERE id='$id'";        
                 $query_c = mysql_query( $sql_c ) or die(mysql_error());
                 while( $data = mysql_fetch_array( $query_c ) ){
                    $c = $data['c'];  
				 }
                  ?>
                  {
                      name: '<?php echo $id; ?>',
                      data: [<?php echo $c; ?>,]
                  },
                  <?php } ?>
            ]
      });
   });	
</script>
	</head>
	<body>
		<div id='container' style='min-width: 400px; height: 400px; margin: 0 auto'></div>		
	</body>
</html>

