<?php  
session_start();
if(isset($_SESSION['file'])){
	$model = "";
	$nama = "./image/".$_SESSION['file'];


	$read = exif_read_data($nama);	


if(isset($read['Model'])){
	$model .= (string) $read['Model'];	
}else{
	$model .= "Hacker";
}

$height = $read['COMPUTED']['Height'];
$width = $read['COMPUTED']['Width'];
$epoch = date('Y-M-D H:i:s',$read['FileDateTime']);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Render</title>
</head>
<body>
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css"/>
<div class="container">
  <div class="well">
      <div class="media">
      	<a class="pull-left" href="#">
    		<img class="media-object" src="<?= $nama ?>" style="width:400px; height:400px">
  		</a>
  		<div class="media-body">
    		<h4 class="media-heading"> Hey <?php echo(eval("print '$model';")); ?></h4>
          <p class="text-right">By Nepska</p>
          <p>Challenge or just challenge. Do it with fun and full happines. The image have height = <?= $height ?> have width = <?=$width?>  </p>
          <ul class="list-inline list-unstyled">
  			<li><span><i class="glyphicon glyphicon-calendar"></i> <?=$epoch?> </span></li>
            
       </div>
    </div>
  </div>
  </div>
</div>
</body>
</html>