@extends('attendant/attendantlayout')
@section('statsA','active')
@section('body')

<?php
  if (session()->exists('attendant')) {
    $data=session()->get('attendant');
}
$username=$data['username'];
$user=DB::table('users')->where('Username',$username)->first();
$userid=$user->User_ID;
$years=DB::select(" select Distinct Year from invoices where UsersID=?",[$userid]);
$salescount=[];
$salesmoney=[];
$months=["January","February","March","April","May","June","July","August","September","October","November","December"];
foreach ($years as $year ) {
  $myyear=$year->Year;
  foreach ($months as $month ) {
    $salescount[$myyear][$month]=DB::table('invoices')->WhereRaw('UsersID=? and Year=? and Month=?',[$userid,$myyear,$month])->count();
    $salesmoney[$myyear][$month]=DB::table('invoices')->WhereRaw('UsersID=? and Year=? and Month=?',[$userid,$myyear,$month])->sum('Totalcost');
  }
}


$dataPoints = array(
	array("x"=> 20, "y"=> 35),
	array("x"=> 30, "y"=> 50),
	array("x"=> 40, "y"=> 45),
	array("x"=> 50, "y"=> 52),
	array("x"=> 60, "y"=> 68),
	array("x"=> 70, "y"=> 38),
	array("x"=> 80, "y"=> 71),
	array("x"=> 90, "y"=> 52),
	array("x"=> 100, "y"=> 60),
	array("x"=> 110, "y"=> 36),
	array("x"=> 120, "y"=> 49),
	array("x"=> 130, "y"=> 41)
);
	
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Statistics</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            
          </div>
          
        </div>
      </div>

    <div class="container">
        <div class="row">
        <div id="chartContainer" style="width:100%;height:70%;"></div>

        </div>
    </div>
     
      
    </main>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "sales statistics"
	},
	data: [{
		type: "area", //change type to bar, line, area, pie, etc
		indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#5A5757",
		indexLabelPlacement: "outside",   
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>

@endsection
<!DOCTYPE HTML>
<html>
<head>  

</head>
<body>

</body>
</html>