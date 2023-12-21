<?php
declare(strict_types=1);

namespace App\Responses\Bizcall;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.bizcall.Rec')]
class Rec
{
    #[OA\Property(description: '가상번호')]
    public readonly string $vn;
    #[OA\Property(description: '착신번호')]
    public readonly ?string $rn;
    #[OA\Property(description: '컬러링 아이디')]
    public readonly int $cr_id;
    #[OA\Property(description: '알림멘트 아이디')]
    public readonly int $if_id;
    #[OA\Property(description: '콜백 SMS 사용여부')]
    public readonly ?string $cb_sms;
    #[OA\Property(description: '콜백 SMS 문구')]
    public readonly ?string $cb_sms_txt;
    #[OA\Property(description: '메모')]
    public readonly ?string $memo;
    #[OA\Property(description: '메모')]
    public readonly ?string $memo2;

    public function __construct(array $rec)
    {
        $this->vn = data_get($rec, 'vn');
        $this->rn = data_get($rec, 'rn');
        $this->cr_id = (int)data_get($rec, 'cr_id');
        $this->if_id = (int)data_get($rec, 'if_id');
        $this->cb_sms = data_get($rec, 'cb_sms');
        $this->cb_sms_txt = data_get($rec, 'cb_sms_txt');
        $this->memo = data_get($rec, 'memo');
        $this->memo2 = data_get($rec, 'memo2');
    }
}
