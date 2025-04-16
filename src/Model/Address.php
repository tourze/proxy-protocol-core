<?php

namespace Tourze\ProxyProtocol\Model;

/**
 * 地址信息模型
 */
class Address
{
    /**
     * @param string $ip IP地址
     * @param int $port 端口号
     */
    public function __construct(
        public readonly string $ip,
        public readonly int $port,
    ) {
    }

    /**
     * 创建一个地址对象
     *
     * @param string $ip IP地址
     * @param int $port 端口号
     * @return static
     */
    public static function create(string $ip, int $port): static
    {
        return new static($ip, $port);
    }
}
