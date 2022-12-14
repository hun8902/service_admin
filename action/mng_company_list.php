<?php
include('../config/db_con.php');

$object = new srms();

//각종권한 체크
$level_check = $object->level_check();
$cp_name_check = $object->cp_name_check();
$login_mame = $object->login_mame();
$cmp_code = $object->cmp_code();
$login_id = $object->login_id();

if(isset($_POST["action"]))
{


	if($_POST["action"] == 'fetch')
	{
		$order_column = array('ac_id,ac_name,ac_phone,ac_created_on');

		$output = array();
        $select_code = $_POST["class_code"];

		$main_query = "
		SELECT * FROM management_account 
		INNER JOIN management
		ON management_account.ac_code = management.cp_code 
		WHERE management_account.ac_code = '".$_POST["class_code"]."'
		";

		$search_query = '';
		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND (ac_id LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'or ac_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'or ac_phone LIKE "%'.$_POST["search"]["value"].'%" )';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY ac_created_on DESC ';
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

        //현재 회사명 구하기
        $object->query = "
        SELECT * FROM management WHERE cp_code = '".$_POST["class_code"]."'
        ";
        $result1 = $object->get_result();
        $class_name = '';
        foreach($result1 as $row)
        {
            $class_name = $row['cp_name'];
        }


		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = html_entity_decode($row["ac_id"]);
			$sub_array[] = html_entity_decode($row["ac_name"]);
            $sub_array[] = html_entity_decode($row["ac_phone"]);
            $sub_array[] = html_entity_decode($row["ac_created_on"]);
			$status = '';
			if($row["ac_status"] == 'Enable')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["ac_idx"].'" data-status="'.$row["ac_status"].'">가능</button>';
                $status_text= '<button type="button" class="btn btn-primary btn-sm disabled" >가능</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["ac_idx"].'" data-status="'.$row["ac_status"].'">불가능</button>';
                $status_text= '<button type="button" class="btn btn-danger btn-sm disabled" >불가능</button>';
			}

            //업체 레벨이 1이거나 자기 자신의 회사인 경우
            if($level_check == "1" ){
                $sub_array[] = $status;
                $sub_array[] = '
                <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["ac_idx"].'">수정</button>
                <button type="button" name="delete_button" class="btn btn-warning btn-danger btn-sm delete_button" data-id="'.$row["ac_idx"].'">삭제</button>
                ';
            }else{
                $sub_array[] = $status_text;
                if($row["ac_id"] == $login_id) {
                    $sub_array[] = '
                    <div align="center">			
                        <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["ac_idx"] . '">수정</button>
                    </div>
                    ';
                }else{
                    $sub_array[] = '
                    <div align="center">			
                    </div>
                    ';
                }
            }


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
		$data = array(
			':ac_idx'			=>	$_POST["class_id"]
		);

		$object->query = "
		SELECT * FROM management_account 
		WHERE ac_idx = :ac_idx
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '이미 데이터가 등록되어있습니다.';
		}
		else
		{
			$data = array(
				':ac_id'				=>	$object->clean_input($_POST["ac_id"]),
				':ac_code'			=>	$object->clean_input($_POST["ac_code"]),
				':ac_name'		=>	$object->clean_input($_POST["ac_name"]),
				':ac_passwd'		=>	$_POST["ac_passwd"],
				':ac_phone'		=>	$_POST["ac_phone"],
				':ac_type'			=>	$_POST["ac_type"],
				':ac_status'		=>	'Enable',
				':ac_created_on'		=>	$object->now
			);
			$object->query = "
			INSERT INTO management_account 
			(ac_id, ac_code, ac_name, ac_passwd, ac_phone, ac_type, ac_status, ac_created_on) 
			VALUES (:ac_id, :ac_code, :ac_name, :ac_passwd, :ac_phone, :ac_type, :ac_status, :ac_created_on)
			";
			$object->execute($data);
			$success = $_POST["ac_name"].' 님의 계정이 생성되었습니다.';
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
		SELECT * FROM management_account 
		WHERE ac_idx = '".$_POST["student_id"]."'
		";
		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{

            $data['ac_id'] = $row['ac_id'];
			$data['ac_name'] = $row['ac_name'];
			$data['ac_passwd'] = $row['ac_passwd'];
			$data['ac_phone'] = $row['ac_phone'];
			$data['ac_created_on'] = $row['ac_created_on'];
		}


		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';
		$success = '';
		$data = array(
			':ac_id'			=>	$_POST["ac_id"],
			':ac_name'	=>	$_POST["ac_name"],
            ':ac_phone'	=>	$_POST["ac_phone"],
			':ac_passwd'		=>	$_POST['ac_passwd']
		);

		$object->query = "
		SELECT * FROM management_account 
		WHERE ac_id = :ac_id 
		";

		$object->execute($data);
		if($object->row_count() > 0)
		{
			$error = '잘못된 데이터 입니다.';
		}
		else
		{

			$data = array(
				':ac_id'				=>	$object->clean_input($_POST["ac_id"]),
				':ac_name'			=>	$object->clean_input($_POST["ac_name"]),
				':ac_passwd'		=>	$object->clean_input($_POST["ac_passwd"]),
				':ac_phone'		=>	$_POST["ac_phone"]
			);

			$object->query = "
			UPDATE management_account 
			SET ac_id = :ac_id, 
			ac_name = :ac_name, 
			ac_passwd = :ac_passwd, 
			ac_phone = :ac_phone
			WHERE ac_idx = '".$_POST['hidden_id']."'
			";

			$object->execute($data);
			$success = '회원정보가 수정되었습니다.';
			
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':student_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE management_account 
		SET ac_status = :student_status 
		WHERE ac_idx = '".$_POST["id"]."'
		";

		$object->execute($data);
		echo '해당 회원의 상태가 변경되었습니다.';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM management_account 
		WHERE ac_idx = '".$_POST["id"]."'
		";

		$object->execute();
		echo '아이디가 삭제되었습니다.';
	}

}



?>