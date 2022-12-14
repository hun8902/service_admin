<?php
include("../config/db_con.php");
include_once("../common/common.php");
include_once("../common/function.php");
//include_once("../common/agent_login_chk.php");
include_once("../common/class_paging.php");
$object = new srms();
$level_check = $object->level_check();
if(!$object->is_login())
{
    header("location:".$object->base_url."/index.php");
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


if($level_check == "1"){
} else {
    header("Location: view/no_permission.php");
}

//검색
if($search == "ok") {
    $addQry .= "WHERE 1 = 1";
    //고객조회
    if($keyword) {
      $addQry .= " AND $key LIKE '%$keyword%'";
    }

}
//총게시물(검색결과)
$object->query = "SELECT count(*) as cnt FROM materialcontrol $addQry";
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
                                <form name="searchForm" id="searchForm" method="get" action="./mng_materialcontrol.php">
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
                        <h5>자제 관리</h5>
                     </div>
                    <div class="card-block">
                        <button type="button" name="add_class" id="add_class" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i> 자제 추가</button>
                        <div class="table-responsive">
                            <table id="class_table" class="display table nowrap table-striped table-hover" style="width:100%">
                                <colgroup>
                                    <col width="10%">
                                    <col width="*">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>번호</th>
                                        <th>자제명</th>
                                        <th>관리</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?
                                $object->query = "SELECT * FROM materialcontrol $addQry ORDER BY idx desc LIMIT $paging->first, $paging->pageSize ";
                                $orderList = $object->get_result();
                                $article_num = $total_num - $num_page * ($paging->curPage - 1);
                                foreach ($orderList as $row) {
                                ?>
                                <tr>
                                    <td><?= $article_num ?></td>
                                    <td><?= $row['model_name'] ?></td>
                                    <td>
                                        <div align="center">
                                            <button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="<?= $row["idx"] ?>">삭제</button>
                                        </div>
                                    </td>
                                <?
                                $article_num--;
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


<div id="classModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="class_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Class</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <label>자제명</label>
                        <input type="text" name="model_name" id="model_name"  class="form-control" required  placeholder="자제명을 입력해주세요" data-parsley-trigger="keyup" />
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

        // 검색 버튼
        $("#schKeyword").change(function() {
            $( "#searchForm" ).submit();
        });


        $('#add_class').click(function(){
            $('#class_form')[0].reset();
            $('#class_form').parsley().reset();
            $('#modal_title').text('자제 추가');
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
                    url:"../action/mng_materialcontrol.php",
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
                            $('#submit_button').val('Add');
                        }
                        else
                        {

                            $('#classModal').modal('hide');
                            swal("정상 처리", data.success, "success").then(function(){
                                location.reload();
                            });

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
                url:"../action/mng_materialcontrol.php",
                method:"POST",
                data:{class_id:class_id, action:'fetch_single'},
                dataType:'JSON',
                success:function(data)
                {
                    $('#model_name').val(data.model_name);
                    $('#modal_title').text('Edit ');
                    $('#action').val('Edit');
                    $('#submit_button').val('Edit');
                    $('#classModal').modal('show');
                    $('#hidden_id').val(class_id);
                }
            })
        });


        $(document).on('click', '.delete_button', function(){
            var id = $(this).data('id');
            if(confirm("정말로 데이터를 삭제하시겠습니까?"))
            {
                $.ajax({
                    url:"../action/mng_materialcontrol.php",
                    method:"POST",
                    data:{id:id, action:'delete'},
                    success:function(data)
                    {
                        swal("상태 변경 완료", data, "success").then(function(){
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
