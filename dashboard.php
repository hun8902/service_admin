<?php include("config/db_con.php");
$object = new srms();
if(!$object->is_login())
{
    header("location:".$object->base_url."");
}else{
    header("location:".$object->base_url."view/mng_total.php");
}
if(!$object->is_master_user())
{
    //header("location:".$object->base_url."result.php");
}
?>
<?php include("common/script.php") ?>
<body>
<?php include("common/header.php") ?>
<!-- 컨텐츠 시작 -->
<?php include("common/page_header.php") ?>
<!-- 컨텐츠 시작 -->

<!-- 컨텐츠 끝 -->
<?php include("common/footer.php") ?>
</body>
</html>
