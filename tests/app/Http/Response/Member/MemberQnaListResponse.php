<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Member;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MemberQnaListResponse')]
class MemberQnaListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '전체 항목 개수')]
    public int $total_cnt;
    #[OA\Property(description: '페이지 당 항목 개수')]
    public int $per_page;
    #[OA\Property(description: '현재 페이지번호')]
    public int $current_page;
    #[OA\Property(description: '마지막 페이지 번호')]
    public int $last_page;
    #[OA\Property(description: '회원 문의 목록', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/QnaList'
    ))]
    public QnaList $list;
}

#[OA\Schema]
class QnaList
{
    #[OA\Property(description: '문의번호')]
    public int $no;
    #[OA\Property(description: '제목')]
    public string $ds_title;
    #[OA\Property(description: '내용')]
    public string $ds_content;
    #[OA\Property(description: '등록일시')]
    public string $dt_reg;
    #[OA\Property(description: '답변자 아이디')]
    public string $id_answer;
    #[OA\Property(description: '답변내용')]
    public string $ds_answer_content;
    #[OA\Property(description: '답변일시')]
    public string $dt_answer;
    #[OA\Property(description: '답변여부(Y:답변완료, N:미답변)')]
    public string $yn_answer;
}
