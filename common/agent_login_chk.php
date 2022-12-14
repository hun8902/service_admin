<?
if(!$_SESSION['AGENT_ID']) {
  ErrorMsg_frm('세션이 만료 되었거나 관리자 로그인 하지 않으셨습니다.','/','top');
}

if($_SESSION['AGENT_GROUP'] != '001') {
  ErrorMsg_frm('부적절한 실행입니다.','/','top');
}
?>