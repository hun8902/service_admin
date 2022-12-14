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

            // 빠른상담 갯수
            $('#pageNum').val($('#pageNumChk').val()).selectric('refresh');

            // 이벤트 리스너
            this.eventListener();
        },

        eventListener: function() {
            var that = this;

            // 빠른상담 상태변경
            $('.speedStatus').selectric().on('change', function() {
                
                var no = $(this).data("no");
                var status = $(this).val();
                //console.log(no, status);

                that.speedStatusUpdate(no, status);
            });

            // 리스트 개수 변경
            $('#pageNum').selectric().on('change', function() {
                $('#pageNumChk').val($(this).val());
                $('#searchForm').submit();
            });

            // 기간 선택시 발생하는 이벤트
            $('#schCate2').selectric().on('change', function() {

                var dateValue = $(this).val().split('/');
                $('#schStartDate').val(dateValue[0]);
                $('#schEndDate').val(dateValue[1]);
            });

            // 엑셀다운 버튼
            $("#btn_excel").click(function() {
                var formData = $( "#searchForm" ).serialize(); 
                console.log(formData);
                location.href="./common/excel_speedList.php?" + formData;
            });

            // 초기화 버튼
            $('.btn_reset').click(function(){
                $('#schCate2').val("");
                $('#schStartDate').val("");
                $('#schEndDate').val("");
                $('.selectric_wrap select').selectric();
            });

            // 검색 버튼
            $("#btn_search").click(function() {
                $( "#searchForm" ).submit(); 
            });
            
        },
        /**
         * 상태를 업데이트 하는 함수
         */
        speedStatusUpdate: function(no, status) {
            $.getJSON("./ajax_ok.php", {
                "type": "speedStatus",
                "no": no,
                "status": status
            }).done(function(json) {
               console.log(json);
            }).success(function(json) {
                Swal.fire({
                    icon: 'success',
                    title: "변경 완료",
                    text: "상태가 변경되었습니다."
                }).then(function(){
                    window.location.reload();
                });
            }).fail(function( jqxhr, textStatus, error ) {
                Swal.fire({
                    icon: 'error',
                    title: "변경 실패",
                    text: "상태가 변경되었습니다."
                }).then(function(){
                    window.location.reload();
                });
                //location.href = './';
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
