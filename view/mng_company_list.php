<?php
include("../config/db_con.php");
include_once("../common/common.php");
include_once("../common/function.php");
//include_once("../common/agent_login_chk.php");
include_once("../common/class_paging.php");
$object = new srms();
//각종권한 체크
$level_check = $object->level_check();
$cp_name_check = $object->cp_name_check();
$login_mame = $object->login_mame();
$cmp_code = $object->cmp_code();
$cmp_name = $object->cmp_name();
$login_id = $object->login_id();
$login_name = $object->login_mame();
if(!$object->is_login())
{
    header("location:../index.php");
}

if(!empty($_GET["page"])){$page = $_GET["page"];}else{ $page = 1;}
if(!empty($_GET["sdate1"])){ $sdate1 = $_GET["sdate1"]; }
if(!empty($_GET["sdate2"])){ $sdate2 = $_GET["sdate2"]; }
if(!empty($_GET["search"])){ $search = $_GET["search"]; }
if(!empty($_GET["status"])){ $status = $_GET["status"]; }
if(!empty($_GET["service"])){ $service = $_GET["service"]; }
if(!empty($_GET["gubun"])){ $gubun = $_GET["gubun"]; }
if(!empty($_GET["keyword"])){ $keyword = $_GET["keyword"]; }
if(!empty($_GET["key"])){ $key = $_GET["key"]; }
if(!empty($_GET["date_opt"])){ $date_opt = $_GET["date_opt"]; }
if(!empty($_GET["num_page"])){ $num_page = $_GET["num_page"];}else{$num_page = 20;}
if(!empty($_GET["date_gubun"])){ $date_gubun = $_GET["date_gubun"]; }
if(!empty($_GET["class"])){ $class = $_GET["class"]; }


$object->query = "
SELECT * FROM management WHERE cp_code = '".$_GET["class"]."'
";
$result1 = $object->get_result();
$class_name = '';
foreach($result1 as $row)
{
    $class_name = $row['cp_name'];
}

//검색
if($search == "ok") {
    //$addQry .= "WHERE 1 = 1";
    //고객조회
    if($keyword) {
        $addQry .= " AND (ac_id LIKE '%$keyword%' || ac_name LIKE '%$keyword%' || ac_phone LIKE '%$keyword%' || cp_name LIKE '%$keyword%'|| cp_ceo LIKE '%$keyword%')";
    }
}


//총게시물(검색결과)
$object->query = "SELECT count(*) as cnt FROM management_account 
		INNER JOIN management
		ON management_account.ac_code = management.cp_code 
		WHERE management_account.ac_code = '$class'
		$addQry";
$notice = $object->get_result();

foreach ($notice as $row) {
    $total_num = $row['cnt'];
}

$paging = new PAGING($total_num, $page, $num_page, 10, 'seo_browntone');

?>
<?php include("../common/script.php") ?>
<body>
<?php include("../common/header.php") ?>
<!-- 컨텐츠 시작 -->
<?php include("../common/top.php") ?>
<!-- 컨텐츠 시작 -->


<div class="main-body">
    <div class="page-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <span id="message"></span>
                    <!-- Page Heading -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12" >
                                <form name="searchForm" id="searchForm" method="get" action="mng_company_list.php">
                                    <input type="hidden" name="search" value="ok">
                                    <input type="hidden" id="schServiceChk" value="<?=$gubun?>">
                                    <input type="hidden" id="class" name="class" value="<?=$class?>">
                                    <input type="hidden" id="pageNumChk" name="num_page" value="<?=$num_page?>">
                                    <div class="row input-daterange">
                                        <div class="col-md-3">
                                            <div class="sch_keyword">
                                                <div class="form_group">
                                                    <div class="selectric_wrap" style="display: none;">
                                                        <select id="schKey" name="key" class="form-control ">
                                                            <option value="model_name" <? if($key == "model_name") echo"selected"; ?>>고객명</option>
                                                            <!--<option value="partner_no" <? if($key == "partner_no") echo"selected"; ?>>고객번호</option>-->
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control form_field" id="schKeyword" name="keyword" placeholder="검색 키워드 입력" value="<?= $keyword ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <button type="button" id="btn_search" class="btn btn-info"><i class="fas fa-filter"></i>검색</button>

                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <span id="message"></span>
                    <!-- Page Heading -->
                    <div class="card-header">
                        <h5><?php echo $class_name; ?> 계정 관리</h5>
                        <span class="d-block m-t-5">로그인 필드에 <span class="red">불가능</span> 이라고 적혀있을 경우 로그인이 차단됩니다.</span>
                    </div>
                    <div class="card-block">
                        <button type="button" name="add_student" id="add_student" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i> 계정추가</button>
                        <div class="table-responsive">
                            <table id="student_table" class="display table nowrap table-striped table-hover" style="width:100%">
                                <colgroup>
                                    <col width="5%">
                                    <col width="*">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="5%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>아이디</th>
                                        <th>이름</th>
                                        <th>연락처</th>
                                        <th>등록일시</th>
                                        <th>로그인</th>
                                        <th>관리</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?
                                $object->query = "SELECT * FROM management_account 
                                INNER JOIN management
                                ON management_account.ac_code = management.cp_code 
                                WHERE management_account.ac_code = '$class' $addQry ORDER BY ac_idx desc LIMIT $paging->first, $paging->pageSize ";
                                $orderList = $object->get_result();
                                $article_num = $total_num - $num_page * ($paging->curPage - 1);
                                foreach ($orderList as $row) {
                                ?>
                                <tr>
                                    <td><?= $article_num ?></td>
                                    <td><?= $row['ac_id'] ?></td>
                                    <td><?= $row['ac_name'] ?></td>
                                    <td><?= $row['ac_phone'] ?></td>
                                    <td><?= $row['ac_created_on'] ?></td>
                                    <?php
                                    $status_btn = '';
                                    if($row["ac_status"] == 'Enable')
                                    {
                                        $status_btn = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["ac_idx"].'" data-status="'.$row["ac_status"].'">가능</button>';
                                        $status_text= '<button type="button" class="btn btn-primary btn-sm disabled" >가능</button>';
                                    }
                                    else
                                    {
                                        $status_btn = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["ac_idx"].'" data-status="'.$row["ac_status"].'">불가능</button>';
                                        $status_text= '<button type="button" class="btn btn-danger btn-sm disabled" >불가능</button>';
                                    }
                                    //업체 레벨이 1이거나 자기 자신의 회사인 경우
                                    if($level_check == "1" ){
                                        echo '<td>'.$status_btn.'</td>';
                                        echo '
                                            <td>
                                                <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["ac_idx"].'">수정</button>
                                                <button type="button" name="delete_button" class="btn btn-warning btn-danger btn-sm delete_button" data-id="'.$row["ac_idx"].'">삭제</button>
                                            </td>';
                                    }else{
                                        echo '<td>'.$status_text.'</td>';
                                        if($row["ac_id"] == $login_id) {
                                            echo '
                                            <td>
                                                <div align="center">			
                                                    <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["ac_idx"] . '">수정</button>
                                                </div>
                                            </td>
                                            ';
                                        }else{
                                             echo '<td></td>';
                                        }
                                    }
                                    ?>
                                </tr>
                                <?
                                $article_num--;
                                } ?>

                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>



<div id="studentModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="student_form" class="validation-idadd">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">계정 생성</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    
                    <div class="form-group">
                        <label>아이디 <span class="badge badge-danger">(필수)</span></label>
                        <input type="text" name="ac_id" id="ac_id" class="form-control" placeholder="아이디를 입력해주세요" required  data-parsley-trigger="keyup" />
                    </div>
                    <div class="form-group">
                        <label>이름 <span class="badge badge-danger">(필수)</span></label>
                        <input type="text" name="ac_name" id="ac_name" class="form-control" placeholder="이름을 입력해주세요" required  data-parsley-trigger="keyup"  />
                    </div>
                    <div class="form-group">
                        <label>비밀번호 <span class="badge badge-danger">(필수)</span></label>
                        <input type="text" name="ac_passwd" id="ac_passwd" class="form-control" placeholder="비밀번호를 입력해주세요" required  data-parsley-trigger="keyup"  />
                    </div>
                    <div class="form-group">
                        <label>연락처 <span class="badge badge-danger">(필수)</span></label>
                        <input type="text" name="ac_phone" id="ac_phone" class="form-control" placeholder="연락처를 입력해주세요" required  data-parsley-trigger="keyup"  />
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="ac_code" id="ac_code" value="<?php echo $_GET["class"]; ?>"/>
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- 컨텐츠 끝 -->
<?php include("../common/footer.php") ?>
</body>
</html>

<script>
    $(document).ready(function(){

        // 검색 버튼
        $("#btn_search").click(function() {
            $( "#searchForm" ).submit();
        });

        $('#add_student').click(function(){
            $('#student_form')[0].reset();
            $('#student_form').parsley().reset();
            $('#ac_id').removeAttr("readonly");
            $('#modal_title').text('계정 추가');
            $('#action').val('Add');
            $('#submit_button').val('추가');
            $('#studentModal').modal('show');
            $('#form_message').html('');
        });

        $('#student_form').parsley();

        $('#student_form').on('submit', function(event){
            event.preventDefault();

            if($('#student_form').parsley().isValid())
            {

                $.ajax({
                    url:"../action/mng_company_list.php",
                    method:"POST",
                    data:$(this).serialize(),
                    dataType:'json',
                    beforeSend:function()
                    {
                        $('#submit_button').attr('disabled', 'disabled');
                        $('#submit_button').val('wait...');
                    },
                    success:function(data)
                    {
                        $('#submit_button').attr('disabled', false);
                        if(data.error != '')
                        {
                            swal("실패", data.error, "error");
                        }
                        else
                        {
                            swal("정상처리", data.success, "success").then(function(){
                                location.reload();
                            });
                            //swal("정상 처리", data.success, "success");
                        }
                    }
                })
            }
        });

        $(document).on('click', '.edit_button', function(){
            var student_id = $(this).data('id');
            $('#student_form')[0].reset();
            $('#student_form').parsley().reset();
            $('#form_message').html('');
            $.ajax({
                url:"../action/mng_company_list.php",
                method:"POST",
                data:{student_id:student_id, action:'fetch_single'},
                dataType:'JSON',
                success:function(data)
                {
                    if(data.ac_name == "<?php echo $login_name ?>" || <?php echo $level_check ?>  == 1){
                        $('#ac_id').val(data.ac_id);
                        $('#ac_id').attr("readonly",true);
                        $('#ac_name').val(data.ac_name);
                        $('#ac_passwd').val(data.ac_passwd);
                        $('#ac_phone').val(data.ac_phone);
                        $('#modal_title').text('계정 수정');
                        $('#action').val('Edit');
                        $('#submit_button').val('수정');
                        $('#studentModal').modal('show');
                        $('#hidden_id').val(student_id);
                    }else {
                        alert("본인 아이디만 수정이 가능합니다.");
                    }
                }
            })
        });

        $(document).on('click', '.status_button', function(){
            var id = $(this).data('id');
            var status = $(this).data('status');
            var next_status = 'Enable';
            var next_status_display = '가능';
            if(status == 'Enable')
            {
                next_status_display= '불가능';
                next_status = 'Disable';
            }
            if(confirm("확인버튼을 누르면 관리자 로그인이 "+next_status_display+" 합니다"))
            {
                $.ajax({
                    url:"../action/mng_company_list.php",
                    method:"POST",
                    data:{id:id, action:'change_status', status:status, next_status:next_status},
                    success:function(data)
                    {
                        swal("상태 변경 완료", data, "success").then(function(){
                            location.reload();
                        });

                        
                    }
                })
            }
        });

        $(document).on('click', '.delete_button', function(){
            var id = $(this).data('id');
            if(confirm("정말로 데이터를 삭제하시겠습니까?"))
            {
                $.ajax({
                    url:"../action/mng_company_list.php",
                    method:"POST",
                    data:{id:id, action:'delete'},
                    success:function(data)
                    {
                        swal("데이터 삭제 완료", data, "success").then(function(){
                            location.reload();
                        });
                    }
                })
            }

        });

    });
</script>