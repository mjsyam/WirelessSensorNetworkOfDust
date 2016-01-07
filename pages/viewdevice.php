<?php
include ('session.php');
$user = "root";
$pass = "";
$database = "power_meter";
$host = "localhost";

$con = mysql_connect($host, $user, $pass) or die ("ERROR".mysql_error());
$db = mysql_select_db($database, $con);
$query = "SELECT * FROM device";
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
                        <li class="active">
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
                        Devices
                        <small></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">View Device</li>
                    </ol>
                </section>
                <div id="result"></div>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                           <!-- /.box -->
                            <div class="box">
                                <div class="box-body">
                                   <div class="row">
                                    <?php while($row = mysql_fetch_assoc($result)){ ?>
                                      <?php $image= $row["image"];?>
                                      <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">
                                          <img src="../img/<?php echo $image;?>" style="width:330px; height:250px;" alt=<?php echo $row["image"];?>>
                                          <div class="caption">
                                            <h3><?php echo $row["sensor_id"];?></h3>
                                            <p><?php echo $row["sensor_name"];?></p>
                                            <p><a href="edit.php?name=<?php echo $row["sensor_id"];?>" class="btn btn-success" role="button">Edit</a> <a href="#" class="btn btn-danger" onclick="del('<?php echo $row["sensor_id"];?>')" role="button">Delete</a></p>
                                          </div>
                                        </div>
                                      </div>
                                      <?php } ?>
                                    </div>
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
        <!-- AdminLTE App -->
        <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <!-- page script -->
        <script type="text/javascript">

        function signOut(){
          window.location.assign("signout.php");
         }
        
        function del(device){
            if(confirm("are you sure?")){
                $.ajax({
                    type: 'POST',
                    url: '../db/delete.php',
                    data: 'tes='+device,
                    success: function(data){
                         $("#result").html(data+"has been deleted"); 
              //adding class
                        $("#result").addClass("alert alert-success");
                        setTimeout(function(){location.reload()}, 2000);
                        
                    }

                })
            }
            return false;
        }
    
        // function edit(name){
        //     $.ajax({
        //         type: 'POST',
        //         url: 'edit.php',
        //         data: "name="+name,
        //         success: function(data){
        //             console.log(data);
        //         },
        //         error: function(xhr, ajaxOptions, thrownError){
        //             alert(thrownError);
        //         }
        //     });
        //}
        </script>

    </body>
</html>
