# 서비스 전산 관리자

## 개발 환경
- apahce 2.4 이상
- php 7.3 이상
- MariaDB 10.0.x

## 설치
- config/db_con.php (db정보 입력)
- service_admin.sql 테이블 가져오기

## 초기 비밀번호

### 최고관리자
- 아이디 : O1
- 비밀번호 : 123456789

### 이카플러그
- 아이디 : E1
- 비밀번호 : 1234

## 로그인
- AJAX 이용하여 로그인 시스템 구현

## 접수관리
- PHP PDO, AJAX 이용한 데이터 INSERT, UPDATE
- GET 파라미터를 이용한 검색
- 코드 관리에서 데이터 가져오기
- 코드 관리에서 카타고리 수정시 이전버전 불러오기
- 출장비, 거리비 DB에 저장된 값 가져오기
- 본사, 센터 가격 다르게 가져오기
- 본사, 센터 노출 부분 다르게 변경
- repeater.js 를 이용하여 자제 입력 여러개 저정 가능 구현
- datatables를 이용하여 댓글 작성 기능 구현
- 파일 업로드 기능
- 접수 내용 복사 기능
- 접수상태가 있는 상태에서 최고 관리자만 삭제 하도록 구현
- 권한에 따른 엑셀 파일 다운로드 기능 구현

## 코드 관리
- nestable.js 를 이용하여 트리구조 UI 표시 및 코드생성 기능 구현
- DB저장시 lastInsertId 를 이용하여 db에 저장되게끔 구현
- 출장비 DB 저장을 통해 접수관리 메뉴에서 사용가능하도록 구현

## 자제관리
- PHP PDO, AJAX 이용한 데이터 INSERT, UPDATE
- 접수관리 -> 자제입력에서 repeater.js 를 이욯하여 여러가지 자제를 입력 할수 있도록 구현

## 업체관리
- PHP PDO, AJAX 이용한 데이터 INSERT, UPDATE
- AJAX를 통한 접근상태 버튼으로 바로 로그인 권한 차단 구현
- 지점별 로그인 계정 관리 페이지 구현
- 계정 관리 페이지에서 로그인 가능 불가능 쉽게 선택할수 있도록 구현



