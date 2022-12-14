<?php
include("../config/db_con.php");
$object = new srms();

$menu = $_POST['menu'];
$array_menu = json_decode($menu, true);

$db->query('TRUNCATE TABLE category');

function updateMenu($menu,$parent = 0)
{
    global $db;


    if (!empty($menu)) {

        foreach ($menu as $value) {

            $label = $value['label'];
            $price1 = $value['price1'];
            $price2 = $value['price2'];

            $sql = "INSERT INTO category (label_menu, price1, price2, parent_id) VALUES ('$label', '$price1', '$price2', $parent)";

            $db->query($sql);
            $id = $db->insertedId();

            if (array_key_exists('children', $value))
                updateMenu($value['children'],$id);
        }

    }
}


updateMenu($array_menu);

header("Location: /view/mng_code.php")
?>