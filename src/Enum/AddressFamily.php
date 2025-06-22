<?php

namespace Tourze\ProxyProtocol\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 地址族（Address Family）和传输协议（Transport Protocol）组合枚举
 *
 * 第一个十六进制位表示地址族（AF_INET=1, AF_INET6=2, AF_UNIX=3）
 * 第二个十六进制位表示传输协议（SOCK_STREAM=1, SOCK_DGRAM=2）
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt 2.2节
 */
enum AddressFamily: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    /**
     * 未指定协议
     */
    case UNSPECIFIED = "\x00";

    /**
     * IPv4 over TCP (AF_INET, SOCK_STREAM)
     */
    case TCP4 = "\x11";

    /**
     * IPv4 over UDP (AF_INET, SOCK_DGRAM)
     */
    case UDP4 = "\x12";

    /**
     * IPv6 over TCP (AF_INET6, SOCK_STREAM)
     */
    case TCP6 = "\x21";

    /**
     * IPv6 over UDP (AF_INET6, SOCK_DGRAM)
     */
    case UDP6 = "\x22";

    /**
     * Unix SOCK_STREAM (AF_UNIX, SOCK_STREAM)
     */
    case UNIX_STREAM = "\x31";

    /**
     * Unix SOCK_DGRAM (AF_UNIX, SOCK_DGRAM)
     */
    case UNIX_DGRAM = "\x32";

    public function getLabel(): string
    {
        return match ($this) {
            self::UNSPECIFIED => '未指定协议',
            self::TCP4 => 'IPv4 over TCP',
            self::UDP4 => 'IPv4 over UDP',
            self::TCP6 => 'IPv6 over TCP',
            self::UDP6 => 'IPv6 over UDP',
            self::UNIX_STREAM => 'Unix SOCK_STREAM',
            self::UNIX_DGRAM => 'Unix SOCK_DGRAM',
        };
    }
}
