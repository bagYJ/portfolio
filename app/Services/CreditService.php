<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\OwinException;
use App\Models\MemberCard;
use App\Models\MemberWallet;
use App\Utils\Code;
use Illuminate\Support\Facades\DB;

class CreditService
{
    /**
     * 주문 가능한 결제 카드 리스트 조회
     * @param int $noUser
     * @param array|null $listCardPg
     * @param string|null $cdMemberLevel
     * @return array
     */
    public static function getOrderCardList(int $noUser, ?array $listCardPg, ?string $cdMemberLevel = ''): array
    {
        $pgQuery = "";
        if ($listCardPg) {
            $pgQuery .= " AND ";
            foreach ($listCardPg as $index => $pg) {
                if ($index) {
                    $pgQuery .= ", ";
                }
                $pgQuery .= "'{$pg}'";
            }
        }
        $memberQuery = "";
        if ($cdMemberLevel === '104500') {
            $memberQuery = " AND cd_card_corp IN ('503007') ";
        }

        return DB::select(
            "SELECT
                DISTINCT z.no_card
                , z.no_card_user
                , z.cd_card_corp
                , z.cd_payment_method
                , z.dt_reg
            FROM
            (
                (
                    SELECT
                        cd_card_corp , no_card , no_card_user , '504100' AS cd_payment_method ,dt_reg
                    FROM
                        member_card
                    WHERE
                        no_user = {$noUser}
                        AND yn_delete = 'N'
                        {$pgQuery}
                        {$memberQuery}
                )
                UNION
                (
                    SELECT
                        cd_card_corp , no_card , no_card_user ,'504200' AS cd_payment_method ,dt_reg
                    FROM
                        member_wallet
                    WHERE
                        no_user = {$noUser}
                        AND yn_delete = 'N'
                        AND cd_card_regist = '605300'
                        {$memberQuery}
                )
            )AS z WHERE 1=1
            ORDER BY z.cd_payment_method DESC  ,  z.dt_reg  DESC"
        );
    }

    /**
     *  [FnB] 주문요청시 사용될 카드 상세정보 반환
     *  member_wallet 나이스토큰  | member_card 빌키  = 구분처리
     *  NICE + KEY 내림차순 빌키 1건 조회
     * @param int $noUser
     * @param int $noCard
     * @param array|null $listCdPg
     * @param string $cdPaymentMethod
     * @return MemberCard|MemberWallet|null
     * @throws OwinException
     */
    public static function getFirstPgCardInfo(
        int $noUser,
        int $noCard,
        ?array $listCdPg,
        string $cdPaymentMethod
    ): MemberCard|MemberWallet|null {
        if ($cdPaymentMethod === '504100') {
            $memberCard = (new MemberCard())->select([
                'member_card.*',
                DB::raw('ds_billkey AS ds_paykey'),
            ])->where([
                'no_user' => $noUser,
                'no_card' => $noCard,
                'yn_delete' => 'N',
            ]);
            if ($listCdPg) {
                $memberCard = $memberCard->whereIn('cd_pg', $listCdPg);
            }
            return $memberCard->orderBy('cd_pg', 'DESC')->first();
        } else {
            if ($cdPaymentMethod === '504200') {
                return MemberWallet::select([
                    'member_wallet.*',
                    DB::raw('tr_id AS ds_paykey'),
                ])->where([
                    'no_user' => $noUser,
                    'no_card' => $noCard,
                    'yn_delete' => 'N',
                    'cd_card_regist' => '605300'
                ])->first();
            }
        }
        throw new OwinException(Code::message('P2060'));
    }
}
