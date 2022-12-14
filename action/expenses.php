<?php
include('../config/db_con.php');

$object = new srms();
//각종권한 체크
$level_check = $object->level_check();
$cp_name_check = $object->cp_name_check();
$login_mame = $object->login_mame();
$cmp_code = $object->cmp_code();
$cmp_name = $object->cmp_name();
$login_id = $object->login_id();
if(!$object->is_login())
{
    header("location:".$object->base_url."/index.php");
}

$error = '';
$success = '';


for ($x = 0; $x < count($_POST['expenses_list']); $x++) {

    $data = array(
        ':expenses'			=>	$_POST['expenses'][$x]
    );

    $object->query = "
                UPDATE travel_expenses  
                SET price = '" . $_POST['expenses'][$x] . "',
                price_center = '" . $_POST['expenses_center'][$x] . "'
                WHERE name = '" . $_POST['expenses_list'][$x] . "'
			    ";

    $object->execute();


}
echo '출장비가 수정되었습니다.';

?>