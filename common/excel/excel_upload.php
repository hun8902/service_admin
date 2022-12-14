<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';

function createDir($path)
{
    if(is_dir($path)) return null;
    if (mkdir($path, 0777)) {
        if (is_dir($path)) {
            chmod($path, 0777);
        }
    }else{
        return null;
    }
    return $path;
}
/**
Helper for xls files. See fileToArray()
 */
function xlsToArray($filename, $limit)
{
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('EUC-KR');
    $data->read($filename);
    $sheet = $data->sheets[0];
    $rows = $sheet['numRows'];
    $cols = $sheet['numCols'];
    $ret = array();
    for ($i = 1; $i <= $rows; $i++) {
        $line = array();
        for ($j = 1; $j <= $cols; $j++) {
            if (isset($sheet['cells'][$i]) && isset($sheet['cells'][$i][$j])) {
                $line[] = $sheet['cells'][$i][$j];
            } else {
                $line[] = '';
            }
        }
        $ret[] = $line;
        if ($limit != 0 && count($ret) >= $limit) {
            break;
        }
    }
    return $ret;
}

$uploaddir = '/home/hosting_users/dtv2000/www/agent_/excel/uploads/';
$uploadfile = $uploaddir . basename($_FILES['excelfile']['name']);
$array = explode(".", strtolower($uploadfile));
$ext = array_pop($array);
if($ext != "xls"){
    echo "xls 파일만 업로드 가능합니다.";
    exit;
}

createDir($uploaddir);
$destfile = $uploaddir . date("YmdHis", time()).".".$ext;
if (move_uploaded_file($_FILES['upfile']['tmp_name'], $destfile)) {
    echo "파일이 유효하고, 성공적으로 업로드 되었습니다.\n";
} else {
    print "파일 업로드 실패!\n";
    exit;
}
$dataList = xlsToArray($destfile,0);

?>