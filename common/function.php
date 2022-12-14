<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 에러정의
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ErrorMsg($msg, $act) {
    if($act=="close") { echo"<script language=\"javascript\">alert('$msg'); self.close();</script>"; exit; }
    else              { echo"<script language=\"javascript\">alert('$msg'); history.go(-{$act});</script>"; exit; }
}

function ErrorMsg_frm($msg, $goPage, $target) {
    echo"<script language=\"javascript\">alert('$msg'); frames['$target'].location.href='$goPage';</script>"; exit;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 암복호화
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function my_simple_crypt( $string, $action = 'e' ) {
    $secret_key = '1ae49a1a1eb120723f07f1260b145526';
    $secret_iv = '2811da22377d62fcfdb02f29aad77d9e';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 저장시간 출력
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Date_Cut($bbs_date, $gubun, $type) {
    if($type == "L") return substr($bbs_date,0,4)."$gubun".substr($bbs_date,4,2)."$gubun".substr($bbs_date,6,2);
    if($type == "V") return substr($bbs_date,0,4)."$gubun".substr($bbs_date,4,2)."$gubun".substr($bbs_date,6,2)." ".substr($bbs_date,8,2).":".substr($bbs_date,10,2);
}


function hasSubdomain($url) {
    $parsed = @parse_url($url);
    $exploded = explode('.', $parsed["host"]);
    return (count($exploded) > 2 && $exploded[0] == 'm');
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Null 체크
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Null_Chk($str) {
    if(!$str || empty($str) || $str == " " || $str == "&nbsp;") return false ;
    else return true ;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 현재시간
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function nDate($no) {
    if($no == 1) { $date=date("YmdHi",time()); return $date; }
    if($no == 2) { $date=date("YmdHis",time()); return $date; }
}


function get_security_key($value) {

    $key = hex2bin("1ae49a1a1eb120723f07f1260b145526");
    $iv = hex2bin("2811da22377d62fcfdb02f29aad77d9e");

    if($value == 2) return array($key,$iv);
    else            return $key;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 휴대전화 하이픈 추가
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function format_phone($phone){
    if(preg_match("/-/", $phone)){
        return $phone;
    } else {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);

        switch ($length) {
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
}
?>