<?php
include("../config/db_con.php");
$object = new srms();
$level_check = $object->level_check();
if(!$object->is_login())
{
    header("location:../index.php");
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
                    <div class="card-header">
                        <h5>업체관리</h5>
                        <span class="d-block m-t-5">use class <code>table-hover</code> inside table element</span>
                    </div>
                    <div class="card-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Email</label>
                                <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Password</label>
                                <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Address</label>
                            <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress2">Address 2</label>
                            <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCity">City</label>
                                <input type="text" class="form-control" id="inputCity">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputState">State</label>
                                <select id="inputState" class="form-control">
                                    <option selected>select</option>
                                    <option>Large select</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">Zip</label>
                                <input type="text" class="form-control" id="inputZip">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck">
                                <label class="form-check-label" for="gridCheck">Check me out</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </form>
                    </div>

                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>사원정보</h5>
                        <span class="d-block m-t-5">use class <code>table-hover</code> inside table element</span>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover table-columned">
                                <thead>
                                    <tr>
                                        <td><b>사원명</b></td>
                                        <td><b>아이디</b></td>
                                        <td><b>비밀번호</b></td>
                                        <td><b>연락처</b></td>
                                        <td><b>로그인</b></td>
                                        <td><b>생성일</b></td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td><a href="javascript:;" onclick="open_win('./modify_member.php?company=수원&amp;no=3','internet_write','600','600','no','no')"><span style="color:blue">정용(테스트)  </span></a></td>
                                        <td>test1</td>
                                        <td>a123456789!</td>
                                        <td>01050500312</td>
                                        <td>가능</td>
                                        <td>2022.02.10 11:08</td>
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
<?php include("../common/footer.php") ?>
</body>
</html>
