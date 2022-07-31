<?php

namespace App\Utils;

class Encrypt
{
    /**
     * AES 암호화
     * @param string $data
     * @param string $secretKey
     * @param string $iv
     *
     * @return string|null
     */
    public static function encrypt(string $data, string $secretKey, string $iv): ?string
    {
        if (!$secretKey || strlen($iv) < 16) {
            return null;
        }
        // decrypt에서 오류가 발생해 PKCS5로 패딩을 맞춰주고 보내야한다면.
        return base64_encode(phpseclib_mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secretKey, self::pkcs5Pad($data, 16), MCRYPT_MODE_CBC, $iv));
//        return openssl_encrypt(self::pkcs5Pad($data, 16), 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * AES 복호화
     * @param string $data
     * @param string $secretKey
     * @param string $iv
     *
     * @return string|null
     */
    public static function decrypt(string $data, string $secretKey, string $iv): ?string
    {
        if (!$secretKey || strlen($iv) < 16) {
            return null;
        }
        return self::pkcs5Unpad(phpseclib_mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secretKey, base64_decode($data), MCRYPT_MODE_CBC, $iv));
//        return self::pkcs5Unpad(openssl_decrypt($data, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $iv));
//        return openssl_decrypt($data, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * Pkcs5pad 데이터 채우기 방법
     * @param string $text
     * @param int $blockSize
     *
     * @return string
     */
    private static function pkcs5Pad(string $text, int $blockSize)
    {
        $pad = $blockSize - (strlen($text) % $blockSize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 여분의 패딩 데이터 삭제
     * @param string $text
     *
     * @return string|bool
     */
    public static function pkcs5Unpad(string $text): string|bool
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
}
