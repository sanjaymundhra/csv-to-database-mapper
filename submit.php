<?php 
    include_once('error.php');
    session_start();
    require_once('database.php');
    if(count(array_unique($_POST))!=8){
        if (isset($_SERVER["HTTP_REFERER"])) {
            $_SESSION['error'] = 1;
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }
    $column_array = array_column($result, 'COLUMN_NAME');
    $file = fopen('csv/'.$_POST['filename'], "r");
    $flag =1;
    while (($emapData = fgetcsv($file)) !== false) {
        if($flag){
            $flag=0;
            continue;
        }
        $categories = explode('|', $emapData[$_POST['category_id']]);
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
        $c_id = implode(',', $cat_ids);
        unset($cat_ids);
        $sku = $_POST['SKU'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
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
                values(null,'$emapData[$title]','$emapData[$sku]',
                '$emapData[$description]','$emapData[$price]','$emapData[$quantity]','$c_id')";
            $db->query($sql5);
        }
    }
    echo "Successful";   
    function dd($a){
        var_dump($a);die();
      }     
?>