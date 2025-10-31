<?php

namespace Tourze\ProxyProtocol\Model;

use Tourze\ProxyProtocol\Enum\Version;

/**
 * 代理协议V1版本的头部信息
 *
 * 代理协议V1是文本格式，格式为：
 * "PROXY" 空格 协议族 空格 源地址 空格 目标地址 空格 源端口 空格 目标端口 "\r\n"
 * 例："PROXY TCP4 192.168.0.1 192.168.0.11 56324 443\r\n"
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt 第2节
 */
class V1Header implements HeaderInterface
{
    /**
     * @var string|null 协议族，例如"TCP4"或"TCP6"
     */
    private ?string $protocol = null;

    /**
     * @var Version 协议版本
     */
    private Version $version = Version::V1;

    /**
     * @var Address|null 源地址信息
     */
    private ?Address $sourceAddress = null;

    /**
     * @var Address|null 目标地址信息
     */
    private ?Address $targetAddress = null;

    /**
     * 获取协议版本
     */
    public function getVersion(): Version
    {
        return $this->version;
    }

    /**
     * 设置协议版本
     */
    public function setVersion(Version $version): void
    {
        $this->version = $version;
    }

    /**
     * 获取源 IP 地址
     *
     * @return string|null 源 IP 地址
     */
    public function getSourceIp(): ?string
    {
        return $this->sourceAddress?->ip;
    }

    /**
     * 获取源端口
     *
     * @return int|null 源端口
     */
    public function getSourcePort(): ?int
    {
        return $this->sourceAddress?->port;
    }

    /**
     * 获取协议族
     */
    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    /**
     * 设置协议族
     */
    public function setProtocol(?string $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * 获取源地址
     */
    public function getSourceAddress(): ?Address
    {
        return $this->sourceAddress;
    }

    /**
     * 设置源地址
     */
    public function setSourceAddress(?Address $sourceAddress): void
    {
        $this->sourceAddress = $sourceAddress;
    }

    /**
     * 获取目标地址
     */
    public function getTargetAddress(): ?Address
    {
        return $this->targetAddress;
    }

    /**
     * 设置目标地址
     */
    public function setTargetAddress(?Address $targetAddress): void
    {
        $this->targetAddress = $targetAddress;
    }

    /**
     * 生成协议字符串
     *
     * 格式：PROXY 协议族 源地址 目标地址 源端口 目标端口\r\n
     * 例：PROXY TCP4 192.168.0.1 192.168.0.11 56324 443\r\n
     */
    public function toProtocolString(): string
    {
        if (null === $this->protocol) {
            return '';
        }

        if (null === $this->sourceAddress || null === $this->targetAddress) {
            return '';
        }

        return sprintf(
            "PROXY %s %s %s %d %d\r\n",
            $this->protocol,
            $this->sourceAddress->ip,
            $this->targetAddress->ip,
            $this->sourceAddress->port,
            $this->targetAddress->port
        );
    }
}
