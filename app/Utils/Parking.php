<?php

declare(strict_types=1);

namespace App\Utils;

use App\Exceptions\OwinException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Parking
{
    /**
     * 주차장 고유 번호로 주차장 조회
     * @param string $noSite
     * @return array|null
     * @throws GuzzleException
     * @throws OwinException
     */
    public static function getParkingSite(string $noSite): ?array
    {
        try {
            $client = new Client();
            $headerKey = Code::conf('parking.header_key');
            $headerValue = Code::conf('parking.header_value');

            $response = $client->get(Code::conf('parking.api_uri') . "/parkings/{$noSite}", [
                'headers' => [
                    $headerKey => $headerValue
                ]
            ]);

            $data = json_decode((string)$response->getBody(), true);
            if ($data) {
                if ($response->getStatusCode() === 200) {
                    return $data;
                } elseif ($data['message']) {
                    throw new OwinException($data['message']);
                }
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('[P9000] getParkingSite', [$e->getMessage()]);
            throw new OwinException(Code::message('P9000'));
        }
    }

    /**
     * 티켓 구매정보 전달
     * @param int $ticketUid 할인권 고유번호
     * @param string $carPlate 차량번호(공백없이)
     * @param int $noUser 유저식별자
     * @return mixed|null
     * @throws OwinException
     * @throws GuzzleException
     */
    public static function setTicket(int $ticketUid, string $carPlate, int $noUser)
    {
        try {
            $client = new Client();
            $headerKey = Code::conf('parking.header_key');
            $headerValue = Code::conf('parking.header_value');

            $response = $client->post(Code::conf('parking.api_uri') . '/bookings', [
                'headers' => [
                    $headerKey => $headerValue
                ],
                'json' => [
                    'ticketUid' => $ticketUid,
                    'carPlate' => $carPlate,
                    'userCode' => (string)$noUser
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            if ($data) {
                if ($response->getStatusCode() === 200) {
                    return $data;
                } elseif ($data['message']) {
                    throw new OwinException($data['message']);
                }
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('[P9001] setTicket', [$e->getMessage()]);
            throw new OwinException(Code::message('P9001'));
        }
    }

    /**
     * 주차 할인권 구매 정보 조회
     * @param int $bookingUid
     * @return array|null
     * @throws GuzzleException
     * @throws OwinException
     */
    public static function getTicket(int $bookingUid): ?array
    {
        try {
            $client = new Client();
            $headerKey = Code::conf('parking.header_key');
            $headerValue = Code::conf('parking.header_value');

            $response = $client->get(Code::conf('parking.api_uri') . "/bookings/{$bookingUid}", [
                'headers' => [
                    $headerKey => $headerValue
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            if ($data) {
                if ($response->getStatusCode() === 200) {
                    return $data;
                } elseif ($data['message']) {
                    throw new OwinException($data['message']);
                }
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('[P9002] getTicket', [$e->getMessage()]);
            throw new OwinException(Code::message('P9002'));
        }
    }

    public static function getTicketsByNoUser($noUser, $page)
    {
        try {
            $client = new Client();
            $headerKey = Code::conf('parking.header_key');
            $headerValue = Code::conf('parking.header_value');

            $response = $client->get(Code::conf('parking.api_uri') . "/bookings", [
                'headers' => [
                    $headerKey => $headerValue
                ],
                'query' => [
                    'page' => $page,
                    'userCode' => $noUser
                ]
            ]);
            $data = json_decode((string)$response->getBody(), true);
            if ($data) {
                if ($response->getStatusCode() === 200) {
                    return $data;
                } elseif ($data['message']) {
                    throw new OwinException($data['message']);
                }
            }
        } catch (Exception $e) {
            Log::channel('error')->error('[P9002] getTicketsByNoUser', [$e->getMessage()]);
            throw new OwinException(Code::message('P9002'));
        }
    }

    public static function getTicketByIds($bookingUids)
    {
        try {
            $client = new Client();
            $headerKey = Code::conf('parking.header_key');
            $headerValue = Code::conf('parking.header_value');

            $response = $client->post(Code::conf('parking.api_uri') . '/bookings/search', [
                'headers' => [
                    $headerKey => $headerValue
                ],
                'json' => [
                    'uids' => $bookingUids,
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            if ($data) {
                if ($response->getStatusCode() === 200) {
                    return $data;
                } elseif ($data['message']) {
                    throw new OwinException($data['message']);
                }
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('[P9002] getTicketByIds', [$e->getMessage()]);
            throw new OwinException(Code::message('P9002'));
        }
    }

    public static function cancelTicket($bookingUid)
    {
        try {
            $client = new Client();
            $headerKey = Code::conf('parking.header_key');
            $headerValue = Code::conf('parking.header_value');

            $response = $client->put(Code::conf('parking.api_uri') . "/bookings/{$bookingUid}/cancel", [
                'headers' => [
                    $headerKey => $headerValue
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            if ($data) {
                if ($response->getStatusCode() === 200) {
                    return $data;
                } elseif ($data['message']) {
                    throw new OwinException($data['message']);
                }
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('[P9003] getTicketByIds', [$e->getMessage()]);
            throw new OwinException(Code::message('P9003'));
        }
    }
}
