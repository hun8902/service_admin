$(document).ready(function() {
    ({
        init: function() {
            
            // 금일 현황
            this.todayState();
            setInterval(this.todayState, 3000);

            // 이벤트 리스너
            this.eventListener();
        },

        eventListener: function() {
            var that = this;
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
