$(document).ready(function() {
    ({
        init: function() {

            $('.selectric_wrap select').selectric();
            this.eventListener();
            this.commentList();
            setInterval(this.commentList, 3000);
        },

        eventListener: function() {
            
            var that = this;

            // 등록버튼 클릭 이벤트
            $('#btnWrite').click(function() {
                
                if($("#comment").val() == "") {

                    alert("코멘트를 입력해 주세요.");
                }
                else {

                    that.commentWrite();
                }
            });

            // 키워드 검색 엔터 입력시 자동 서브밋
            $('#comment').keydown(function(e) {
                if (e.keyCode == 13) {
                    if($("#comment").val() == "") {

                        alert("코멘트를 입력해 주세요.");
                    }
                    else {
    
                        that.commentWrite();
                    }
                }
            });
        },

        //코멘트를 등록하는 함수
        commentWrite: function() {
            var that = this;
            console.log($("#status").val());
            
            $.getJSON("./ajax_ok.php",{
                "type": "commentWrite",
                "no": $("#no").val(),
                "gno": $("#gno").val(),
                "content": $("#comment").val(),
                "status": $("#status").val()

            }).done(function(json) {
                console.log(json);
                
                that.commentList();
            });

        },

        // 코멘트 리스트를 그리는 함수
        commentList: function() {
            var that = this;
            var agentId = $('#agent_id').val();
            var yesterDay = $('#yesterDay').val();

            $.getJSON("./ajax_ok.php",{
                "type": "commentList",
                "no": $("#no").val(),
                "gno": $("#gno").val()

            }).done(function(json) {
                var html_tag = "";

                $("#userName").text(json.data.userName);
                $("#totalNum").text(json.data.totalNum);
                $('.comment_list ul').empty();

                if(json.data.totalNum > 0) {
                    $.each(json.data.list, function(index, item) {
                        var me = "";
                        var newIcon = "";
                        //console.log(item.dateValue, yesterDay);
                        
                        if(agentId == item.id) { me = "me"; }
                        if(item.dateValue > yesterDay ) { newIcon = "<i>N</i>"; }
    
                        
                        html_tag += '<li>';
                        html_tag += '    <div class="comment_box '+ me +'">';
                        html_tag += '        <div class="comment_box_info">';
                        html_tag += '            <h4>'+ item.name +'</h4><span>'+ item.date +'</span>' + newIcon;
                        html_tag += '        </div>';
                        html_tag += '        <p>'+ item.content +'</p>';
                        html_tag += '    </div>';
                        html_tag += '</li>';
                    });

                    $(".comment_list ul").html(html_tag);
                }
                else{
                    $(".comment_list ul").append(`
                        <li>
                            <div class="comment_box none">
                                <p>상담코멘트가 없습니다.</p>
                            </div>
                        </li>
                    `);
                }
            });
        }
    }).init();
});
