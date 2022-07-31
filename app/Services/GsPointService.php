<?php

namespace App\Services;

use App\Models\GsSaleCard;
use App\Models\User;
use App\Queues\Socket\ArkServer;

class GsPointService
{
    public static function getPointCardInfo(User $user, string $pointCardId) {
        $body = ArkServer::makeCardInfoPacketSale('card_info', $user, $pointCardId);
        $gsPointCard = (new ArkServer('ARK', 'oil', $body, ''))->init();
        if ($gsPointCard['result_code'] == '00000') {
            $parameter = [
                'ds_sale_code' => $gsPointCard['ds_sale_code'],
                'ds_cvc' => $gsPointCard['ds_cvc'],
                'dt_reg' => $gsPointCard['dt_reg'],
                'ds_publisher' => $gsPointCard['ds_publisher'],
                'ds_publisher_code' => $gsPointCard['ds_publisher_code'],
                'ds_status_name' => $gsPointCard['ds_status_name'],
                'ds_status_code' => $gsPointCard['ds_status_code'],
                'ds_type_name' => $gsPointCard['ds_type_name'],
                'ds_type_code' => $gsPointCard['ds_type_code'],
                'at_save_amt' => $gsPointCard['at_save_amt'],
                'at_can_save_amt' => $gsPointCard['at_can_save_amt'],
                'at_can_save_total' => $gsPointCard['at_can_save_total'],
                'ds_expire_date' => $gsPointCard['ds_expire_date'],
                'ds_expire_time' => $gsPointCard['ds_expire_time'],
                'yn_sale_status' => $gsPointCard['yn_sale_status'],
                'ds_sale_name' => $gsPointCard['ds_sale_name'],
                'yn_can_save' => $gsPointCard['at_can_save_amt'] ? 'Y' : 'N',
            ];
            (new MemberService())->updateGsSaleCard($parameter, [
                'id_pointcard' => $pointCardId
            ]);
        }

        return GsSaleCard::where([
            'no_user' => $user->no_user,
            'id_pointcard' => $pointCardId,
        ])->first();
    }
}