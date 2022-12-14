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

            $("#btnStsts").on("change", function(){
                stats_chage = $("#btnStsts option:selected").val();

                var result = confirm('변경할 내용을 확인해주세요');

                if(result) {
                    if(stats_chage == "") {
                        alert("항목을 선택해 주세요.");
                    }
                    else {
                        that.statsWrite();
                    }
                } else {

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

            $.getJSON("./ajax_ok.php",{
                "type": "commentWrite",
                "no": $("#no").val(),
                "gno": $("#gno").val(),
                "content": $("#comment").val(),
                "status": $("input[name='status']:checked").val()
                
            }).done(function(json) {

                console.log(json);

                if(json.reload == "Y") {
                    opener.document.location.href='./orderList.php';
                    location.reload();         
                }
                else{
                    that.commentList();
                }                    
            });

        },

        //코멘트를 등록하는 함수
        statsWrite: function() {
            var that = this;

            $.getJSON("./ajax_ok.php",{
                "type": "statsWrite",
                "no": $("#no").val(),
                "gno": $("#gno").val(),
                "status": stats_chage

            }).done(function(json) {

                console.log(json);
                alert("상태가 변경되었습니다.");

                if(json.reload == "Y") {
                    opener.document.location.href='./orderList.php';
                    location.reload();
                }
                else{
                    that.commentList();
                }
            });

        },

        // 코멘트 리스트를 그리는 함수
        commentList: function() {
            console.log("***********************");
            var that = this;
            var agentId = $('#agent_id').val();
            var yesterDay = $('#yesterDay').val();
            console.log("코멘트x");

            $.getJSON("./ajax_ok.php",{
                "type": "commentList",
                "no": $("#no").val(),
                "gno": $("#gno").val()
            }).done(function(json) {
                var html_tag = "";
                console.log(json);
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
    
                        // html_tag += `
                        // <li>
                        //     <div class="comment_box `+ me +`">
                        //         <div class="comment_box_info">
                        //             <h4>`+ item.name +`</h4><span>`+ item.date +`</span>` + newIcon +`
                        //         </div>
                        //         <p>`+ item.content +`</p>
                        //     </div>
                        // </li>
                        // `;

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
                else {
                    $(".comment_list ul").append('<li><div class="comment_box none"><p>상담코멘트가 없습니다.</p></div></li>');
                }
            }).fail(function(json) {
                console.log( json );
                console.log( "error" );
              })
              .always(function() {
                console.log( "complete" );
              });
        }
    }).init();
});
