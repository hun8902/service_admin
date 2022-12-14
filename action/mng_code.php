<?php
include('../config/db_con.php');


$menu = $_POST['menu'];
$array_menu = json_decode($menu[0], true);

/*
$menu ='[{"url":1,"price2":"","price1":"","label":"TYH","id":1,"children":[{"url":2,"price2":18000,"price1":20000,"label":"수리1급","id":2,"children":[{"url":3,"price2":18000,"price1":20000,"label":"일반","id":3,"children":[{"url":4,"price2":18000,"price1":20000,"label":"일반","id":4}]},{"url":5,"price2":20000,"price1":20000,"label":"긴급","id":5,"children":[{"url":6,"price2":20000,"price1":20000,"label":"긴급","id":6}]}]},{"url":7,"price2":13000,"price1":15000,"label":"수리2급","id":7,"children":[{"url":8,"price2":13000,"price1":15000,"label":"일반","id":8,"children":[{"url":9,"price2":13000,"price1":15000,"label":"일반","id":9}]},{"url":10,"price2":15000,"price1":15000,"label":"긴급","id":10,"children":[{"url":11,"price2":15000,"price1":15000,"label":"긴급","id":11}]}]},{"url":12,"price2":20000,"price1":20000,"label":"상시","id":12,"children":[{"url":13,"price2":20000,"price1":20000,"label":"일반","id":13,"children":[{"url":14,"price2":20000,"price1":20000,"label":"일반","id":14}]},{"url":15,"price2":20000,"price1":20000,"label":"긴급","id":15,"children":[{"url":16,"price2":20000,"price1":20000,"label":"긴급","id":16}]}]},{"url":17,"price2":0,"price1":0,"label":"협의","id":17,"children":[{"url":18,"price2":0,"price1":0,"label":"일반","id":18,"children":[{"url":19,"price2":0,"price1":0,"label":"일반","id":19}]},{"url":20,"price2":0,"price1":0,"label":"긴급","id":20,"children":[{"url":21,"price2":0,"price1":0,"label":"긴급","id":21}]}]}]},{"url":22,"price2":"","price1":"","label":"CPW","id":22,"children":[{"url":23,"price2":18000,"price1":20000,"label":"수리1급","id":23,"children":[{"url":24,"price2":18000,"price1":20000,"label":"일반","id":24,"children":[{"url":25,"price2":18000,"price1":20000,"label":"일반","id":25}]},{"url":26,"price2":20000,"price1":20000,"label":"긴급","id":26,"children":[{"url":27,"price2":20000,"price1":20000,"label":"긴급","id":27}]}]},{"url":28,"price2":13000,"price1":15000,"label":"수리2급","id":28,"children":[{"url":29,"price2":13000,"price1":15000,"label":"일반","id":29,"children":[{"url":30,"price2":13000,"price1":15000,"label":"일반","id":30}]},{"url":31,"price2":15000,"price1":15000,"label":"긴급","id":31,"children":[{"url":32,"price2":15000,"price1":15000,"label":"긴급","id":32}]}]},{"url":33,"price2":20000,"price1":20000,"label":"상시","id":33,"children":[{"url":34,"price2":20000,"price1":20000,"label":"일반","id":34,"children":[{"url":35,"price2":20000,"price1":20000,"label":"일반","id":35}]},{"url":36,"price2":20000,"price1":20000,"label":"긴급","id":36,"children":[{"url":37,"price2":20000,"price1":20000,"label":"긴급","id":37}]}]},{"url":38,"price2":0,"price1":0,"label":"협의","id":38,"children":[{"url":39,"price2":0,"price1":0,"label":"일반","id":39,"children":[{"url":40,"price2":0,"price1":0,"label":"일반","id":40}]},{"url":41,"price2":0,"price1":0,"label":"긴급","id":41,"children":[{"url":42,"price2":0,"price1":0,"label":"긴급","id":42}]}]}]},{"url":43,"price2":"","price1":"","label":"CPH","id":43,"children":[{"url":44,"price2":18000,"price1":20000,"label":"수리1급","id":44,"children":[{"url":45,"price2":18000,"price1":20000,"label":"일반","id":45,"children":[{"url":46,"price2":18000,"price1":20000,"label":"일반","id":46}]},{"url":47,"price2":20000,"price1":20000,"label":"긴급","id":47,"children":[{"url":48,"price2":20000,"price1":20000,"label":"긴급","id":48}]}]},{"url":49,"price2":13000,"price1":15000,"label":"수리2급","id":49,"children":[{"url":50,"price2":13000,"price1":15000,"label":"일반","id":50,"children":[{"url":51,"price2":13000,"price1":15000,"label":"일반","id":51}]},{"url":52,"price2":15000,"price1":15000,"label":"긴급","id":52,"children":[{"url":53,"price2":15000,"price1":15000,"label":"긴급","id":53}]}]},{"url":54,"price2":20000,"price1":20000,"label":"상시","id":54,"children":[{"url":55,"price2":20000,"price1":20000,"label":"일반","id":55,"children":[{"url":56,"price2":20000,"price1":20000,"label":"일반","id":56}]},{"url":57,"price2":20000,"price1":20000,"label":"긴급","id":57,"children":[{"url":58,"price2":20000,"price1":20000,"label":"긴급","id":58}]}]},{"url":59,"price2":0,"price1":0,"label":"협의","id":59,"children":[{"url":60,"price2":0,"price1":0,"label":"일반","id":60,"children":[{"url":61,"price2":0,"price1":0,"label":"일반","id":61}]},{"url":62,"price2":0,"price1":0,"label":"긴급","id":62,"children":[{"url":63,"price2":0,"price1":0,"label":"긴급","id":63}]}]}]},{"url":64,"price2":"","price1":"","label":"CPT","id":64,"children":[{"url":65,"price2":18000,"price1":20000,"label":"수리1급","id":65,"children":[{"url":66,"price2":18000,"price1":20000,"label":"일반","id":66,"children":[{"url":67,"price2":18000,"price1":20000,"label":"일반","id":67}]},{"url":68,"price2":20000,"price1":20000,"label":"긴급","id":68,"children":[{"url":69,"price2":20000,"price1":20000,"label":"긴급","id":69}]}]},{"url":70,"price2":13000,"price1":15000,"label":"수리2급","id":70,"children":[{"url":71,"price2":13000,"price1":15000,"label":"일반","id":71,"children":[{"url":72,"price2":13000,"price1":15000,"label":"일반","id":72}]},{"url":73,"price2":15000,"price1":15000,"label":"긴급","id":73,"children":[{"url":74,"price2":15000,"price1":15000,"label":"긴급","id":74}]}]},{"url":75,"price2":20000,"price1":20000,"label":"상시","id":75,"children":[{"url":76,"price2":20000,"price1":20000,"label":"일반","id":76,"children":[{"url":77,"price2":20000,"price1":20000,"label":"일반","id":77}]},{"url":78,"price2":20000,"price1":20000,"label":"긴급","id":78,"children":[{"url":79,"price2":20000,"price1":20000,"label":"긴급","id":79}]}]},{"url":80,"price2":0,"price1":0,"label":"협의","id":80,"children":[{"url":81,"price2":0,"price1":0,"label":"일반","id":81,"children":[{"url":82,"price2":0,"price1":0,"label":"일반","id":82}]},{"url":83,"price2":0,"price1":0,"label":"긴급","id":83,"children":[{"url":84,"price2":0,"price1":0,"label":"긴급","id":84}]}]}]},{"url":85,"price2":"","price1":"","label":"CPP","id":85,"children":[{"url":86,"price2":18000,"price1":20000,"label":"수리1급","id":86,"children":[{"url":87,"price2":18000,"price1":20000,"label":"일반","id":87,"children":[{"url":88,"price2":18000,"price1":20000,"label":"일반","id":88}]},{"url":89,"price2":20000,"price1":20000,"label":"긴급","id":89,"children":[{"url":90,"price2":20000,"price1":20000,"label":"긴급","id":90}]}]},{"url":91,"price2":13000,"price1":15000,"label":"수리2급","id":91,"children":[{"url":92,"price2":13000,"price1":15000,"label":"일반","id":92,"children":[{"url":93,"price2":13000,"price1":15000,"label":"일반","id":93}]},{"url":94,"price2":15000,"price1":15000,"label":"긴급","id":94,"children":[{"url":95,"price2":15000,"price1":15000,"label":"긴급","id":95}]}]},{"url":96,"price2":20000,"price1":20000,"label":"상시","id":96,"children":[{"url":97,"price2":200000,"price1":20000,"label":"일반","id":97,"children":[{"url":98,"price2":20000,"price1":20000,"label":"일반","id":98}]},{"url":99,"price2":20000,"price1":20000,"label":"긴급","id":99,"children":[{"url":100,"price2":20000,"price1":20000,"label":"긴급","id":100}]}]},{"url":101,"price2":"","price1":"","label":"협의","id":101,"children":[{"url":102,"price2":"","price1":"","label":"일반","id":102,"children":[{"url":103,"price2":"","price1":"","label":"일반","id":103}]},{"url":104,"price2":"","price1":"","label":"긴급","id":104,"children":[{"url":105,"price2":"","price1":"","label":"긴급","id":105}]}]}]}]';
$array_menu = json_decode($menu, true);*/

//날짜 구하기
$update_date = date('Y-m-d H:i:s');
$object = new srms();
$prev_date = "";
$object->query = "SELECT * FROM category where parent_id = '0' AND update_date IN (SELECT MAX(update_date) FROM category) ORDER BY id_menu ASC limit 1";
$object->execute();
if($object->row_count() == 0){
        $prev_date = $update_date;
}else{
    $object->query = "SELECT * FROM category where parent_id = '0' AND update_date IN (SELECT MAX(update_date) FROM category) ORDER BY id_menu ASC limit 1";
    $result = $object->get_result();
    foreach($result as $row){
        $prev_date = $row['update_date'];
    }
}


function updateMenu($menu,$parent = 0)
{
    $object = new srms();
    $prev_date = $GLOBALS['prev_date'];
    $update_date = $GLOBALS['update_date'];

    if (!empty($menu)) {
        foreach ($menu as $value) {
            $label = $value['label'];
            $price1 = $value['price1'];
            $price2 = $value['price2'];


            $object->query = "
            INSERT INTO category (label_menu, price1, price2, parent_id, prev_date, update_date)
            VALUES ('$label', '$price1', '$price2', '$parent', '$prev_date', '$update_date')
            ";
            $object->execute();

            $id = $object->lastInsertIdsave();

            if (array_key_exists('children', $value))
                updateMenu($value['children'],$id);
        }
    }

}

echo "변경이 완료되었습니다.";
updateMenu($array_menu);


//header("Location: /view/mng_code.php")
?>