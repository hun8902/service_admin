$(document).ready(function() {
    ({
        init: function() {

            // 이벤트 리스너
            this.eventListener();
        },

        eventListener: function() {
            var that = this;


            // 핸드폰 번호
            $(document).on("keyup", "#admin_id", function() {
                $(this).val(that.autoHypenPhone($(this).val()));
            });

        },

        // 전화번호에 하이픈을 넣어주는 함수
        autoHypenPhone: function(str){
            str = str.replace(/[^0-9]/g, '');
            // console.log(str.length);
            var tmp = '';
            if( str.length < 4){
                return str;
            }else if(str.length <= 7){
                tmp += str.substr(0, 3);
                tmp += '-';
                tmp += str.substr(3, 4);
                return tmp;
            }else{
                tmp += str.substr(0, 4);
                tmp += '-';
                tmp += str.substr(4, 4);
                return tmp;
            }
            return str;
        }


    }).init();
});
