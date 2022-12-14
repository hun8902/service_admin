$(document).ready(function() {
    ({
        init: function() {
            
            // selectric
            $('.selectric_wrap select').selectric();
            
            // datepicker
            $('[data-toggle="datepicker"]').datepicker({
                language: 'ko-KR',
                format: 'yyyy-mm-dd',
                autoHide: true,
            });

            // 금일 현황
            this.todayState();
            setInterval(this.todayState, 3000);

            // 제품군 최신화
            this.schCate1Load($('#schService option:selected').val());

            // 접수현황 갯수
            $('#pageNum').val($('#pageNumChk').val()).selectric('refresh');
            this.eventListener();

            $('[data-toggle="focus"]').ggpopover();
            
        },

        eventListener: function() {
            var that = this;
            
            // 프린트 버튼을 클릭했을경우
            $('.printPage').click(function(){
                window.print();
                return false;
            });

            // 렌탈사 선택시
            $('#schService').selectric().on('change', function() {
                $('#schServiceChk').val("");
                that.schCate1Load($(this).val());
            });

            // 기간 선택시 발생하는 이벤트
            $('#schCate2').selectric().on('change', function() {

                var dateValue = $(this).val().split('/');
                $('#schStartDate').val(dateValue[0]);
                $('#schEndDate').val(dateValue[1]);
            });

            // 리스트 개수 변경
            $('#pageNum').selectric().on('change', function() {
                $('#pageNumChk').val($(this).val());
                $('#searchForm').submit();
            });

            // 초기화 버튼
            $('.btn_reset').click(function(){
                that.schFromReset();
            });

            // 엑셀다운 버튼
            $("#btn_excel").click(function() {
                var formData = $( "#searchForm" ).serialize(); 
                console.log(formData);
                location.href="./common/excel.php?" + formData;
            });

            // 검색 버튼
            $("#btn_search").click(function() {
                $( "#searchForm" ).submit(); 
            });

            // 키워드 검색 엔터 입력시 자동 서브밋
            $('#schKeyword').keydown(function(e) {
                if (e.keyCode == 13) {
                    $('#searchForm').submit();
                }
            });

            
        },

        // 검색폼 초기화
        schFromReset: function(){

            $('#schKey').val("name");
            $('#schKeyword').val("");
            $('#schStatus').val("");
            $('#schService').val("");
            $('#schCate1').val("");
            $('#schCate2').val("");
            $('#schStartDate').val("");
            $('#schEndDate').val("");
            $('.selectric_wrap select').selectric();
        },

        // 제품군 SelectBox 최신화
        schCate1Load: function(code) {

            $('#schCate1').empty();
            $('#schCate1').append('<option value="">전체</option>');

            $.getJSON("./ajax_ok.php",{
                "type": "searchCodeList",
                "code": code
            }).done(function(json) {
                
                $.each(json.data, function(index, item){
                    var selected = "";

                    if($('#schServiceChk').val() == item.value){
                        selected = "selected";
                    }

                    $('#schCate1').append('<option value="'+ item.value +'" '+ selected +'>' + item.title + '</option>');
                });
                $('#schCate1').selectric('refresh');
            });
        },

        // 제품군 SelectBox 최신화
        todayState: function() {
            $.getJSON("./ajax_ok.php",{
                "type": "todayState"
            }).done(function(json) {
                $('#fastNum').text(json.data.fast);
                $('#customerNum').text(json.data.notice);
                $('#confirmNum').text(json.data.confirm);
                $('#completeNum').text(json.data.complete);
                $('#receiptNum').text(json.data.receipt);
                $('#deliveryNum1').text(json.data.delivery1);
                $('#deliveryNum2').text(json.data.delivery2);
                $('#reqNum').text(json.data.request);
            });
        }
    }).init();
});
