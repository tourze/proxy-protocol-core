<?php

namespace Tourze\ProxyProtocol\Model;

use Tourze\ProxyProtocol\Enum\Version;

/**
 * 代理协议头部接口
 *
 * 定义了所有代理协议头部实现应该提供的共同方法
 */
interface HeaderInterface
{
    /**
     * 获取协议版本
     */
    public function getVersion(): Version;

    /**
     * 获取源 IP 地址
     *
     * @return string|null 源 IP 地址
     */
    public function getSourceIp(): ?string;

    /**
     * 获取源端口
     *
     * @return int|null 源端口
     */
    public function getSourcePort(): ?int;

    /**
     * 获取源地址
     *
     * @return Address|null 源地址对象
     */
    public function getSourceAddress(): ?Address;

    /**
     * 获取目标地址
     *
     * @return Address|null 目标地址对象
     */
    public function getTargetAddress(): ?Address;
}
