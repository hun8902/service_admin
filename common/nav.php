<?php
$base_filename = basename($_SERVER['PHP_SELF']); //현재 페이지 파일명
$gnb_selected = "";
?>

<nav class="pcoded-navbar theme-horizontal">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="../index.php" class="b-brand">
                <div class="b-bg">
                    <i class="feather icon-trending-up"></i>
                </div>
                <span class="b-title">Admin System</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
            <ul class="nav pcoded-inner-navbar sidenav-inner">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo $gnb_selected ?>">
                    <a href="../index.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">접수 관리</span></a>
                </li>
                <?php if($level_check == "1"){?>
                <li data-username="mng_code Page" class="nav-item"><a href="../view/mng_code.php" class="nav-link <?php echo $gnb_selected ?>"><span class="pcoded-micon"><i class="feather icon-sidebar"></i></span><span class="pcoded-mtext">코드관리</span></a></li>
                <li data-username="mng_materialcontrol Page" class="nav-item"><a href="../view/mng_materialcontrol.php" class="nav-link <?php echo $gnb_selected ?>"><span class="pcoded-micon"><i class="feather icon-file-plus"></i></span><span class="pcoded-mtext">자제관리</span></a></li>
                <? } ?>
                <li data-username="mng_company Page" class="nav-item"><a href="../view/mng_company.php" class="nav-link <?php echo $gnb_selected ?>"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">업체관리</span></a></li>

                <li data-username="logout Page" class="nav-item"><a href="../logout.php" class="nav-link <?php echo $gnb_selected ?>"><span class="pcoded-micon"><i class="feather icon-log-out"></i></span><span class="pcoded-mtext">로그아웃</span></a></li>
            </ul>
        </div>
    </div>
</nav>