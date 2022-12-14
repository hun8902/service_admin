<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
include('../config/db_con.php');
include('../include/Compat/Function/hash_hmac.php');

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
    header("location:index.php");
}

if(!empty($_GET["search"])){$search = $_GET["search"];}
if(!empty($_GET["status"])){$status = $_GET["status"];}
if(!empty($_GET["keyword"])){$keyword = $_GET["keyword"];}
if(!empty($_GET["key"])){$key = $_GET["key"];}
if(!empty($_GET["sdate1"])){$sdate1 = $_GET["sdate1"];}
if(!empty($_GET["sdate2 "])){$sdate2 = $_GET["sdate2"];}
if(!empty($_GET["date_gubun"])){$date_gubun = $_GET["date_gubun"];}
if(!empty($_GET["service"])){$service = $_GET["service"];}
if(!empty($_GET["gubun"])){$gubun = $_GET["gubun"];}

$object = new srms();
$addQry = " WHERE 1 = 1";

function format_phone($phone){
    $phone = preg_replace("/[^0-9]/", "", $phone);
    $length = strlen($phone);
    switch($length){
        case 11 :
            return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            break;
        case 10:
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            break;
        default :
            return $phone;
            break;
    }
}


//검색
if($search == "ok") {
    //진행상태
    if($status) {
        $addQry .= " AND status = '$status'";
    }
    //고객조회
    if($keyword) {
        if($key == "hp") {
            $hp = explode("-",$keyword);
            $addQry .= " AND hp1 = '$hp[0]' AND hp2 = '$hp[1]' AND hp3 = '$hp[2]'";
        }
        else if($key == "tel") {
            $tel = explode("-",$keyword);
            $addQry .= " AND tel1 = '$tel[0]' AND tel2 = '$tel[1]' AND tel3 = '$tel[2]'";
        }
        else {
            $addQry .= " AND $key LIKE '%$keyword%'";
        }
    }

    //검색기간
    if($sdate1 && $sdate2) {
        $sdate1 = str_replace('-','',$sdate1);
        $sdate2 = str_replace('-','',$sdate2);

        if($date_gubun == "status_date") $addQry .= " AND status_date >= '$sdate1' AND status_date <= '$sdate2'"; //진행일
        else                             $addQry .= " AND substring(date,1,8) >= '$sdate1' AND substring(date,1,8) <= '$sdate2'"; //접수일
    }

    //렌탈사
    if($service) {
        $addQry .= " AND code3 = '$service'";
    }

    //상품군
    if($gubun) {
        $addQry .= " AND code5 = '$gubun'";
    }
}


//총게시물(검색결과)
$object->query = "SELECT count(*) as cnt FROM post INNER JOIN post_mgt ON post.idx = post_mgt.idx_no $addQry";
$notice = $object->get_result();

foreach ($notice as $row) {
    $total_num = $row['cnt'];
}

$FileName = "접수내역";
/*
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=$FileName.xls" );
header( "Content-Description: PHP4 Generated Data" );

*/
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-type: application/x-msexcel; charset=utf-8");
header( "Content-Disposition: attachment; filename=$FileName.xls" );
header('Cache-Control: max-age=0');

?>
<html>
<head>
<style>
    table{white-space:nowrap;border-collapse:collapse}
td {color: #333333; font-family: 굴림; font-size: 12px; height:22px;}
</style>
</head>
<body>
<div style="width: 100%; overflow: auto;">
<table border="1" cellspacing="0" cellpadding="0">
<tr>
    <th rowspan="2" style="width:50px;">NO</th>
    <th rowspan="2" style="width:80px;">등록일</th>
    <th rowspan="2" style="width:80px;">고객명</th>
    <th rowspan="2" style="width:80px;">연락처</th>
    <th rowspan="2" style="width:80px;">센터</th>
    <th rowspan="2" style="width:200px;">주소</th>
    <th rowspan="2" style="width:300px;">접수증상</th>
    <th rowspan="2" style="width:300px;">기사 확인 사항</th>
    <?php if($level_check <= "2"){ ?>
    <th colspan="7"><? if($level_check == "1"){ ?>본사 <? }?> 비용</th>
    <? } ?>
    <?php if($level_check == "1" || $level_check == "3"){ ?>
    <th colspan="7"><? if($level_check == "1"){ ?>센터 <? }?>비용</th>
    <? } ?>
    <th colspan="5">서비스</th>
    <th colspan="2">접수상태</th>
    <th colspan="2">회수상태</th>
    <th colspan="2">정산상태</th>
    <th rowspan="2" style="width:200px;">검수자</th>
</tr>
<tr>
    <?php if($level_check <= "2"){ ?>
    <th style="width:80px;">출장비</th>
    <th style="width:80px;">수리비</th>
    <th style="width:80px;">거리비</th>
    <th style="width:80px;">긴급</th>
    <th style="width:80px;">자재</th>
    <th style="width:80px;">기타</th>
    <th style="width:80px;">총비용</th>
    <? } ?>
    <?php if($level_check == "1" || $level_check == "3"){ ?>
    <th style="width:80px;">출장비</th>
    <th style="width:80px;">수리비</th>
    <th style="width:80px;">거리비</th>
    <th style="width:80px;">긴급</th>
    <th style="width:80px;">자재</th>
    <th style="width:80px;">기타</th>
    <th style="width:80px;">총비용</th>
    <? } ?>

    <th style="width:100px;">모델명</th>
    <th style="width:100px;">구분</th>
    <th style="width:100px;">분류1</th>
    <th style="width:100px;">분류2</th>
    <th style="width:100px;">분류3</th>
    <th style="width:100px;">본사</th>
    <th style="width:100px;">센터</th>
    <th style="width:100px;">본사</th>
    <th style="width:100px;">센터</th>
    <th style="width:100px;">본사</th>
    <th style="width:100px;">센터</th>
</tr>
<?
$object->query = "SELECT * FROM post INNER JOIN post_mgt ON post.idx = post_mgt.idx_no $addQry ORDER BY idx DESC";
$viewRow = $object->get_result();
foreach ($viewRow as $row) {
    $address = explode(" ",$row['address1']);
?>
<tr>
    <!-- NO -->
    <td align="center"><?= $total_num ?></td>
    <!-- 접수일 -->
    <td align="center"><?= substr(html_entity_decode($row["write_date"]), 2, 8) ?></td>
    <!-- 고객명 -->
    <td><? if(!empty($row['select_name'])) { ?>[<?= $row['select_name'] ?>] <? } ?><?= $row['user_name'] ?></td>
    <td><?= format_phone($row['phone1']); ?></td>
    <td><?= $row['center_name'] ?></td>
    <td><?= $row['addr'] ?></td>
    <td><?= $row['memo'] ?></td>
    <td><?= $row['memo1'] ?></td>
    <?php if($level_check <= "2"){ ?>
    <td><? if(!empty($row['price_0'])){echo number_format($row['price_0']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_1'])){echo number_format($row['price_1']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_2'])){echo number_format($row['price_2']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_3'])){echo number_format($row['price_3']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_4'])){echo number_format($row['price_4']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_5'])){echo number_format($row['price_5']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_hap'])){echo number_format($row['price_hap']) ?>원<? } ?></td>
    <?php } ?>
    <?php if($level_check == "1" || $level_check == "3"){ ?>
    <td><? if(!empty($row['price1_0'])){echo number_format($row['price1_0']) ?>원<? } ?></td>
    <td><? if(!empty($row['price1_1'])){echo number_format($row['price1_1']) ?>원<? } ?></td>
    <td><? if(!empty($row['price1_2'])){echo number_format($row['price1_2']) ?>원<? } ?></td>
    <td><? if(!empty($row['price1_3'])){echo number_format($row['price1_3']) ?>원<? } ?></td>
    <td><? if(!empty($row['price1_4'])){echo number_format($row['price1_4']) ?>원<? } ?></td>
    <td><? if(!empty($row['price1_5'])){echo number_format($row['price1_5']) ?>원<? } ?></td>
    <td><? if(!empty($row['price_hap1'])){echo number_format($row['price_hap1']) ?>원<? } ?></td>
    <?php } ?>
    <?
    for ($x = 1; $x <= 4; $x++) {
        $select_query = "test_".$x;
        $select_code = "step".$x;
        if($row[$select_code] == NULL){
            echo "<td></td>";
        }else {
            $object->query = "SELECT label_menu FROM category where id_menu = '" . $row[$select_code] . "'";
            $select_query = $object->get_result();
            foreach ($select_query as $row1) {
                if($row1["label_menu"] != NULL){
                    echo "<td>".html_entity_decode($row1["label_menu"])."</td>";
                }else{
                    echo "<td></td>";
                }
            }
        }
    }

    //출장비 분리
    if ($row["step5"] == NULL) {
        echo "<td></td>";
    } else {
        $object->query = "SELECT * FROM travel_expenses where name = '" . $row["step5"] . "'";
        $select_query = $object->get_result();
        foreach ($select_query as $row1) {
            if ($row1["name"] != NULL) {
                echo "<td>" . html_entity_decode($row1["name"]) . "</td>";
            } else {
                echo "<td></td>";
            }
        }
    }

    for ($x = 1; $x <= 2; $x++) {
        $select_query = "result".$x;
        $select_code = "stats_fd".$x;
        $date_code = "date_".$x;

        if($row[$select_code] == NULL){
            echo "<td></td>";
        }else{

            $object->query = "SELECT name FROM post_mgt_select where select_pos = '".$select_code."' and  select_code = '".$row[$select_code]."'";
            $select_query = $object->get_result();
            foreach($select_query as $row1)
            {
                echo "<td>".html_entity_decode($row1["name"])."<br/>".html_entity_decode($row[$date_code])."</td>";
            }
        }
    }

    $xz="3";
    for ($x = 4; $x <= 7; $x++) {

        $select_query = "result".$x;
        $select_code = "stats_fd".$x;
        $date_code = "date_".$xz;

        if($row[$select_code] == NULL){
            echo "<td></td>";
        }else{

            $object->query = "SELECT name FROM post_mgt_select where select_pos = '".$select_code."' and  select_code = '".$row[$select_code]."'";
            $select_query = $object->get_result();
            foreach($select_query as $row1)
            {
                echo "<td>".html_entity_decode($row1["name"])."<br/>".html_entity_decode($row[$date_code])."</td>";
            }
        }
        $xz++;
    }

    echo "<td>".html_entity_decode($row["last_uesr"])."</td>";

    ?>

    </td>
</tr>
<?
  $total_num--;
}
?>
</table>
</div>
</body>
</html>