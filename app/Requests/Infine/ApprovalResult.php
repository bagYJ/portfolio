<?php
declare(strict_types=1);

namespace App\Requests\Infine;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.infine.ApprovalResult', description: '')]
class ApprovalResult
{
    #[OA\Property(description: '주문번호')]
    public readonly string $noOrder;
    #[OA\Property(description: '인파인 주문번호')]
    public readonly string $infineOrder;
    #[OA\Property(description: '가맹점ID')]
    public readonly string $dsUni;
    #[OA\Property(description: '주유결과 (0: 정상주유 1: 1분 timeout 주유취소, 2: 0원0리터)')]
    public readonly string $result;
    #[OA\Property(description: '유종 (204100: 휘발유, 204200: 경유, 204300:  LPG, 204400: 고급휘발유)')]
    public readonly string $cdGasKind;
    #[OA\Property(description: '노즐번호')]
    public readonly string $dsUnitId;
    #[OA\Property(description: '주문 금액')]
    public readonly int $atPrice;
    #[OA\Property(description: '실제 주유 리터')]
    public readonly float $atLiterGas;
    #[OA\Property(description: '단가금액 (기준(L))')]
    public readonly float $atLiterPrice;
    #[OA\Property(description: '유종할인 단가')]
    public readonly float $atDiscountLiter;
    #[OA\Property(description: '현장할인금액')]
    public readonly ?int $atDiscount;
    #[OA\Property(description: '승인금액')]
    public readonly float $atPricePg;
    #[OA\Property(description: '승인번호')]
    public readonly string $noApproval;
    #[OA\Property(description: '승인시각')]
    public readonly string $dtApproval;
    #[OA\Property(description: '가승인 취소 (1: 가승인 취소 성공, 2: 가승인 취소 오류)')]
    public readonly string $tempCancelStatus;
    #[OA\Property(description: '가승인 취소 번호')]
    public readonly string $noApprovalTemp;
    #[OA\Property(description: '가승인 취소 시각')]
    public readonly string $dtApprovalTemp;
    #[OA\Property(description: '보너스적립 금액')]
    public readonly ?float $atSaving;
    #[OA\Property(description: '보너스적립 금액')]
    public readonly ?float $atTotalSaving;

    /**
     * @param Request $request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->noOrder = data_get($valid, 'no_order');
        $this->infineOrder = data_get($valid, 'infine_order');
        $this->dsUni = data_get($valid, 'ds_uni');
        $this->result = data_get($valid, 'result');
        $this->cdGasKind = data_get($valid, 'cd_gas_kind');
        $this->dsUnitId = data_get($valid, 'ds_unit_id');
        $this->atPrice = data_get($valid, 'at_price');
        $this->atLiterGas = data_get($valid, 'at_liter_gas');
        $this->atLiterPrice = data_get($valid, 'at_liter_price');
        $this->atDiscountLiter = data_get($valid, 'at_discount_liter');
        $this->atDiscount = data_get($valid, 'at_discount');
        $this->atPricePg = data_get($valid, 'at_price_pg');
        $this->noApproval = data_get($valid, 'no_approval');
        $this->dtApproval = data_get($valid, 'dt_approval');
        $this->tempCancelStatus = data_get($valid, 'temp_cancel_status');
        $this->noApprovalTemp = data_get($valid, 'no_approval_temp');
        $this->dtApprovalTemp = data_get($valid, 'dt_approval_temp');
        $this->atSaving = data_get($valid, 'at_saving');
        $this->atTotalSaving = data_get($valid, 'at_total_saving');
    }
}
