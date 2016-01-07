<html>
<body>
<?php
$user= 'root';
$pass= '';
$database= 'power_meter';
$host= 'localhost';

$con = mysql_connect($host, $user, $pass) or die (mysql_error());
$db = mysql_select_db($database);
//$query= "SELECT * FROM history WHERE datetime LIKE '2014-12-02 10%'";
$query= "SELECT * FROM history WHERE datetime LIKE '2015-01-02 16%'";
$result = mysql_query($query);

//while($row = mysql_fetch_assoc($result)){
?>
<!-- <input name="check[]" type="checkbox" id="checkk[]" value="<?php// echo $row['sensor_id'];?>" onchange='	validateCheckbox()'><?php //echo $row['sensor_id'];?>
<?php// }?>
 -->
<table border=1>
	<?php
while($row = mysql_fetch_assoc($result)){?>	
	<tr>
	<td>
		<?php echo $row['datetime'];?>
	</td>
	<td>
		<?php echo $row['watt'];?>
	</td>
	</tr>
<?php } ?>
<script>
     function validateCheckbox () {
        var checkboxes = document.getElementsByName('check[]'); //selected by name since OP wanted to select by name

         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].checked) {
             console.log(checkboxes[i].value);
              // found 1 checked checkbox
             }
         }
     }
</script>
</body>
</html>