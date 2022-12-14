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
    // 화면 출력
    if($_POST["action"] == 'fetch_search')
    {
        $order_column = array('user_name', 'phone1', 'phone2');

        $output = array();
        //레벨 3은 자기 자신만 보이도록 설정.
        if($level_check <= "2"){
            $main_query = "
            SELECT * FROM post 
            INNER JOIN post_mgt 
            ON post.idx = post_mgt.idx_no 
            ";

        }else{
            $main_query = "
            SELECT * FROM post 
            INNER JOIN post_mgt 
            ON post.idx = post_mgt.idx_no
            WHERE center_name = '".$cmp_name."'  
            ";
        }
        $search_query = '';

        //날짜 검색 필터 처리
        if($_POST["from_date"] != '')
        {
            $search_query = "AND DATE_FORMAT(write_date, '%m/%d/%Y') BETWEEN '".$_POST["from_date"]."' AND  '".$_POST["to_date"]."' AND ( ";
        }
        else
        {
            if($level_check <= "2") {
                $search_query = "WHERE";
            }else{
                $search_query = "AND (";
            }
        }

        if(isset($_POST["search"]["value"]))
        {
            $search_query .= 'user_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR phone1 LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR phone2 LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR center_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR addr LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR frist_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR last_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR write_date LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR update_date LIKE "%'.$_POST["search"]["value"].'%" ';

            if($_POST["from_date"] != '') {
                $search_query .= ') ';
            }else{
                if($level_check <= "2") {
                    $search_query = "";
                }else{
                    $search_query .= ') ';
                }
            }
        }

        //정렬처리
        if(isset($_POST["order"])){
            $order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $order_query = 'ORDER BY post.idx DESC ';
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
        
        //리스트 화면출력부분
        foreach($result as $row)
        {

            $sub_array = array();
            $sub_array[] = substr(html_entity_decode($row["write_date"]), 2, 8);
            $sub_array[] = html_entity_decode($row["user_name"]);
            $sub_array[] = html_entity_decode($row["phone1"]);
            $sub_array[] = html_entity_decode($row["center_name"]);

            for ($x = 1; $x <= 4; $x++) {
                $select_query = "test_".$x;
                $select_code = "step".$x;
                if($row[$select_code] == NULL){
                    $sub_array[] = "";
                }else {
                    $object->query = "SELECT label_menu FROM category where id_menu = '" . $row[$select_code] . "'";
                    $select_query = $object->get_result();
                    foreach ($select_query as $row1) {
                        if($row1["label_menu"] != NULL){
                            $sub_array[] = html_entity_decode($row1["label_menu"]);
                        }else{
                            $sub_array[] = "";
                        }
                    }
                }
            }

            //출장비 분리
            if($row["step5"] == NULL){
                $sub_array[] = "";
            }else{
                $object->query = "SELECT * FROM travel_expenses where name = '" . $row["step5"] . "'";
                $select_query = $object->get_result();
                foreach ($select_query as $row1) {
                    if($row1["name"] != NULL){
                        $sub_array[] = html_entity_decode($row1["name"]);
                    }else{
                        $sub_array[] = "";
                    }
                }
            }


            for ($x = 1; $x <= 2; $x++) {
                $select_query = "result".$x;
                $select_code = "stats_fd".$x;
                $date_code = "date_".$x;

                if($row[$select_code] == NULL){
                        $sub_array[] = "";
                }else{

                    $object->query = "SELECT name FROM post_mgt_select where select_pos = '".$select_code."' and  select_code = '".$row[$select_code]."'";
                    $select_query = $object->get_result();
                    foreach($select_query as $row1)
                    {
                        $sub_array[] = html_entity_decode($row1["name"])."<br/>".html_entity_decode($row[$date_code]);
                    }
                }
            }

            $xz="3";
            for ($x = 4; $x <= 7; $x++) {

                $select_query = "result".$x;
                $select_code = "stats_fd".$x;
                $date_code = "date_".$xz;

                if($row[$select_code] == NULL){
                    $sub_array[] = "";
                }else{

                    $object->query = "SELECT name FROM post_mgt_select where select_pos = '".$select_code."' and  select_code = '".$row[$select_code]."'";
                    $select_query = $object->get_result();
                    foreach($select_query as $row1)
                    {
                        $sub_array[] = html_entity_decode($row1["name"])."<br/>".html_entity_decode($row[$date_code]);
                    }
                }
                $xz++;
            }

            $sub_array[] = html_entity_decode($row["last_uesr"]);
            if ($level_check <= "2") {
                $sub_array[] = '
                <div align="center">
                    <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["idx"] . '">관리</button>
                    <button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="' . $row["idx"] . '">삭제</button>
                </div>
                ';
            }else{
                $sub_array[] = '
                <div align="center">
                    <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["idx"] . '">보기</button>
                </div>';
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

    if($_POST["action"] == 'fetch_comment')
    {
        $order_column = array('id', 'name', 'content');

        $output = array();

        $main_query = "
		SELECT * FROM comment 
        ";

        $search_query = '';

        if(isset($_POST["search"]["value"]))
        {
           // $search_query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
            //$search_query .= 'OR content LIKE "%'.$_POST["search"]["value"].'%" ';
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
            if($row["secret"] == "true" && $level_check == "3") {
            }else{
            $sub_array = array();
            $sub_array[] = substr(html_entity_decode($row["write_date"]), 5, 6);
            $sub_array[] = html_entity_decode($row["secret"]);
            $sub_array[] = html_entity_decode($row["name"]);
            $sub_array[] = html_entity_decode($row["content"]);
            $sub_array[] = '
			<div align="center">
			    <button type="button" name="comment_delete_button" class="btn btn-danger btn-circle btn-sm comment_delete_button" data-id="'.$row["idx"].'">삭제</button>
			</div>
			';

            $data[] = $sub_array;
            }
        }

        $output = array(
            "draw"    			=> 	intval($_POST["draw"]),
            "recordsTotal"  	=>  $total_rows,
            "recordsFiltered" 	=> 	$filtered_rows,
            "data"    			=> 	$data
        );

        echo json_encode($output);
    }

    if($_POST["action"] == 'fetch_comment1')
    {
        $order_column = array('id');
        $output = array();
        $main_query = "
		SELECT * FROM comment 
        WHERE comment_code = '".$_POST['class_id']."'
        ";


        $search_query = '';

        if(isset($_POST["search"]["value"]))
        {
            $search_query .= 'AND ( name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR content LIKE "%'.$_POST["search"]["value"].'%" )';
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
            if($row["secret"] == "true" && $level_check == "3") {

            }else{


            if($row["secret"] == "true"){
                $secret_txt = "<i class='feather icon-lock'></i>";
            }else{
                $secret_txt = "";
            }
            $sub_array = array();

            $sub_array[] = html_entity_decode($row["name"]);
            $sub_array[] = html_entity_decode($secret_txt." ".$row["content"]);
            $sub_array[] = substr(html_entity_decode($row["write_date"]), 5, 6);
            $sub_array[] = '
			<div align="center">
			    <button type="button" name="comment_delete_button" class="btn btn-danger btn-circle btn-sm comment_delete_button" data-id="'.$row["idx"].'">삭제</button>
			</div>
			';
            $data[] = $sub_array;

            }
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
            ':user_name'	=>	$_POST["user_name"],
            ':phone1'	=>	$_POST["phone1"]
        );
        $object->query = "
		SELECT * FROM post 
		WHERE user_name = :user_name 
        AND phone1 = :phone1
		";
        $object->execute($data);
        if($object->row_count() > 0)
        {
            $error = '이미 동일한 가진 데이터가 있습니다.';
        }
        else
        {
            $user_image = '';
            if($_FILES["user_image"]["name"] != '')
            {
                $user_image = upload_image();
            }
            else
            {
                //$user_image = make_avatar(strtoupper($_POST["user_name"][0]));
                $user_image = "";
            }


            if($level_check == 1){
                $data = array(
                    ':post_code'			=>	$post_uid,
                    ':user_name'			=>	$object->clean_input($_POST["user_name"]),
                    ':phone1'			=>	$object->clean_input($_POST["phone1"]),
                    ':phone2'			=>	$object->clean_input($_POST["phone2"]),
                    ':step1'			=>	$object->clean_input($_POST["step1"]),
                    ':step2'			=>	$object->clean_input($_POST["step2"]),
                    ':step3'			=>	$object->clean_input($_POST["step3"]),
                    ':step4'			=>	$object->clean_input($_POST["step4"]),
                    ':step5'			=>	$object->clean_input($_POST["step5"]),
                    ':price_0'			=>	$object->clean_input($_POST["price_0"]),
                    ':price_1'			=>	$object->clean_input($_POST["price_1"]),
                    ':price_2'			=>	$object->clean_input($_POST["price_2"]),
                    ':price_3'			=>	$object->clean_input($_POST["price_3"]),
                    ':price_4'			=>	$object->clean_input($_POST["price_4"]),
                    ':price_5'			=>	$object->clean_input($_POST["price_5"]),
                    ':price_6'			=>	$object->clean_input($_POST["price_6"]),
                    ':price1_0'			=>	$object->clean_input($_POST["price1_0"]),
                    ':price1_1'			=>	$object->clean_input($_POST["price1_1"]),
                    ':price1_2'			=>	$object->clean_input($_POST["price1_2"]),
                    ':price1_3'			=>	$object->clean_input($_POST["price1_3"]),
                    ':price1_4'			=>	$object->clean_input($_POST["price1_4"]),
                    ':price1_5'			=>	$object->clean_input($_POST["price1_5"]),
                    ':price1_6'			=>	$object->clean_input($_POST["price1_6"]),
                    ':distance_select'			=>	$object->clean_input($_POST["distance_select"]),
                    ':price_hap'			=>	$object->clean_input($_POST["price_hap"]),
                    ':price_hap1'			=>	$object->clean_input($_POST["price_hap1"]),
                    ':addr'			=>	$object->clean_input($_POST["addr"]),
                    ':center_name'			=>	$object->clean_input($_POST["center_name"]),
                    ':frist_name'			=>	$object->clean_input($_POST["login_name"]),
                    ':last_name'			=>	$object->clean_input($_POST["login_name"]),
                    ':memo'			=>	$object->clean_input($_POST["memo"]),
                    ':memo1'			=>	$object->clean_input($_POST["memo1"]),
                    ':user_profile'		=>	$user_image,
                    ':frist_user'			=>	$object->clean_input($_POST["login_name"]),
                    ':last_uesr'			=>	$object->clean_input($_POST["login_name"]),
                    ':write_date'		=>	$object->now,
                    ':update_date'		=>	$object->now
                );

            }else{
                $data = array(
                    ':post_code'			=>	$post_uid,
                    ':user_name'			=>	$object->clean_input($_POST["user_name"]),
                    ':phone1'			=>	$object->clean_input($_POST["phone1"]),
                    ':phone2'			=>	$object->clean_input($_POST["phone2"]),
                    ':step1'			=>	$object->clean_input($_POST["step1"]),
                    ':step2'			=>	$object->clean_input($_POST["step2"]),
                    ':step3'			=>	$object->clean_input($_POST["step3"]),
                    ':step4'			=>	$object->clean_input($_POST["step4"]),
                    ':step5'			=>	$object->clean_input($_POST["step5"]),
                    ':price_0'			=>	$object->clean_input($_POST["price_0"]),
                    ':price_1'			=>	$object->clean_input($_POST["price_1"]),
                    ':price_2'			=>	$object->clean_input($_POST["price_2"]),
                    ':price_3'			=>	$object->clean_input($_POST["price_3"]),
                    ':price_4'			=>	$object->clean_input($_POST["price_4"]),
                    ':price_5'			=>	$object->clean_input($_POST["price_5"]),
                    ':price_6'			=>	$object->clean_input($_POST["price_6"]),
                    ':price1_0'			=>	$object->clean_input($_POST["price1_0"]),
                    ':price1_1'			=>	$object->clean_input($_POST["price1_1"]),
                    ':price1_2'			=>	$object->clean_input($_POST["price1_2"]),
                    ':price1_3'			=>	$object->clean_input($_POST["price1_3"]),
                    ':price1_4'			=>	$object->clean_input($_POST["price1_4"]),
                    ':price1_5'			=>	$object->clean_input($_POST["price1_5"]),
                    ':price1_6'			=>	$object->clean_input($_POST["price1_6"]),
                    ':distance_select'			=>	"",
                    ':price_hap'			=>	$object->clean_input($_POST["price_hap"]),
                    ':price_hap1'			=>	$object->clean_input($_POST["price_hap1"]),
                    ':addr'			=>	$object->clean_input($_POST["addr"]),
                    ':center_name'			=>	"주식회사 옳음",
                    ':frist_name'			=>	$object->clean_input($_POST["frist_name"]),
                    ':last_name'			=>	$object->clean_input($_POST["last_name"]),
                    ':memo'			=>	$object->clean_input($_POST["memo"]),
                    ':memo1'			=>	$object->clean_input($_POST["memo1"]),
                    ':user_profile'		=>	$user_image,
                    ':frist_user'			=>	$login_mame,
                    ':last_uesr'			=>	$login_mame,
                    ':write_date'		=>	$object->now,
                    ':update_date'		=>	$object->now
                );

            }


            $object->query = "
			INSERT INTO post 
			(post_code, user_name, phone1, phone2, step1, step2, step3, step4, step5, price_0, price_1, price_2, price_3, price_4, price_5, price_6, price1_0, price1_1, price1_2, price1_3, price1_4, price1_5, price1_6, distance_select, price_hap, price_hap1, addr, center_name, frist_name, last_name, memo, memo1, user_profile, frist_user, last_uesr, write_date, update_date) 
			VALUES (:post_code, :user_name, :phone1, :phone2, :step1, :step2, :step3, :step4, :step5, :price_0, :price_1, :price_2, :price_3, :price_4, :price_5, :price_6, :price1_0, :price1_1, :price1_2, :price1_3, :price1_4, :price1_5, :price1_6, :distance_select, :price_hap, :price_hap1, :addr, :center_name, :frist_name, :last_name, :memo, :memo1, :user_profile, :frist_user, :last_uesr, :write_date, :update_date)
			";

            $object->execute($data);

            $object->query = "
            SELECT * FROM post_mgt 
            WHERE post_code = '".$post_uid."'
            ";

            $object->execute();

            if($object->row_count() == 0)
            {
                $data = array(
                    ':post_code'	=>	$post_uid
                );
                $object->query = "
                INSERT INTO post_mgt 
                (post_code) 
                VALUES (:post_code)
                ";
                $object->execute($data);
            }
            $success = '<div class="alert alert-success">접수신청이 되었습니다.</div>';
        }

        $output = array(
            'error'		=>	$error,
            'success'	=>	$success
        );

        echo json_encode($output);

    }

    if($_POST["action"] == 'comment_add')
    {

        $error = '';
        $success = '';
        $post_uid = uniqid();

        $data = array(
            ':comment_code'			=>	$object->clean_input($_POST["c_hidden_id"]),
            ':id'			=>	$object->login_id(),
            ':user_name'			=>	$object->login_mame(),
            ':cm_content'			=>	$object->clean_input($_POST["comment_content"]),
            ':secret'			=>	$object->clean_input($_POST["switch_1"]),
            ':write_date'		=>	$object->now,
            ':update_date'		=>	$object->now
        );

        $object->query = "
        INSERT INTO comment 
        (comment_code, id, name, content, secret, write_date, update_date) 
        VALUES (:comment_code, :id, :user_name, :cm_content, :secret, :write_date, :update_date)
        ";

        $object->execute($data);
        echo '<div class="alert alert-success">댓글이 등록 되었습니다.</div>';

    }

    if($_POST["action"] == 'materialcontrol_add')
    {

        $error = '';
        $success = '';
        $post_uid = uniqid();

        $object->query = "
		SELECT * FROM materialcontrol_data 
		WHERE group_code = '".$_POST["m_hidden_id"]."'
		";
        $object->execute();

        if($object->row_count() > 0)
        {
            //이미 데이터 존재 할시 삭제
            $object->query = "
            DELETE FROM materialcontrol_data 
		    WHERE group_code = '".$_POST["m_hidden_id"]."'
            ";
            $object->execute();

        }

        for ($x = 0; $x < count($_POST['repeater_data']["group-a"]); $x++) {
            $data = array(
                ':group_code'			=>	$_POST['m_hidden_id'],
                ':material1'			=>	$_POST['repeater_data']["group-a"][$x]['material1'],
                ':material2'			=>	$_POST['repeater_data']["group-a"][$x]['material2']
            );

            $object->query = "
            INSERT INTO materialcontrol_data 
            (group_code, group_data1, group_data2) 
            VALUES (:group_code, :material1, :material2)
            ";
            $object->execute($data);
        }

        echo '정상적으로 처리 되었습니다.';

    }



    if($_POST["action"] == 'fetch_single')
    {
        $object->query = "
		SELECT * FROM post 
		INNER JOIN post_mgt 
		ON post.idx = post_mgt.idx_no
		WHERE idx = '".$_POST["class_id"]."'
		";

        $result = $object->get_result();

        $data = array();

        foreach($result as $row)
        {
            //첨부파일 이미지 처리
            if($row['user_profile'] != NULL){
                $user_profile = '<a href="'.$row['user_profile'].'" target="_blank" download> <button type="button" class="btn btn-secondary btn-sm" >완료확인서 다운로드</button> </a><div style="padding-top:10px;"><input type="button" class="btn btn-danger img_delete btn-sm" value="삭제"></div>';
            }else{
                $user_profile = "";
            }
            $data['idx'] = $row['idx'];
            $data['user_name'] = $row['user_name'];
            $data['phone1'] = $row['phone1'];
            $data['phone2'] = $row['phone2'];
            $data['step1'] = $row['step1'];
            $data['step2'] = $row['step2'];
            $data['step3'] = $row['step3'];
            $data['step4'] = $row['step4'];
            $data['step5'] = $row['step5'];

            $object->query = "SELECT * FROM category where parent_id = '0'";
            $select_query_load = $object->get_result();
            foreach ($select_query_load as $row1) {
                if($row1["id_menu"] == $row['step1']){
                    $select_choice = "selected";
                }else{
                    $select_choice = "";
                }
                $data['step0_text'] .= '<option id="'. $row['step1'].'" value="'.$row1["id_menu"].'" '.$select_choice.' >'.$row1["label_menu"].'</option>';
            };


            $k = 2;
            for ($x = 1; $x <= 4; $x++) {

                $select_query = "test_".$x;
                $select_code = "step".$x;
                $select_code1 = "step".$x."_text";
                $select_code3 = "step".$k;

                if($row[$select_code] == NULL){
                    $sub_array[] = "";
                }else {
                    $object->query = "SELECT * FROM category where parent_id = '" . $row[$select_code] . "'";
                    $select_query = $object->get_result();
                    foreach ($select_query as $row1) {
                        if($row1["id_menu"] == $row[$select_code3]){
                            $select_choice = "selected";
                        }else{
                            $select_choice = "";
                        }

                        $data[$select_code1] .= '<option id="'. $row[$select_code].'" value="'.$row1["id_menu"].'" '.$select_choice.' >'.$row1["label_menu"].'</option>';
                    }
                }
                $k++;
            }

            $object->query = "SELECT * FROM travel_expenses";
            $travel_expenses = $object->get_result();
            foreach ($travel_expenses as $row1) {
                $object->query = "SELECT * FROM travel_expenses where name = '" . $row['step5'] . "'";
                $travel_expenses1 = $object->get_result();
                foreach ($travel_expenses1 as $row2) {
                    if($row1["name"] == $row2["name"]){
                        $select_choice = "selected";
                    }else{
                        $select_choice = "";
                    }

                    $data['step5_text'] .= '<option id="'. $row1['price'].','. $row1['price_center'].'" value="'.$row1["name"].'" '.$select_choice.' >'.$row1["name"].'</option>';
                }
            }

            $object->query = "SELECT * FROM travel_expenses where name = '" . $row['step5'] . "'";
            $travel_expenses2 = $object->get_result();
            foreach ($travel_expenses2 as $row2) {
                $data['step5_price'] .= $row2["price"];
            }
            $data['price_0'] = $row['price_0'];
            $data['price_1'] = $row['price_1'];
            $data['price_2'] = $row['price_2'];
            $data['price_3'] = $row['price_3'];
            $data['price_4'] = $row['price_4'];
            $data['price_5'] = $row['price_5'];
            $data['price_6'] = $row['price_6'];
            $data['price1_0'] = $row['price1_0'];
            $data['price1_1'] = $row['price1_1'];
            $data['price1_2'] = $row['price1_2'];
            $data['price1_3'] = $row['price1_3'];
            $data['price1_4'] = $row['price1_4'];
            $data['price1_5'] = $row['price1_5'];
            $data['price1_6'] = $row['price1_6'];
            $data['distance_select'] = $row['distance_select'];
            $data['price_hap'] = $row['price_hap'];
            $data['price_hap1'] = $row['price_hap1'];
            $data['addr'] = $row['addr'];
            $data['center_name'] = $row['center_name'];
            $data['last_name'] = $row['last_name'];
            $data['memo'] = $row['memo'];
            $data['memo1'] = $row['memo1'];
            $data['user_profile'] = $user_profile;
            $data['stats_fd1'] = $row['stats_fd1'];
            $data['stats_fd2'] = $row['stats_fd2'];
            $data['stats_fd3'] = $row['stats_fd3'];
            $data['stats_fd4'] = $row['stats_fd4'];
            $data['stats_fd5'] = $row['stats_fd5'];
            $data['stats_fd6'] = $row['stats_fd6'];
            $data['stats_fd7'] = $row['stats_fd7'];
            $data['date_1'] = $row['date_1'];
            $data['date_2'] = $row['date_2'];
            $data['date_3'] = $row['date_3'];
            $data['date_4'] = $row['date_4'];
            $data['date_5'] = $row['date_5'];
            $data['date_6'] = $row['date_6'];
            $data['frist_user'] = $row['frist_user'];
            $data['last_uesr'] = $row['last_uesr'];
            $data['write_date'] = $row['write_date'];
            $data['update_date'] = $row['update_date'];

        }
        echo json_encode($data);
    }

    if($_POST["action"] == 'mt_single')
    {

        $object->query = "SELECT * FROM materialcontrol";
        $result = $object->get_result();
        foreach($result as $row)
        {
            $material_options .= '<option value="'.$row["model_name"].'">'.$row["model_name"].'</option>';
        }

        $object->query = "
		SELECT * FROM materialcontrol_data 
		WHERE group_code = '".$_POST["class_id"]."'
		";

        $result = $object->get_result();
        $data = array();

        foreach($result as $row => $value)
        {
            $data['group_data1'][] .='
            <div class="form-row" data-repeater-item>
                <div class="col-md-6 mb-3">
                    <select name="group-a['.$row.'][material1]" required class="form-control">
                        <option value="'.$value['group_data1'].'">'.$value['group_data1'].'</option>
                         '.$material_options.'
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <input type="number" required min="0" max="999"  name="group-a['.$row.'][material2]" value="'.$value['group_data2'].'" class="form-control" placeholder="수량을 입력해주세요"/>
                </div>
                <div class="col-md-3 mb-3">
                    <input data-repeater-delete type="button" class="btn btn-danger " value="삭제"/>
                </div>
            </div>
            ';
        }

        echo json_encode($data);
    }

    if($_POST["action"] == 'Edit')
    {
        $error = '';
        $success = '';
        $data = array(
            ':idx'	=>	$_POST["idx"],
            ':user_name'	=>	$_POST["user_name"],
            ':phone1'	=>	$_POST["phone1"],
            ':phone2'	=>	$_POST["phone2"],
            ':step1'	=>	$_POST["step1"],
            ':step2'	=>	$_POST["step2"],
            ':step3'	=>	$_POST["step3"],
            ':step4'	=>	$_POST["step4"],
            ':step5'	=>	$_POST["step5"],
            ':price_0'	=>	$_POST["price_0"],
            ':price_1'	=>	$_POST["price_1"],
            ':price_2'	=>	$_POST["price_2"],
            ':price_3'	=>	$_POST["price_3"],
            ':price_4'	=>	$_POST["price_4"],
            ':price_5'	=>	$_POST["price_5"],
            ':price_6'	=>	$_POST["price_6"],
            ':price1_0'	=>	$_POST["price1_0"],
            ':price1_1'	=>	$_POST["price1_1"],
            ':price1_2'	=>	$_POST["price1_2"],
            ':price1_3'	=>	$_POST["price1_3"],
            ':price1_4'	=>	$_POST["price1_4"],
            ':price1_5'	=>	$_POST["price1_5"],
            ':price1_6'	=>	$_POST["price1_6"],
            ':distance_select'	=>	$_POST["distance_select"],
            ':price_hap'	=>	$_POST["price_hap"],
            ':price_hap1'	=>	$_POST["price_hap1"],
            ':addr'	=>	$_POST["addr"],
            ':center_name'	=>	$_POST["center_name"],
            ':last_name'	=>	$_POST["last_name"],
            ':memo'	=>	$_POST["memo"],
            ':memo1'	=>	$_POST["memo1"],
            ':user_profile'	=>	$_POST["user_profile"],
            ':stats_fd1'	=>	$_POST["stats_fd1"],
            ':stats_fd2'	=>	$_POST["stats_fd2"],
            ':stats_fd3'	=>	$_POST["stats_fd3"],
            ':stats_fd4'	=>	$_POST["stats_fd4"],
            ':stats_fd5'	=>	$_POST["stats_fd5"],
            ':stats_fd6'	=>	$_POST["stats_fd6"],
            ':stats_fd7'	=>	$_POST["stats_fd7"],
            ':date_1'	=>	$_POST["date_1"],
            ':date_2'	=>	$_POST["date_2"],
            ':date_3'	=>	$_POST["date_3"],
            ':date_4'	=>	$_POST["date_4"],
            ':date_5'	=>	$_POST["date_5"],
            ':date_6'	=>	$_POST["date_6"],
            ':hidden_id'		=>	$_POST['hidden_id'],

        );

        $object->query = "
		SELECT * FROM post 
		INNER JOIN post_mgt 
		ON post.idx = post_mgt.idx_no
		WHERE idx = :idx 
		
		";


        $object->execute($data);

        if($object->row_count() > 0)
        {
            $error = '<div class="alert alert-danger">Class Name Already Exists</div>';
        }
        else
        {
            $user_image = $_POST["hidden_user_image"];
            if($_FILES["user_image"]["name"] != '')
            {
                $user_image = upload_image();
            }

            if($level_check == 1) {
                $data = array(
                    ':user_name'		=>	$object->clean_input($_POST["user_name"]),
                    ':phone1'		=>	$object->clean_input($_POST["phone1"]),
                    ':phone2'		=>	$object->clean_input($_POST["phone2"]),
                    ':step1'		=>	$object->clean_input($_POST["step1"]),
                    ':step2'		=>	$object->clean_input($_POST["step2"]),
                    ':step3'		=>	$object->clean_input($_POST["step3"]),
                    ':step4'		=>	$object->clean_input($_POST["step4"]),
                    ':step5'		=>	$object->clean_input($_POST["step5"]),
                    ':price_0'		=>	$object->clean_input($_POST["price_0"]),
                    ':price_1'		=>	$object->clean_input($_POST["price_1"]),
                    ':price_2'		=>	$object->clean_input($_POST["price_2"]),
                    ':price_3'		=>	$object->clean_input($_POST["price_3"]),
                    ':price_4'		=>	$object->clean_input($_POST["price_4"]),
                    ':price_5'		=>	$object->clean_input($_POST["price_5"]),
                    ':price_6'		=>	$object->clean_input($_POST["price_6"]),
                    ':price1_0'		=>	$object->clean_input($_POST["price1_0"]),
                    ':price1_1'		=>	$object->clean_input($_POST["price1_1"]),
                    ':price1_2'		=>	$object->clean_input($_POST["price1_2"]),
                    ':price1_3'		=>	$object->clean_input($_POST["price1_3"]),
                    ':price1_4'		=>	$object->clean_input($_POST["price1_4"]),
                    ':price1_5'		=>	$object->clean_input($_POST["price1_5"]),
                    ':price1_6'		=>	$object->clean_input($_POST["price1_6"]),
                    ':distance_select'		=>	$object->clean_input($_POST["distance_select"]),
                    ':price_hap'		=>	$object->clean_input($_POST["price_hap"]),
                    ':price_hap1'		=>	$object->clean_input($_POST["price_hap1"]),
                    'addr'		=>	$object->clean_input($_POST["addr"]),
                    ':center_name'		=>	$object->clean_input($_POST["center_name"]),
                    ':last_name'		=>	$login_mame,
                    ':memo'		=>	$object->clean_input($_POST["memo"]),
                    ':memo1'		=>	$object->clean_input($_POST["memo1"]),
                    ':user_profile'		=>	$user_image,
                    ':last_uesr'		=>	$login_mame,
                    ':update_date'		=>	$object->now,
                    ':stats_fd1'		=>	$object->clean_input($_POST["stats_fd1"]),
                    ':stats_fd2'		=>	$object->clean_input($_POST["stats_fd2"]),
                    ':stats_fd3'		=>	$object->clean_input($_POST["stats_fd3"]),
                    ':stats_fd4'		=>	$object->clean_input($_POST["stats_fd4"]),
                    ':stats_fd5'		=>	$object->clean_input($_POST["stats_fd5"]),
                    ':stats_fd6'		=>	$object->clean_input($_POST["stats_fd6"]),
                    ':stats_fd7'		=>	$object->clean_input($_POST["stats_fd7"]),
                    ':date_1'		=>	$object->clean_input($_POST["date_1"]),
                    ':date_2'		=>	$object->clean_input($_POST["date_2"]),
                    ':date_3'		=>	$object->clean_input($_POST["date_3"]),
                    ':date_4'		=>	$object->clean_input($_POST["date_4"]),
                    ':date_5'		=>	$object->clean_input($_POST["date_5"]),
                    ':date_6'		=>	$object->clean_input($_POST["date_6"]),

                );

                $object->query = "
                UPDATE post  
                INNER JOIN post_mgt
                ON post.idx = post_mgt.idx_no
                SET user_name = :user_name,    
                phone1 = :phone1,    
                phone2 = :phone2,    
                step1 = :step1,
                step2 = :step2,
                step3 = :step3,
                step4 = :step4,
                step5 = :step5,
                price_0 = :price_0,
                price_1 = :price_1,
                price_2 = :price_2,
                price_3 = :price_3,
                price_4 = :price_4,
                price_5 = :price_5,
                price_6 = :price_6,
                price1_0 = :price1_0,
                price1_1 = :price1_1,
                price1_2 = :price1_2,
                price1_3 = :price1_3,
                price1_4 = :price1_4,
                price1_5 = :price1_5,
                price1_6 = :price1_6,
                distance_select = :distance_select,    
                price_hap = :price_hap,
                price_hap1 = :price_hap1,
                addr = :addr,    
                center_name = :center_name,
                last_name = :last_name,
                memo = :memo,
                memo1 = :memo1,
                user_profile = :user_profile,
                last_uesr = :last_uesr,
                update_date = :update_date,
                stats_fd1 = :stats_fd1,    
                stats_fd2 = :stats_fd2,    
                stats_fd3 = :stats_fd3,    
                stats_fd4 = :stats_fd4,    
                stats_fd5 = :stats_fd5,    
                stats_fd6 = :stats_fd6,    
                stats_fd7 = :stats_fd7,    
                date_1 = :date_1,
                date_2 = :date_2,
                date_3 = :date_3,
                date_4 = :date_4,
                date_5 = :date_5,
                date_6 = :date_6
                WHERE idx = '" . $_POST['hidden_id'] . "'
			    ";
            }elseif($level_check == 2) {
                $data = array(
                    ':user_name'		=>	$object->clean_input($_POST["user_name"]),
                    ':phone1'		=>	$object->clean_input($_POST["phone1"]),
                    ':phone2'		=>	$object->clean_input($_POST["phone2"]),
                    ':step1'		=>	$object->clean_input($_POST["step1"]),
                    ':step2'		=>	$object->clean_input($_POST["step2"]),
                    ':step3'		=>	$object->clean_input($_POST["step3"]),
                    ':step4'		=>	$object->clean_input($_POST["step4"]),
                    ':step5'		=>	$object->clean_input($_POST["step5"]),
                    ':price_0'		=>	$object->clean_input($_POST["price_0"]),
                    ':price_1'		=>	$object->clean_input($_POST["price_1"]),
                    ':price_2'		=>	$object->clean_input($_POST["price_2"]),
                    ':price_3'		=>	$object->clean_input($_POST["price_3"]),
                    ':price_4'		=>	$object->clean_input($_POST["price_4"]),
                    ':price_5'		=>	$object->clean_input($_POST["price_5"]),
                    ':price_6'		=>	$object->clean_input($_POST["price_6"]),
                    ':price1_0'		=>	$object->clean_input($_POST["price1_0"]),
                    ':price1_1'		=>	$object->clean_input($_POST["price1_1"]),
                    ':price1_2'		=>	$object->clean_input($_POST["price1_2"]),
                    ':price1_3'		=>	$object->clean_input($_POST["price1_3"]),
                    ':price1_4'		=>	$object->clean_input($_POST["price1_4"]),
                    ':price1_5'		=>	$object->clean_input($_POST["price1_5"]),
                    ':price1_6'		=>	$object->clean_input($_POST["price1_6"]),
                    ':price_hap'		=>	$object->clean_input($_POST["price_hap"]),
                    ':price_hap1'		=>	$object->clean_input($_POST["price_hap1"]),
                    'addr'		=>	$object->clean_input($_POST["addr"]),
                    ':last_name'		=>	$login_mame,
                    ':memo'		=>	$object->clean_input($_POST["memo"]),
                    ':memo1'		=>	$object->clean_input($_POST["memo1"]),
                    ':user_profile'		=>	$user_image,
                    ':last_uesr'		=>	$login_mame,
                    ':update_date'		=>	$object->now,
                    ':stats_fd1'		=>	$object->clean_input($_POST["stats_fd1"]),
                    ':stats_fd2'		=>	$object->clean_input($_POST["stats_fd2"]),
                    ':stats_fd3'		=>	$object->clean_input($_POST["stats_fd3"]),
                    ':stats_fd4'		=>	$object->clean_input($_POST["stats_fd4"]),
                    ':stats_fd5'		=>	$object->clean_input($_POST["stats_fd5"]),
                    ':stats_fd6'		=>	$object->clean_input($_POST["stats_fd6"]),
                    ':stats_fd7'		=>	$object->clean_input($_POST["stats_fd7"]),
                    ':date_1'		=>	$object->clean_input($_POST["date_1"]),
                    ':date_2'		=>	$object->clean_input($_POST["date_2"]),
                    ':date_3'		=>	$object->clean_input($_POST["date_3"]),
                    ':date_4'		=>	$object->clean_input($_POST["date_4"]),
                    ':date_5'		=>	$object->clean_input($_POST["date_5"]),
                    ':date_6'		=>	$object->clean_input($_POST["date_6"]),

                );

                $object->query = "
                UPDATE post  
                INNER JOIN post_mgt
                ON post.idx = post_mgt.idx_no
                SET user_name = :user_name,    
                phone1 = :phone1,    
                phone2 = :phone2,    
                step1 = :step1,
                step2 = :step2,
                step3 = :step3,
                step4 = :step4,
                step5 = :step5,
                price_0 = :price_0,
                price_1 = :price_1,
                price_2 = :price_2,
                price_3 = :price_3,
                price_4 = :price_4,
                price_5 = :price_5,
                price_6 = :price_6,
                price1_0 = :price1_0,
                price1_1 = :price1_1,
                price1_2 = :price1_2,
                price1_3 = :price1_3,
                price1_4 = :price1_4,
                price1_5 = :price1_5,
                price1_6 = :price1_6,
                price_hap = :price_hap,
                price_hap1 = :price_hap1,
                addr = :addr,    
                last_name = :last_name,
                memo = :memo,
                memo1 = :memo1,
                user_profile = :user_profile,
                last_uesr = :last_uesr,
                update_date = :update_date,
                stats_fd1 = :stats_fd1,    
                stats_fd2 = :stats_fd2,    
                stats_fd3 = :stats_fd3,    
                stats_fd4 = :stats_fd4,    
                stats_fd5 = :stats_fd5,    
                stats_fd6 = :stats_fd6,    
                stats_fd7 = :stats_fd7,    
                date_1 = :date_1,
                date_2 = :date_2,
                date_3 = :date_3,
                date_4 = :date_4,
                date_5 = :date_5,
                date_6 = :date_6
                WHERE idx = '" . $_POST['hidden_id'] . "'
			    ";
            }else{
                $data = array(
                    ':last_name'		=>	$login_mame,
                    ':distance_select'		=>	$object->clean_input($_POST["distance_select"]),
                    ':price_2'		=>	$object->clean_input($_POST["price_2"]),
                    ':price1_2'		=>	$object->clean_input($_POST["price1_2"]),
                    ':price_hap'		=>	$object->clean_input($_POST["price_hap_hd"]),
                    ':price_hap1'		=>	$object->clean_input($_POST["price_hap1_hd"]),
                    ':memo1'		=>	$object->clean_input($_POST["memo1"]),
                    ':user_profile'		=>	$user_image,
                    ':last_uesr'		=>	$login_mame,
                    ':update_date'		=>	$object->now,
                    ':stats_fd2'		=>	$object->clean_input($_POST["stats_fd2"]),
                    ':stats_fd3'		=>	$object->clean_input($_POST["stats_fd3"]),
                    ':stats_fd5'		=>	$object->clean_input($_POST["stats_fd5"]),
                    ':stats_fd7'		=>	$object->clean_input($_POST["stats_fd7"]),
                    ':date_2'		=>	$object->clean_input($_POST["date_2"]),
                    ':date_4'		=>	$object->clean_input($_POST["date_4"]),
                    ':date_6'		=>	$object->clean_input($_POST["date_6"]),
                );

                $object->query = "
                UPDATE post  
                INNER JOIN post_mgt
                ON post.idx = post_mgt.idx_no
                SET last_name = :last_name,
                distance_select = :distance_select,
                price_2 = :price_2,
                price1_2 = :price1_2,     
                price_hap = :price_hap,
                price_hap1 = :price_hap1,
                memo1 = :memo1,
                user_profile = :user_profile, 
                last_uesr = :last_uesr,
                update_date = :update_date,
                stats_fd2 = :stats_fd2,    
                stats_fd3 = :stats_fd3,      
                stats_fd5 = :stats_fd5,    
                stats_fd7 = :stats_fd7,    
                date_2 = :date_2,
                date_4 = :date_4,
                date_6 = :date_6
                WHERE idx = '" . $_POST['hidden_id'] . "'
			    ";
            }
            $object->execute($data);


            $success = '접수 정보가 수정되었습니다.';
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
		DELETE FROM post 
		WHERE idx = '".$_POST["id"]."'
		";
        $object->execute();
        $object->query = "
		DELETE FROM post_mgt 
		WHERE idx = '".$_POST["id"]."'
		";
        $object->execute();
        echo '해당 데이터가 삭제 되었습니다.';
    }

    if($_POST["action"] == 'comment_delete')
    {

        $object->query = "
		SELECT * FROM comment 
		WHERE idx = '".$_POST["id"]."'
		";
        $result = $object->get_result();

        $data = array();

        foreach($result as $row) {
            $data['id'] = $row['id'];
        }

        if($data['id'] == $login_name || $level_check == "1"){
            $object->query = "
            DELETE FROM comment 
            WHERE idx = '".$_POST["id"]."'
            ";
            $object->execute();
            echo '정상적으로 삭제되었습니다.';
        }else{
            echo '삭제 권한이 없습니다.';
        }

    }
    if($_POST["action"] == 'img_delete')
    {

        $object->query = "
        UPDATE post  
        SET user_profile = ''
        WHERE idx  = '".$_POST["id"]."'
		";
        $object->execute();
        echo '파일이 삭제 되었습니다.</div>';
    }
}

function upload_image()
{
    if(isset($_FILES["user_image"]))
    {
        $extension = explode('.', $_FILES['user_image']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = '../data/' . $new_name;
        move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
        return $destination;
    }else{

    }
}

function make_avatar($character)
{
    $path = "../images/". time() . ".png";
    $image = imagecreate(200, 200);
    $red = rand(0, 255);
    $green = rand(0, 255);
    $blue = rand(0, 255);
    imagecolorallocate($image, 230, 230, 230);
    $textcolor = imagecolorallocate($image, $red, $green, $blue);
    imagettftext($image, 100, 0, 55, 150, $textcolor, '../font/arial.ttf', $character);
    imagepng($image, $path);
    imagedestroy($image);
    return $path;
}
?>