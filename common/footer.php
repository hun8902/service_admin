
            </div>
        </div>
    </div>
</div>


<script src="../assets/js/vendor-all.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../assets/js/pcoded.min.js"></script>

<script src="../assets/plugins/prism/js/prism.min.js"></script>
<script src="../assets/js/horizontal-menu.js"></script>

<script src="../assets/plugins/modal-window-effects/js/classie.js"></script>
<script src="../assets/plugins/modal-window-effects/js/modalEffects.js"></script>

<script src="../assets/plugins/data-tables/js/datatables.min.js"></script>
<script src="../assets/js/pages/tbl-datatable-custom.js"></script>

<!-- Sweet alert Js -->
<script src="../assets/plugins/sweetalert/js/sweetalert.min.js"></script>

<!-- Input mask Js -->
<script src="../vendor/parsley/dist/parsley.min.js"></script>

<!-- Datepicker Js -->
<script src="../assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
<!--<script src="../assets/js/pages/ac-datepicker.js"></script>-->
<script>
    $(function () {
        $(".datepicker_search").datepicker({
            // The ISO language code (built-in: en-US)
            changeYear: true,
            changeMonth: false,
            // The date string format
            format: 'yyyy-mm-dd',
           
        });
        $(".datepicker").datepicker({

        });
    });
</script>
<script src="../assets/js/jquery.repeater.js"></script>

<script src="//cdn.datatables.net/plug-ins/1.11.5/dataRender/ellipsis.js"></script>

<!-- Nestable Js -->
<script src="../assets/plugins/nestable-master/js/jquery.nestable.js"></script>

<script src="../assets/js/jquery.printelement.js"></script>
<script>
    $(document).ready(function () {
        'use strict';
        $("#simplePrint").click(function(){
            $('#write_form').printElement();
        });

        $('.repeater').repeater({
            defaultValues: {

            },
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('해당 자제를 삭제하시겠습니까?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            }
        });

    });

    // Collapse menu
    (function() {
        if ($('#layout-sidenav').hasClass('sidenav-horizontal') || window.layoutHelpers.isSmallScreen()) {
            return;
        }
        try {
            window.layoutHelpers.setCollapsed(
                localStorage.getItem('layoutCollapsed') === 'true',
                false
            );
        } catch (e) {}
    })();
    $(function() {
        // Initialize sidenav
        $('#layout-sidenav').each(function() {
            new SideNav(this, {
                orientation: $(this).hasClass('sidenav-horizontal') ? 'horizontal' : 'vertical'
            });
        });

        // Initialize sidenav togglers
        $('body').on('click', '.layout-sidenav-toggle', function(e) {
            e.preventDefault();
            window.layoutHelpers.toggleCollapsed();
            if (!window.layoutHelpers.isSmallScreen()) {
                try {
                    localStorage.setItem('layoutCollapsed', String(window.layoutHelpers.isCollapsed()));
                } catch (e) {}
            }
        });
    });
    $(document).ready(function() {
        $("#pcoded").pcodedmenu({
            themelayout: 'horizontal',
            MenuTrigger: 'hover',
            SubMenuTrigger: 'hover',
        });

        Parsley.addMessages('ko', {
            defaultMessage: "입력하신 내용이 올바르지 않습니다.",
            type: {
                email:        "이메일을 입력해야 합니다.",
                url:          "URL을 입력해야 합니다.",
                number:       "숫자를 입력해야 합니다.",
                integer:      "정수를 입력해야 합니다.",
                digits:       "입력하신 내용은 숫자의 조합이여야 합니다.",
                alphanum:     "입력하신 내용은 알파벳과 숫자의 조합이어야 합니다."
            },
            notblank:       "공백은 입력하실 수 없습니다.",
            required:       "필수 입력사항입니다.",
            pattern:        "입력하신 내용이 올바르지 않습니다.",
            min:            "입력하신 내용이 %s보다 크거나 같아야 합니다. ",
            max:            "입력하신 내용이 %s보다 작거나 같아야 합니다.",
            range:          "입력하신 내용이 %s보다 크고 %s 보다 작아야 합니다.",
            minlength:      "%s 이상의 글자수를 입력하십시오. ",
            maxlength:      "%s 이하의 글자수를 입력하십시오. ",
            length:         "입력하신 내용의 글자수가 %s보다 크고 %s보다 작아야 합니다.",
            mincheck:       "최소한 %s개를 선택하여 주십시오. ",
            maxcheck:       "%s개 또는 그보다 적게 선택하여 주십시오.",
            check:          "선택하신 내용이 %s보다 크거나 %s보다 작아야 합니다.",
            equalto:        "같은 값을 입력하여 주십시오."
        });

        Parsley.setLocale('ko');
    });

</script>
