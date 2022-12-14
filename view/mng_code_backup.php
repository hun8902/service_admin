<?php
include("../config/db_con.php");
$object = new srms();
//각종권한 체크
$level_check = $object->level_check();
$cp_name_check = $object->cp_name_check();
$login_mame = $object->login_mame();
$cmp_code = $object->cmp_code();
$login_id = $object->login_id();

if($level_check == "1"){
} else {
    header("Location: /view/no_permission.php");
}
if(!$object->is_login())
{
    header("location:".$object->base_url."/index.php");
}

$object->query = "
SELECT * FROM travel_expenses 
ORDER BY idx ASC;
";
$result = $object->get_result();
$travel_1 = '';
foreach($result as $row => $value)
{
    $travel_1 .= '
     <div class="col-md-6">
        <h5>'.$value['name'].'  <span class="badge badge-danger">(필수)</span></h5>
        <hr/>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputZip">본사</label>
                <input type="hidden" name="expenses_text" id="expenses_text'.$row.'" value="'.$value['name'].'">
                <input type="text" class="form-control" name="expenses" id="expenses'.$row.'" class="expenses" placeholder="금액을 설정해주세요" value="'.$value['price'].'">
            </div>
            <div class="form-group col-md-6">
                <label for="inputZip">센터</label>
                <input type="text" class="form-control" name="expenses_center" id="expenses_center'.$row.'" class="expenses" placeholder="금액을 설정해주세요" value="'.$value['price_center'].'">
            </div>
        </div>
    </div>
    '
    ;

}


function renderMenuItem($id, $label, $price1, $price2)
{
    return '<li class="dd-item dd3-item" data-id="' . $id . '" data-label="' . $label . '" data-price1="' . $price1 . '" data-price2="' . $price2 . '" data-url="' . $id . '">' .
        '<div class="dd-handle dd3-handle" > Drag</div>' .
        '<div class="dd3-content"><input type="text" class="form-control2" name="navigation_label" value="' . $label . '">' .
        '<div class="item-delete">삭제</div>' .
        '<div class="item-edit">가격 입력</div>' .
        '</div>' .
        '<div class="item-settings d-none">' .
        '<p><label for="">위탁 수수료<br><input type="text" name="price1" id="price1" value="' . $price1 . '"></label></p>' .
        '<p><label for="">처리 수수료<br><input type="text" name="price2"  id="price2"  value="' . $price2 . '"></label></p>' .
        '<p>' .
        '<a class="item-close" href="javascript:;">닫기</a></p>' .
        '</div>';
}

function menuTree($parent_id = 0)
{
    global $db;
    $items = '';
    $query = $db->query("SELECT * FROM category WHERE parent_id = ? ", $parent_id);

    if ($query->numRows() > 0) {
         $items .= '<ol class="dd-list">';
        $result = $query->fetchAll();
        foreach ($result as $row) {
            $items .= renderMenuItem($row['id_menu'], $row['label_menu'], $row['price1'], $row['price2']);
            $items .= menuTree($row['id_menu']);
            $items .= '</li>';
        }
        $items .= '</ol>';

    }
    return $items;
}

?>
<?php include("../common/script.php") ?>
<body>
<?php include("../common/header.php") ?>
<!-- 컨텐츠 시작 -->
<?php include("../common/top.php") ?>
<!-- 컨텐츠 시작 -->

<link rel="stylesheet" href="../assets/nestable/css/jquery.nestable.css">
<link rel="stylesheet" href="../assets/nestable/css/style.css">

<div class="main-body">
    <div class="page-wrapper">

        <div class="row">

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo $class_name; ?> 리스트 관리</h5>
                        <span class="d-block m-t-5">설정 저장 버튼을 눌러야 적용됩니다.</span>
                    </div>
                    <div class="card-body">

                        <nav id="nestable-menu">
                            <button class="btn btn-primary btn-sm" type="submit" id="setting_save">설정 저장</button>
                            <button class="btn btn-secondary btn-sm m-b-10 m-r-10" type="button" data-action="expand-all">모두 열기</button>
                            <button class="btn btn-secondary btn-sm m-b-10" type="button" data-action="collapse-all">모두 닫기</button>
                        </nav>
                        <div class="cf nestable-lists">
                            <div class="dd" id="nestable">
                                <?php
                                $html_menu = menuTree();
                                echo (empty($html_menu)) ? '<ol class="dd-list"></ol>' : $html_menu;
                                ?>
                            </div>
                        </div>
                        <form action="../action/mng_code.php" method="post">
                            <!--<textarea id="nestable-output" name="menu" style="width: 100%; height:100px;"></textarea>-->
                            <input type="hidden" id="nestable-output" name="menu">
                            <button class="btn btn-primary btn-sm" type="submit" id="setting_save">설정 저장</button>
                        </form>

                    </div>
                </div>
            </div>
           
                
            <div id="right_pannel" class="col-sm-6" >
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo $class_name; ?> 코드 생성</h5>
                        <span class="d-block m-t-5">추가버튼을 누르면 리스트 관리란에 추가됩니다.</span>
                    </div>
                    <div class="card-body">
                        <form id="add-item">

                            카테고리 설정은 최대 하위메뉴 5번째까지만 지원합니다.
                            <input type="text" class="form-control" name="name" placeholder="추가할 이름을 입력해주세요" >
                            <br/>
                            가격 설정은 하위메뉴 2번째와 5번쨰 구간에서만 작동합니다. 위 값들은 접수관리와 연동됩니다.
                            <input type="text" class="form-control" name="price1" placeholder="위탁 수수료 또는 유상 출장비를 기입해주세요">
                            <br/>
                            <input type="text" class="form-control" name="price2" placeholder="처리 수수료">
                            <hr />
                            <button class="btn btn-primary btn-sm" type="submit">추가</button>
                        </form>

                    </div>
                    <div class="card-header">
                        <h5><?php echo $class_name; ?> 출장비 관리</h5>
                    </div>
                    <div class="card-body">
                        <form name="testForm">
                            <span id="form_message"></span>
                            <div class="row">
                                <?php echo $travel_1 ?>
                            </div>
                            <button class="btn btn-primary " id="add-item1"  type="submit">설정</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 컨텐츠 끝 -->
    <?php include("../common/footer.php") ?>
</body>
</html>
<script src="../assets/nestable/js/jquery.nestable.js"></script>
<script src="../assets/nestable/js/script.js"></script>
<script>
    $(document).ready(function(){
        $('#add-item1').click(function() {
            event.preventDefault();
            //출장비 유형
            var expenses_list = new Array();
            $("input[name=expenses_text]").each(function(index, item){
                expenses_list.push($(item).val());
            });
            //출장비 
            var expenses = new Array();
            $("input[name=expenses]").each(function(index, item){
                expenses.push($(item).val());
            });

            var expenses_center = new Array();
            $("input[name=expenses_center]").each(function(index, item){
                expenses_center.push($(item).val());
            });

            var queryString = $("form[name=testForm]").serialize() ;
            var result = confirm('확인버튼을 누르면 코드가 변경됩니다. 확인해주세요');
            if(result) {
                //확인버튼 누를시
                $.ajax({
                    url:"../action/expenses.php",
                    method:"POST",
                    data:{expenses_list, expenses, expenses_center},
                    //data:{objParams},
                    success:function(data){
                        swal("처리 완료", data, "success");
                    }
                })
            } else {
                //취소버튼 누를시
                return false;
            }
        });
        $('#setting_save').click(function() {
            var result = confirm('확인버튼을 누르면 코드가 변경됩니다. 확인해주세요');
            if(result) {
                //확인버튼 누를시
                $("form").submit();
                //location.replace('../action/mng_code.php');
            } else {
                //취소버튼 누를시
                return false;
            }
        });
        $('#nestable-menu').on('click', function(e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd-list').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd-list').nestable('collapseAll');
            }
        });
    });
    /*
    $(window).resize(function(){ 
        if (window.innerWidth > 700) {
            $('#right_pannel').css({"position": "fixed", "right": "0"});
        } else {
            $('#right_pannel').css({"position": "relative"});
        }
    }).resize();
    */
</script>