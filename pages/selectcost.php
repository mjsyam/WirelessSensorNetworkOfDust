<?php
include ('session.php');
$user = "root";
$pass = "";
$database = "power_meter";
$host = "localhost";

$con = mysql_connect($host, $user, $pass) or die ("ERROR".mysql_error());
$db = mysql_select_db($database, $con);
$query = "SELECT cost FROM selector";
$result = mysql_query($query);
$res = mysql_result($result, 0);

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
                        <li>
                            <a href="selectdate.php">
                                <i class="fa fa-table"></i> <span>Report</span>
                            </a>
                        </li>
                        <li class="active">
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
                        Update Cost
                        <small></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Update Cost</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                           <!-- /.box -->

                            <div class="box" style="height: 250px;">
                                <div class="box-header">
                                    <h3 class="box-title">Update your basic electricity fee</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <div class="form-group">
                                        Rp
                                   <input type="text" style="text-align:right;" name="cost" id="cost" onkeypress="return isNumberKey(event)" value="<?php echo $res;?>">
                                        per kWh
                                    </div>
                                   <div class="form-group">
                                   <button class="btn btn-primary" style="margin-left: 20px;" onclick="savePrice()">Submit</button>
                                   </div>
                                </div><!-- /.box-body -->
                                <div id="tes"></div>
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
       
        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }   

        function savePrice(){
             var cost = document.getElementById("cost").value;
              console.log(cost);

              $.ajax({
                type: "POST",
                url: "../db/selector.php",
                data: "cost="+cost,
                success: function(data){
                    if(cost!=false){
                    $("#tes").html("<div class='alert alert-success'><a class='close' data-dismiss='alert'>×</a><span>Basic electricity fee has been saved. <strong>Rp "+cost+"</strong> per kWh</span></div>");
                     console.log(data);}
                     else{
                    $("#tes").html("<div class='alert alert-warning'><a class='close' data-dismiss='alert'>×</a><span>'Please insert your new cost'</span></div>");
                     console.log(data);
                     }
                }
              })
           }
        </script>

    </body>
</html>
