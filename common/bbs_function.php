<?


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Null 체크
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Null_Chk($str) {
  if(!$str || empty($str) || $str == " " || $str == "&nbsp;") return false ;
  else return true ;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 회원 레벨 출력
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Member_Lev($lev) {
  global $db;
  $result = $db->Query("SELECT * FROM green_member_level ORDER BY ml_no ASC");
  while($row = $db->Fetch($result)) {
    if($lev == $row[ml_no]) echo"<option value='$row[ml_no]' selected>$row[ml_name]</option>\n";
    else                  echo"<option value='$row[ml_no]'>$row[ml_name]</option>\n";
  }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 현재시간
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function nDate($no) {
  if($no == 1) { $date=date("YmdHi",time()); return $date; }
  if($no == 2) { $date=date("YmdHis",time()); return $date; }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 저장시간 출력
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Date_Cut($bbs_date, $gubun, $type) {
  if($type == "L") return substr($bbs_date,0,4)."$gubun".substr($bbs_date,4,2)."$gubun".substr($bbs_date,6,2);
  if($type == "V") return substr($bbs_date,0,4)."$gubun".substr($bbs_date,4,2)."$gubun".substr($bbs_date,6,2)." ".substr($bbs_date,8,2).":".substr($bbs_date,10,2);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// HTML 적용여부
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Tag_Allowed($str, $html) {
  if($html == 1) { //사용안함(텍스트)
    $str = stripslashes($str);
    $str = htmlspecialchars($str);
    $str = str_replace(" ","&nbsp;",$str);
    $str = nl2br($str);
  }
  else if($html == 2) { //사용함(<br>자동입력)
    $str = str_replace("<img","<img name=target_resize",$str);
    $str = nl2br(stripslashes($str));
  }
  else if($html == "3") { //사용함(<br>직접입력)
    $str = str_replace("<img","<img name='target_resize'",$str);
    $str = stripslashes($str);
  }
  return $str;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 체크박스 파일 삭제
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function File_Del_ChkBox($bbs_id, $bbs_no, $no) {
  global $db, $CFG_UPLOAD_DIR;
  $is_file_del = false;
  $result = $db->Query("SELECT * FROM green_$bbs_id WHERE bbs_no='$bbs_no'");
  $row = $db->Fetch($result);
  if($row['bbs_thumb'.$no]) {
    @unlink("./upload/".$CFG_UPLOAD_DIR."/".$row['bbs_thumb'.$no]);
  }
  if($row['bbs_file'.$no.'_micro']) {
    if(@unlink("./upload/".$CFG_UPLOAD_DIR."/".$row['bbs_file'.$no.'_micro'])) {
      $is_file_del = true;
    }
  }
  if($is_file_del == true) {
    $bbs_file       = "bbs_file".$no; //실제 파일명
    $bbs_thumb      = "bbs_thumb".$no; //썸네일명
    $bbs_file_micro = "bbs_file".$no."_micro"; //마이크로타임 파일명
    $bbs_file_size  = "bbs_file".$no."_size"; //파일사이즈
    $bbs_down_hit   = "bbs_down".$no."_hit"; //다운수
    $db->Query("UPDATE green_$bbs_id SET $bbs_file='', $bbs_thumb='', $bbs_file_micro='', $bbs_file_size='', $bbs_down_hit=''  WHERE bbs_no='$bbs_no'");
  }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 썸네일 생성
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function imgThumbo($filePath, $saveName, $sFactor, $saveDir = "./", $destroy="1") {
  $sz = @getimagesize($filePath); // 이미지 사이즈구함
  if($sz[0]  > $sFactor || $sz[1] > $sFactor){
    if($sz[0]>$sz[1]) $per=$sFactor/$sz[0];
    else $per=$sFactor/$sz[1];
    $imgW=ceil($sz[0]*$per);
    $imgH=ceil($sz[1]*$per);
  }
  else {
    $imgW=ceil($sz[0]);//width 값
    $imgH=ceil($sz[1]);//height 값
  }

  switch ($sz[2]) {
    case 1:
      $src_img = imagecreatefromgif($filePath);
      $dst_img = imagecreatetruecolor($imgW, $imgH);
      ImageCopyResized($dst_img,$src_img,0,0,0,0,$imgW,$imgH,$sz[0],$sz[1]);
      ImageInterlace($dst_img);
      ImageGIF($dst_img, $saveDir.$saveName);
      break;
    case 2:
      $src_img = imagecreatefromjpeg($filePath);
      $dst_img = imagecreatetruecolor($imgW, $imgH);
      ImageCopyResized($dst_img,$src_img,0,0,0,0,$imgW,$imgH,$sz[0],$sz[1]);
      ImageInterlace($dst_img);
      ImageJPEG($dst_img, $saveDir.$saveName,90);
      break;
    case 3:
      $src_img = imagecreatefrompng($filePath);
      $dst_img = imagecreatetruecolor($imgW, $imgH);
      ImageCopyResized($dst_img,$src_img,0,0,0,0,$imgW,$imgH,$sz[0],$sz[1]);
      ImageInterlace($dst_img);
      ImagePNG($dst_img, $saveDir.$saveName);
      break;
    default:
      return false;
      break;
  }
  return $saveAll = array($saveDir, $saveName, $imgW, $imgH);
  if($destroy){
    ImageDestroy($dst_img);
    ImageDestroy($src_img);
  }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 폴더 용량 체크
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Folder_Size($path) {
  $total_size = 0;
  if($handle = opendir($path)) {
    while(false !== ($file = readdir($handle))) {
      if($file != "." && $file != "..") {
         if(is_dir("$path/$file")) {
           $total_size += Folder_Size("$path/$file"); //재귀호출
         }
         else {
           $total_size += filesize("$path/$file");
         }
      }
    }
    closedir($handle);
    return $total_size;
  }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 담당자이름 찾기
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function search_id($id) {
  global $db;
  $result = $db->Query("SELECT admin_company_name FROM green_member WHERE admin_id = '$id'");
  return $db->Fetch($result,'result');
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 담당자이름 찾기 - 딜러쪽에 접수센터 이름 뿌려질때
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function search_id_agent($id) {
  global $db;
  $result = $db->Query("SELECT admin_company_name,admin_group FROM green_member WHERE admin_id = '$id'");
  $row = $db->Fetch($result);
  if($row[admin_group] == "002") return "접수센터";
  else                           return $row[admin_company_name];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 고객히스토리 갯수 (거래처,접수처)
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function search_history1($table,$gno) {
  global $db;
  $result = $db->Query("SELECT count(*) FROM $table WHERE gno = '$gno' AND display = 'Y'");
  $total_num = $db->Fetch($result,'result');
  if($total_num > 0) return $total_num;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 카테고리 찾기(서비스업체)
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function search_code($code1,$code2,$code3,$code4,$code5,$code6,$depth) {
  global $db;
  if($depth == 2)      $addQry = "code1 = '$code1' AND code2 = '$code2'";
  else if($depth == 3) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3'";
  else if($depth == 4) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3' AND code4 = '$code4'";
  else if($depth == 5) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3' AND code4 = '$code4' AND code5 = '$code5'";
  else if($depth == 6) $addQry = "code1 = '$code1' AND code2 = '$code2' AND code3 = '$code3' AND code4 = '$code4' AND code5 = '$code5' AND code6 = '$code6'";

  //echo $addQry;

  $result = $db->Query("SELECT name FROM green_code WHERE $addQry AND depth = '$depth'");
  return $db->Fetch($result,'result');
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// AES-128 암.복호화
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function hex2bin($hexdata) {
  $bindata="";
  for ($i=0;$i<strlen($hexdata);$i+=2) {
    $bindata.=chr(hexdec(substr($hexdata,$i,2)));
  }
  return $bindata;
}

function toPkcs7 ($value) {
  if ( is_null ($value) ) $value = "" ;
  $padSize = 16 - (strlen ($value) % 16) ;
  return $value . str_repeat (chr ($padSize), $padSize) ;
}

function fromPkcs7 ($value) {
  $valueLen = strlen ($value) ;
  if ( $valueLen % 16 > 0 ) $value = "";
  $padSize = ord ($value{$valueLen - 1}) ;
  if ( ($padSize < 1) or ($padSize > 16) ) $value = "";
  // Check padding.
  for ($i = 0; $i < $padSize; $i++) {
    if ( ord ($value{$valueLen - $i - 1}) != $padSize ) $value = "";
  }
  return substr ($value, 0, $valueLen - $padSize) ;
}

function encrypt ($key, $iv, $value) {
  if ( is_null ($value) ) $value = "" ;
  $value = toPkcs7 ($value) ;
  $output = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv) ;
  return base64_encode ($output) ;
}

function decrypt ($key, $iv, $value) {
  if ( is_null ($value) ) $value = "" ;
  $value = base64_decode ($value) ;
  $output = mcrypt_decrypt (MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv) ;
  return fromPkcs7 ($output) ;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 외부서버에 저장된 보안키 호출
// 양방향 : AES-128 적용
// 단방향 : SHA-256 적용 (로그인 암호)
// 키 변경주기 : 6개월
// 키 변경일시 : 2019-07-15
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_security_key($value) {


  $key = hex2bin("1ae49a1a1eb120723f07f1260b145526");
  $iv = hex2bin("2811da22377d62fcfdb02f29aad77d9e");
  //$key = hex2bin($Ext_key);
  //$iv = hex2bin($Ext_iv);

  if($value == 2) return array($key,$iv);
  else            return $key;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 도메인명만 추출
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getHostName($url) {

	$value = strtolower(trim($url));

	if (preg_match('/^(?:(?:[a-z]+):\/\/)?((?:[a-z\d\-]{2,}\.)+[a-z]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value)) {

		preg_match('/([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value, $matches);
		$host = (!$matches[1]) ? $value : $matches[1];
		//$hostName = explode(".", $host);
		$hostName = $host;

	}
	return $hostName;
}
?>