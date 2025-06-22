<?php

namespace Tourze\ProxyProtocol\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 代理协议版本枚举
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt
 */
enum Version: int
 implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    /**
     * 代理协议版本1，文本格式
     */
    case V1 = 1;

    /**
     * 代理协议版本2，二进制格式
     */
    case V2 = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::V1 => '版本1 (文本格式)',
            self::V2 => '版本2 (二进制格式)',
        };
    }
}
