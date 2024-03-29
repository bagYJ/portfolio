<?php

namespace App\Pg\Nicepay;

use const ERR_CONN;
use const ERR_MISSING_PARAMETER;
use const ERR_NO_RESPONSE;
use const ERR_OPENLOG;
use const ERR_SSLCONN;
use const ERR_WRONG_ACTIONTYPE;
use const ERR_WRONG_PARAMETER;

extract($_POST);
extract($_GET);

/*____________________________________________________________
Copyright (C) 2017 NICE IT&T
*
* 해당 라이브러리는 수정하시는경우 승인및 취소에 문제가 발생할 수 있습니다.
* 임의로 수정된 코드에 대한 책임은 전적으로 수정자에게 있음을 알려드립니다.
*
*	@ description		: SSL 전문 통신을 담당한다. WEB-API 연동 버전
*	@ name				: NicepayCryptoLite.php
*	@ auther			: NICEPAY I&T (tech@nicepay.co.kr)
*	@ date				:
*	@ modify
*
*	2017.07.28			최초 작성
*____________________________________________________________
*/
require_once('NicepayLiteLog.php');
require_once('NicepayLiteCommon.php');

class NicepayCryptoLite
{
    // configuration Parameter
    public $m_NicepayHome;            // 로그 경로

    // requestPage Parameter
    public $m_EdiDate;                // 처리 일시
    public $m_MerchantKey;            // 상점에 부여된 고유 키
    public $m_Price;                // 결제 금액
    public $m_HashedString;        // 주요 데이터 hash값
    public $m_VBankExpDate;        // 가상계좌 입금 마감일
    public $m_MerchantServerIp;    // 상점 서버 아이피
    public $m_UserIp;                // 구매자 아이피

    // resultPage Parameter
    public $m_GoodsName;            // 상품명
    public $m_Amt;                    // 상품 가격
    public $m_Moid;                // 상점 주문번호
    public $m_BuyerName;            // 구매자 이름
    public $m_BuyerEmail;            // 구매자 이메일
    public $m_BuyerTel;            // 구매자 전화번호
    public $m_MallUserID;            // 구매자 상점 아이디
    public $m_MallReserved;        // 상점 고유필드
    public $m_GoodsCl;                // 상품 유형
    public $m_GoodsCnt;            // 상품 갯수
    public $m_MID;                    // 상점 아이디
    public $m_MallIP;                // 상점 서버 아이피 **
    public $m_TrKey;                // 암호화 데이터
    public $m_EncryptedData;        // 실제 암호화 데이터
    public $m_PayMethod;            // 결제 수단
    public $m_TransType;
    public $m_ActionType;
    public $m_LicenseKey;
    public $m_EncodeKey;

    public $m_ReceiptAmt;            //현금영수증 발급 금액
    public $m_ReceiptSupplyAmt;    //현금영수증 공급액
    public $m_ReceiptVAT;            //현금영수증 부가세액
    public $m_ReceiptServiceAmt;    //현금영수증 서비스액
    public $m_ReceiptType;            //현금영수증 구분
    public $m_ReceiptTypeNo;        //

    // 부가세, 봉사료 등 관련
    public $m_ServiceAmt;
    public $m_SupplyAmt;
    public $m_GoodsVat;
    public $m_TaxFreeAmt;

    // ARS
    public $m_ArsAlertShow;
    public $m_ArsReqType;

    public $m_CardInterest;
    public $m_ResultCode;            // 결과 코드
    public $m_ResultMsg;            // 결과 메시지
    public $m_ErrorCD;                // 에러 코드
    public $m_ErrorMsg;            // 에러메시지
    public $m_AuthDate;            // 승인 시각
    public $m_AuthCode;            // 승인 번호
    public $m_TID;                    // 거래 아이디
    public $m_CardCode;            // 카드 코드
    public $m_CardName;            // 승인 카드사 이름
    public $m_CardNo;                // 카드 번호
    public $m_CardQuota;            // 할부개월
    public $m_BankCode;            // 은행 코드
    public $m_BankName;            // 승인 은행 이름
    public $m_Carrier;                // 이통사 코드
    public $m_DestAddr;            //
    public $m_VbankBankCode;        // 가상계좌 은행 코드
    public $m_VbankBankName;        // 가상계좌 은행 이름
    public $m_VbankNum;            // 가상계좌 번호

    public $m_charSet;                // 캐릭터셋

    // 취소 관련
    public $m_CancelAmt;            // 취소 금액
    public $m_CancelMsg;            // 취소 메시지
    public $m_CancelPwd;           // 취소 패스워드
    public $m_PartialCancelCode;    // 부분취소 코드

    public $m_ExpDate;                // 입금 예정일자
    public $m_ReqName;                // 입금자
    public $m_ReqTel;                // 입금자 연락처

    // 공통
    public $m_uri;                    // 처리 uri
    public $m_ssl;                    // 보안접속 여부
    public $m_queryString = array(); // 쿼리 스트링
    public $m_ResultData = array();  // 결과 array

    // 빌링 관련
    public $m_BillKey;             // 빌키
    public $m_ExpYear;             // 카드 유효기간
    public $m_ExpMonth;            // 카드 유효기간
    public $m_IDNo;                // 주민번호
    public $m_CardPwd;             // 카드 비밀번호
    public $m_CancelFlg;            // 삭제요청 플래그

    public $m_CartType;            // 장바구니 인지 판별 여부

    public $m_DeliveryCoNm;        // 배송 업체
    public $m_InvoiceNum;            // 송장 번호
    public $m_BuyerAddr;            // 배송지주소
    public $m_RegisterName;        // 등록자이름
    public $m_BuyerAuthNum;        // 식별자 (주민번호)
    public $m_ReqType;                // 요청 타입
    public $m_ConfirmMail;            // 이메일 발송 여부

    public $m_log;                    // 로그 사용 유무
    public $m_debug;                // 로그 타입 설정

    public $m_ReqHost;                // 인증 서버 IP
    public $m_ReqPort;                // 인증 서버 Port
    public $m_requestPgIp;            // 승인서버IP
    public $m_requestPgPort;        // 승인서버Port


    // 총 4가지의 일을 해야함.
    // 1. 각 주요 필드의 hash 값생성
    // 2. 가상계좌 입금일 설정
    // 3. 사용자 IP 설정
    // 4. 상점 서버 아이피 설정
    public function requestProcess()
    {
        // hash 처리
        $this->m_EdiDate = date("YmdHis");
        $str_temp = $this->m_EdiDate . $this->m_MID . $this->m_Price . $this->m_MerchantKey;
        //echo($str_temp);
        $this->m_HashedString = base64_encode(md5($str_temp));

        // 가상계좌 입금일 설정
        $this->m_VBankExpDate = date("Ymd", strtotime("+3 day", time()));

        // 사용자 IP 설정
        $this->m_UserIp = $_SERVER['REMOTE_ADDR'];

        // 상점 서버아이피 설정
        $this->m_MerchantServerIp = $_SERVER['SERVER_ADDR'];
    }

    // https connection 을 해서 승인 요청을 함.
    public function startAction()
    {
        if (trim($this->m_ActionType) == "") {
            $this->MakeErrorMsg(ERR_WRONG_ACTIONTYPE, "actionType 설정이 잘못되었습니다.");
            return;
        }

        // MID를 설정한다.
        if ($this->m_MID == "" || $this->m_MID == null) {
            if ($this->m_TID == "" || strlen($this->m_TID) != 30) {
                $this->MakeErrorMsg(ERR_MISSING_PARAMETER, "필수 파라미터[MID]가 누락되었습니다.");
                return;
            } else {
                $this->m_MID = substr($this->m_TID, 0, 10);
            }
        }

        /*
         * 가맹점키 변수가 엉망이라 가맹점키 필드를 동일하게 설정해준다.
         * EncodeKey로 사용할 수 없는 이유는 결제창에서 해당 필드에 값을 설정하여 내려주므로
         * 가맹점이 요청할 EncodeKey로 재설정해주는 로직이 필요함.
         * 일단 LicenseKey로만 설정
         */
        $this->SetMerchantKey();

        $NICELog = new NICELog($this->m_log, $this->m_debug, $this->m_ActionType);

        if (!$NICELog->StartLog($this->m_NicepayHome, $this->m_MID)) {
            $this->MakeErrorMsg(ERR_OPENLOG, "로그파일을 열수가 없습니다.");
            return;
        }

        // 취소인 경우,
        if (trim($this->m_ActionType) == "CLO") {
            // validation
            if (trim($this->m_TID) == "") {
                $this->MakeErrorMsg(ERR_WRONG_PARAMETER, "요청페이지 파라메터가 잘못되었습니다. [TID]");
                return;
            } else {
                if (trim($this->m_CancelAmt) == "") {
                    $this->MakeErrorMsg(ERR_WRONG_PARAMETER, "요청페이지 파라메터가 잘못되었습니다. [CancelAmt]");
                    return;
                } else {
                    if (trim($this->m_CancelMsg) == "") {
                        $this->MakeErrorMsg(ERR_WRONG_PARAMETER, "요청페이지 파라메터가 잘못되었습니다. [CancelMsg]");
                        return;
                    }
                }
            }

            $this->m_uri = "/api/cancelProcessAPI.jsp";
            unset($this->m_queryString);

            $this->m_queryString = $_POST;
            $this->m_queryString["MID"] = $this->m_MID;
            $this->m_queryString["TID"] = $this->m_TID;
            $this->m_queryString["CancelAmt"] = $this->m_CancelAmt;
            $this->m_queryString["CancelMsg"] = $this->m_CancelMsg;
            $this->m_queryString["CancelPwd"] = $this->m_CancelPwd;
            $this->m_queryString["PartialCancelCode"] = $this->m_PartialCancelCode;
            $this->m_queryString["CartType"] = $this->m_CartType;

            if ($this->m_charSet == "UTF8") {
                $this->m_queryString["CancelMsg"] = iconv("UTF-8", "CP949", $this->m_queryString["CancelMsg"]);
            }
        } else {
            // 승인
            if (trim($_POST["MID"]) == "") {
                $this->MakeErrorMsg(ERR_WRONG_PARAMETER, "요청페이지 파라메터가 잘못되었습니다. [MID]");
                return;
            } else {
                if (trim($_POST["Amt"]) == "") {
                    $this->MakeErrorMsg(ERR_WRONG_PARAMETER, "요청페이지 파라메터가 잘못되었습니다. [Amt]");
                    return;
                }
            }

            $this->m_uri = "/api/payProcessAPI.jsp";
            unset($this->m_queryString);

            $this->m_queryString = $_POST;
            $this->m_queryString["EncodeKey"] = $this->m_LicenseKey;
            // java lite 모듈처럼 TID를 생성하도록 변경
            $this->m_TID = genTIDNew($this->m_MID, $this->m_PayMethod);
            $this->m_queryString["TID"] = $this->m_TID;

            if ($this->m_charSet == "UTF8") {
                $this->m_queryString["BuyerName"] = iconv("UTF-8", "CP949", $this->m_queryString["BuyerName"]);
                $this->m_queryString["GoodsName"] = iconv("UTF-8", "CP949", $this->m_queryString["GoodsName"]);
                $this->m_queryString["BuyerAddr"] = iconv("UTF-8", "CP949", $this->m_queryString["BuyerAddr"]);
                $this->m_queryString["AuthResultMsg"] = iconv("UTF-8", "CP949", $this->m_queryString["AuthResultMsg"]);
            }
        }

        // TID 값 확인
        if (isset($this->m_queryString["TID"]) && $this->m_queryString["TID"] != "") {
            $NICELog->WriteLog("TID: " . $this->m_queryString["TID"]);
        } else {
            $NICELog->WriteLog("TID IS EMPTY");
        }

        // 연결 도메인 설정
        if ($this->m_ReqHost != "" && $this->m_ReqHost != null) {
            $pos = strpos($this->m_ReqHost, ':');
            if ($pos === true) {
                // 연결서버 뒤에 Port가 붙는 경우 처리
                list($host, $port) = explode(":", $this->m_ReqHost);
                $this->m_ReqHost = $host;
                $this->m_ReqPort = $port;
            }

            $NICELog->WriteLog("ReqHost: " . $this->m_ReqHost . ", ReqPort: " . $this->m_ReqPort);
        }

        // 연결 승인서버 설정
        if ($this->m_requestPgIp != null && $this->m_requestPgIp != "") {
            $this->m_queryString["requestPgIp"] = $this->m_requestPgIp;
            $this->m_queryString["requestPgPort"] = $this->m_requestPgPort;

            $NICELog->WriteLog("특정 IP,Port로 요청합니다.");
            $NICELog->WriteLog("requestPgIp >> " . $this->m_requestPgIp);
            $NICELog->WriteLog("requestPgIp >> " . $this->m_requestPgPort);
        }

        $this->MakeParam($NICELog);

        // 이건 의미가 없어보임
        $this->m_queryString["LibInfo"] = getLibInfo();

        $httpclient = new HttpClient($this->m_ssl, $this->m_ReqHost, $this->m_ReqPort);
        //connect
        if (!$httpclient->HttpConnect($NICELog)) {
            $NICELog->WriteLog('Server Connect Error!!' . $httpclient->getErrorMsg());
            $resultMsg = $httpclient->getErrorMsg() . "서버연결을 할 수가 없습니다.";
            if ($this->m_ssl == "true") {
                $resultMsg .= "<br>귀하의 서버는 SSL통신을 지원하지 않습니다. 결제처리파일에서 m_ssl=false로 셋팅하고 시도하세오.";
                $this->MakeErrorMsg(ERR_SSLCONN, $resultMsg);
            } else {
                $this->MakeErrorMsg(ERR_CONN, $resultMsg);
            }

            $NICELog->CloseNiceLog("");

            return;
        }

        //request
        if (!$httpclient->HttpRequest($this->m_uri, $this->m_queryString, $NICELog)) {
            // 요청 오류시 처리
            $NICELog->WriteLog('POST Error!!' . $httpclient->getErrorMsg());

            if ($this->doNetCancel($httpclient, $NICELog)) {
                $this->ParseMsg($httpclient->getBody(), $NICELog);
                $NICELog->WriteLog(
                    'Net Cancel ResultCode=[' . $this->m_ResultData["ResultCode"] . '], ResultMsg=[' . $this->m_ResultData["ResultMsg"] . ']'
                );
                $this->MakeErrorMsg(ERR_NO_RESPONSE, "서버 응답 오류"); // 이 코드가 없는 경우 결과 메세지가 [2001]취소성공 으로 나가게 됨
            }

            $NICELog->CloseNiceLog($this->m_resultMsg);
            return;
        }

        if ($httpclient->getStatus() == "200") {
            $this->ParseMsg($httpclient->getBody(), $NICELog);
            if (isset($this->m_ResultData['TID'])) {
                $NICELog->WriteLog("TID -> " . "[" . $this->m_ResultData['TID'] . "]");
            }
            $NICELog->WriteLog($this->m_ResultData['ResultCode'] . "[" . $this->m_ResultData['ResultMsg'] . "]");
            $NICELog->CloseNiceLog("");
        } else {
            $NICELog->WriteLog(
                'SERVER CONNECT FAIL:' . $httpclient->getStatus() . $httpclient->getErrorMsg(
                ) . $httpclient->getHeaders()
            );
            $resultMsg = $httpclient->getStatus() . "서버에러가 발생했습니다.";

            //NET CANCEL Start---------------------------------
            if ($httpclient->getStatus() != 200) {
                if ($this->m_PayMethod == "CARD_CAPTURE") {
                    // 수동매입인 경우에는 이전에 구매한 기록이 취소되지 않도록 함.
                    $this->MakeErrorMsg(ERR_NO_RESPONSE, $resultMsg);
                    $NICELog->CloseNiceLog("");
                    return;
                }

                if ($this->doNetCancel($httpclient, $NICELog)) {
                    // 망취소 성공인 경우 body 파싱 후 서버응답오류 코드로 내려준다.
                    $this->ParseMsg($httpclient->getBody(), $NICELog);
                    $NICELog->WriteLog(
                        'Net Cancel ResultCode=[' . $this->m_ResultData["ResultCode"] . '], ResultMsg=[' . $this->m_ResultData["ResultMsg"] . ']'
                    );
                    $this->MakeErrorMsg(ERR_NO_RESPONSE, $resultMsg); // 이 코드가 없는 경우 결과 메세지가 [2001]취소성공 으로 나가게 됨
                }
            }
            //NET CANCEL End---------------------------------
            $NICELog->CloseNiceLog("");
            return;
        }
    }

    public function MakeParam($NICELog)
    {
        // 4개 필드 backup
        $mid = $this->m_queryString["MID"];
        $moid = $this->m_queryString["Moid"];
        $ediDate = $this->m_queryString["EdiDate"];
        $encodeType = getIfEmptyDefault($this->m_charSet, "CP949");

        if ($encodeType == "UTF8") {
            $encodeType = "UTF-8";
        }

        $post_array = array();
        foreach ($_POST as $key => $value) {
            if ($encodeType == "CP949") {
                if (has_hangul($value)) {
                    $post_array[$key] = iconv($encodeType, "UTF-8", $value);
                } else {
                    $post_array[$key] = $value;
                }
            } else {
                $post_array[$key] = $value;
            }
        }

        // jsonObj로 변환 (UTF-8)만 지원
        $jsonStr = json_encode($post_array);

        // 암호화
        if (version_compare(phpversion(), '7.1.0', '<')) {
            $data = aesEncrypt($jsonStr, $this->m_LicenseKey);
        } else {
            $data = aesEncryptSSL($jsonStr, $this->m_LicenseKey);
        }

        // WEB-API에 연동에 필요한 각 필드 셋팅
        unset($m_queryString);
        $this->m_queryString["MID"] = $mid;
        $this->m_queryString["Moid"] = $moid;
        $this->m_queryString["EdiDate"] = $ediDate;
        $this->m_queryString["EncodeType"] = $encodeType;
        $this->m_queryString["Data"] = $data;

        // 로그
        $NICELog->WriteLog(
            "MakeParam.src MID=" . $this->m_queryString["MID"] . ", Moid=" . $this->m_queryString["Moid"] . ", EdiDate=" . $this->m_queryString["EdiDate"] . ", EncodeType=" . $this->m_queryString["EncodeType"] . ", Data=" . $this->m_queryString["Data"]
        );
    }

    // 에러 메시지 처리
    public function MakeErrorMsg($err_code, $err_msg)
    {
        $this->m_ResultCode = $err_code;
        $this->m_ResultMsg = "[" . $err_code . "][" . $err_msg . "]";
        $this->m_ResultData["ResultCode"] = $err_code;
        $this->m_ResultData["ResultMsg"] = $err_msg;
    }

    // 결과메시지 파싱
    public function ParseMsg($result_string, $NICELog)
    {
        $encodeType = getIfEmptyDefault($this->m_charSet, "CP949");

        if ($encodeType == "UTF8") {
            $encodeType = "UTF-8";
        }

        // json_decode는 UTF-8만 인식한다. 임시로 전체를 UTF-8로 만든다.
        $result_string_utf = iconv("CP949", "UTF-8", $result_string);

        $jsonObj = json_decode($result_string_utf); // 전체 응답 내역을 JSON Object로 변환

        if ($jsonObj->ResultCode == "3001"  // 신용카드 승인 성공
            || $jsonObj->ResultCode == "4000" // 계좌이체 승인 성공
            || $jsonObj->ResultCode == "A000" // 휴대폰 승인 성공
            || $jsonObj->ResultCode == "4100" // 가상계좌 승인 성공
            || $jsonObj->ResultCode == "2001" // 취소성공
            || $jsonObj->ResultCode == "0000" // 그 외 성공 (공통)
        ) {
            if (version_compare(phpversion(), '7.1.0', '<')) {
                $jsonDataStr = aesDecrypt($jsonObj->Data, $this->m_LicenseKey);
            } else {
                $jsonDataStr = aesDecryptSSL($jsonObj->Data, $this->m_LicenseKey);
            }

            $jsonDataStr = iconv("CP949", "UTF-8", $jsonDataStr);

            $jsonDataObj = json_decode($jsonDataStr); // 복호화한 Data 항목을 JSON Object 변환

            foreach ($jsonDataObj as $key => $value) {
                if ($encodeType == "CP949") {
                    if (has_hangul($value)) {
                        $this->m_ResultData[$key] = iconv("UTF-8", $encodeType, $value);
                    } else {
                        $this->m_ResultData[$key] = $value;
                    }
                } else {
                    $this->m_ResultData[$key] = $value;
                }
            }
        } else {
            //echo "<BR> ===== 실패 ====== <BR>";
        }

        $this->m_ResultData["ResultCode"] = $jsonObj->ResultCode;
        $this->m_ResultData["ResultMsg"] = iconv("UTF-8", $encodeType, $jsonObj->ResultMsg);
    }

    public function SetMerchantKey()
    {
        if ($this->m_MerchantKey != "") {
            $this->m_LicenseKey = $this->m_MerchantKey;
            $this->m_EncodeKey = $this->m_EncodeKey;
        } else {
            if ($this->m_LicenseKey != "") {
                $this->m_MerchantKey = $this->m_LicenseKey;
                $this->m_EncodeKey = $this->m_LicenseKey;
            } else {
                if ($this->m_EncodeKey != "") {
                    $this->m_MerchantKey = $this->m_EncodeKey;
                    $this->m_LicenseKey = $this->m_EncodeKey;
                }
            }
        }
    }

    public function doNetCancel($httpclient, $NICELog)
    {
        if (empty($this->m_TID)) {
            $this->MakeErrorMsg(ERR_WRONG_PARAMETER, "필수값[TID]이 없어 망취소가 불가능 합니다. 가맹점에 문의 바랍니다.");
            return false;
        }

        //NET CANCEL Start---------------------------------
        $NICELog->WriteLog("Net Cancel Start by TID=[" . $this->m_TID . "]");

        // unset 하기 전에 승인 시 사용했던 금액 backup
        $amt = $this->m_queryString["Amt"];

        //Set Field
        $this->m_uri = "/api/cancelProcessAPI.jsp";
        unset($this->m_queryString);
        $this->m_queryString["MID"] = substr($this->m_TID, 0, 10);
        $this->m_queryString["TID"] = $this->m_TID;
        // 망상취소금액이 없는 경우, 승인 금액으로 설정
        $this->m_queryString["CancelAmt"] = empty($this->m_NetCancelAmt) ? $amt : $this->m_NetCancelAmt;
        $this->m_queryString["CancelMsg"] = "NICE_NET_CANCEL";
        $this->m_queryString["CancelPwd"] = $this->m_NetCancelPW;
        $this->m_queryString["NetCancelCode"] = "1";
        $this->m_queryString["LibInfo"] = getLibInfo();

        if (!$httpclient->HttpConnect($NICELog)) {
            $NICELog->WriteLog('Net Cancel Server Connect Error!!' . $httpclient->getErrorMsg());
            $resultMsg = $httpclient->getErrorMsg() . "서버연결을 할 수가 없습니다.";
            $this->MakeErrorMsg(ERR_CONN, $resultMsg);

            return false;
        }
        if (!$httpclient->HttpRequest($this->m_uri, $this->m_queryString, $NICELog)) {
            $NICELog->WriteLog("Net Cancel FAIL");
            if ($this->m_ActionType == "PYO") {
                $this->MakeErrorMsg(ERR_NO_RESPONSE, "승인여부 확인요망");
            } else {
                if ($this->m_ActionType == "CLO") {
                    $this->MakeErrorMsg(ERR_NO_RESPONSE, "취소여부 확인요망");
                }
            }

            return false;
        } else {
            $NICELog->WriteLog("Net Cancel Request-Response SUCESS");
        }

        return true;
    }
}

?>
