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

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('cp_name', 'cp_ceo', 'cp_bnumber', 'cp_number', 'cp_fax', 'cp_email', 'cp_status', 'cp_created_on');

		$output = array();
        if($level_check == "1") {
            $main_query = "
		SELECT * FROM management
        WHERE (cp_display ='Enable' or cp_display ='') 
        ";
        }else{
        $main_query = "
		SELECT * FROM management
        WHERE (cp_name = '".$cmp_name."') AND (cp_display ='Enable' or cp_display ='') 
        ";
        }

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND (cp_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_ceo LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_bnumber LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_number LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_fax LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_email LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_addr LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR cp_srttn LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR cp_memo LIKE "%'.$_POST["search"]["value"].'%")';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY cp_idx DESC ';
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
            $sub_array[] = html_entity_decode($row["cp_name"]);
            $status = '';
			if($row["cp_status"] == 'Enable')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["cp_idx"].'" data-status="'.$row["cp_status"].'">가능</button>';
                $status_text= '<button type="button" class="btn btn-primary btn-sm disabled" >가능</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["cp_idx"].'" data-status="'.$row["cp_status"].'">불가능</button>';
                $status_text= '<button type="button" class="btn btn-danger btn-sm disabled" >불가능</button>';
			}

            $sub_array[] = html_entity_decode($row["cp_ceo"]);
            $sub_array[] = html_entity_decode($row["cp_bnumber"]);
            $sub_array[] = html_entity_decode($row["cp_number"]);
            $sub_array[] = html_entity_decode($row["cp_email"]);
            $sub_array[] = html_entity_decode($row["cp_created_on"]);
            $sub_array[] = html_entity_decode($row["cp_srttn"]);

            //업체 레벨이 1이거나 자기 자신의 회사인 경우
            if($level_check == "1" || $row["cp_ceo"] == $cp_name_check){
                $sub_array[] = $status;
                $sub_array[] = '
                <div align="center">			
                    <a href="mng_company_list.php?&class=' . $row["cp_code"] . '" class="btn btn-secondary btn-sm">계정 관리</a>
                    <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["cp_idx"] . '">수정</button>
                    <button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="' . $row["cp_idx"] . '">삭제</button>
                </div>
			    ';
            }else{
                $sub_array[] = $status_text;
                $sub_array[] = '
                <div align="center">			
                    <a href="mng_company_list.php?&class=' . $row["cp_code"] . '" class="btn btn-secondary btn-sm">계정 관리</a>
                </div>
			    ';
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
			':cp_name'	=>	$_POST["cp_name"]
		);
		$object->query = "
		SELECT * FROM management 
		WHERE cp_name = :cp_name
		";
		$object->execute($data);
		if($object->row_count() > 0)
		{
			$error = '이미 동일한 가진 데이터가 있습니다.';
		}
		else
		{
            //레벨에 아무것도 입력이 안되면 기본적으로 최하 레벨로 설정
            if($_POST["cp_level"] == ""){
                $cp_level = "3";
            }else{
                $cp_level = $_POST["cp_level"];
            };

			$data = array(
				':cp_name'			=>	$object->clean_input($_POST["cp_name"]),
                ':cp_code'			=>	$object->clean_input($_POST["cp_code"]),
                ':cp_ceo'			=>	$object->clean_input($_POST["cp_ceo"]),
                ':cp_bnumber'			=>	$object->clean_input($_POST["cp_bnumber"]),
                ':cp_number'			=>	$object->clean_input($_POST["cp_number"]),
                ':cp_fax'			=>	$object->clean_input($_POST["cp_fax"]),
                ':cp_email'			=>	$object->clean_input($_POST["cp_email"]),
                ':cp_addr'			=>	$object->clean_input($_POST["cp_addr"]),
                ':cp_srttn'			=>	$object->clean_input($_POST["cp_srttn"]),
                ':cp_join'			=>	$object->now,
                ':cp_memo'			=>	$object->clean_input($_POST["cp_memo"]),
				':cp_code'			=>	uniqid(),
				':cp_level'			=>	$cp_level,
                ':cp_status'			=>	'Enable',
				':cp_created_on'		=>	$object->now
			);

			$object->query = "
			INSERT INTO management 
			(cp_name, cp_code, cp_ceo, cp_bnumber, cp_number, cp_fax, cp_email, cp_addr, cp_join, cp_srttn, cp_memo, cp_level, cp_status, cp_created_on) 
			VALUES (:cp_name, :cp_code, :cp_ceo, :cp_bnumber, :cp_bnumber, :cp_fax, :cp_email, :cp_addr, :cp_join, :cp_srttn, :cp_memo, :cp_level, :cp_status, :cp_created_on)
			";

			$object->execute($data);
			$success = '업체가 추가되었습니다.';
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
		SELECT * FROM management 
		WHERE cp_idx = '".$_POST["class_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
            $data['cp_name'] = $row['cp_name'];
            $data['cp_code'] = $row['cp_code'];
            $data['cp_ceo'] = $row['cp_ceo'];
            $data['cp_bnumber'] = $row['cp_bnumber'];
            $data['cp_number'] = $row['cp_number'];
            $data['cp_fax'] = $row['cp_fax'];
            $data['cp_email'] = $row['cp_email'];
            $data['cp_addr'] = $row['cp_addr'];
            $data['cp_join'] = $row['cp_join'];
            $data['cp_srttn'] = $row['cp_srttn'];
            $data['cp_memo'] = $row['cp_memo'];
            $data['cp_level'] = $row['cp_level'];
            $data['cp_created_on'] = $row['cp_created_on'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';
		$success = '';
		$data = array(
            ':cp_name'	=>	$_POST["cp_name"],
			':hidden_id'		=>	$_POST['hidden_id'],
		);

		$object->query = "
		SELECT * FROM management 
		WHERE cp_name = :cp_name 
		";
		$object->execute($data);
		if($object->row_count() > 0)
		{
			$error = '이미 존재하는 업체입니다.';
		}
		else
		{
			$data = array(
				':cp_name'		=>	$object->clean_input($_POST["cp_name"]),
                ':cp_ceo'		=>	$object->clean_input($_POST["cp_ceo"]),
                ':cp_bnumber'		=>	$object->clean_input($_POST["cp_bnumber"]),
                ':cp_number'		=>	$object->clean_input($_POST["cp_number"]),
                ':cp_fax'		=>	$object->clean_input($_POST["cp_fax"]),
                ':cp_email'		=>	$object->clean_input($_POST["cp_email"]),
                ':cp_addr'		=>	$object->clean_input($_POST["cp_addr"]),
                ':cp_join'		=>	$object->clean_input($_POST["cp_join"]),
                ':cp_srttn'		=>	$object->clean_input($_POST["cp_srttn"]),
                ':cp_memo'		=>	$object->clean_input($_POST["cp_memo"]),
                ':cp_level'		=>	$object->clean_input($_POST["cp_level"]),
			);

			$object->query = "
			UPDATE management 
			SET cp_name = :cp_name,    
			cp_ceo = :cp_ceo,
			cp_bnumber = :cp_bnumber,
			cp_number = :cp_number,
			cp_fax = :cp_fax,
			cp_email = :cp_email,
			cp_addr = :cp_addr,
			cp_join = :cp_join,
			cp_srttn = :cp_srttn,
			cp_memo = :cp_memo,
			cp_level = :cp_level
			WHERE cp_idx = '".$_POST['hidden_id']."'
			";

			$object->execute($data);
			$success = '업체 정보가 수정되었습니다.';

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
			':cp_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE management 
		SET cp_status = :cp_status 
		WHERE cp_idx = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '해당 회원의 상태가 변경되었습니다.';
		//echo '<div class="alert alert-success">Class Status change to '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
        //숨기기
        $object->query = "
		UPDATE management 
		SET cp_display ='Disable'
		WHERE cp_idx = '".$_POST["id"]."'
		";

        $object->execute($data);

		echo '해당 데이터가 삭제 되었습니다.';
	}
}

?>