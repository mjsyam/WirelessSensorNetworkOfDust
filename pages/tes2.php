<?php
include ('../db/dbcon.php');
include ('session.php');

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Flot Charts</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
     
        <!-- bootstrap 3.0.2 -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="../css/datepicker.css" type="text/css">

        <link href="../css/morris/morris.css" rel="stylesheet" type="text/css" />
        
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
  <div id="line-example"></div>
  <br/>
  <input type="checkbox" id="activate" checked="checked"/> Toggle "Series B"


        <!-- jQuery 2.0.2 -->
        <script type="text/javascript" src="../js/plugins/jquery/2.0.2/jquery.min.js"></script>
       
        <script type="text/javascript" src="../js/plugins/jquery/1.8.3/jquery.min.js"> </script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <!-- AdminLTE for demo purposes -->
        <script src="../js/AdminLTE/demo.js" type="text/javascript"></script>
        <!-- FLOT CHARTS -->
        <script src="../js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
        <script src="../js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
        <script src="../js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
        <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
        <script src="../js/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>

        <script src="../js/plugins/flot/jquery.flot.time.js" type="text/javascript"></script>

        <script src="../js/plugins/flot/jquery.flot.axislabels.js" type="text/javascript"></script>

        <script src="../js/plugins/strftime/strftime.js" type="text/javascript"></script>

        <script src="../js/plugins/flot/jquery.flot.spline.js" type="text/javascript"></script>

        <script src="../js/plugins/flot/jquery.flot.tooltip.js" type="text/javascript"></script>   

        <script src="../js/bootstrap-datepicker.js" type="text/javascript"></script> 

        <script src="../js/plugins/morris/morris.min.js" type="text/javascript"></script>

        <script src="../js/raphael-min.js" type="text/javascript"></script>      

        <!-- Page script -->
       
        <script type="text/javascript">

function data(toggle, sens) {
  var ret = [
      { y: '2006', a: 100, b: 90 },
      { y: '2007', a: 75,  b: 65 },
      { y: '2008', a: 50,  b: 40 },
      { y: '2009', a: 75,  b: 65 },
      { y: '2010', a: 50,  b: 40 },
      { y: '2011', a: 75,  b: 65 },
      { y: '2012', a: 100, b: 90 }
    ];
  
  
  if(toggle == 1) {
   
    for(var i = 0; i < ret.length; i++)
      delete ret[i][sens];
  
  }
    
  return ret;
};

var morris = Morris.Line({
  element: 'line-example',
  data: data(),
  xkey: 'y',
  ykeys: ['a', 'b'],
  labels: ['Series A', 'Series B']
});

jQuery(function($) {
    $('#activate').on('change', function() {
      var isChecked = $(this).is(':checked');
      if(isChecked)
      {
         morris.setData(data(0, 'b'));
      } else {
         morris.setData(data(1, 'b'));
      }
    });
});
        </script>


    </body>

</html>
