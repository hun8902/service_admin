-- --------------------------------------------------------
-- 호스트:                          k890210.cafe24.com
-- 서버 버전:                        10.1.13-MariaDB - MariaDB Server
-- 서버 OS:                        Linux
-- HeidiSQL 버전:                  12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 테이블 k890210.category 구조 내보내기
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `label_menu` varchar(20) NOT NULL COMMENT '코드명',
  `price1` varchar(50) DEFAULT NULL COMMENT '가격',
  `price2` varchar(50) DEFAULT NULL COMMENT '가격',
  `parent_id` int(11) NOT NULL COMMENT '하위카테고리 지정',
  `prev_date` datetime DEFAULT NULL COMMENT '버전관리를 위한 날짜저장',
  `update_date` datetime DEFAULT NULL COMMENT '업데이트 날짜',
  PRIMARY KEY (`id_menu`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=736 DEFAULT CHARSET=utf8mb4 COMMENT='코드관리';

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.comment 구조 내보내기
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `comment_code` varchar(255) NOT NULL,
  `id` varchar(255) NOT NULL COMMENT '아이디',
  `name` varchar(255) DEFAULT NULL COMMENT '이름',
  `content` varchar(255) DEFAULT NULL COMMENT '내용',
  `secret` varchar(255) DEFAULT NULL COMMENT '비밀글여부',
  `write_date` datetime NOT NULL COMMENT '등록일',
  `update_date` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`idx`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.distance 구조 내보내기
DROP TABLE IF EXISTS `distance`;
CREATE TABLE IF NOT EXISTS `distance` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.management 구조 내보내기
DROP TABLE IF EXISTS `management`;
CREATE TABLE IF NOT EXISTS `management` (
  `cp_idx` int(11) NOT NULL AUTO_INCREMENT,
  `cp_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '업체명',
  `cp_code` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cp_ceo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '대표자',
  `cp_bnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '사업자번호',
  `cp_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '연락처',
  `cp_fax` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '팩스',
  `cp_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '이메일',
  `cp_addr` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '주소',
  `cp_join` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '생성일',
  `cp_srttn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '업체구분',
  `cp_memo` text COLLATE utf8_unicode_ci COMMENT '메모',
  `cp_level` int(10) DEFAULT NULL COMMENT '1: 슈퍼어드민 2. 부분권한 3. 수정없는 레벨',
  `cp_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL COMMENT '업체구분',
  `cp_created_on` datetime NOT NULL COMMENT '생성일',
  `cp_display` enum('Enable','Disable') COLLATE utf8_unicode_ci DEFAULT 'Enable',
  PRIMARY KEY (`cp_idx`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.management_account 구조 내보내기
DROP TABLE IF EXISTS `management_account`;
CREATE TABLE IF NOT EXISTS `management_account` (
  `ac_idx` int(11) NOT NULL AUTO_INCREMENT,
  `ac_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '아이디',
  `ac_code` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '소속회사 코드',
  `ac_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '이름',
  `ac_passwd` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '비밀번호',
  `ac_phone` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '연락처',
  `ac_type` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL,
  `ac_created_on` datetime NOT NULL,
  PRIMARY KEY (`ac_idx`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.materialcontrol 구조 내보내기
DROP TABLE IF EXISTS `materialcontrol`;
CREATE TABLE IF NOT EXISTS `materialcontrol` (
  `idx` int(20) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(255) DEFAULT NULL COMMENT '자재명',
  `model_code` varchar(255) DEFAULT NULL COMMENT '모델코드',
  `model_etc` varchar(255) DEFAULT NULL,
  `model_cnt` int(20) DEFAULT NULL COMMENT '수량',
  PRIMARY KEY (`idx`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.materialcontrol_data 구조 내보내기
DROP TABLE IF EXISTS `materialcontrol_data`;
CREATE TABLE IF NOT EXISTS `materialcontrol_data` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `group_code` varchar(255) DEFAULT NULL,
  `group_data1` varchar(255) DEFAULT NULL,
  `group_data2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idx`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=906 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.post 구조 내보내기
DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `post_code` varchar(255) DEFAULT NULL,
  `select_name` varchar(50) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL COMMENT '고객명 ',
  `phone1` varchar(255) DEFAULT NULL COMMENT '연락처1',
  `phone2` varchar(255) DEFAULT NULL COMMENT '연락처2',
  `step1` varchar(255) DEFAULT NULL,
  `step2` varchar(255) DEFAULT NULL,
  `step3` varchar(255) DEFAULT NULL,
  `step4` varchar(255) DEFAULT NULL,
  `step5` varchar(255) DEFAULT NULL,
  `price_0` varchar(50) DEFAULT '0' COMMENT '출장비',
  `price_1` varchar(50) DEFAULT '0' COMMENT '수리비',
  `price_2` varchar(50) DEFAULT '0' COMMENT '거리비',
  `price_3` varchar(50) DEFAULT '0' COMMENT '긴급',
  `price_4` varchar(50) DEFAULT '0' COMMENT '자재',
  `price_5` varchar(50) DEFAULT '0' COMMENT '기타',
  `price_6` varchar(50) DEFAULT '0' COMMENT '납품가',
  `price1_0` varchar(50) DEFAULT '0',
  `price1_1` varchar(50) DEFAULT '0',
  `price1_2` varchar(50) DEFAULT '0',
  `price1_3` varchar(50) DEFAULT '0',
  `price1_4` varchar(50) DEFAULT '0',
  `price1_5` varchar(50) DEFAULT '0',
  `price1_6` varchar(50) DEFAULT '0',
  `distance_select` varchar(255) DEFAULT NULL COMMENT '거리',
  `price_hap` varchar(50) DEFAULT '0' COMMENT '총비용',
  `price_hap1` varchar(50) DEFAULT '0',
  `addr` varchar(255) DEFAULT NULL COMMENT '주소 ',
  `center_name` varchar(255) DEFAULT NULL COMMENT '센터명 ',
  `frist_name` varchar(255) DEFAULT NULL COMMENT '등록자',
  `last_name` varchar(255) DEFAULT NULL COMMENT '수정한 사람',
  `memo` text COMMENT '접수증상',
  `memo1` text COMMENT '기사 확인사항',
  `user_profile` text,
  `frist_user` varchar(255) DEFAULT NULL,
  `last_uesr` varchar(255) DEFAULT NULL,
  `write_date` datetime NOT NULL COMMENT '등록일',
  `update_date` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.post_mgt 구조 내보내기
DROP TABLE IF EXISTS `post_mgt`;
CREATE TABLE IF NOT EXISTS `post_mgt` (
  `idx_no` int(11) NOT NULL AUTO_INCREMENT,
  `post_code` varchar(255) DEFAULT NULL,
  `stats_fd1` varchar(255) DEFAULT NULL,
  `stats_fd2` varchar(255) DEFAULT NULL,
  `stats_fd3` varchar(255) DEFAULT NULL,
  `stats_fd4` varchar(255) DEFAULT NULL,
  `stats_fd5` varchar(255) DEFAULT NULL,
  `stats_fd6` varchar(255) DEFAULT NULL,
  `stats_fd7` varchar(255) DEFAULT NULL,
  `date_1` varchar(255) DEFAULT NULL,
  `date_2` varchar(255) DEFAULT NULL,
  `date_3` varchar(255) DEFAULT NULL,
  `date_4` varchar(255) DEFAULT NULL,
  `date_5` varchar(255) DEFAULT NULL,
  `date_6` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idx_no`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.post_mgt_select 구조 내보내기
DROP TABLE IF EXISTS `post_mgt_select`;
CREATE TABLE IF NOT EXISTS `post_mgt_select` (
  `idx` int(15) NOT NULL AUTO_INCREMENT,
  `select_pos` varchar(255) DEFAULT NULL,
  `select_code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.post_stats 구조 내보내기
DROP TABLE IF EXISTS `post_stats`;
CREATE TABLE IF NOT EXISTS `post_stats` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `post_code` varchar(255) NOT NULL,
  `register_hdq` varchar(255) NOT NULL COMMENT '고객명 ',
  `register_ctr` varchar(255) NOT NULL COMMENT '연락처1',
  `recovery_hdq` varchar(255) NOT NULL COMMENT '연락처2',
  `recovery_ctr` varchar(255) NOT NULL COMMENT '주소 ',
  `calculate_hdq` varchar(255) NOT NULL COMMENT '센터명 ',
  `calculate_ctr` varchar(255) NOT NULL COMMENT '등록자',
  PRIMARY KEY (`idx`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 k890210.travel_expenses 구조 내보내기
DROP TABLE IF EXISTS `travel_expenses`;
CREATE TABLE IF NOT EXISTS `travel_expenses` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `model_code` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `price` int(50) DEFAULT '0',
  `price_center` int(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='출장비';

-- 내보낼 데이터가 선택되어 있지 않습니다.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
