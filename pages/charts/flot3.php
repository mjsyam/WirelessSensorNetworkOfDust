<?php $hostname = 'localhost';
$database = 'power_meter';
$username = 'root';
$password = '';

$connect = mysql_connect($hostname, $username, $password) or die ('Mysql cannot connect'. mysql_error());
$bool = mysql_select_db($database,$connect);

if($bool = false){
    echo "cannot connect database ORDER BY ID";
}
$query = "SELECT * FROM history ";
$result = mysql_query($query) or die ("SQL error:" . mysql_error());
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Flot Charts</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
     
        <!-- bootstrap 3.0.2 -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <link href="../../css/morris/morris.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
    <!--disini header dan navbar dan side bar-->

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) disini -->
                

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- interactive chart -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="fa fa-bar-chart-o"></i>
                                    <h3 class="box-title">Interactive Area Chart</h3>
                                    <div class="box-tools pull-right">
                                    <h5>Y Max:</h5>
                                    <input id="maxY"></input>
                                    <button id="update" type="button">update</button>
                                    </div>
                                    <div class="box-tools pull-right">
                                        Options
                                    <div class="btn-group" id="options" data-toggle="btn-toggle">
                                        <button type="button" class="btn btn-default btn-sm active" data-toggle="voltage">voltage</button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="watt">watt</button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="current">current</button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="energy">energy</button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="temperature">temperature</button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="humidity">humidity</button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="intensity">intensity</button>
                                    </div>
                                    </div>
                                    <div class="box-tools pull-right">
                                        Real time
                                        <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                                            <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
                                            <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div id="interactive" style="height: 300px;"></div>
                                </div><!-- /.box-body-->
                            </div><!-- /.box -->

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <!--row yg lain disini-->
                </section><!-- /.content -->

            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- jQuery 2.0.2 -->
        <script type="text/javascript" src="../../js/plugins/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../../js/AdminLTE/demo.js" type="text/javascript"></script>
        <!-- FLOT CHARTS -->
        <script src="../../js/plugins/morris/morris.min.js" type="text/javascript"></script>

        <script src="../../js/raphael-min.js" type="text/javascript"></script>

        <!-- Page script -->
        <script type="text/javascript">
        var clabel= 'voltage';
        var rlabel= 'volt';
        var cUrl = 'vdb.php';
         $("#options .btn").click(function(){
                    if ($(this).data("toggle") === "voltage"){
                        cUrl = 'vdb.php';
                        rlabel = 'volt';
                        update();
                    }
                    else if ($(this).data("toggle") === "watt"){
                        cUrl = 'wdb.php';
                        rlabel = 'watt';
                        update();
                    }
                    else if ($(this).data("toggle") === "temperature"){
                        cUrl = 'tdb.php';
                        rlabel = 'celcius';
                        update();
                    }
                    else if($(this).data("toggle") === "humidity"){
                        cUrl = 'hdb.php';
                        rlabel = '%'
                        update();
                    }
                    else if($(this).data("toggle") === "current"){
                        cUrl = 'cdb.php';
                        rlabel = 'ampere';
                        update();
                    }
                    else if($(this).data("toggle") === "energy"){
                        cUrl = 'edb.php';
                        rlabel = 'joule';
                        update();
                    }
                    else {
                        cUrl = 'idb.php';
                        rlabel = 'Cd';
                        update();
                    }
                });
        var tahun = '2014';
         var graph = new Morris.Line({
          // ID of the element in which to draw the chart.
          element: 'interactive',
         
          // Chart data records -- each entry in this array corresponds to a point on
          // the chart.
          data: [<?php while($row = mysql_fetch_object($result)): ?>
                {time:'<?php echo $row->datetime;?>', 
                data:<?php echo $row->voltage;?>},
                <?php endwhile; ?>
                ],

          // The name of the data record attribute that contains x-values.
            xkey: 'time',
          // A list of names of data record attributes that contain y-values.
            ykeys: ['data'],
          // Labels for the ykeys -- will be displayed when you hover over the
          // chart.
            labels: [rlabel],
            xLabels: "month",
            xLabelFormat: function(x){
                var IndextoMonth= [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des" ];
                var month = IndextoMonth[x.getMonth()];
                var year = x.getFullYear();
                return month;
            },
             dateFormat: function (x) {
                  var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des" ];
                  var month = IndexToMonth[ new Date(x).getMonth() ];
                  var year = new Date(x).getFullYear();
                  return month;
              },
            events: [tahun],
            lineColors: ['#00991A'],
            hideHover: 'auto',
            pointSize: 0
        });
        var realtime = "on";
        function update(){
           $.ajax({
                        // have to use synchronous here, else returns before data is fetched
                        async: false,
                        url: cUrl,
                        dataType:'json',
                        success: function (data) {
                        //graph.options.ykeys = [clabel];
                        graph.options.labels = [rlabel];
                        graph.setupGrid;
                        graph.setData(data);
                        //graph.setupGrid();
                       //graph.options.ykeys= clabel;
                        }
                    });
           
        };
        
        $("#realtime .btn").click(function(){
                if ($(this).data("toggle") === "on"){
                    realtime = "on";
                }
                else{
                    realtime = "off";
                }
                update();
        });
        if (realtime = "on")
                setInterval(update,1000);
               
            //     var options = {
            //       grid: {
            //             hoverable: true,
            //             borderColor: "#f3f3f3",
            //             borderWidth: 1,
            //             tickColor: "#f3f3f3"
            //         },
            //        series: {
            //             shadowSize: 0,
            //             lines: {
            //                 show: true
            //             },
            //             // curvedLines: {
            //             //     active: true
            //             //},
            //             points: {
            //                 show: true
            //             },
            //             label: clabel
            //         },
            //         lines: {
            //             fill: false,
            //             color: ["#3c8dbc", "#f56954"]
            //         },
                    
            //         yaxis: {
            //             min: 0,
            //             max: 300,
            //             axisLabel: clabel,
            //             show: true
            //         },
            //         xaxis: {
            //         mode: 'time',
            //         timeformat: '%Y/%m/%d %H:%M:%S',
            //         tickSize: [5, "minute"],
            //         // min: 0,
            //         // max: 300,
            //         show: true,
            //         axisLabel: 'time'
            //         },
            //     };

            //     $("<div class='tooltip-inner' id='line-chart-tooltip'></div>").css({
            //         position: "absolute",
            //         display: "none",
            //         opacity: 0.8
            //     }).appendTo("body");
            //       $("#interactive").bind("plothover", function(event, pos, item) {

            //         if (item) {
            //             var d = new Date(item.datapoint[0]), x = d.strftime('%Y/%m/%d %H:%M:%S'),
            //                 y = item.datapoint[1].toFixed(2);

            //             $("#line-chart-tooltip").html(item.series.label + " of " + x + " = " + y + " Volt")
            //                     .css({top: item.pageY + 5, left: item.pageX + 5})
            //                     .fadeIn(200);
            //         } else {
            //             $("#line-chart-tooltip").hide();
            //         }

            //     });
            //     var interactive_plot = $.plot("#interactive", [plotData], options);
            //     var maxY ;
            //     $('#maxY').change(function(){
            //             maxY = parseInt(this.value);
            //     });
                
            //     $('#update').click(function(){
            //             interactive_plot.getAxes().yaxis.options.max = maxY;
            //             update();
            //     });
            //     var updateInterval = 500; //Fetch data ever x milliseconds
            //     var realtime = "on"; //If == to on then fetch data every x seconds. else stop fetching
            //     function update() {
            //         jsonData();
            //         interactive_plot.setData([plotData]);
            //         interactive_plot.setupGrid();
            //         interactive_plot.getOptions().series.label = clabel;
            //         interactive_plot.getOptions().yaxes[0].axisLabel = clabel;
                    
            //         //interactive_plot.setSeries({label: clabel});
            //         // Since the axes don't change, we don't need to call plot.setupGrid()
            //         interactive_plot.draw();
                       
            //     }

            //     //INITIALIZE REALTIME DATA FETCHING
            //     if (realtime === "on") {
            //          setTimeout(update, updateInterval);
            //     }
            //     //REALTIME TOGGLE
            //     $("#realtime .btn").click(function() {
            //         if ($(this).data("toggle") === "on") {
            //             realtime = "on";
            //         }
            //         else {
            //             realtime = "off";
            //         }
            //         update();
            //     });
            //     /*
            //      * END INTERACTIVE CHART
            //      */

            // /*
            //  * Custom Label formatter
            //  * ----------------------
            //  */
            // // function labelFormatter(label, series) {
            // //     return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>"
            // //             + label
            // //             + "<br/>"
            // //             + Math.round(series.percent) + "%</div>";
            // // }
            
            
        
        </script>

    </body>

</html>
