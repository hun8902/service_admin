<?php
include('../config/db_con.php');

$object = new srms();
if(isset($_POST["action"]))
{
    $x = 1;
    $output = '';
    if($_POST["action"] == "step1")
    {
        $object->query  = "SELECT * FROM category WHERE parent_id = '".$_POST["query"]."'";
        $result = $object->get_result();
        foreach($result as $row)
        {
            $output .= '<option value="'.$row["id_menu"].'">'.$row["label_menu"].'</option>';
        }
    }
    if($_POST["action"] == "step2")
    {
        $object->query  = "SELECT * FROM category WHERE parent_id = '1'";
        $result_won = $object->get_result();
        foreach($result_won as $row_won)
        {
            if($x == "1"){
                if($row_won["price1"] != null){
                    $output .= $row_won["price1"].",";
                }else{
                    $output .= "0,";
                }
                $x++;
            }

        };

        $object->query  = "SELECT * FROM category WHERE parent_id = '".$_POST["query"]."'";
        $result = $object->get_result();
        foreach($result as $row)
        {
            $output .= '<option value="'.$row["id_menu"].'">'.$row["label_menu"].'</option>';
        }
    }
    if($_POST["action"] == "step3")
    {
        $object->query  = "SELECT * FROM category WHERE parent_id = '".$_POST["query"]."'";
        $result = $object->get_result();
        foreach($result as $row)
        {
            $output .= '<option value="'.$row["id_menu"].'">'.$row["label_menu"].'</option>';
        }
    }
/*    if($_POST["action"] == "step4")
    {
        $object->query  = "SELECT * FROM category WHERE parent_id = '".$_POST["query"]."'";
        $result = $object->get_result();
        foreach($result as $row)
        {

            $output .= '<option value="'.$row["id_menu"].'">'.$row["label_menu"].'</option>';
        }
    }*/
    if($_POST["action"] == "step4")
    {
        $object->query  = "SELECT * FROM category WHERE id_menu = '".$_POST["query"]."'";
        $result = $object->get_result();
        foreach($result as $row)
        {
            if($row["price1"] != null){
                $output .= $row["price1"].",";
            }else{
                $output .= "0,";
            }
            if($row["price2"] != null){
                $output .= $row["price2"].",";
            }else{
                $output .= "0,";
            }
            //$output .= $row["label_menu"];
        }
    }

    echo $output;
}
?>
