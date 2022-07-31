<?php

declare(strict_types=1);

namespace App\Services\Pg;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Interface\PgInterface;
use App\Services\Pg\Kcp\C_payplus_cli;
use App\Utils\Code;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function app_path;
use function storage_path;

class KcpService extends PgService implements PgInterface
{
    private C_payplus_cli $kcp;

    public function __construct()
    {
        parent::__construct('kcp');
        $this->kcp = new C_payplus_cli();
        $this->kcp->mf_clear();
    }

    public function request(array $request): array
    {
        $noOrder = Auth::id() . (time() + 1000000000) . mt_rand(100, 999);
        $this->setPayment($noOrder, $this->pgInfo['tx_cd'], [
            'ordr_idxx' => $noOrder,
            'good_name' => Code::conf('billkey.nm_order'),
            'good_mny' => Code::conf('billkey.at_price_zero'),
            'buyr_name' => Auth::user()->nm_user,
            'buyr_tel1' => Auth::user()->ds_phone,
            'buyr_tel2' => Auth::user()->ds_phone,
            'buyr_mail' => Auth::user()->id_user,
        ], [
            'common' => [
                'amount' => Code::conf('billkey.at_price_zero'),
                'currency' => $this->pgInfo['currency'],
                'cust_ip' => getenv('REMOTE_ADDR'),
                'escw_mod' => EnumYN::N->name
            ],
            'card' => [
                'card_mny' => Code::conf('billkey.at_price_zero'),
                'card_tx_type' => '12100000',
                'card_no' => $request['no_cardnum'],
                'card_expiry' => $request['no_expyea'] . $request['no_expmon'],
                'card_taxno' => match (empty($request['no_biz'])) {
                    true => substr(Auth::user()->ds_birthday, 2, 6),
                    default => $request['no_biz']
                }
            ],
            'auth' => [
                'sign_txtype' => '0001',
                'group_id' => $this->pgInfo['id']
            ]
        ]);

        $cardResult = $this->hasBillkey(
            Auth::id(),
            $this->kcp->mf_get_res_data('batch_key'),
            EnumYN::N,
            $this->pgInfo['code']
        );
        if ($cardResult === true) {
            throw new OwinException(Code::message('P1023'));
        }
        Log::channel('response')->info('kcp request response: ', parameterReplace($this->kcp->m_res_data));

        return [
            'result_msg' => $this->kcp->m_res_msg, //
            'result_code' => $this->kcp->m_res_cd, //
            'ds_billkey' => $this->kcp->mf_get_res_data('batch_key'), //
            'cd_pg' => $this->pgInfo['code'], //
            'cd_card_corp' => $this->kcp->mf_get_res_data("card_cd") . ($this->kcp->mf_get_res_data(
                    "card_cd"
                ) == 'CCBC' ? $this->kcp->mf_get_res_data("card_bank_cd") : ''), //
            'no_card_user' => substr($request['no_cardnum'], 12, 4), //
            'res_param' => '', //
            'ds_res_order_no' => $noOrder ?? '', //
            'yn_credit' => EnumYN::N->name, //
        ];
    }

    public function payment(array $request): array
    {
        $this->setPayment($request['no_order'], $this->pgInfo['tran_cd'], [
            'ordr_idxx' => $request['no_order'],
            'good_name' => iconv('UTF-8', 'EUC-KR', $request['nm_order']),
            'good_mny' => $request['at_price_pg'],
            'buyr_name' => iconv('UTF-8', 'EUC-KR', $request['nm_user']),
            'buyr_tel1' => $request['ds_phone'],
            'buyr_tel2' => $request['ds_phone'],
            'buyr_mail' => $request['id_user']
        ], [
            'common' => [
                'amount' => $request['at_price_pg'],
                'currency' => $this->pgInfo['currency'],
                'cust_ip' => getenv('REMOTE_ADDR'),
                'escw_mod' => EnumYN::N->name
            ],
            'card' => [
                'card_mny' => $request['at_price_pg'],
                'card_tx_type' => '11511000',
                'quota' => '00',
                'bt_group_id' => $this->pgInfo['id'],
                'bt_batch_key' => $request['ds_billkey']
            ]
        ]);
        Log::channel('response')->info('kcp payment response: ', parameterReplace($this->kcp->m_res_data));
        return [
            'res_cd' => $this->kcp->m_res_cd,
            'res_msg' => $this->kcp->m_res_msg,
            'ds_res_order_no' => $this->kcp->m_res_data['tno'] ?? null,
            'at_price_pg' => $this->kcp->m_res_data['card_mny'] ?? null,
            'ds_req_param' => $request,
            'ds_res_param' => $this->kcp->m_res_data,
        ];
    }

    public function refund(array $orderList, string $reason): array
    {
        $this->setPayment('', $this->pgInfo['refund_cd'], [], [], [
            'tno' => $orderList['ds_res_order_no'],
            'mod_type' => 'STSC',
            'mod_ip' => getenv('REMOTE_ADDR'),
            'mod_desc' => '',
        ]);
        Log::channel('response')->info('kcp refund response: ', parameterReplace($this->kcp->m_res_data));

        return [
            'res_cd' => $this->kcp->m_res_cd,
            'res_msg' => $this->kcp->m_res_msg,
        ];
    }

    private function setPayment(string $noOrder, string $tx_cd, array $ordr, array $payx, ?array $modx = []): void
    {
        foreach ($ordr as $key => $value) {
            $this->kcp->mf_set_ordr_data($key, $value);
        }
        foreach ($payx as $key => $pays) {
            $data = '';
            foreach ($pays as $payKey => $payValue) {
                $data .= $this->kcp->mf_set_data_us($payKey, $payValue);
            }

            $this->kcp->mf_add_payx_data($key, $data);
        }
        foreach ($modx as $key => $value) {
            $this->kcp->mf_set_modx_data($key, $value);
        }

        $this->kcp->mf_do_tx(
            trace_no: '',
            home_dir: app_path() . $this->pgInfo['home_dir'],
            site_cd: $this->pgInfo['site_cd'],
            site_key: $this->pgInfo['site_key'],
            tx_cd: $tx_cd,
            pub_key_str: '',
            pa_url: $this->pgInfo['gw_url'],
            pa_port: $this->pgInfo['gw_port'],
            user_agent: 'payplus_cli_slib',
            ordr_idxx: $noOrder,
            cust_ip: getenv('REMOTE_ADDR'),
            log_level: $this->pgInfo['log_level'],
            opt: 0,
            mode: 0,
            g_conf_log_path: storage_path() . $this->pgInfo['log_path']
        );
    }
}
