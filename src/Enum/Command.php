<?php

namespace Tourze\ProxyProtocol\Enum;

/**
 * 代理协议命令类型枚举
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt 2.2节
 */
enum Command: int
{
    /**
     * 本地生成的连接（例如，管理连接）
     */
    case LOCAL = 0;

    /**
     * 代理连接
     */
    case PROXY = 1;
}
