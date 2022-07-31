<?php

namespace App\Services\Pg\Fdk;

class Fdk
{
    /**
     * 결제확인용 통신
     */
    public function paymentSendHttps($host, $path, $post, $hashValue = null, $EncodeType = null)
    {
        $sock = 0;
        $ssl = "ssl://";
        $port = "443";
        //  HTTP 버전으로 테스트가 필요한 경우 사용
        //	$ssl = "";
        //	$port = "80";

        $sendData = "";
        $recvData = "";

        $sendData = $this->setPaymentSendParameters($post, $hashValue, $EncodeType);

        /** CONNECT **/
        /** HTTPS : PHP 5 & OPENSSL REQUIRED **/
        if (!$sock = @fsockopen($ssl . $host, $port, $errno, $errstr, 5)) {
            $SocketCode = $errno;

            switch ($errno) {
                case -3:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"Socket Creation Failed\"}";
                case -4:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"DNS Lookup Failure\"}";
                case -5:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"Connection Refused or Timed Out\"}";
                default:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"Connection failed\"}";

                    if ($recvData == "") {
                        $recvData = "{\"ReplyCode\":\"" . $errno . "\",\"ReplyMessage\":" . $errstr . "\"}";
                    }
            }
        }

        if ($recvData == "") {
            //SEND
            $send = "POST " . $path . " HTTP/1.0\r\n";
            $send .= "Connection: close\r\n";
            $send .= "Host: " . $host . "\r\n";
            $send .= "Content-type: application/x-www-form-urlencoded\r\n";
            $send .= "Content-length: " . strlen($sendData) . "\r\n";
            $send .= "Accept: */*\r\n";
            $send .= "\r\n";
            $send .= $sendData . "\r\n";
            $send .= "\r\n";

            fwrite($sock, $send);

            //RECV
            stream_set_blocking($sock, false);

            $streamstart = true;
            $streamheader = true;
            $streamtimeout = false;
            $streamstarttm = time();
            $readtimeout = 15;
            $headerdata = '';

            while (!feof($sock) && !$streamtimeout) {
                $readline = fgets($sock, 4096);

                $waitingtm = time() - $streamstarttm;

                if ($waitingtm >= $readtimeout) {
                    $streamtimeout = true;
                }
                if ($streamheader) {
                    if ($readline == "") //for stream_set_blocking
                    {
                        continue;
                    }

                    if (substr($readline, 0, 2) == "\r\n")  //end of header
                    {
                        $streamheader = false;
                        continue;
                    }

                    $headerdata .= $readline;

                    if ($streamstart) {
                        $streamstart = false;

                        if (!preg_match('/HTTP\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $readline, $httpdata)) {
                            $recvData = "{\"ReplyCode\":\"9998\",\"ReplyMessage\":\"Status code line invalid\"}";
                            fclose($sock);
                        }

                        continue;
                    }
                } else {
                    $recvData .= $readline;
                }
            }

            fclose($sock);

            if ($streamtimeout) {
                $recvData = "{\"ReplyCode\":\"9998\",\"ReplyMessage\":\"Socket Timeout\"}";
            }
        }
        return urldecode(trim($recvData));
    }

    /**
     * 결제확인용 파라미터 셋팅
     */
    public function setPaymentSendParameters($post, $hashData, $EncodeType)
    {
        $rtnValue = "";

        foreach ($post as $Key => $value) {
            $rtnValue .= $Key . "=" . urlencode(iconv("utf-8", "euc-kr", $value)) . "&";
        }

        //hashData 추가
        if ($hashData) {
            $rtnValue .= "FDHash=" . $hashData . "&";
        }

        //EncodeType 추가
        if ($EncodeType) {
            $rtnValue .= "EncodeType=" . $EncodeType;
        }
        return $rtnValue;
    }


    /**
     *  빌키방식 결제 파라미터 셋팅
     */
    public function sendHttps($host, $path, $freq, $hashValue = null, $EncodeType = null): string
    {
        $sock = 0;
        $ssl = "ssl://";
        $port = "443";

        // HTTP	버전으로 테스트가 필요한 경우 사용
        //	$ssl = "";
        //	$port =	"80";

        $sendData = "";
        $recvData = "";

        $sendData = json_encode($freq);

        /**    CONNECT    **/
        /**    HTTPS :    PHP    5 &    OPENSSL    REQUIRED **/
        if (!$sock = @fsockopen($ssl . $host, $port, $errno, $errstr, 5)) {
            $SocketCode = $errno;

            switch ($errno) {
                case -3:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"Socket Creation	Failed\"}";
                case -4:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"DNS	Lookup Failure\"}";
                case -5:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"Connection Refused or Timed	Out\"}";
                default:
                    $recvData = "{\"ReplyCode\":\"9999\",\"ReplyMessage\":\"Connection failed\"}";

                    if ($recvData == "") {
                        $recvData = "{\"ReplyCode\":\"" . $errno . "\",\"ReplyMessage\":" . $errstr . "\"}";
                    }
            }
        }

        $headerdata = '';
        if ($recvData == "") {
            //SEND
            $send = "POST " . $path . " HTTP/1.0\r\n";
            $send .= "Connection: close\r\n";
            $send .= "Host:	" . $host . "\r\n";
            $send .= "Content-type:	application/x-www-form-urlencoded\r\n";
            $send .= "Content-length: " . strlen($sendData) . "\r\n";
            $send .= "Accept: */*\r\n";
            $send .= "\r\n";
            $send .= $sendData . "\r\n";
            $send .= "\r\n";

            fwrite($sock, $send);

            //RECV
            stream_set_blocking($sock, false);

            $streamstart = true;
            $streamheader = true;
            $streamtimeout = false;
            $streamstarttm = time();
            $readtimeout = 15;

            while (!feof($sock) && !$streamtimeout) {
                $readline = fgets($sock, 4096);

                $waitingtm = time() - $streamstarttm;

                if ($waitingtm >= $readtimeout) {
                    $streamtimeout = true;
                }
                if ($streamheader) {
                    if ($readline == "") //for	stream_set_blocking
                    {
                        continue;
                    }

                    if (substr($readline, 0, 2) == "\r\n")  //end of	header
                    {
                        $streamheader = false;
                        continue;
                    }

                    $headerdata .= $readline;

                    if ($streamstart) {
                        $streamstart = false;

                        if (!preg_match('/HTTP\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $readline, $httpdata)) {
                            $recvData = "{\"ReplyCode\":\"9998\",\"ReplyMessage\":\"Status code	line invalid\"}";
                            fclose($sock);
                        }

                        continue;
                    }
                } else {
                    $recvData .= $readline;
                }
            }

            fclose($sock);

            if ($streamtimeout) {
                $recvData = "{\"ReplyCode\":\"9998\",\"ReplyMessage\":\"Socket Timeout\"}";
            }
        }

        return urldecode($recvData);
    }


    public function StringToJsonProc($data)
    {
        $jsonObj = json_decode($data, true);

        return $jsonObj;
    }
}
