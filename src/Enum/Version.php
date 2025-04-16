<?php

namespace Tourze\ProxyProtocol\Enum;

/**
 * 代理协议版本枚举
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt
 */
enum Version: int
{
    /**
     * 代理协议版本1，文本格式
     */
    case V1 = 1;

    /**
     * 代理协议版本2，二进制格式
     */
    case V2 = 2;
}
