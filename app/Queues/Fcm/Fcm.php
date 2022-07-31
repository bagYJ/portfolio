<?php

declare(strict_types=1);

namespace App\Queues\Fcm;

use App\Services\AdminService;
use App\Services\MemberService;
use App\Utils\Code;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Fcm
{
    private string $type;
    private ?string $step;
    private string $uri;
    private string $appkey;
    private int $noShop;
    private array $data;
    private ?bool $notification;
    private ?int $noUser;
    private string $noOrder;
    private array $yml;
    private string $sound;
    private ?string $receiver;

    /**
     * @param string $type
     * @param int|null $noShop
     * @param string $noOrder
     * @param array $data
     * @param bool|null $notification
     * @param string|null $receiver
     * @param int|null $noUser
     * @param string|null $step
     */
    public function __construct(string $type, ?int $noShop, string $noOrder, array $data, ?bool $notification = false, ?string $receiver = null, ?int $noUser = null, ?string $step = null)
    {
        $this->type = $type;
        $this->step = $step;
        $this->receiver = $receiver;
        $config = $this->config($receiver);
        $this->uri = Code::fcm('uri');
        $this->appkey = Code::fcm($config['appkey']);
        $this->noShop = $noShop;
        $this->notification = $notification;
        $this->noUser = $noUser;
        $this->noOrder = $noOrder;
        $this->sound = $config['sound'];
        match ($receiver) {
            'user' => [
                $this->data = $this->dataSetting($data),
                $this->yml = $this->dataConverting($config, $data)
            ],
            default => $this->data = $data
        };
    }

    /**
     * @param $receiver
     * @return void
     */
    public function init(): void
    {
        $udids = match ($this->receiver) {
            'user' => MemberService::getMemberWithParam([
                'no_user' => $this->noUser
            ], ['ds_udid'])->pluck('ds_udid'),
            default => AdminService::getPartnerManager([
                'no_shop' => $this->noShop
            ], ['ds_udid'])->pluck('ds_udid')
        };

        $udids->whenNotEmpty(
            function ($collect) {
                $this->send($collect->all());
            }
        );
    }

    /**
     * @param $receiver
     * @return array|string[]
     */
    private function config($receiver): array
    {
        return match ($receiver) {
            'user' => [
                'appkey' => 'user.appkey',
                'title' => 'user.' . $this->type . "." . $this->step . '.title',
                'message' => 'user.' . $this->type . "." . $this->step . '.body',
                'sound' => 'default'
            ],
            default => [
                'appkey' => 'manager.appkey',
                'title' => 'manager.type.%s.title',
                'message' => 'manager.message',
                'sound' => sprintf('%s.wav', $this->type)
            ]
        };
    }

    /**
     * @param array $data
     * @return array
     */
    private function dataSetting(array $data): array
    {
        return [
            'biz_kind' => $this->type,
            'status' => match ($this->step) {
                'shop_accept' => '매장수락',
                'shop_complete' => '준비완료',
                'delivery_complete' => '전달완료',
                'cancel', 'cancel_etc' => '매장취소',
                'enter' => '입차완료',
                'complete' => '출차완료',
                default => ''
            },
            'is_ordering' => $data['ordering'],
            'no_order' => $this->noOrder,
            'no_shop' => $this->noShop,
        ];
    }

    /**
     * @param $config
     * @param $data
     * @return array
     */
    private function dataConverting($config, $data): array
    {
        $message = match ($this->step) {
            'shop_accept' => sprintf(Code::fcm($config['message']), $data['pickup_time']),
            'complete' => sprintf(Code::fcm($config['message']), $data['at_price_pg']),
            default => Code::fcm($config['message'])
        };

        return ['message' => $message, 'title' => sprintf(Code::fcm($config['title']), $data['nm_shop'])];
    }

    /**
     * @param array $udids
     * @return void
     */
    private function send(array $udids): void
    {
        $parameter = [
            'registration_ids' => $udids,
            'priority' => 'high',
            'content_available' => true,
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => $this->sound,
                    ]
                ]
            ],
            'data' => $this->data
        ];
        if ($this->notification === true) {
            $parameter += [
                'notification' => [
                    'title' => $this->yml['title'],
                    'body' => $this->yml['message'],
                    'sound' => $this->sound
                ],
                'android' => [
                    'notification' => [
                        'title' => $this->yml['title'],
                        'body' => $this->yml['message'],
                        'channel_id' => $this->type,
                        'sound' => $this->sound
                    ]
                ]
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => sprintf('key=%s', $this->appkey),
            'Content-Type' => 'application/json'
        ])->post($this->uri, $parameter);

        Log::channel('response')->info(__CLASS__, $response->collect()->all());
    }
}
