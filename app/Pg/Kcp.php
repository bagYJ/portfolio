<?php
declare(strict_types=1);

namespace App\Pg;

use App\Pg\Kcp\C_payplus_cli;
use App\Requests\Pg\Payment;
use App\Requests\Pg\Refund;
use App\Requests\Pg\Regist;
use Exception;

class Kcp
{
    private readonly C_payplus_cli $kcp;

    public function __construct()
    {
        $this->kcp = new C_payplus_cli();
        $this->kcp->mf_clear();
    }

    /**
     * @param Regist $request
     * @return \App\Responses\Pg\Regist
     * @throws Exception
     */
    public function regist(Regist $request): \App\Responses\Pg\Regist
    {
        $this->setPayment($request->noOrder, getenv('KCP_TX_CD'), [
            'ordr_idxx' => $request->noOrder,
            'good_name' => $request->nmOrder,
            'good_mny' => $request->price,
            'comm_green_deposit_mny' => $request->price,
            'buyr_name' => $request->nmBuyer,
            'buyr_tel1' => $request->phone,
            'buyr_tel2' => $request->phone,
            'buyr_mail' => $request->email,
        ], [
            'common' => [
                'amount' => $request->price,
                'currency' => getenv('KCP_CURRENCY'),
                'cust_ip' => getenv('REMOTE_ADDR'),
                'escw_mod' => 'N'
            ],
            'card' => [
                'card_mny' => $request->price,
                'card_tx_type' => '12100000',
                'card_no' => $request->cardNum,
                'card_expiry' => sprintf('%s%s', $request->expYear, $request->expMon),
                'card_taxno' => match (empty($request->noBiz)) {
                    true => substr($request->birthday, 2, 6),
                    default => $request->noBiz
                }
            ],
            'auth' => [
                'sign_txtype' => '0001',
                'group_id' => getenv('KCP_ID')
            ]
        ]);

        if ($this->kcp->m_res_cd != getenv('RETURN_TRUE')) {
            throw new Exception($this->kcp->m_res_msg);
        }

        return (new \App\Responses\Pg\Regist)->kcp($request->cardNum, $request->noOrder, $this->kcp->m_res_data);
    }

    /**
     * @param Payment $request
     * @return \App\Responses\Pg\Payment
     */
    public function payment(Payment $request): \App\Responses\Pg\Payment
    {
        setlocale(LC_CTYPE, 'ko_KR.euc-kr');
        $this->setPayment($request->noOrder, getenv('KCP_TRAN_CD'), [
            'ordr_idxx' => $request->noOrder,
            'good_name' => iconv('UTF-8', 'CP949', $request->nmOrder),
            'good_mny' => $request->price,
            'buyr_name' => iconv('UTF-8', 'CP949', $request->nmBuyer),
            'buyr_tel1' => $request->phone,
            'buyr_tel2' => $request->phone,
            'buyr_mail' => $request->email
        ], [
            'common' => [
                'amount' => $request->price,
                'currency' => getenv('KCP_CURRENCY'),
                'cust_ip' => getenv('REMOTE_ADDR'),
                'escw_mod' => 'N',
                'comm_green_deposit_mny' => $request->atCupDeposit,
            ],
            'card' => [
                'card_mny' => $request->price,
                'card_tx_type' => '11511000',
                'quota' => '00',
                'bt_group_id' => getenv('KCP_ID'),
                'bt_batch_key' => $request->billkey
            ]
        ], [], $request->nmPg);

        return (new \App\Responses\Pg\Payment)->kcp($request, $this->kcp->m_res_cd, $this->kcp->m_res_msg, $this->kcp->m_res_data);
    }

    public function refund(Refund $request): \App\Responses\Pg\Refund
    {
        $this->setPayment('', getenv('KCP_REFUND_CD'), [], [], [
            'tno' => $request->dsResOrderNo,
            'mod_type' => 'STSC',
            'mod_ip' => getenv('REMOTE_ADDR'),
            'mod_desc' => ''
        ], $request->nmPg);

        return (new \App\Responses\Pg\Refund)->kcp($this->kcp->m_res_cd, $this->kcp->m_res_msg);
    }

    /**
     * @param string $noOrder
     * @param string $tx_cd
     * @param array $ordr
     * @param array $payx
     * @param array|null $modx
     * @param string|null $prefix
     * @return void
     */
    private function setPayment(string $noOrder, string $tx_cd, array $ordr, array $payx, ?array $modx = [], ?string $prefix = 'KCP'): void
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
            home_dir: app_path() . getenv('KCP_HOME_DIR'),
            site_cd: getenv(sprintf('%s_SITE_CD', $prefix)),
            site_key: getenv(sprintf('%s_SITE_KEY', $prefix)),
            tx_cd: $tx_cd,
            pub_key_str: '',
            pa_url: getenv('KCP_URI'),
            pa_port: getenv('KCP_GW_PORT'),
            user_agent: 'payplus_cli_slib',
            ordr_idxx: $noOrder,
            cust_ip: getenv('REMOTE_ADDR'),
            log_level: getenv('KCP_LOG_LEVEL'),
            opt: 0,
            mode: 0,
            g_conf_log_path: sprintf('%s%s', storage_path(), getenv('KCP_LOG_PATH'))
        );
    }
}
