<?php
include('../config/db_con.php');

$object = new srms();


if(isset($_POST["action"]))
{
    if($_POST["action"] == 'fetch')
    {
        $order_column = array('model_name');

        $output = array();

        $main_query = "
		SELECT * FROM materialcontrol 
		";

        $search_query = '';

        if(isset($_POST["search"]["value"]))
        {
            $search_query .= 'WHERE model_name LIKE "%'.$_POST["search"]["value"].'%" ';
            /*$search_query .= 'OR model_code LIKE "%'.$_POST["search"]["value"].'%" ';*/
        }

        if(isset($_POST["order"]))
        {
            $order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $order_query = 'ORDER BY idx DESC ';
        }
        $limit_query = '';
        if($_POST["length"] != -1)
        {
            $limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $object->query = $main_query . $search_query . $order_query;
        $object->execute();
        $filtered_rows = $object->row_count();
        $object->query .= $limit_query;
        $result = $object->get_result();
        $object->query = $main_query;
        $object->execute();
        $total_rows = $object->row_count();
        $data = array();
        foreach($result as $row)
        {
            $sub_array = array();
            $sub_array[] = html_entity_decode($row["model_name"]);
            $sub_array[] = '
			<div align="center">
			    <button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["idx"].'">삭제</button>
			</div>
			';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"    			=> 	intval($_POST["draw"]),
            "recordsTotal"  	=>  $total_rows,
            "recordsFiltered" 	=> 	$filtered_rows,
            "data"    			=> 	$data
        );

        echo json_encode($output);
    }


    if($_POST["action"] == 'Add')
    {
        $error = '';
        $success = '';
        $post_uid = uniqid();

        $data = array(
            ':model_name'	=>	$_POST["model_name"]
        );
        $object->query = "
		SELECT * FROM materialcontrol 
		WHERE model_name = :model_name
		";
        $object->execute($data);
        if($object->row_count() > 0)
        {
            $error = '이미 동일한 가진 데이터가 있습니다.';
        }
        else
        {

            $data = array(
                ':model_name'			=>	$object->clean_input($_POST["model_name"])

            );

            $object->query = "
			INSERT INTO materialcontrol 
			(model_name) 
			VALUES (:model_name)
			";

            $object->execute($data);

            $success = '데이터가 추가 되었습니다.';
        }

        $output = array(
            'error'		=>	$error,
            'success'	=>	$success
        );

        echo json_encode($output);

    }


    if($_POST["action"] == 'fetch_single')
    {
        $object->query = "
		SELECT * FROM materialcontrol 
		WHERE idx = '".$_POST["class_id"]."'
		";

        $result = $object->get_result();

        $data = array();

        foreach($result as $row)
        {
            $data['idx'] = $row['idx'];
            $data['model_name'] = $row['model_name'];
            $data['model_code'] = $row['model_code'];
            $data['model_etc'] = $row['model_etc'];
            $data['model_cnt'] = $row['model_cnt'];
        }

        echo json_encode($data);
    }

    if($_POST["action"] == 'Edit')
    {
        $error = '';
        $success = '';
        $data = array(
            ':idx'	=>	$_POST["idx"],
            ':model_name'	=>	$_POST["model_name"],
            ':model_code'	=>	$_POST["model_code"],
            ':model_etc'	=>	$_POST["model_etc"],
            ':model_cnt'	=>	$_POST["model_cnt"],
            ':hidden_id'		=>	$_POST['hidden_id'],

        );

        $object->query = "
		SELECT * FROM materialcontrol 
		WHERE idx = :idx 
		";


        $object->execute($data);

        if($object->row_count() > 0)
        {
            $error = '<div class="alert alert-danger">Class Name Already Exists</div>';
        }
        else
        {

            $data = array(
                ':model_name'		=>	$object->clean_input($_POST["model_name"]),
                ':model_code'		=>	$object->clean_input($_POST["model_code"]),
                ':model_etc'		=>	$object->clean_input($_POST["model_etc"]),
                ':model_cnt'		=>	$object->clean_input($_POST["model_cnt"]),
            );

            $object->query = "
            UPDATE materialcontrol
            SET model_name = :model_name,    
            model_code = :model_code,    
            model_etc = :model_etc,    
            model_cnt = :model_cnt
            WHERE idx = '" . $_POST['hidden_id'] . "'
            ";
            $object->execute($data);
            $success = '정보가 수정되었습니다.';
        }

        $output = array(
            'error'		=>	$error,
            'success'	=>	$success
        );
        echo json_encode($output);
    }



    if($_POST["action"] == 'delete')
    {
        $object->query = "
		DELETE FROM materialcontrol 
		WHERE idx = '".$_POST["id"]."'
		";

        $object->execute();


        echo '해당 데이터가 삭제 되었습니다.';
    }

}

?>