<?php
include("../config/db_con.php");
$object = new srms();
$level_check = $object->level_check();
if(!$object->is_login())
{
    header("location:".$object->base_url."index.php");
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
    $query = $db->query("SELECT * FROM category WHERE parent_id = ? ORDER BY id_menu ASC", $parent_id);

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



<div class="auth-wrapper offline">
    <div class="text-center">
        <h1 class="mb-4">해당 페이지에 대한 접근 권한이 없습니다.</h1>
        <h5 class="text-muted mb-4">관리자에게 문의주세요</h5>
        <form action="../index.php">
            <button class="btn btn-primary mb-4"><i class="feather icon-home"></i>홈으로</button>
        </form>
    </div>
</div>






    <!-- 컨텐츠 끝 -->
    <?php include("../common/footer.php") ?>
</body>
</html>