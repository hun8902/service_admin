<?php
include('../config/db_con.php');

$object = new srms();

if(isset($_POST["ac_id"]))
{
	//sleep(1);

	$error = '';
	$url = '';
	$data = array(
		':ac_id'	=>	$_POST["ac_id"]
	);

	$object->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_id = :ac_id
	";

	$object->execute($data);
	$total_row = $object->row_count();
	if($total_row == 0)
	{
		$error = '해당 아이디가 존재 하지 않습니다.';
	}
	else
	{
		//$result = $statement->fetchAll();
		$result = $object->statement_result();
		foreach($result as $row)
		{
			if($row["ac_status"] == 'Enable' && $row["cp_status"] == 'Enable')
			{
				if($_POST["ac_passwd"] == $row["ac_passwd"])
				{
					$_SESSION['user_id'] = $row['ac_id'];
					$_SESSION['user_type'] = $row['ac_type'];
					if($row['user_type'] == '1')
					{
						$url = $object->base_url . 'dashboard.php';
					}
					else
					{
						$url = $object->base_url . 'dashboard.php';
					}
				}
				else
				{
					$error = '비밀번호가 올바르지 않습니다.';
				}
			}
			else
			{
				$error = '접근이 불가능한 사용자입니다.';
			}
		}
	}

	$output = array(
		'error'		=>	$error,
		'url'		=>	$url
	);

	echo json_encode($output);
}

?>