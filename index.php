<?php 
session_start();
if(isset($_POST['go'])){
	if(!empty($_FILES['flag']['type'])){
		$dir = './image/';
		$allowed = array('jpg', 'jpeg');
		$path = pathinfo($_FILES['flag']['name']);
		$ext = strtolower($path['extension']);
		
		//verify ext
		if(!in_array($ext, $allowed)){
			die('Just allowed jpg/jpeg');
		}

		//verify size
		if($_FILES['flag']['size'] > 2000000){
			die('Sorry just allowed less than 2 Mb');
		}

		//verify image
		$data = getimagesize(realpath($_FILES['flag']['tmp_name']));
        $width = $data[0];
        $height = $data[1];
        if(empty($width) || empty($height)){
        	die('Please upload image!');
        }

        if($data !== false){
        	if(!move_uploaded_file($_FILES['flag']['tmp_name'], $dir.$_FILES['flag']['name'])){
        		die('Error contact admin for patching. ');
        	}else{
        		$_SESSION['file'] = $_FILES['flag']['name'];
        		header("location: render.php");exit;
        	}
        }

        #die('Allowed');
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<style type="text/css">
	/* layout.css Style */
.upload-drop-zone {
  height: 200px;
  border-width: 2px;
  margin-bottom: 20px;
}

/* skin.css Style*/
.upload-drop-zone {
  color: #ccc;
  border-style: dashed;
  border-color: #ccc;
  line-height: 200px;
  text-align: center
}
.upload-drop-zone.drop {
  color: #222;
  border-color: #222;
}
</style>
</head>
<body>

<!------ Include the above in your HEAD tag ---------->

<div class="container">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Upload Files</strong> <small>Bootstrap files upload</small></div>
        <div class="panel-body">

          <!-- Standar Form -->
          <h4>Select files from your computer</h4>
          <form action="" method="POST" enctype="multipart/form-data" >
            <div class="form-inline">
              <div class="form-group">
                <input type="file" name="flag">
              </div>
<input type="submit" name="go" class="btn btn-sm btn-primary" id="js-upload-submit">
            </div>
          </form>

          <!-- Drop Zone -->
          <h4>Hehehe you cant drag drop file</h4>
          <div class="upload-drop-zone" id="drop-zone">
            You can't drag drop file here hehe
          </div>

          <!-- Progress Bar -->
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
              <span class="sr-only">60% Complete</span>
            </div>
          </div>

          <!-- Upload Finished -->
          <div class="js-upload-finished">
          </div>
        </div>
      </div>
    </div> <!-- /container -->
</body>
</html>