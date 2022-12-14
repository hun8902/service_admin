<script language="JavaScript">
var obj = document.getElementsByName('bbs_no[]');

function Allchange(f) {
  if(f.checked == true) for(var i = 0; i < obj.length; i++) obj[i].checked = true;
  else                  for(var i = 0; i < obj.length; i++) obj[i].checked = false;
}

function All_del() {
  var cnt=0;
  for(var i=0; i<obj.length; i++) {
    if(obj[i].checked == true) {
      cnt=1;;
      break;
    }
  }
  if(cnt==0) {
    alert("삭제하실 항목을 선택해 주세요");
    return;
  }
  if(confirm('선택하신 데이터를 모두 삭제 하시겠습니까?\n\n삭제후 복구는 불가능 합니다.')) document.del_form.submit();
  else  return;
}
</script>