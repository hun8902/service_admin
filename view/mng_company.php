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

//검색
if($search == "ok") {
    //$addQry .= "WHERE 1 = 1";
    //고객조회
    if($keyword) {
        $addQry .= " AND (cp_name LIKE '%$keyword%' || cp_ceo LIKE '%$keyword%' || cp_bnumber LIKE '%$keyword%' || cp_number LIKE '%$keyword%'|| cp_email LIKE '%$keyword%'|| cp_addr LIKE '%$keyword%'|| cp_fax LIKE '%$keyword%')";
    }
}

/* 자제관리 쿼리 */
$object->query = "SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		";
$result = $object->get_result();
foreach($result as $row)
{
    $leverl_options .= '<option value="'.$row["cp_level"].'">'.$row["cp_level"].'</option>';
}

//총게시물(검색결과)
if($level_check == "1") {
    $object->query = "SELECT count(*) as cnt FROM management WHERE (cp_display ='Enable' or cp_display ='') $addQry";
}else{
    $object->query = "SELECT count(*) as cnt FROM management WHERE (cp_name = '".$cmp_name."') AND (cp_display ='Enable' or cp_display ='')  $addQry";
}

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
                                <form name="searchForm" id="searchForm" method="get" action="mng_company.php">
                                    <input type="hidden" name="search" value="ok">
                                    <input type="hidden" id="schServiceChk" value="<?=$gubun?>">
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
                        <h5>업체관리</h5>
                        <span class="d-block m-t-5">로그인 필드에 <span class="red">불가능</span> 이라고 적혀있을 경우 해당 회사 전체 계정이 로그인 불가능 합니다. 차단됩니다.</span>

                    </div>
                    <div class="card-block">
                        <?php if($level_check <= "1"){ ?>
                        <button type="button" name="add_class" id="add_class" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i> 업체 추가</button>
                        <?php } ?>
                        <div class="table-responsive">
                            <table id="class_table" class="display table nowrap table-striped table-hover" style="width:100%">
                                <colgroup>
                                    <col width="10%">
                                    <col width="*">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="5%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>관할지역</th>
                                        <th>대표자</th>
                                        <th>업체명</th>
                                        <th>연락처</th>
                                        <th>주소</th>
                                        <th>생성일</th>
                                        <th>업체구분</th>
                                        <th>접근상태</th>
                                        <th>관리</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?
                                if($level_check == "1") {
                                    $object->query = "SELECT * FROM management WHERE (cp_display ='Enable' or cp_display ='') $addQry ORDER BY cp_idx desc LIMIT $paging->first, $paging->pageSize ";
                                }else{
                                    $object->query = "SELECT * FROM management WHERE (cp_name = '".$cmp_name."') AND (cp_display ='Enable' or cp_display ='')  $addQry ORDER BY cp_idx desc LIMIT $paging->first, $paging->pageSize ";
                                }
                                $orderList = $object->get_result();
                                $article_num = $total_num - $num_page * ($paging->curPage - 1);
                                foreach ($orderList as $row) {
                                $status_mode = '';
                                if($row["cp_status"] == 'Enable'){
                                    $status_mode = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["cp_idx"].'" data-status="'.$row["cp_status"].'">가능</button>';
                                    $status_text= '<button type="button" class="btn btn-primary btn-sm disabled" >가능</button>';
                                }else{
                                    $status_mode = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["cp_idx"].'" data-status="'.$row["cp_status"].'">불가능</button>';
                                    $status_text= '<button type="button" class="btn btn-danger btn-sm disabled" >불가능</button>';
                                }
                                ?>
                                <tr>
                                <td><?= $article_num ?></td>
                                <td><?= $row['cp_name'] ?></td>
                                <td><?= $row['cp_ceo'] ?></td>
                                <td><?= $row['cp_bnumber'] ?></td>
                                <td><?= $row['cp_number'] ?></td>
                                <td><?= $row['cp_email'] ?></td>
                                <td><?= $row['cp_created_on'] ?></td>
                                <td><?= $row['cp_srttn'] ?></td>
                                <?
                                    //업체 레벨이 1이거나 자기 자신의 회사인 경우
                                    if($level_check == "1" || $row["cp_ceo"] == $cp_name_check){
                                        echo "<td>".$status_mode."</td>";
                                        echo '<td>
                                            <div align="center">			
                                                <a href="mng_company_list.php?&class=' . $row["cp_code"] . '" class="btn btn-secondary btn-sm">계정 관리</a>
                                                <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["cp_idx"] . '">수정</button>
                                                <button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="' . $row["cp_idx"] . '">삭제</button>
                                            </div>
                                        </td>';

                                    }else{
                                        echo "<td>".$status_text."</td>";
                                        echo '<td>
                                            <div align="center">			
                                                <a href="mng_company_list.php?&class=' . $row["cp_code"] . '" class="btn btn-secondary btn-sm">계정 관리</a>
                                            </div>
                                        </td>';
                                    }
                                $article_num--;
                                    ?>
                                </tr>
                                    <?
                                } ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>

                            <div class="dataTables_paginate paging_simple_numbers" id="class_table_paginate">
                                <ul class="pagination">
                                    <?
                                    $page_link = "&search=$search&status=$status&date_gubun=$date_gubun&sdate1=$sdate1&sdate2=$sdate2&key=$key&keyword=$keyword&service=$service&gubun=$gubun&num_page=$num_page";
                                    if($total_num > 0) {
                                        $paging->addQueryString($page_link);
                                        $paging->showPage();
                                    }
                                    else {
                                        echo"&nbsp;\n";
                                    }
                                    ?>

                                </ul>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- 컨텐츠 끝 -->
<?php include("../common/footer.php") ?>



<div id="classModal" class="modal fade" >
    <div class="modal-dialog"  style="max-width: 880px !important;">
        <form method="post" id="class_form" class="needs-validation" novalidate>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Class</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <span id="form_message"></span>

                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label>관할지역 <span class="badge badge-danger">(필수)</span></label>
                            <input type="text" name="cp_name" id="cp_name" placeholder="관할지역을 입력해주세요" required class="form-control"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label>대표자 <span class="badge badge-danger">(필수)</span></label>
                            <input type="text" name="cp_ceo" id="cp_ceo" placeholder="대표자명을 입력해주세요" required  class="form-control"  />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>업체명 <span class="badge badge-danger">(필수)</span></label>
                            <input type="text" name="cp_bnumber" id="cp_bnumber" placeholder="사업자번호를 입력해주세요" required class="form-control"  />
                        </div>
                        <div class="form-group col-md-4">
                            <label>연락처 <span class="badge badge-danger">(필수)</span></label>
                            <input type="text" name="cp_number" id="cp_number" placeholder="연락처를 입력해주세요" required  class="form-control"  />
                        </div>
                        <div class="form-group col-md-4">
                            <label>팩스번호 </label>
                            <input type="text" name="cp_fax" id="cp_fax" placeholder="팩스번호를 입력해주세요"  class="form-control"  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>이메일 <span class="badge badge-danger">(필수)</span></label>
                        <input type="text" name="cp_email" id="cp_email"  placeholder="이메일을 입력해주세요" required class="form-control"  />
                    </div>
                    <div class="form-group">
                        <label>주소지 <span class="badge badge-danger">(필수)</span></label>
                        <input type="text" name="cp_addr" id="cp_addr"  class="form-control" onclick="sample5_execDaumPostcode()" placeholder="주소지를 입력해주세요" required />
                        <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
                        <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2720e5f82e787177241992c73a452de&libraries=services"></script>
                        <script>
                            function sample5_execDaumPostcode() {
                                new daum.Postcode({
                                    oncomplete: function(data) {
                                        var cp_addr = data.address; // 최종 주소 변수

                                        // 주소 정보를 해당 필드에 넣는다.
                                        document.getElementById("cp_addr").value = cp_addr;

                                    }
                                }).open();
                            }
                        </script>
                    </div>
                    <?php if($level_check == "1"){ ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>업체구분</label>
                            <select name="cp_srttn" id="cp_srttn" class="form-control">
                                <option value="">업체구분</option>
                                <option value="본사">본사</option>
                                <option value="센터">센터</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>레벨</label>
                            <select name="cp_level" id="cp_level"  class="form-control">
                                <option value="">권한 선택</option>
                                <option value="1">마스터</option>
                                <option value="2">이카플러그</option>
                                <option value="3">서비스센터</option>
                            </select>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label>메모</label>
                        <textarea  name="cp_memo" id="cp_memo"  class="form-control" placeholder="메모를 입력해주세요"></textarea>

                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function(){

        // 검색 버튼
        $("#btn_search").click(function() {
            $( "#searchForm" ).submit();
        });


        $('#add_class').click(function(){
            $('#class_form')[0].reset();
            $('#class_form').parsley().reset();
            $('#modal_title').text('업체 추가');
            $('#action').val('Add');
            $('#submit_button').val('추가');
            $('#classModal').modal('show');
            $('#form_message').html('');
        });

        $('#class_form').parsley();
        $('#class_form').on('submit', function(event){
            event.preventDefault();
            if($('#class_form').parsley().isValid())
            {
                $.ajax({
                    url:"../action/mng_company.php",
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
                            $('#classModal').modal('hide');
                            $('#message').html(data.success);
                            swal("정상 완료", data, "success").then(function(){
                                location.reload();
                            });
                            setTimeout(function(){
                                $('#message').html('');
                            }, 5000);
                        }
                    }
                })
            }
        });



        $(document).on('click', '.edit_button', function(){
            var class_id = $(this).data('id');
            $('#class_form').parsley().reset();
            $('#form_message').html('');
            $.ajax({
                url:"../action/mng_company.php",
                method:"POST",
                data:{class_id:class_id, action:'fetch_single'},
                dataType:'JSON',
                success:function(data)
                {
                    console.log(data);
                    $('#cp_name').val(data.cp_name);
                    $('#cp_ceo').val(data.cp_ceo);
                    $('#cp_bnumber').val(data.cp_bnumber);
                    $('#cp_number').val(data.cp_number);
                    $('#cp_fax').val(data.cp_fax);
                    $('#cp_email').val(data.cp_email);
                    $('#cp_addr').val(data.cp_addr);
                    $('#cp_srttn').val(data.cp_srttn);
                    $('#cp_level').val(data.cp_level);
                    $('#cp_memo').val(data.cp_memo);
                    $('#modal_title').text('수정 ');
                    $('#action').val('Edit');
                    $('#submit_button').val('수정');
                    $('#classModal').modal('show');
                    $('#hidden_id').val(class_id);

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
            if(confirm("확인 버튼을 누르면 계정이 "+next_status_display+" 됩니다."))
            {

                $.ajax({
                    url:"../action/mng_company.php",
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

                    url:"../action/mng_company.php",
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

</body>
</html>
