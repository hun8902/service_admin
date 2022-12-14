<?php include("config/db_con.php");
$object = new srms();
if(!$object->is_login())
{
    header("location:".$object->base_url."");
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
<div class="main-body">
    <div class="page-wrapper">
        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-header">
                        <h5>리스트</h5>
                    </div>
                    <style>
                        .table tr th {text-align:center; vertical-align: middle}
                        .table tr td {text-align:center; vertical-align: middle}
                    </style>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover table-columned">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">등록일</th>
                                        <th rowspan="2">고객명</th>
                                        <th rowspan="2">연락처</th>
                                        <th rowspan="2">센터</th>
                                        <th colspan="5">서비스</th>
                                        <th colspan="4">접수상태</th>
                                        <th colspan="2">회수상태</th>
                                        <th colspan="2">정산상태</th>
                                        <th rowspan="2">검수자</th>
                                        <th rowspan="2" >C</th>
                                    </tr>
                                    <tr>
                                        <th>모델명</th>
                                        <th>구분</th>
                                        <th>분류1</th>
                                        <th>분류2</th>
                                        <th>분류3</th>
                                        <th colspan="2">본사</th>
                                        <th colspan="2">센터</th>
                                        <th>본사</th>
                                        <th>센터</th>
                                        <th>본사</th>
                                        <th>센터</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- 번호 -->
                                        <td>7</td>
                                        <!-- 등록일 -->
                                        <td>02-07</td>
                                        <!-- 고객명 -->
                                        <td style="cursor:hand" onclick="open_win('./view.php?no=8','','850','850','yes','yes')"><font color="blue">정용</font></td>
                                        <!-- 연락처 -->
                                        <td>010-5050-0321</td>
                                        <!-- 센터명 -->
                                        <td>주식회사 빌리고</td>
                                        <!-- 서비스 - 모델명 -->
                                        <td></td>
                                        <!-- 서비스 - 구분 -->
                                        <td></td>
                                        <!-- 서비스 - 분류1 -->
                                        <td>출장비</td>
                                        <!-- 서비스 - 분류2 -->
                                        <td>단순장애</td>
                                        <!-- 서비스 - 분류3 -->
                                        <td>일반</td>
                                        <!-- 접수상태 - 본사 - 상태 -->
                                        <td ><span style="background-color:#66FFFF">서비스요청</span></td>
                                        <!-- 접수상태 - 본사 - 일자 -->
                                        <td>02-07</td>
                                        <!-- 접수상태 - 센터명 - 상태 -->
                                        <td><span style="background-color:#D3FFDE">작업완료</span></td>
                                        <!-- 접수상태 - 센터명 - 일자 -->
                                        <td>02-08</td>
                                        <!-- 회수상태 - 본사 - 상태 -->
                                        <td><span style="background-color:#66FFFF">회수요청</span></td>
                                        <!-- 회수상태 - 센터명 - 상태 -->
                                        <td><span style="background-color:#FDE1FD">회수중</span></td>
                                        <!-- 정산상태 - 본사 - 상태 -->
                                        <td><span style="background-color:"></span></td>
                                        <!-- 정산상태 - 센터명 - 상태 -->
                                        <td><span style="background-color:"></span></td>
                                        <!-- 접수자 -->
                                        <td>김사원</td>
                                        <!-- 코멘트 -->
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- 번호 -->
                                        <td>7</td>
                                        <!-- 등록일 -->
                                        <td>02-07</td>
                                        <!-- 고객명 -->
                                        <td style="cursor:hand" onclick="open_win('./view.php?no=8','','850','850','yes','yes')"><font color="blue">정용</font></td>
                                        <!-- 연락처 -->
                                        <td>010-5050-0321</td>
                                        <!-- 센터명 -->
                                        <td>주식회사 빌리고</td>
                                        <!-- 서비스 - 모델명 -->
                                        <td></td>
                                        <!-- 서비스 - 구분 -->
                                        <td></td>
                                        <!-- 서비스 - 분류1 -->
                                        <td>출장비</td>
                                        <!-- 서비스 - 분류2 -->
                                        <td>단순장애</td>
                                        <!-- 서비스 - 분류3 -->
                                        <td>일반</td>
                                        <!-- 접수상태 - 본사 - 상태 -->
                                        <td ><span style="background-color:#66FFFF">서비스요청</span></td>
                                        <!-- 접수상태 - 본사 - 일자 -->
                                        <td>02-07</td>
                                        <!-- 접수상태 - 센터명 - 상태 -->
                                        <td><span style="background-color:#D3FFDE">작업완료</span></td>
                                        <!-- 접수상태 - 센터명 - 일자 -->
                                        <td>02-08</td>
                                        <!-- 회수상태 - 본사 - 상태 -->
                                        <td><span style="background-color:#66FFFF">회수요청</span></td>
                                        <!-- 회수상태 - 센터명 - 상태 -->
                                        <td><span style="background-color:#FDE1FD">회수중</span></td>
                                        <!-- 정산상태 - 본사 - 상태 -->
                                        <td><span style="background-color:"></span></td>
                                        <!-- 정산상태 - 센터명 - 상태 -->
                                        <td><span style="background-color:"></span></td>
                                        <!-- 접수자 -->
                                        <td>김사원</td>
                                        <!-- 코멘트 -->
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<!-- 컨텐츠 끝 -->
<?php include("common/footer.php") ?>
</body>
</html>
