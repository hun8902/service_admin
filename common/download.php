<?php
include('../config/db_con.php');
include('../include/Compat/Function/hash_hmac.php');
$object = new srms();
session_start();

//include_once("./function.php");
include_once("./agent_login_chk.php");

if(!empty($_GET["bbs_no"])){$bbs_no = $_GET["bbs_no"];}
if(!empty($_GET["bbs_id"])){$bbs_id = $_GET["bbs_id"];}
if(!empty($_GET["file_no"])){$file_no = $_GET["file_no"];}


$object->query = "SELECT * FROM green_$bbs_id WHERE bbs_no='$bbs_no'";
$viewRow = $object->get_result();


foreach ($viewRow as $row) {
    $real_file = $row["bbs_file".$file_no] ;// 서버 저장되기 전 원래 파일명
    $micro_file = $row["bbs_file".$file_no."_micro"]; // 서버에 저장된 저장된 파일명
    $file = "../upload/".$bbs_id."/".$micro_file; //실제 파일명 또는 경로
    $filesize = filesize($file);
}


header("Content-type: application/octet-stream");
header("Content-Length: ".filesize("$file"));
header("Content-Disposition: attachment; filename=$real_file"); // 다운로드되는 파일명 (실제 파일명과 별개로 지정 가능)
header("Content-Transfer-Encoding: binary");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");
header("Expires: 0");


if(is_file("$file")) {
    //다운수 업데이트
    $down_field = "bbs_down".$file_no."_hit";
    $down_hit = $row["bbs_down".$file_no."_hit"] + 1;
    $object->query = "UPDATE green_$bbs_id SET $down_field='$down_hit' WHERE bbs_no = '$bbs_no'";
    $object->execute();

    $fp = fopen("$file", "rb");
    if(!fpassthru($fp)) {
        fclose($fp);
    }
}else{
    //ErrorMsg('해당파일 또는 경로가 존재하지 않습니다.',1);
}

?>