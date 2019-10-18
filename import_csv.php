<?php 
session_start();
require_once('database.php');
// Query to get columns from table
$query = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = 'products'");

while($row = $query->fetch_assoc()){
    $result[] = $row;
}
if(isset($_SESSION['error'])){
  echo "error- same csv fields assigned to different table fields";
  unset($_SESSION['error']);
}
else{
  if($_FILES["file"]['type']=="text/csv")
  {
    $file = fopen($_FILES["file"]["tmp_name"], "r");
    if (!isset($_POST['title'])){
      echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
      if(($csv_first_row  = fgetcsv($file)) !== FALSE){
        $column_array = array_column($result, 'COLUMN_NAME');
        echo '<ul>';
        foreach($column_array as $key=>$v){
          echo "<li>$v".  draw_columns($csv_first_row,$v)."</li>";
        }
        echo '</ul>';
      }
    
      echo "<input type='submit'>";
      echo "</form>";
    }
    else{
    while (($emapData = fgetcsv($file)) !== FALSE){ 
        $categories = explode('|',$emapData[$_POST['category']]);
        $cat_ids = array();
        foreach ($categories as $c) {
            $sql1 = "select id from category where title = '$c'";
            $id1 = $db->query($sql1);
            if (!$id1->num_rows) {
                $sql2 = "insert into category(id,title) values (null,'$c')";
                $db->query($sql2);
                $c_id = $db->insert_id;
            } else {
                $c_id = mysqli_fetch_row($id1)[0];
            }
            $cat_ids[] = $c_id;
        }
        $c_id = implode(',',$cat_ids);
        unset($cat_ids);
        $sku = $_POST['SKU'];$title = $_POST['title'];$description = $_POST['description'];
        $price = $_POST['price'];$quantity = $_POST['quantity'];
        $sql3 = "select id from products where SKU = '".$emapData[$sku]."'";
        $id3 = $db->query($sql3);
        if ($id3->num_rows) {
            $sql4 = "update products SET 
            title=`$emapData[$title]`, 
            description=`$emapData[$description]`,
            price =`$emapData[$price]`,
            quantity=`$emapData[$quantity]`,
            category_id=`$c_id`
            WHERE id=".mysqli_fetch_row($id3)[0];
            $db->query($sql4);
        } else {
            $sql5 = "INSERT into products
                (".implode(',', array_values($column_array)).")
                values($emapData[0],'$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$c_id')";
            $db->query($sql5);d($sql5);
        }          
    }
    }
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