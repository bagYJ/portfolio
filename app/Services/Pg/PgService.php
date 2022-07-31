<?php

declare(strict_types=1);

namespace App\Services\Pg;

use App\Enums\EnumYN;
use App\Models\MemberCard;
use App\Models\MemberCardRequest;
use App\Services\Service;
use App\Utils\Code;

use function env;

class PgService extends Service
{
    protected array $pgInfo;
    protected string $pg;
    public $service;

    public function __construct(string $pgName)
    {
        parent::__construct();
        $this->pgInfo = Code::conf('pg.' . env('DEVELOPMENT') . '.' . $pgName);
        $this->pg = $pgName;
    }

    public function setPg(): self
    {
        $this->service = match ($this->pg) {
            'kcp' => new KcpService(),
            'nicepay' => new NicepayService(),
            'fdk' => new FdkService(),
            'uplus' => new UplusService()
        };

        return $this;
    }

    protected function hasBillkey(int $noUser, string $dsBillkey, EnumYN $enumYN, int $pgCode): bool
    {
        return MemberCard::where('no_user', $noUser)
                ->where('ds_billkey', '=', $dsBillkey)
                ->where('yn_delete', '=', $enumYN->name)
                ->where('cd_pg', '=', $pgCode)
                ->count() > 0;
    }

    protected function setMemberCardRequest(
        int $noUser,
        array $dsFdkHash,
        array $dsOwinHash,
        string $cdCardRegist,
        array $dsResParam
    ): void {
        (new MemberCardRequest([
            'no_user' => $noUser,
            'ds_fdk_hash' => json_encode($dsFdkHash),
            'ds_owin_hash' => json_encode($dsOwinHash),
            'cd_card_regist' => $cdCardRegist,
            'ds_res_param' => json_encode($dsResParam)
        ]))->saveOrFail();
    }
}
