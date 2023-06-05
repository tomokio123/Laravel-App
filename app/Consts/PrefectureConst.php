<?php
namespace App\Consts;

class PrefectureConst
{
    //増減に関しての定数リスト
    const LIST = array(
        'add' => 1,
        'reduce' => 2,
    );

    //表示順の定数とリスト
    const ORDER_RECOMMEND = '0';
    const ORDER_HIGHER = '1';
    const ORDER_LOWER = '2';
    const ORDER_LATER = '3';
    const ORDER_OLDER = '4';

    const SORT_ORDER = [
        'recommend' => self::ORDER_RECOMMEND,
        'higherPrice' => self::ORDER_HIGHER,
        'lowerPrice' => self::ORDER_LOWER,
        'later' => self::ORDER_LATER,
        'older' => self::ORDER_OLDER
    ];
}