<?
//글목록
$list_link = "board.php?bbs_id=$bbs_id&key=$key&keyword=$en_keyword";

//글목록
$list_link2 = "board.php?bbs_id=$bbs_id&page=$page&key=$key&keyword=$en_keyword";

//글쓰기
$write_link = "board.php?bbs_id=$bbs_id&mode=write&page=$page&key=$key&keyword=$en_keyword";

//글수정
$modify_link = "board.php?bbs_id=$bbs_id&mode=password&act=modify&bbs_no=$bbs_no&page=$page&key=$key&keyword=$en_keyword";

//글수정1
$modify_link1 = "board.php?bbs_id=$bbs_id&mode=write&act=modify&bbs_no=$bbs_no&page=$page&key=$key&keyword=$en_keyword";

//글답변
$reply_link = "board.php?bbs_id=$bbs_id&mode=write&act=reply&bbs_no=$bbs_no&page=$page&key=$key&keyword=$en_keyword";

//글삭제
$delete_link = "board.php?bbs_id=$bbs_id&mode=password&act=delete&bbs_no=$bbs_no&page=$page&key=$key&keyword=$en_keyword";

//비밀글 취소
$password_cancel_link = "board.php?bbs_id=$bbs_id&page=$page&key=$key&keyword=$en_keyword";

//검색취소
$search_cancel_link = "board.php?bbs_id=$bbs_id";

//글목록(폼)
$list_link_form = "board.php?bbs_id=$bbs_id&page=$page&key=$key&keyword=$en_keyword";


//카테고리 사용시 링크 추가
if($is_category == true) {
  $list_link .= "&scate=$en_cate";
  $list_link2 .= "&scate=$en_cate";
  $write_link .= "&scate=$en_cate";
  $modify_link .= "&scate=$en_cate";
  $modify_link1 .= "&scate=$en_cate";
  $reply_link .= "&scate=$en_cate";
  $delete_link .= "&scate=$en_cate";
  $password_cancel_link .= "&scate=$en_cate";
  $search_cancel_link .= "&scate=$en_cate";
  $list_link_form  .= "&scate=$en_cate";
}

//관리자모드 영역
if(__ADMIN_ID__ && $bbs_admin == "chk") {
  $list_link .= "&bbs_admin=chk";
  $list_link2 .= "&bbs_admin=chk";
  $write_link .= "&bbs_admin=chk";
  $modify_link .= "&bbs_admin=chk";
  $modify_link1 .= "&bbs_admin=chk";
  $reply_link .= "&bbs_admin=chk";
  $delete_link .= "&bbs_admin=chk";
  $password_cancel_link .= "&bbs_admin=chk";
  $search_cancel_link .= "&bbs_admin=chk";
  $list_link_form  .= "&bbs_admin=chk";
}
?>