<?php
include ('../../db/dbcon.php');
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

        <link rel="stylesheet" href="../../css/datepicker.css" type="text/css">

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
                                    <h3 class="box-title">Realtime Chart</h3>
                                    Node:
                                    <div id="sensor_list">
                                    <select name="sensor" id="nodesensor" onchange="getSensor(this.value)">
                                        <?php while($row= mysql_fetch_array($result)){?>
                                        <option value= "<?php echo $row['sensor_id']?>"><?php echo $row['sensor_id']?></option>
                                        <?php } ?>
                                    </select>
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
                    <div class="row">
                        <div class="col-ms-12">
                            <div class= "box box-primary">
                                <div class= "box-header">
                                    <div class= "box-tools pull-right">
                                         <select id ="optionday" onchange="getOptionDay(this.value)">
                                            <option>voltage</option>
                                            <option>watt</option>
                                            <option>temperature</option>
                                            <option>humidity</option>
                                            <option>energy</option>
                                            <option>intensity</option>
                                            <option>current</option>
                                        </select>
                                    </div>
                                    <div>
                                    Date:
                                    <input id="datepicker" name="date" data-date-format="yyyy-mm-dd">
                                    <button onclick="getDt()">update</button>
                                    <span class="add-on">
                                        <i class="icon-calendar"></i>
                                    </span>
                                    </div>
                                </div>
                                <div class= "box-body">
                                    <div id= "daychart" style= "height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                           <div class="col-xs-12">
                             <div class= "box box-primary">
                                <div class= "box-header">
                                    <div class= "box-tools pull-right">
                                        <select id="yearoption" onchange="getOptionYear(this.value)">
                                            <option>voltage</option>
                                            <option>watt</option>
                                            <option>temperature</option>
                                            <option>humidity</option>
                                            <option>energy</option>
                                            <option>intensity</option>
                                            <option>current</option>
                                        </select>
                                    </div>
                                    <div>
                                    Select Year:                                    
                                            <select id="year" name="year" onchange="getYearJ()"></select>
                                    </div>  
                                </div>
                                <div class= "box-body">
                                    <div id= "monthchart" style= "height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--row yg lain disini-->
                </section><!-- /.content -->

            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- jQuery 2.0.2 -->
        <script type="text/javascript" src="../../js/plugins/jquery/2.0.2/jquery.min.js"></script>
       
        <script type="text/javascript" src="../../js/plugins/jquery/1.8.3/jquery.min.js"> </script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../../js/AdminLTE/demo.js" type="text/javascript"></script>
        <!-- FLOT CHARTS -->
        <script src="../../js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
        <script src="../../js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
        <script src="../../js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
        <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
        <script src="../../js/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>

        <script src="../../js/plugins/flot/jquery.flot.time.js" type="text/javascript"></script>

        <script src="../../js/plugins/flot/jquery.flot.axislabels.js" type="text/javascript"></script>

        <script src="../../js/plugins/strftime/strftime.js" type="text/javascript"></script>

        <script src="../../js/plugins/flot/jquery.flot.tooltip.js" type="text/javascript"></script>   

        <script src="../../js/bootstrap-datepicker.js" type="text/javascript"></script> 

        <script src="../../js/plugins/morris/morris.min.js" type="text/javascript"></script>

        <script src="../../js/raphael-min.js" type="text/javascript"></script>      

        <!-- Page script -->
       
        <script type="text/javascript">
              //penting 
              //*************************************year box*****************************
              var start = 2007;
              var end = new Date().getFullYear();
              var options = "";
              for (var year = start; year <= end; year++) {
                options += "<option>"+year+"</year>";
              }
              document.getElementById("year").innerHTML = options;
              
        // var coba = document.getElementById("year").value;
        // console.log(coba);
            
                $(function() {
                     $( "#datepicker" ).datepicker();
                    });
                // document.getElementById("datepicker").value= "2014-09-30";
                // var datett =  document.getElementById("datepicker").value;
                
               var bar= "409f40de";
               function getSensor(nilai){
                        $.ajax({
                            type: 'POST',
                            url: '../../db/node.php',
                            data: "sensor="+nilai,
                            success: function(data) {
                                getDt();
                                setYearJ();
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                            alert(thrownError);
                            }
                        });
                    };
           
        
                var arr;           
                var clabel="voltage";
                var cUrl = "../../db/vdb.php";
                   $.ajax({
                        // have to use synchronous here, else returns before data is fetched
                        async: false,
                        url: cUrl,
                        dataType:'json',
                        success: function (data) {
                         arr=data;
                        }
                    });

                var plotData;
                $("#options .btn").click(function(){
                    if ($(this).data("toggle") === "voltage"){
                        clabel = 'voltage';
                        cUrl = "db/vdb.php";
                        update();
                    }
                    else if ($(this).data("toggle") === "watt"){
                        clabel = 'watt';
                        cUrl = "wdb.php";
                        update(); 
                    }
                    else if ($(this).data("toggle") === "temperature"){
                        clabel = 'temperature';
                        cUrl = "tdb.php";
                        update();
                    }
                    else if($(this).data("toggle") === "humidity"){
                        clabel = 'humidity';
                        cUrl = "hdb.php";
                        update();
                    }
                    else if($(this).data("toggle") === "current"){
                        clabel = 'current';
                        cUrl = "cdb.php";
                        update();
                    }
                    else if($(this).data("toggle") === "energy"){
                        clabel = 'energy';
                        cUrl = "edb.php";
                        update();
                    }
                    else {
                        clabel = 'intensity';
                        cUrl = "idb.php";
                        update();
                        }
                });

                var options = {

                  grid: {
                        hoverable: true,
                        borderColor: "#f3f3f3",
                        borderWidth: 1,
                        tickColor: "#f3f3f3"
                    },
                   series: {
                        shadowSize: 0,
                        lines: {
                            show: true,
                            lineWidth: 3
                        },
                        points: {
                            show: true
                        },
                        label: clabel
                    },
                    
                    lines: {
                        fill: false,
                        color: ["#3c8dbc"]
                    },
                    
                    yaxis: {
                        axisLabel: clabel,
                        show: true
                    },
                    xaxis: {
                    mode: 'time',
                    timeformat: '%Y-%m-%d<br>%H:%M:%S',
                    tickSize: [1, "minute"],
                    axisLabel: 'time'
                    },
                    tooltip: true,
                    tooltipOpts:{
                        content: '%x, %s = %y'
                    }

                }
                var interactive_plot = $.plot("#interactive", [arr], options);
                var updateInterval = 500; //Fetch data ever x milliseconds
                var realtime = "on";
                $("#realtime .btn").click(function() {
                    if ($(this).data("toggle") === "on") {
                        realtime = "on";
                        update();
                    }
                    else {
                        realtime = "off";
                    }
                    
                });
             //If == to on then fetch data every x seconds. else stop fetching
                function update() {

                    if (realtime === "on") {
                      $.ajax({
                        // have to use synchronous here, else returns before data is fetched
                        async: false,
                        url: cUrl,
                        dataType:'json',
                        success: function (data) {
                    interactive_plot.draw();
                    interactive_plot.setupGrid();
                    interactive_plot.getOptions().series.label = clabel;
                    interactive_plot.getOptions().yaxes[0].axisLabel = clabel;
                    interactive_plot.setData([data]);
                        }
                    }); 
                  }

                };
                 
                 if (realtime === "on") {
                      setInterval(update,500);
                  };

                //INITIALIZE REALTIME DATA FETCHING
               
                //REALTIME TOGGLE
                /*
                 * END INTERACTIVE CHART
                 */
             
             
             /***************************************************************************
             DAY CHART
             ****************************************************************************/



            var dayData;
            var events = '2014-10-10';
            function getOptionDay(nilai){
                $.ajax({
                    type: 'POST',
                    url: 'selector.php',
                    data: 'optionday='+nilai,
                    success: function(){
                        getDt();
                    }
                })
             }
             $.ajax({
                async: false,
                dataType: 'json',
                url: 'dayChartdb.php',
                success: function(data){
                    dayData = data;
                }

             });
                var graph = new Morris.Line({
              // ID of the element in which to draw the chart.
                element: 'daychart',
              // Chart data records -- each entry in this array corresponds to a point on
              // the chart.
                data: dayData,

              // The name of the data record attribute that contains x-values.
                xkey: 'datetime',
              // A list of names of data record attributes that contain y-values.
                ykeys: ['data'],
              // Labels for the ykeys -- will be displayed when you hover over the
              // chart.
              events: ['2014-10-10'],
                labels: ['coba'],
                xLabels: "hours",
                xLabelFormat: function(x){
                    var IndextoHour= [ "00","01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13",
                                        "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"];
                    var hour = IndextoHour[x.getHours()];
                    return hour;
                },
                //  dateFormat: function (x) {
                //       var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des" ];
                //       var month = IndexToMonth[ new Date(x).getMonth() ];
                //       var year = new Date(x).getFullYear();
                //       return month;
                //   },
                //events: [events],
                lineColors: ['#00991A'],
                hideHover: 'auto',
                pointSize: 0,
                lineWidth: 1,
               // resize: true
            });
            
            function getDt(){
                    datett= String(document.getElementById("datepicker").value);
                    $.ajax({
                        type: 'POST',
                        url: 'selector.php',
                        data: "day="+datett,
                        success: function() {
                              $.ajax({
                        async: false,
                        dataType: 'json',
                        url: 'dayChartdb.php',
                        success: function(data){
                            graph.setData(data);
                            //graph.options.events = [datett];
                        }
                    })
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                        alert(thrownError);
                        }
                    })
                }
    /****************************************

    YEAR CHART


    *****************************************/
            var tahun = '2014';
            var yearData;
            function getOptionYear(nilai){
                $.ajax({
                    type: 'POST',
                    url: 'selector.php',
                    data: 'optionyear='+nilai,
                    success: function(){
                        getYearJ();
                    }

                })
             }
            var eyear= '2010';
                 $.ajax({
                async: false,
                dataType: 'json',
                url: 'yearChartdb.php',
                success: function(data){
                    yearData = data;
                }
             });

                //graf.options.events("2014");
              var graf = new Morris.Line({
              // ID of the element in which to draw the chart.
              element: 'monthchart',
             
              // Chart data records -- each entry in this array corresponds to a point on
              // the chart.
              data: yearData,

              // The name of the data record attribute that contains x-values.
                xkey: 'datetime',
              // A list of names of data record attributes that contain y-values.
                ykeys: ['data'],
              // Labels for the ykeys -- will be displayed when you hover over the
              // chart.
                labels: ['coba'],
                xLabels: "month",
                xLabelFormat: function(x){
                    var IndextoMonth= ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des"];
                    var month = IndextoMonth[x.getMonth()];
                    var year = x.getFullYear();
                    return month;
                },
                // dateFormat: function(x) {
                //     var IndexToMonth= ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des"];
                //     var month = IndexToMonth[ new Date(x).getMonth() ];
                //     var year = new Date(x).getFullYear();
                //     return month;
                //   },
                //events: [eyear],
                lineColors: ['#00991A'],
                hideHover: 'auto',
                pointSize: 1,
                resize: true
            });
            var yearJ;
             function getYearJ(){
                yearJ = document.getElementById("year").value;
                        $.ajax({
                            type: 'POST',
                            url: 'selector.php',
                            data: "year="+yearJ,
                            success: function(){
                                setYearJ();
                                //graf.options.events = [yearJ];
                                // $.ajax({
                                //     async: false,
                                //     dataType: 'json',
                                //     url: 'yearChartdb.php',
                                //     success: function(data){
                                //         graf.setData(data);
                                //         //graf.options.events = [data];
                                //     }
                                // })
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                            alert(thrownError);
                            }
                        });
                    };

                function setYearJ(){
                       $.ajax({
                                    async: false,
                                    dataType: 'json',
                                    url: 'yearChartdb.php',
                                    success: function(data){
                                        graf.setData(data);
                                    }
                                });
                   }
        </script>

    </body>

</html>
