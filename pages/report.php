<?php
include ('session.php');
$user = "root";
$pass = "";
$database = "power_meter";
$host = "localhost";
$month = $_POST['month'];
$year = $_POST['year'];
$date = $year."-".$month;

$con = mysql_connect($host, $user, $pass) or die ("ERROR".mysql_error());
$db = mysql_select_db($database, $con);
$query = "SELECT * FROM kwhmonth WHERE datetime LIKE '$date%'";
$result = mysql_query($query);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mougy</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less --> 
        <?php include('header.php');?>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="../img/<?php echo $imagess;?>" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                          <p>Hello, <?php echo $usern;?></p>
                        </div>
                    </div>
                    <!-- search form -->
                   <!-- <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>-->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li>
                            <a href="dashboard.php">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="flotv1,1.php">
                                <i class="fa fa-bar-chart-o"></i>
                                <span>Monitoring</span>
                            </a>
                        </li>
                        <li>
                            <a href="viewdevice.php">
                                <i class="fa fa-laptop"></i>
                                <span>View Device</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="selectdate.php">
                                <i class="fa fa-table"></i> <span>Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="selectcost.php">
                                <i class="fa fa-dollar"></i> <span>Update Cost</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Report
                        <small>KWH and Cost</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                         <li><a href="selectdate.php"> Select Date</a></li>
                        <li class="active">Report</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                           <!-- /.box -->

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">KWH Report</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Node</th>
                                                <th>Date</th>
                                                <th>KWH</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $totalh = 0;
                                                $totalkw = 0;
                                                $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
                                            	while($row = mysql_fetch_array($result)){
                                                $tes = strtotime($row['datetime']);
                                                $ddt = date('m', $tes);
                                                $realdate = Intval($ddt);
                                                $totalkw = $totalkw+$row['kwh'];
                                                $totalh = $totalh+$row['price'];

                                            ?>
                                            <tr>
                                            	<td><?php echo $row['sensor_id'];?></td>
                                            	<td><?php echo $month[$realdate-1]." ".date('Y', $tes);?></td>
                                            	<td><?php echo $row['kwh'];?></td>
                                            	<td><?php echo "Rp ".round($row['price'],2);?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>TOTAL</th>
                                                <th><?php echo $totalkw;?></th>
                                                <th><?php echo "Rp ".round($totalh, 2);?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->


        <!-- jQuery 2.0.2 -->
        <script type="text/javascript" src="../js/plugins/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT -->
        <script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <!-- page script -->
        <script type="text/javascript">
            $(function() {
                $("#example1").dataTable();

            });
        function signOut(){
          window.location.assign("signout.php");
         }
        </script>

    </body>
</html>
