<?php
include('../config/db_con.php');
include('../include/Compat/Function/hash_hmac.php');
$object = new srms();
session_start();

if(!empty($_GET["search"])){$search = $_GET["search"];}
if(!empty($_GET["sdate1"])){$sdate1 = $_GET["sdate1"];}
if(!empty($_GET["sdate2"])){$sdate2 = $_GET["sdate2"];}
if(!empty($_GET["pagenm"])){$sdate1 = $_GET["pagenm"];}

include_once("./agent_login_chk.php");

//$addQry = "WHERE agent_id = '$_SESSION[AGENT_ID]'";

if($_SESSION['AGENT_MASTER'] == "Y"){
    $addQry = " WHERE 1 = 1";
}else{
    $addQry = " WHERE agent_id = '".$_SESSION['AGENT_ID']."' ";
}

//검색
if($search == "ok") {
    //검색기간
    if($sdate1 && $sdate2) {
        $sdate1 = str_replace('-','',$sdate1);
        $sdate2 = str_replace('-','',$sdate2);     
        $addQry .= " AND substring(date,1,8) >= '$sdate1' AND substring(date,1,8) <= '$sdate2'";
    }
}

$FileName = "문의현황";



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 카테고리 찾기(서비스업체)
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function search_code($code1,$code2,$code3,$code4,$code5,$code6,$depth) {
    $object = new srms();
    if($depth == 2)      $addQry = "code1 = '$code1' AND code2 = '$code2'";
    else if($depth == 3) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3'";
    else if($depth == 4) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3' AND code4 = '$code4'";
    else if($depth == 5) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3' AND code4 = '$code4' AND code5 = '$code5'";
    else if($depth == 6) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3' AND code4 = '$code4' AND code5 = '$code5' AND code6 = '$code6'";

    //echo $addQry;

    $object->query = "SELECT name FROM green_code WHERE $addQry AND depth = '$depth'";
    $total = $object->get_result();
    foreach ($total as $row) {
        $search_code = $row['name'];
    }
    return $search_code;

}

//총게시물(검색결과)
$object->query = "SELECT count(*) as cnt FROM green_speed_agent $addQry";
$notice = $object->get_result();

foreach ($notice as $row) {
    $total_num = $row['cnt'];
}

header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename=$FileName.xls" );
header( "Content-Description: PHP4 Generated Data" );
?>
<html>
<head>
<style>
td {color: #333333; font-family: 굴림; font-size: 12px;}
</style>
</head>
<body>
<table border="1" cellspacing="0" cellpadding="0">
<tr>
    <th scope="col" align="center" width="50" bgcolor="#E4F4FF">NO</th>
    <th scope="col" align="center" width="80" bgcolor="#E4F4FF">등록일시</th>
    <th scope="col" align="center" width="100" bgcolor="#E4F4FF">고객명</th>
    <th scope="col" align="center" width="100" bgcolor="#E4F4FF">휴대폰번호</th>
    <th scope="col" align="center" width="80" bgcolor="#E4F4FF">렌탈사</th>
    <th scope="col" align="center" width="120" bgcolor="#E4F4FF">신청구분</th>
    <th scope="col" align="center" width="80" bgcolor="#E4F4FF">아이피</th>
    <th scope="col" align="center" width="80" bgcolor="#E4F4FF">접속기기</th>
    <th scope="col" align="center" width="80" bgcolor="#E4F4FF">유입</th>
    <th scope="col" align="center" width="80" bgcolor="#E4F4FF">상태</th>
</tr>
<?
$object->query = "SELECT * FROM green_speed_agent $addQry ORDER BY no DESC";
$result = $object->get_result();
foreach($result as $row){
    if ($row["apply_type"] == "order") {
        $applyString = "접수";
        preg_match_all('/(렌탈사 : )(.*?)\n/', $row["memo"], $output_array);
        $seller = trim($output_array[2][0]);
        $applyString = "접수";
        preg_match_all('/(상품명 : )(.*?)\n/', $row["memo"], $output_array);
        $gname = trim($output_array[2][0]);
    }else{
        $applyString = "문의";
        $seller = "-";
        $gname = "<a href=\"".$row['url']."\" target=\"_blank\">유입경로</a>";
    }
?>
<tr>
    <!-- NO -->
    <td align="center"><?= $total_num ?></td>

    <!-- 등록일시 -->
    <td align="center"><?= substr($row[date],4,2) ?>-<?= substr($row['date'],6,2) ?></td>

    <!-- 고객명 -->
    <td align="center">
        <?= preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $row['name']) ?>
    </td>

    <!-- 휴대폰번호 -->
    <td align="center">
        <?= $row['hp'] ?>
    </td>

    <!-- 렌탈사 -->
    <td align="center">
        <?= $seller ?>
    </td>

    <!-- 신청구분 -->
    <td align="center">
    <?
        if($row["apply_type"] == "order") {
            echo "접수신청";
        }
        else if(!empty($row["url"])) {
            echo "문의신청";
        }
    ?>
    </td>

    <!-- 아이피 -->
    <td align="center"><?= $row['userip'] ?></td>

    <!-- 접속기기 -->
    <td align="center">
    <?
        if($row['site'] == "m") { $siteIco = "모바일"; }
        else if($row['site'] == "p") { $siteIco = "PC"; }
        else { $siteIco = ""; }
        echo $siteIco;
    ?>
    
    </td>

    <!-- 유입 -->
    <td align="center">
        <?= $row['apply_title'] ?>
    </td>

    <!-- 상태 -->
    <td align="center">
        <? if($row['status'] == "N") echo"상담대기"; ?>
        <? if($row['status'] == "W") echo"통화예정"; ?>
        <? if($row['status'] == "S") echo"문자발송"; ?>
        <? if($row['status'] == "Y") echo"상담완료"; ?>
    </td>
</tr>
<?
  $total_num--;
}
?>
</table>
</body>
</html>