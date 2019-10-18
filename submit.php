<?php 
    include_once('error.php');
    session_start();
    require_once('database.php');
    if(count(array_unique($_POST))!=7){
        if (isset($_SERVER["HTTP_REFERER"])) {
            $_SESSION['error'] = 1;
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }
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
        $sql3 = "select id from products where SKU = '$emapData[$_POST['SKU']]'";
        $id3 = $db->query($sql3);
        if ($id3->num_rows) {
            $sql4 = "update products SET 
            title=`$emapData[$_POST['title']]`, 
            description=`$emapData[$_POST['description']]`,
            price =`$emapData[$_POST['price']]`,
            quantity=`$emapData[$_POST['quantity']]`,
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
?>