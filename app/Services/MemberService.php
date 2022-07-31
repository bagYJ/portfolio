<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnumYN;
use App\Models\BbsQna;
use App\Models\Beacon;
use App\Models\CarList;
use App\Models\CarListHk;
use App\Models\GsSaleCard;
use App\Models\Member;
use App\Models\MemberApt;
use App\Models\MemberCard;
use App\Models\MemberCarinfo;
use App\Models\MemberCarinfoLog;
use App\Models\MemberDeal;
use App\Models\MemberDetail;
use App\Models\MemberEvent;
use App\Models\MemberGroup;
use App\Models\MemberOwinCouponRequest;
use App\Models\OauthAccessTokens;
use App\Models\PersonalAccessTokens;
use App\Models\TollMember;
use App\Models\User;
use App\Utils\Code;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\NewAccessToken;

class MemberService extends Service
{
    public static function get($noUser)
    {
        return Member::where('no_user', $noUser)->with([
            'memberDetail'
        ])->first();
    }

    public static function getMember(array $parameter): Collection
    {
        return User::with('memberDetail')->where($parameter)->get();
    }

    public static function getMemberOrWhere(array $parameter): Collection
    {
        return User::orWhere(function ($query) use ($parameter) {
            foreach ($parameter as $key => $value) {
                $query->orWhere($key, $value);
            }
        })->get();
    }

    public static function getMemberDetail(array $parameter): Collection
    {
        return MemberDetail::with('memberCarInfo')->where($parameter)->get();
    }

    public function memberDetailUpdate(MemberDetail $memberDetail, array $parameter): void
    {
        $memberDetail->updateOrFail($parameter);
    }

    public static function createAccessToken(int $noUser): NewAccessToken
    {
        return User::find($noUser)->createToken('nav_access_token', $noUser, ['*']);
    }

    public function profileEdit(Request $request): void
    {
        if (empty($request->post('nm_change_nick')) === false) {
            Auth::user()->updateOrFail([
                'nm_nick' => $request->post('nm_change_nick')
            ]);
        }

        if ($request->hasFile('file_profile')) {
            $path = Storage::putFileAs(
                Code::conf('member.profile_path') . substr(Auth::id(), -2),
                $request->file('file_profile'),
                Auth::id() . '.' . $request->file('file_profile')->extension()
            );

            Auth::user()->memberDetail->updateOrFail([
                'ds_profile_path' => $path
            ]);
        }
    }

    public function owinCouponRequest(array $param): void
    {
        (new MemberOwinCouponRequest($param))->saveOrFail();
    }

    public function beacon(array $parameter): Collection
    {
        return Beacon::where($parameter)->get();
    }

    public static function memberEvent(array $noUsers, array $noSeqs, string $noSeq): Collection
    {
        return MemberEvent::whereIn('no_user', $noUsers)
            ->whereNotIn('no_seq', $noSeqs)
            ->where('no_seq', '!=', $noSeq)->get();
    }

    public function memberGroupFirstOrCreate(array $parameter, array $where): void
    {
        MemberGroup::firstOrCreate($where, $parameter);
    }

    public static function memberDeal(array $parameter): Collection
    {
        return MemberDeal::where($parameter)->get();
    }

    public function memberDealFirstOrCreate(array $parameter, ?array $where): MemberDeal
    {
        return MemberDeal::firstOrCreate($parameter, $where);
    }

    public function carDetail(string $memLevel, string $accountStatus, int $seq): CarList|CarListHk
    {
        $carTable = match ($memLevel == '104400' && $accountStatus == EnumYN::Y->name) {
            true => new CarListHk(),
            default => new CarList()
        };

        return $carTable->where('seq', $seq)->first();
    }

    public function memberCarinfo(array $parameter): Collection
    {
        return MemberCarinfo::where($parameter)->get();
    }

    public static function createMemberCarinfo(array $parameter): void
    {
        (new MemberCarinfo($parameter))->saveOrFail();
    }

    public function createMemberCarinfoLog(array $parameter): void
    {
        (new MemberCarinfoLog($parameter))->saveOrFail();
    }

    public static function createMember(array $parameter, int $no_user): void
    {
        (new User(array_merge($parameter['member'], ['no_user' => $no_user])))->saveOrFail();
        (new MemberDetail(array_merge($parameter['detail'], ['no_user' => $no_user])))->saveOrFail();
    }

    public static function upsertMemberCarInfo(array $parameter, ?array $where): void
    {
        MemberCarinfo::updateOrCreate($where, $parameter);
    }

    public static function updateMember(array $parameter, array $where): void
    {
        User::where($where)->update($parameter);
    }

    public static function updateMemberDetail(array $parameter, array $where): void
    {
        MemberDetail::where($where)->update($parameter);
    }

    public function withdrawalMember(array $parameter, array $noUser): void
    {
        self::updateMember($parameter['member'], $noUser);
        self::updateMemberDetail($parameter['detail'], $noUser);

        PersonalAccessTokens::where('tokenable_id', $noUser)->delete();
        OauthAccessTokens::where('user_id', $noUser)->delete();
    }

    public function tollMember(array $parameter): Collection
    {
        return TollMember::where($parameter)->get();
    }

    public static function updateMemberCarinfo(MemberCarinfo $carinfo, array $parameter): void
    {
        $carinfo->update($parameter);
    }

    public static function updateAutoParkingInfo(array $where, array $parameter): void
    {
        MemberCarinfo::where($where)->update($parameter);
    }

    public static function deleteMemberCarInfo(MemberCarinfo $carinfo): void
    {
        $carinfo->update(['yn_delete' => 'Y']);
    }

    public function getMyApt(array $parameter): Collection
    {
        return MemberApt::with('aptList')->where($parameter)->get();
    }

    public static function getCarInfo(int $seq): Collection
    {
        return CarList::where('seq', $seq)->get();
    }

    public function getMemberDealInfo($noUser)
    {
        return MemberDeal::with(['promotionDeal'])->where('no_user', $noUser)->first();
    }

    public function updateGsSaleCard(array $parameter, array $where): void
    {
        GsSaleCard::where($where)->update($parameter);
    }

    public function getOrderList(int $noUser, int $size, int $offset): LengthAwarePaginator
    {
        return (new OrderService())->getOrderListByMember($noUser, $size, $offset);
    }

    public static function updateMemberCardInfo(?MemberCard $card, array $parameter): void
    {
        $card?->update($parameter);
    }

    public static function getQnaList(array $select, array $where, int $size, int $offset): LengthAwarePaginator
    {
        return BbsQna::select($select)->where($where)->orderByDesc('no')->paginate(perPage: $size, page: $offset);
    }

    public static function createMemberQnaInfo(array $parameter): void
    {
        (new BbsQna($parameter))->saveOrFail();
    }

    public static function updateEventPushYn(string $parameter): void
    {
        User::where(['no_user' => Auth::id()])->update(['yn_push_msg_event' => $parameter]);
    }

    public static function getMemberWithParam(array $parameter, ?array $whereNotNull = []): Collection
    {
        return MemberDetail::where($parameter)
            ->whereNotNull($whereNotNull)->get();
    }
}
