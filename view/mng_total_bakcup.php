<?php
include("../config/db_con.php");
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
    header("location:".$object->base_url."/index.php");
}


$object->query = "
SELECT * FROM category where parent_id = '0'
";
$result = $object->get_result();
$class_name = '';
foreach($result as $row)
{
    $class_name .= '<option value="'.$row["id_menu"].'">'.$row["label_menu"].'</option>';
}

//업체 셀렉트 박스
$object->query = "
SELECT cp_name FROM management 
";
$result = $object->get_result();


foreach($result as $row)
{
    $company_list .= '<option value="'.$row["cp_name"].'">'.$row["cp_name"].'</option>';
}

/* 거리 셀렉트 박스 */
$object->query = "
SELECT * FROM distance 
";
$result_distance = $object->get_result();
$distance_name = '';
foreach($result_distance as $row)
{
    $distance_name .= '<option value="'.$row["price"].'">'.$row["name"].'</option>';
}

/* 자제 불러오기 */
$object->query = "SELECT * FROM materialcontrol";
$result = $object->get_result();
foreach($result as $row)
{
    $material_options .= '<option value="'.$row["model_name"].'">'.$row["model_name"].'</option>';
}

/* 출장비 박스 */
$object->query = "SELECT * FROM travel_expenses";
$result = $object->get_result();
foreach($result as $row)
{
    $travel_expenses .= '<option id="'.$row["price"].','.$row["price_center"].'" value="'.$row["name"].'">'.$row["name"].'</option>';
}

/* 현황 셀렉트 박스 */
for ($x = 1; $x <= 6; $x++) {
    $select_code11 = "stats_fd".$x;
    $object->query = "SELECT * FROM post_mgt_select where select_pos = '".$select_code11."'";
    $select_query = $object->get_result();
    foreach($select_query as $row1)
    {
        $select_name[$x] .= '<option value="'.$row1["select_code"].'">'.$row1["name"].'</option>';
    }
}


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
                            <div class="col-sm-6">
                                <h5><?php echo $cmp_name?> 접수관리</h5>
                            </div>
                            <div class="col-sm-6" align="right">
                                <div class="row input-daterange">
                                    <div class="col-md-4">
                                        <input type="text" name="from_date" id="from_date" class="form-control datepicker" placeholder="검색할 날짜를 선택해주세요" readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="to_date" id="to_date" class="form-control datepicker" placeholder="검색할 날짜를 선택해주세요" readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" name="filter" id="filter" class="btn btn-info"><i class="fas fa-filter"></i>필터 적용</button>
                                        <button type="button" name="refresh" id="refresh" class="btn btn-secondary"><i class="fas fa-sync-alt"></i>초기화</button>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div id="btn_excel" style="float:right;">
                        </div>
                        <?php if($level_check <= "2"){
                            ?>
                            <button type="button" name="add_class" id="add_class" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i>접수 추가</button>
                        <?php } ?>
                        <div class="table-responsive">
                            <table id="class_table" class="display table nowrap   table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th rowspan="2">등록일</th>
                                    <th rowspan="2">고객명</th>
                                    <th rowspan="2">연락처</th>
                                    <th rowspan="2">센터</th>
                                    <th colspan="5">서비스</th>
                                    <th colspan="2">접수상태</th>
                                    <th colspan="2">회수상태</th>
                                    <th colspan="2">정산상태</th>
                                    <th rowspan="2">검수자</th>
                                    <th rowspan="2">관리</th>
                                </tr>
                                <tr>
                                    <th>모델명</th>
                                    <th>구분</th>
                                    <th>분류1</th>
                                    <th>분류2</th>
                                    <th>분류3</th>
                                    <th>본사</th>
                                    <th>센터</th>
                                    <th>본사</th>
                                    <th>센터</th>
                                    <th>본사</th>
                                    <th>센터</th>
                                </tr>
                                </thead>
                                <tbody>

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


<!-- 컨텐츠 끝 -->
<?php include("../common/footer.php") ?>



<div id="classModal" class="modal fade">
    <div class="modal-dialog" style="max-width: 880px !important;">
        <form method="post" id="class_form" class="repeater" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Class</h4>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">

                    <span id="form_message"></span>
                    <div class="form-row" style="display: -ms-flexbox; display: flex; -ms-flex-align: center; align-items: center;-ms-flex-pack: end; justify-content: flex-end;">
                    <input type="button" value="프린트" id="simplePrint" class="btn btn-info  btn-sm" style="float: right"/>
                    </div>
                    <div id="write_form" class="write_form">
                        <input type="hidden" name="login_name" id="login_name" value="<?php echo $login_mame; ?>"/>
                        <input type="hidden" name="login_id" id="login_id" value="<?php echo $login_id; ?>"/>
                        <input type="hidden" name="cmp_name" id="cmp_name" value="<?php echo $cmp_name; ?>"/>
                        <input type="hidden" name="cmp_code" id="cmp_code" value="<?php echo $cmp_code; ?>"/>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">고객명 <span class="badge badge-danger">(필수)</span></label>
                                    <input type="text" <?php if($level_check >= 3){?> disabled <?php }?> <?php $input_disamble?> required=""  data-parsley-group="total" class="form-control" name="user_name" id="user_name"  placeholder="고객명을 입력해주세요" >
                                    <small id="emailHelp" class="form-text text-muted"> 이름을 입력해주세요</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">연락처1 <span class="badge badge-danger">(필수)</span></label>
                                    <input type="text" class="form-control"  <?php if($level_check >= 3){?> disabled <?php }?> required="" data-parsley-type="number" name="phone1" id="phone1" placeholder="연락가능한 번호를 입력해주세요" >
                                    <small id="emailHelp" class="form-text text-muted"> -을 제외한 전화번호를 입력해주세요</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">연락처2</label>
                                    <input type="text" class="form-control" <?php if($level_check >= 3){?> disabled <?php }?>  name="phone2" id="phone2" placeholder="비상시 연락 가능한 번호를 입력해주세요"  >
                                    <small id="emailHelp" class="form-text text-muted"> -을 제외한 전화번호를 입력해주세요</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="inputZip">센터 <span class="badge badge-danger">(필수)</span></label>
                                    <select name="center_name" <?php if($level_check != 1){?> disabled <?php }?> required=""  size="1" id="center_name" class="form-control" >
                                        <option value="">센터를 선택해주세요</option>
                                        <?php echo $company_list ?>
                                    </select>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="inputEmail4">주소 <span class="badge badge-danger">(필수)</span></label>
                                <input type="text" class="form-control" <?php if($level_check >= 3){?> disabled <?php }?> required=""  name="addr" id="addr" <?php if($level_check <= 2){?> onclick="return_execDaumPostcode()"  <?php }?>  placeholder="주소지를 입력해주세요" >
                                <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
                                <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2720e5f82e787177241992c73a452de&libraries=services"></script>
                                <script>
                                    function sample5_execDaumPostcode() {
                                        new daum.Postcode({
                                            oncomplete: function(data) {
                                                var addr = data.address; // 최종 주소 변수

                                                // 주소 정보를 해당 필드에 넣는다.
                                                document.getElementById("addr").value = addr;

                                            }
                                        }).open();
                                    }
                                </script>
                                <script>
                                    function return_execDaumPostcode() {
                                        new daum.Postcode({
                                            oncomplete: function(data) {
                                                var addr = data.address; // 최종 주소 변수

                                                // 주소 정보를 해당 필드에 넣는다.
                                                document.getElementById("addr").value = addr;

                                            }
                                        }).open();
                                    }
                                </script>
                            </div>

                        </div>
                        <div class="form-group ">
                            <label for="inputZip">출장비</label>
                            <select name="step5" <?php if($level_check >= 3){?> disabled <?php }?>  size="4" id="step5" class="form-control tr_action">
                                <?php echo $travel_expenses; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <?php echo $class_name1; ?>
                                <div class="form-group col-md-3">
                                    <label for="inputZip">모델선택 </label>
                                    <select name="step1" size="3" id="step1" <?php if($level_check >= 3){?> disabled <?php }?>  class="form-control action">
                                        <?php echo $class_name; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputZip">서비스 유형</label>
                                    <select name="step2" size="3" id="step2" <?php if($level_check >= 3){?> disabled <?php }?>  class="form-control action">


                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputZip">일반 긴급</label>
                                    <select name="step3" size="3" id="step3" <?php if($level_check >= 3){?> disabled <?php }?>  class="form-control action">
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputZip">선택 </label>
                                    <select name="step4" size="3" id="step4" <?php if($level_check >= 3){?> disabled <?php }?>  class="form-control action">
                                    </select>
                                </div>
                            </div>

                        </div>

                        <?php if($level_check == "1" || $level_check == "3" ){ ?>
                            <div class="form-group ">
                                <label for="inputZip">거리선택</label>
                                <select name="distance_select" <?php if($level_check == 2){?> disabled <?php }?>  size="7" id="distance_select" class="form-control as_action">
                                    <?php echo $distance_name; ?>
                                </select>
                            </div>
                        <?php } ?>
                        <?php if($level_check <= "2"){ ?>
                            <hr/>
                            <div class="form-row">
                                <?php if($level_check == "1"){  ?>
                                    <div class="form-group col-md-12">
                                        <h5>본사</h5>
                                    </div>
                                <?php } ?>
                                <div class="form-group col-md-2">
                                    <label for="inputZip">출장비</label>
                                    <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?> data-a-sign="won"  name="price_0" id="price_0" value="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputZip">수리비</label>
                                    <input type="text" class="form-control " <?php if($level_check >= 3){?> disabled <?php }?> name="price_1" id="price_1" value="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputZip">거리비</label>
                                    <input type="text" class="form-control input_cal" name="price_2" id="price_2" value="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputZip">긴급</label>
                                    <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?> name="price_3" id="price_3" value="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputZip">자재</label>
                                    <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?> name="price_4" id="price_4" value="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputZip">기타</label>
                                    <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?> name="price_5" id="price_5" value="0">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="inputZip">총비용</label>
                                    <input type="text" name="price_hap"  id="price_hap"  <?php if($level_check >= 3){?> disabled <?php }?>  class="form-control" value="0"/>
                                    <input type="hidden" name="price_hap_hd"  id="price_hap_hd"  class="form-control" value="0"/>

                                </div>
                            </div>

                        <?php } ?>

                        <?php if($level_check == "1" || $level_check == "3"){ ?>
                        <div class="form-row">
                            <?php if($level_check == "1"){  ?>
                                <div class="form-group col-md-12">
                                    <h5>센터</h5>
                                </div>
                            <?php } ?>
                            <div class="form-group col-md-2">
                                <label for="inputZip">출장비</label>
                                <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?>  name="price1_0" id="price1_0" value="0">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">수리비</label>
                                <input type="text" class="form-control " <?php if($level_check >= 3){?> disabled <?php }?>  name="price1_1" id="price1_1" value="0">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">거리비</label>
                                <input type="text" class="form-control input_cal"   name="price1_2" id="price1_2" value="0">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">긴급</label>
                                <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?> name="price1_3" id="price1_3" value="0">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">자재</label>
                                <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?> name="price1_4" id="price1_4" value="0">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">기타</label>
                                <input type="text" class="form-control input_cal" <?php if($level_check >= 3){?> disabled <?php }?>  name="price1_5" id="price1_5" value="0">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="inputZip">총비용</label>
                                <input type="text" name="price_hap1"  id="price_hap1" class="form-control"  <?php if($level_check >= 3){?> disabled <?php }?> value="0"/>
                                <input type="hidden" name="price_hap1_hd"  id="price_hap1_hd"  class="form-control" value="0"/>
                            </div>
                        </div>

                        <hr/>
                        <div class="form-group">
                            <label for="inputZip">접수증상</label>
                            <textarea  name="memo" id="memo"  <?php if($level_check >= 3){?> disabled <?php }?> class="form-control" ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputZip">기사 확인 사항</label>
                            <textarea  name="memo1" id="memo1"  class="form-control" ></textarea>
                        </div>
                    </div>
                    <?php } ?>


                    <div class="update_form">
                        <div class="form-group">
                            <label for="inputZip">완료확인서</label>
                            <div class="form-row">
                                <div id="images_message"></div>
                                 <input type="file" name="user_image" class="form-control" id="user_image" />
                                 <small id="emailHelp" class="form-text text-muted"> (gif,jpg,jpeg,png,tiff,pdf,zip 파일만 가능)</small>
                            </div>
                            <div class="form-row">
                                <div id="upload_img" class="upload_img">
                                    <span id="user_uploaded_image"></span>
                                </div>

                            </div>
                        </div>


                        <hr/>
                        <span class="h5">자제 입력</span> <input data-repeater-create type="button" class="btn btn-success  btn-sm" value="자제 추가"/>

                        <hr/>
                            <span id="repeater_message"></span>

                            <div data-repeater-list="group-a" id="material_content">
                                <div class="form-row" data-repeater-item>
                                    <div class="col-md-6 mb-3">
                                        <select name="material1"  class="form-control">
                                            <option value="">자제 선택</option>
                                            <?php echo $material_options ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <input type="number" min="0" max="5" name="material2" value="0" class="form-control" placeholder="수량을 입력해주세요" />
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <input data-repeater-delete type="button" class="btn btn-danger " value="삭제"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">


                            </div>

                        <hr/>
                        <div class="row">
                            <div class="col-md-4">
                                <h5>접수상태</h5>
                                <hr/>
                                <div class="form-row">
                                    <div class="form-group col-md-6">

                                        <label for="inputZip">본사</label>
                                        <select name="stats_fd1" <?php if($level_check >= 3){?> disabled <?php }?>  id="stats_fd1" class="form-control ">
                                            <option value="">접수상태</option>
                                            <?php echo $select_name[1] ?>
                                        </select>
                                        <br/>
                                        <input type="text" <?php if($level_check >= 3){?> disabled <?php }?> autocomplete="off" readonly class="form-control input_cal datepicker" name="date_1" id="date_1">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputZip">센터</label>
                                        <select name="stats_fd2"  id="stats_fd2" class="form-control ">
                                            <option value="">접수상태</option>
                                            <?php echo $select_name[2] ?>
                                        </select>
                                        <br/>
                                        <input type="text" class="form-control input_cal datepicker" name="date_2" autocomplete="off" readonly id="date_2">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>회수상태</h5>
                                <hr/>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputZip">본사</label>
                                        <select name="stats_fd4" <?php if($level_check >= 3){?> disabled <?php }?>   id="stats_fd4" class="form-control ">
                                            <option value="">접수상태</option>
                                            <?php echo $select_name[3] ?>
                                        </select>
                                        <br/>
                                        <input type="text" <?php if($level_check >= 3){?> disabled <?php }?>  autocomplete="off" readonly class="form-control input_cal datepicker" name="date_3" id="date_3">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputZip">센터</label>
                                        <select name="stats_fd5"  id="stats_fd5" class="form-control ">
                                            <option value="">반납상태</option>
                                            <?php echo $select_name[4] ?>
                                        </select>
                                        <br/>
                                        <input type="text" class="form-control input_cal datepicker" autocomplete="off" readonly name="date_4" id="date_4">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>정산상태</h5>
                                <hr/>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputZip">본사</label>
                                        <select name="stats_fd6" <?php if($level_check >= 3){?> disabled <?php }?>  id="stats_fd6" class="form-control ">
                                            <option value="">정산상태</option>
                                            <?php echo $select_name[5] ?>
                                        </select>
                                        <br/>
                                        <input type="text" <?php if($level_check >= 3){?> disabled <?php }?>  autocomplete="off" readonly class="form-control input_cal datepicker" name="date_5" id="date_5">

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputZip">센터</label>
                                        <select name="stats_fd7"  id="stats_fd7" class="form-control ">
                                            <option value="">정산상태</option>
                                            <?php echo $select_name[6] ?>
                                        </select>
                                        <br/>
                                        <input type="text" class="form-control input_cal datepicker"  autocomplete="off" readonly name="date_6" id="date_6">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <form method="post" id="comment_form">
                                    <span id="comment_message"></span>

                                    <label for="inputZip">댓글작성</label>
                                    <textarea  name="content" id="comment_content" name="comment_content" class="form-control" ></textarea>

                                    <input type="button" name="c_submit" id="c_submit_button" class="btn btn-success" value="등록" style="margin-top:5px;">
                                    <?php if($level_check <= "2"){
                                        ?>
                                        <div class="switch d-inline m-r-10">
                                            <input type="checkbox" id="switch-1" name="switch-1"  >
                                            <label for="switch-1" class="cr"></label>
                                        </div>
                                        <label>비밀글 체크시 센터에서는 코멘트가 보이지 않습니다.</label>
                                    <? } ?>
                                    <input type="hidden" name="c_hidden_id" id="c_hidden_id" />
                                </form>
                            </div>


                            <div class="table-responsive">
                                <style>
                                    .table {width: 100% !important; }
                                </style>
                                <table class="table table-striped" id="comment_table" >
                                    <colgroup>
                                        <col width="5%"/>
                                        <col width="*"/>
                                        <col width="5%"/>
                                        <col width="5%"/>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>작성자</th>
                                        <th>내용</th>
                                        <th>등록일</th>
                                        <th>관리</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="hidden" name="m_hidden_id" id="m_hidden_id" />

                    <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function(){
        var lang_kor = {
            "decimal" : "",
            "emptyTable" : "데이터가 없습니다.",
            "info" : "_START_ - _END_ (총 _TOTAL_ 건)",
            "infoEmpty" : "0건",
            "infoFiltered" : "(전체 _MAX_ 건 중 검색결과)",
            "infoPostFix" : "",
            "thousands" : ",",
            "lengthMenu" : "_MENU_ 개씩 보기",
            "loadingRecords" : "로딩중...",
            "processing" : "처리중...",
            "search" : "검색 : ",
            "zeroRecords" : "검색된 데이터가 없습니다.",
            "paginate" : {
                "first" : "첫 페이지",
                "last" : "마지막 페이지",
                "next" : "다음",
                "previous" : "이전"
            },
            "aria" : {
                "sortAscending" : " :  오름차순 정렬",
                "sortDescending" : " :  내림차순 정렬"
            }
        };
        load_data();

        function load_data(from_date = '', to_date = '')
        {
            var dataTable = $('#class_table').DataTable({
                "processing" : true,
                "serverSide" : true,
                language : lang_kor,
                "order" : [],
                "ajax" : {
                    url:"../action/mng_total.php",
                    type:"POST",
                    data:{action:'fetch_search', from_date:from_date, to_date:to_date}
                },
                "columnDefs":[
                    {
                        "targets":[3, 4,5,6,7,8,9,10,11,12,13,14,15,16],
                        "orderable":false,
                    },
                ],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel'
                        ,text: '엑셀출력'
/*                        ,filename: '엑셀파일명'
                        ,title: '엑셀파일 안에 쓰일 제목'*/
                        // customize 옵션으로 excel를 수정하는게가능하다.
                        ,customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            // A열의 1번째행의 제목을 수정하는 부분
                            $('c[r=A1] t', sheet).text( '접수관리' );

                            $('c[r^="Q"] t', sheet).text( '' );
                            //$('c[r^="B2"], c[r^="B7"]', sheet).attr( 's', '20' );
                            var col = $('col', sheet);
                            $(col[0]).attr('width', 15);
                            $(col[1]).attr('width', 15);
                            $(col[2]).attr('width', 15);
                            $(col[3]).attr('width', 60);
                            $(col[4]).attr('width', 15);
                            $(col[5]).attr('width', 15);
                            $(col[6]).attr('width', 15);
                            $(col[7]).attr('width', 15);
                            $(col[8]).attr('width', 15);
                            $(col[9]).attr('width', 20);
                            $(col[10]).attr('width', 20);
                            $(col[11]).attr('width', 20);
                            $(col[12]).attr('width', 20);
                            $(col[13]).attr('width', 20);
                            $(col[14]).attr('width', 20);
                            $(col[15]).attr('width', 15);

                        }
                    }
                ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]

            });
            dataTable.buttons().container().appendTo($('#btn_excel'));

        }

        /*댓글 작업*/
        var c_hidden_id = $("#c_hidden_id").val();
        var dataTable1 = $('#comment_table').DataTable({
            "processing" : true,
            "serverSide" : true,
            "bDestroy": true,
            language : lang_kor,
            "order" : [],
            "ajax" : {
                url:"../action/mng_total.php",
                type:"POST",
                data:{action:'fetch_comment'}
            },
            "columnDefs":[
                {
                    "targets":[0,1,2,3],
                    "orderable":false,
                },
            ],
        });

        //접수추가 버튼 클릭시 작업
        $('#add_class').click(function(){
            $('#class_form')[0].reset();
            $('#class_form').parsley().reset();
            $('#modal_title').text('접수하기');
            $('#action').val('Add');
            $('#submit_button').val('접수하기');
            $('#classModal').modal('show');
            //메세지 초기화
            $('#form_message').html('');
            //코멘트 초기화
            $('#comment_content').val("");
            $('#comment_table tbody').html("");
            //이미지 초기화
            $('#user_image').val("");
            $('#user_uploaded_image').html('');
            $('#repeater_message').html('');
            $('[data-repeater-list]').empty();
            $('[data-repeater-create]').click();
            if(<?php echo $level_check ?> == "1"){
            }else{
                $("#center_name").val("주식회사 옳음").prop("selected", true);
            };
            // 글쓰기 일떄 일부 폼 감추기
            $('.update_form').hide();
            $('#step2').html("");
            $('#step3').html("");
            $('#step4').html("");
        });


        //모달창 폼 전송부분
        $('#class_form').parsley();
        $('#class_form').on('submit', function(event){
            event.preventDefault();
            if($('#class_form').parsley().isValid())
            {
                $.ajax({
                    url:"../action/mng_total.php",
                    method:"POST",
                    data:new FormData(this),
                    dataType:'json',
                    contentType:false,
                    processData:false,
                    beforeSend:function()
                    {
                        $('#submit_button').attr('disabled', 'disabled');
                        $('#submit_button').val('처리중...');
                    },
                    success:function(data)
                    {
                        $('#submit_button').attr('disabled', false);
                        if(data.error != '')
                        {
                            swal("실패", data.error, "error");
                            $('#submit_button').val('접수하기');
                        }
                        else
                        {
                            $('#classModal').modal('hide');
                            $('#class_table').DataTable().destroy();
                            repeater_load();
                            load_data();
                            swal("처리완료", data.success, "success");                        
                            dataTable.ajax.reload(null, false);
                        }
                    }
                })
            }
        });

        //수정버튼 클릭시 모달창 띄우고 정보들 가져옴
        $(document).on('click', '.edit_button', function(){
            var class_id = $(this).data('id');
            var repeater_data = $('.repeater').repeaterVal();

            //폼 초기화
            $('#class_form').parsley().reset();
            $('#step1').html("");
            $('#step2').html("");
            $('#step3').html("");
            $('#step4').html("");
            $('#form_message').html('');

            $('#comment_content').val("");
            $('.update_form').show();
            $('[data-repeater-list]').empty();

            //댓글 부분 데이터테이블 처리.
            var dataTable1 = $('#comment_table').DataTable({
                "processing" : true,
                "serverSide" : true,
                language : lang_kor,
                "bDestroy": true,
                "order" : [],
                "ajax" : {
                    url:"../action/mng_total.php",
                    type:"POST",
                    data:{class_id: class_id, action:'fetch_comment1'}
                },
                "columnDefs":[
                    {
                        "targets":[0,1,2,3],
                        "orderable":false,
                    },
                ],
            });
            //데이터 가져와서 모달창에 뿌려주는 부분
            $.ajax({
                url:"../action/mng_total.php",
                method:"POST",
                data:{class_id:class_id, m_hidden_id:class_id, repeater_data:repeater_data, action:'fetch_single'},
                dataType:'JSON',
                success:function(data)
                {
                    $('#user_name').val(data.user_name);
                    $('#phone1').val(data.phone1);
                    $('#phone2').val(data.phone2);
                    $('#step1').html(data.step0_text);
                    $('#step2').html(data.step1_text);
                    $('#step3').html(data.step2_text);
                    $('#step4').html(data.step3_text);
                    $('#step5').html(data.step5_text);
                    $('#step5_price').val(data.step5_price);
                    $('#price_0').val(data.price_0);
                    $('#price_1').val(data.price_1);
                    $('#price_2').val(data.price_2);
                    $('#price_3').val(data.price_3);
                    $('#price_4').val(data.price_4);
                    $('#price_5').val(data.price_5);
                    $('#price1_0').val(data.price1_0);
                    $('#price1_1').val(data.price1_1);
                    $('#price1_2').val(data.price1_2);
                    $('#price1_3').val(data.price1_3);
                    $('#price1_4').val(data.price1_4);
                    $('#price1_5').val(data.price1_5);
                    $('#distance_select').val(data.distance_select);
                    $('#price_hap').val(data.price_hap);
                    $('#price_hap1').val(data.price_hap1);
                    $('#price_hap_hd').val(data.price_hap_hd);
                    $('#price_hap1_hd').val(data.price_hap1_hd);
                    $('#addr').val(data.addr);
                    $('#center_name').val(data.center_name);
                    $('#last_name').val(data.last_name);
                    $('#memo').val(data.memo);
                    $('#memo1').val(data.memo1);
                    $('#user_uploaded_image').html(data.user_profile);
                    $('#stats_fd1').val(data.stats_fd1);
                    $('#stats_fd2').val(data.stats_fd2);
                    $('#stats_fd3').val(data.stats_fd3);
                    $('#stats_fd4').val(data.stats_fd4);
                    $('#stats_fd5').val(data.stats_fd5);
                    $('#stats_fd6').val(data.stats_fd6);
                    $('#stats_fd7').val(data.stats_fd7);
                    $('#date_1').val(data.date_1);
                    $('#date_2').val(data.date_2);
                    $('#date_3').val(data.date_3);
                    $('#date_4').val(data.date_4);
                    $('#date_5').val(data.date_5);
                    $('#date_6').val(data.date_6);
                    $('#modal_title').text('접수수정');
                    $('#action').val('Edit');
                    $('#submit_button').val('수정');
                    $('#classModal').modal('show');
                    $('#hidden_id').val(class_id);
                    $('#c_hidden_id').val(class_id);
                    $('#m_hidden_id').val(class_id);
                }
            })
            //자제 데이터 불러오기.
            $.ajax({
                url:"../action/mng_total.php",
                method:"POST",
                data:{class_id:class_id, action:'mt_single'},
                dataType:'JSON',
                success:function(data)
                {
                    $('#material_content').html(data.group_data1);

                }
            })
        });
        // 데이터 삭제 처리
        $(document).on('click', '.delete_button', function(){
            var id = $(this).data('id');
            if(confirm("정말로 데이터를 삭제하시겠습니까?"))
            {
                $.ajax({
                    url:"../action/mng_total.php",
                    method:"POST",
                    data:{id:id, action:'delete'},
                    success:function(data)
                    {
                        $('#class_table').DataTable().destroy();
                        load_data();
                        //dataTable.ajax.reload();
                        swal("처리완료", data, "success"); 

                    }
                })
            }
        });

        //파일 삭제버튼
        $(document).on('click', '.img_delete', function(){
            var id = $("#img_delete").data('id');
            if(confirm("파일을 삭제하시겠습니까?"))
            {
                $.ajax({
                    url:"../action/mng_total.php",
                    method:"POST",
                    data:{id:id, action:'img_delete'},
                    success:function(data)
                    {
                        swal("처리완료", data, "success");                        
                        dataTable.ajax.reload(null, false);
                    }
                })
            }
        });

        //이미지 첨부
        $('#user_image').change(function(){
            var extension = $('#user_image').val().split('.').pop().toLowerCase();
            if(extension != '')
            {
                if(jQuery.inArray(extension, ['gif','png','jpg','jpeg','tiff','pdf','zip']) == -1)
                {
                    swal("에러", "확장자를 확인해주세요 gif, png, jpg, jpeg 파일의 형식을 지원합니다.", "error");                        
                    $('#user_image').val('');
                    return false;
                }
            }
            if($("#user_image").val() != ""){

                var maxSize = 20 * 1024 * 1024; // 20MB

                var fileSize = $("#user_image")[0].files[0].size;
                if(fileSize > maxSize){
                    swal("에러", "첨부파일 사이즈는 20MB 이내로 등록 가능합니다.", "error");  
                    $('#user_image').val('');
                    return false;
                }
            }
        });


        function repeater_load() {
            var m_hidden_id = $("#m_hidden_id").val();
            var repeater_data = $('.repeater').repeaterVal();
            $.ajax({
                url:"../action/mng_total.php",
                method:"POST",
                data:{m_hidden_id:m_hidden_id, repeater_data:repeater_data, action:'materialcontrol_add'},
                success:function(data)
                {
                    swal("처리완료", data, "success");                        
                    dataTable.ajax.reload(null, false);
                }

            })
        }

        //댓글 전송
        $('#class_form1').parsley();
        $('#class_form1').on('submit', function(event){
            event.preventDefault();
            if($('#class_form1').parsley().isValid())
            {
                $.ajax({
                    url:"../action/mng_total.php",
                    method:"POST",
                    data:$(this).serialize(),
                    dataType:'json',
                    beforeSend:function()
                    {
                        $('#c_submit_button').attr('disabled', 'disabled');
                        $('#c_submit_button').val('wait...');
                    },
                    success:function(data)
                    {
                        $('#submit_button').attr('disabled', false);
                        if(data.error != '')
                        {
                            $('#form_message').html(data.error);
                            $('#c_submit_button').val('Add');
                        }
                        else
                        {
                            $('#c_submit_button').html(data.success);
                            setTimeout(function(){
                                $('#message').html('');

                            }, 5000);
                        }
                    }
                })
            }
        });

        //댓글 쓰기
        $('#c_submit_button').click(function(event){
            event.preventDefault();
            var c_hidden_id = $("#c_hidden_id").val();
            var comment_content = $("#comment_content").val();
            var switch_1 = $('#switch-1').is(':checked');
            if(confirm("확인 버튼을 누르시면 댓글이 작성됩니다"))
            {
                $.ajax({
                    url:"../action/mng_total.php",
                    method:"POST",
                    data:{c_hidden_id:c_hidden_id, comment_content:comment_content, switch_1:switch_1, action:'comment_add'},
                    success:function(data)
                    {
                        
                        var dataTable1 = $('#comment_table').DataTable({
                            "processing" : true,
                            "serverSide" : true,
                            language : lang_kor,
                            "bDestroy": true,
                            "order" : [],
                            "ajax" : {
                                url:"../action/mng_total.php",
                                type:"POST",
                                data:{class_id: c_hidden_id, action:'fetch_comment1'}
                            },
                            success:function(data){
                                $('#comment_content').val("");                                
                            }
                        });
                        swal("처리완료", data, "success");                        
                        dataTable.ajax.reload(null, false);
                        setTimeout(function(){
                            $('#comment_message').html('');
                            $('#comment_content').val("");
                        }, 5000);
                    }
                })
            }
        });

        //댓글 삭제
        $(document).on('click', '.comment_delete_button', function(){
            var id = $(this).data('id');
            var c_hidden_id = $("#c_hidden_id").val();
            if(confirm("정말로 데이터를 삭제하시겠습니까?"))
            {
                $.ajax({
                    url:"../action/mng_total.php",
                    method:"POST",
                    data:{id:id, action:'comment_delete'},
                    success:function(data)
                    {
                        var dataTable1 = $('#comment_table').DataTable({
                            "processing" : true,
                            "serverSide" : true,
                            language : lang_kor,
                            "bDestroy": true,
                            "order" : [],
                            "ajax" : {
                                url:"../action/mng_total.php",
                                type:"POST",
                                data:{class_id: c_hidden_id, action:'fetch_comment1'}
                            },
                            "columnDefs":[
                                {
                                    "targets":[0,1,2,3],
                                    "orderable":false,
                                },
                            ],
                        });
                       
                        swal("데이터 삭제 완료", data, "success");
                        dataTable1.ajax.reload(null, false);
                    }
                })
            }
        });


        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $('#class_table').DataTable().destroy();
            load_data(from_date, to_date);
        });

        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#class_table').DataTable().destroy();
            load_data();
        });

        $('#export').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date != '' && to_date != '')
            {
                window.location.href="<?php echo $visitor->base_url; ?>export.php?from_date="+from_date+"&to_date="+to_date+"";
            }
            else
            {
                window.location.href="<?php echo $visitor->base_url; ?>export.php";
            }
        });


        $('.action').change(function(){
            if($(this).val() != '')
            {
                var action = $(this).attr("id");
                var query = $(this).val();
                var result = '';
                if(action == "step1")
                {
                    result = 'step2';
                    $('#step3').html("");
                    $('#step4').html("");
                }
                else if(action == "step2")
                {
                    result = 'step3';
                    $('#step3').html("");
                    $('#step4').html("");
                }
                else if(action == "step3")
                {
                    result = 'step4';
                    $('#step4').html("");
                }
                else if(action == "step4")
                {
                    result = 'step4';
                }
                else if(action == "step5")
                {
                    result = 'step5';
                }
                $.ajax({
                    url:"../action/mng_selectbox.php",
                    method:"POST",
                    data:{action:action, query:query},
                    success:function(data){
                        if(action == "step4"){
                            console.log(data);
                            const splitResult = data.split(',');
                            $('#price_1').val(splitResult[0]);
                            $('#price1_1').val(splitResult[1]);
                        }
                        else{
                            $('#price_1').val("0");
                            $('#price1_1').val("0");
                            $('#'+result).html(data);
                        }
                    }
                })
                price_hap();
                price_hap1();
            }
        });
        $('.as_action').change(function(){
            if($(this).val() != '')
            {
                var action = $(this).attr("id");
                var query = $(this).val();
                $('#price_2').val(query);
                $('#price1_2').val(query);
            };
            price_hap();
            price_hap1();
        });
        $('.tr_action').change(function(){
            if($(this).val() != '')
            {
                var action = $(this).attr("id");
                var query = $("#step5 option:selected").attr("id");
                const splitResult1 = query.split(',');
                $('#price_0').val(splitResult1[0]);
                $('#price1_0').val(splitResult1[1]);
                //var query = $(this).val();
            };
            price_hap();
            price_hap1();
        });


        $('.input_cal').change(function(){
            price_hap();
            price_hap1();
        });


        function price_hap() {
            var price_hap = Number($('#price_0').val()) + Number($('#price_1').val()) + Number($('#price_2').val()) + Number($('#price_3').val()) + Number($('#price_4').val()) + Number($('#price_5').val()) ;
            $('#price_hap').val(price_hap);
            $('#price_hap_hd').val(price_hap);

        }
        function price_hap1() {
            var price_hap1 = Number($('#price1_0').val()) + Number($('#price1_1').val()) + Number($('#price1_2').val()) + Number($('#price1_3').val()) + Number($('#price1_4').val()) + Number($('#price1_5').val());
            $('#price_hap1').val(price_hap1);
            $('#price_hap1_hd').val(price_hap1);
        }

    });
</script>

</body>
</html>
