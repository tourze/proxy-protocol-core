<?php

namespace Tourze\ProxyProtocol\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 代理协议命令类型枚举
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt 2.2节
 *
 * @internal
 */
enum Command: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    /**
     * 本地生成的连接（例如，管理连接）
     */
    case LOCAL = 0;

    /**
     * 代理连接
     */
    case PROXY = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::LOCAL => '本地连接',
            self::PROXY => '代理连接',
        };
    }
}
