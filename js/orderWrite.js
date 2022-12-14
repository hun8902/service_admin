$(document).ready(function() {
    ({
        init: function() {
            
            // selectric
            $('.selectric_wrap select').selectric();
    
            // email - select
            $('select#select_email').change(function(){
                var value = $(this).val();
                $('input#orderEmail2').val(value);
            });
            
            // 신청제품 세팅
            this.reantalSel();

            // 이벤트 리스너
            this.eventListener();
        },

        eventListener: function() {
            var that = this;
            
            // 다음 주소검색 호출
            $('.daum_post').click(function() {                
                that.daumPostcode();
            });

            // 다음 주소검색 닫기
            $('#btnCloseLayer').click(function() {             
                that.closeDaumPostcode();
            });

            // 신청상품 추가
            $('#btnRentalAdd').click(function() {          

                if (typeof $("input[name=rental]:checked").val() == "undefined") {
                    alert("렌탈사를 선택해 주세요.");
                }else{
                    that.rentalAddDarw();
                }
            });

            // 신청상품 삭제        
            $(document).on("click", '.btnRentalDel', function() {
                if ($(".prodBox").length > 1) {
                    console.log("삭제");
                    
                    $(this).closest(".prodBox").remove();
                }else{
                    alert("신청제품을 1개 이상 등록하셔야 합니다.");
                }
            });

            //렌탈사 선택
            $(document).on("click", '.rentalClass', function() {
                that.reantalSel();
            });

            $("form").submit(function(){
                if (typeof $("input[name=rental]:checked").val() == "undefined") {
                    
                    $(".rentalClass").focus();
                    alert("렌탈사를 선택해 주세요.");                    
                    return false;
                }
                
            }); // end submit()

            //제품군 선택
            $(document).on("change", '.prodClass', function() {

                var reltal = $("input[name=rental]:checked").val();
                var product = $(this).closest("ul").find(".prodClass").val();
                var selBox = $(this).closest("ul").find(".prodMonth");

                $.getJSON("./ajax_ok.php", {
                    "type": "searchCode",
                    "mode": "gigan",
                    "code3": reltal,
                    "code5": product
                }, function(json) {
                    // 렌탈기간 옵션 추가
                    selBox.empty();
                    selBox.append('<option value="">렌탈기간 선택</option>');

                    $.each(json.data, function(index, item){
                        selBox.append('<option value="'+ item.value +'">' + item.name + '</option>');
                    });
                    selBox.selectric('refresh');
                });
            });

            // 핸드폰 번호
            $(document).on("keyup", "#orderPhoneNum", function() {
                $(this).val(that.autoHypenPhone($(this).val()));
            });

            // 추가 연락처
            $(document).on("keyup", "#orderTelNum", function() {
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
        },
      
        /**
         * 신청제품을 그리는 함수
         */
        rentalAddDarw: function() {
            
            var that = this;

            rentalTag = '';
            rentalTag += '<div class="prodBox">';
            rentalTag += '    <ul>';
            rentalTag += '        <li style="width:40%;">';
            rentalTag += '            <div class="selectric_wrap">';
            rentalTag += '                <select id="prodClass" class="prodClass" name="product[]" title="제품군을 선택해 주세요." required>';
            rentalTag += '                    <option value="">제품군 선택</option>';
            rentalTag += '                </select>';
            rentalTag += '            </div>';
            rentalTag += '        </li>';
            rentalTag += '        <li style="width:18%;">';
            rentalTag += '            <div class="selectric_wrap">';
            rentalTag += '                <select id="prodMonth" class="prodMonth" name="gigan[]" title="렌탈기간을 선택해 주세요." required>';
            rentalTag += '                    <option value="">렌탈기간 선택</option>';
            rentalTag += '                </select>';
            rentalTag += '            </div>';
            rentalTag += '        </li>';
            rentalTag += '        <li>';
            rentalTag += '            <button type="button" class="btn btn_sm btn_basic btn_delete btnRentalDel"><i class="icon-minus"></i>삭제</button>';
            rentalTag += '        </li>';
            rentalTag += '    </ul>';
            rentalTag += '</div>';
            $("#rentalList").append(rentalTag);
            $('.selectric_wrap select').selectric();
            that.productSelOpt();
        },
        /**
         * 렌탈사 셀랙트 박스 
         */
        reantalSel: function() {
            var that = this;

            // 신청제품 삭제
            $(".prodBox").remove();
            that.rentalAddDarw();

            var reltal = $("input[name=rental]:checked").val();
            console.log(reltal);
            var selBox = $(".prodClass");
            var selGiganBox = $(".prodMonth");

            $.getJSON("./ajax_ok.php", {
                "type": "searchCode",
                "mode": "product",
                "code3": reltal,
            }, function(json) {
                // 제품군 옵션 추가
                selBox.empty();
                selBox.append('<option value="">제품군 선택</option>');

                selGiganBox.empty();
                selGiganBox.append('<option value="">렌탈기간 선택</option>');
                $.each(json.data, function(index, item){
                    selBox.append('<option value="'+ item.value +'">' + item.name + '</option>');
                });
                selBox.selectric('refresh');

            });
        },

        /**
         * 제품군 셀렉트 박스 옵션
         */
        productSelOpt: function(){

            var reltal = $("input[name=rental]:checked").val();
            var selBox = $(".prodClass:last");
            var selGiganBox = $(".prodMonth:last");

            $.getJSON("./ajax_ok.php", {
                "type": "searchCode",
                "mode": "product",
                "code3": reltal,
            }, function(json) {
                // 제품군 옵션 추가
                selBox.empty();
                selBox.append('<option value="">제품군 선택</option>');

                selGiganBox.empty();
                selGiganBox.append('<option value="">렌탈기간 선택</option>');
                
                $.each(json.data, function(index, item){
                    selBox.append('<option value="'+ item.value +'">' + item.name + '</option>');
                });
                selBox.selectric('refresh');
            });
        },

        /**
         * 다음 주소검색 API 호출
         */
        daumPostcode: function() {

            var that = this;
            
            // 우편번호 찾기 화면을 넣을 element
            var element_layer = document.getElementById('layerPost');

            new daum.Postcode({
                oncomplete: function(data) {
                    // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
        
                    // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                    // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                    var addr = ''; // 주소 변수
                    var extraAddr = ''; // 참고항목 변수
        
                    //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                    if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                        addr = data.roadAddress;
                    } else { // 사용자가 지번 주소를 선택했을 경우(J)
                        addr = data.jibunAddress;
                    }
        
                    // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                    if(data.userSelectedType === 'R'){
                        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                        if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                            extraAddr += data.bname;
                        }
                        // 건물명이 있고, 공동주택일 경우 추가한다.
                        if(data.buildingName !== '' && data.apartment === 'Y'){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                        if(extraAddr !== ''){
                            extraAddr = ' (' + extraAddr + ')';
                        }
                        // 조합된 참고항목을 해당 필드에 넣는다.
                        //document.getElementById("sample2_extraAddress").value = extraAddr;
                        document.getElementById("orderAddr1").value = addr + extraAddr;
                    
                    } else {
                        document.getElementById("orderAddr1").value = addr;
                    }
                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    document.getElementById('orderPostcode').value = data.zonecode;
                    
                    // 커서를 상세주소 필드로 이동한다.
                    document.getElementById("orderAddr2").focus();
        
                    // iframe을 넣은 element를 안보이게 한다.
                    // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                    element_layer.style.display = 'none';
                },
                width : '100%',
                height : '100%',
                maxSuggestItems : 5
            }).embed(element_layer);
        
            // iframe을 넣은 element를 보이게 한다.
            element_layer.style.display = 'block';
        
            // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
            that.initLayerPosition();
        },

        /**
         * 다음주소 검색 닫기
         */
        closeDaumPostcode: function() {
            
            // 우편번호 찾기 화면을 넣을 element
            var element_layer = document.getElementById('layerPost');

            // iframe을 넣은 element를 안보이게 한다.
            element_layer.style.display = 'none';
        },

        /**
         * 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
         * resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
         * 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
         */
        initLayerPosition: function(){
            // 우편번호 찾기 화면을 넣을 element
            var element_layer = document.getElementById('layerPost');

            var width = 400; //우편번호서비스가 들어갈 element의 width
            var height = 500; //우편번호서비스가 들어갈 element의 height
            var borderWidth = 0; //샘플에서 사용하는 border의 두께

            // 위에서 선언한 값들을 실제 element에 넣는다.
            element_layer.style.width = width + 'px';
            element_layer.style.height = height + 'px';
            element_layer.style.border = borderWidth + 'px solid';
            // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
            element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
            element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
        },
        getQueryStringObject: function() {
        var a = window.location.search.substr(1).split('&');
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i) {
            var p = a[i].split('=', 2);
            if (p.length == 1)
                b[p[0]] = "";
            else
                b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    }
    }).init();
});
