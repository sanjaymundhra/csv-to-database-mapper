<html>
  <head>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <style>
            body{
                background-color: #fafafa;
            }
            .div_1{
                box-shadow: 2px 2px 12px 1px lightgray;
                border-radius: 6px;
                margin: 20px;
            }
            .col-sm-12{
                padding: 20px;
            }
            .col-sm-6 p{
                padding-top: 10px;
            }
            h3{
                text-align: center;
                border-bottom: 1px solid lightgray;
                padding-bottom: 20px;
            }
        </style>
</head>
</html>
<?php 
session_start();
require_once('database.php');

if(isset($_SESSION['error'])){
  echo "error- same csv fields assigned to different table fields";
  unset($_SESSION['error']);
}
else{
  if($_FILES["file"]['type']=="text/csv")
  {
    
    $file = fopen($_FILES["file"]["tmp_name"], "r");
    if (!isset($_POST['title'])){
      echo "<form action='submit.php' method='post'>";
      if(($csv_first_row  = fgetcsv($file)) !== FALSE){
        $column_array = array_column($result, 'COLUMN_NAME');
        echo '<ul>';
        foreach($column_array as $key=>$v){
          echo "<li>$v".  draw_columns($csv_first_row,$v)."</li>";
        }
        echo '</ul>';
      }
      echo "<input type='hidden' name='filename' value='".basename($_FILES["file"]["name"])."'>";
      
      echo "<input type='submit'>";
      echo "</form>";
    }
    $target_file = "csv/".basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    
    fclose($file);
  }
  else {
      echo "Error: Please Upload only CSV File";
  }
}

function draw_columns($csv_first_row,$v){
  echo "<select name='$v'>";
    foreach($csv_first_row  as $key => $value){
      echo "<option value='$key'>$value</option>";
    }
    echo "</select>";
}
function dd($a){
  var_dump($a);die();
}
function d($a){
  echo '<pre>';
  var_dump($a);
}
?>