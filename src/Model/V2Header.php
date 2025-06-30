<?php

namespace Tourze\ProxyProtocol\Model;

use ReflectionClass;
use Tourze\ProxyProtocol\Enum\AddressFamily;
use Tourze\ProxyProtocol\Enum\Command;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Exception\InvalidProtocolException;
use Tourze\ProxyProtocol\Exception\UnsupportedProtocolException;

/**
 * 代理协议V2版本的头部信息
 *
 * 协议V2版本是二进制格式，提供了更紧凑和高效的表示
 *
 * @see https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt 2.2节
 */
class V2Header implements HeaderInterface
{
    /**
     * 协议签名数据，固定为 12 字节
     *
     * \r\n\r\n\0\r\nQUIT\n
     * 用十六进制表示是 0x0D 0x0A 0x0D 0x0A 0x00 0x0D 0x0A 0x51 0x55 0x49 0x54 0x0A
     *
     * @var string 协议签名，用于识别代理协议V2的头部
     */
    public const SIG_DATA = "\x0D\x0A\x0D\x0A\x00\x0D\x0A\x51\x55\x49\x54\x0A";

    /**
     * 不同地址族和传输协议的二进制格式长度
     *
     * @var array<int|string, int> 各种协议的数据长度映射
     */
    protected static array $lengths = [
        "\x11" => 12, // TCP4
        "\x12" => 12, // UDP4
        "\x21" => 36, // TCP6
        "\x22" => 36, // UDP6
        "\x31" => 216, // UNIX_STREAM
        "\x32" => 216, // UNIX_DGRAM
    ];

    /**
     * 协议版本，固定为V2
     *
     * @var Version 协议版本
     */
    private Version $version = Version::V2;

    /**
     * 命令类型
     *
     * @var Command 命令类型
     */
    private Command $command = Command::PROXY;

    /**
     * 地址族和传输协议组合
     *
     * @var AddressFamily 地址族和传输协议
     */
    private AddressFamily $addressFamily = AddressFamily::TCP4;

    /**
     * 客户端（源）IP地址
     *
     * @var string|null 源地址
     */
    private ?string $sourceAddress = null;

    /**
     * 客户端连接的目标IP地址
     *
     * @var string|null 目标地址
     */
    private ?string $targetAddress = null;

    /**
     * 客户端（源）端口
     *
     * @var int|null 源端口
     */
    private ?int $sourcePort = null;

    /**
     * 客户端连接的目标端口
     *
     * @var int|null 目标端口
     */
    private ?int $targetPort = null;

    /**
     * 获取协议版本
     *
     * @return Version 协议版本
     */
    public function getVersion(): Version
    {
        return $this->version;
    }

    /**
     * 设置协议版本
     *
     * @param Version $version 协议版本
     * @return void
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
        return $this->sourceAddress;
    }

    /**
     * 获取命令类型
     *
     * @return Command 命令类型
     */
    public function getCommand(): Command
    {
        return $this->command;
    }

    /**
     * 设置命令类型
     *
     * @param Command $command 命令类型
     * @return void
     */
    public function setCommand(Command $command): void
    {
        $this->command = $command;
    }

    /**
     * 获取地址族和传输协议
     *
     * @return AddressFamily 地址族和传输协议
     */
    public function getAddressFamily(): AddressFamily
    {
        return $this->addressFamily;
    }

    /**
     * 设置地址族和传输协议
     *
     * @param AddressFamily $addressFamily 地址族和传输协议
     * @return void
     */
    public function setAddressFamily(AddressFamily $addressFamily): void
    {
        $this->addressFamily = $addressFamily;
    }

    /**
     * 获取原始源地址
     *
     * @return string|null 源地址
     */
    public function getRawSourceAddress(): ?string
    {
        return $this->sourceAddress;
    }

    /**
     * 获取源地址
     *
     * @return Address|null 源地址对象
     */
    public function getSourceAddress(): ?Address
    {
        if ($this->sourceAddress === null || $this->sourcePort === null) {
            return null;
        }

        return new Address($this->sourceAddress, $this->sourcePort);
    }

    /**
     * 设置源地址
     *
     * @param string|null $sourceAddress 源地址
     * @return void
     */
    public function setSourceAddress(?string $sourceAddress): void
    {
        $this->sourceAddress = $sourceAddress;
    }

    /**
     * 获取原始目标地址
     *
     * @return string|null 目标地址
     */
    public function getRawTargetAddress(): ?string
    {
        return $this->targetAddress;
    }

    /**
     * 获取目标地址
     *
     * @return Address|null 目标地址对象
     */
    public function getTargetAddress(): ?Address
    {
        if ($this->targetAddress === null || $this->targetPort === null) {
            return null;
        }

        return new Address($this->targetAddress, $this->targetPort);
    }

    /**
     * 设置目标地址
     *
     * @param string|null $targetAddress 目标地址
     * @return void
     */
    public function setTargetAddress(?string $targetAddress): void
    {
        $this->targetAddress = $targetAddress;
    }

    /**
     * 获取源端口
     *
     * @return int|null 源端口
     */
    public function getSourcePort(): ?int
    {
        return $this->sourcePort;
    }

    /**
     * 设置源端口
     *
     * @param int|null $sourcePort 源端口
     * @return void
     */
    public function setSourcePort(?int $sourcePort): void
    {
        $this->sourcePort = $sourcePort;
    }

    /**
     * 获取目标端口
     *
     * @return int|null 目标端口
     */
    public function getTargetPort(): ?int
    {
        return $this->targetPort;
    }

    /**
     * 设置目标端口
     *
     * @param int|null $targetPort 目标端口
     * @return void
     */
    public function setTargetPort(?int $targetPort): void
    {
        $this->targetPort = $targetPort;
    }

    /**
     * 获取地址族和传输协议
     *
     * @return string 地址族和传输协议
     */
    protected function getProtocol(): string
    {
        if ($this->version === Version::V2) {
            return $this->addressFamily->value;
        } else {
            return array_flip((new ReflectionClass($this))->getConstants())[$this->addressFamily->value];
        }
    }

    /**
     * 获取版本和命令的组合值
     *
     * 在第13个字节中，高4位表示版本，低4位表示命令
     *
     * @return string 版本和命令的组合字节
     */
    protected function getVersionCommand(): string
    {
        if ($this->version === Version::V2) {
            return chr(($this->version->value << 4) + $this->command->value);
        }

        return '';
    }

    /**
     * 获取地址数据的长度
     *
     * @return string 地址数据长度的网络字节序表示（2字节）
     */
    protected function getAddressLength(): string
    {
        if ($this->version === Version::V2) {
            return pack('n', self::$lengths[$this->addressFamily->value]);
        }

        return '';
    }

    /**
     * 将IP地址编码为二进制格式
     *
     * @param string $address IP地址
     * @param AddressFamily $addressFamily 地址族
     * @return string 编码后的地址数据
     * @throws UnsupportedProtocolException 在不支持的地址族时抛出
     * @throws InvalidProtocolException 在协议无效时抛出
     */
    protected function encodeAddress(string $address, AddressFamily $addressFamily): string
    {
        if ($this->version === Version::V1) {
            return $address;
        }

        return match ($addressFamily) {
            AddressFamily::TCP4, AddressFamily::UDP4, AddressFamily::TCP6, AddressFamily::UDP6 => inet_pton($address),
            AddressFamily::UNIX_STREAM, AddressFamily::UNIX_DGRAM => throw new UnsupportedProtocolException("Unix socket not (yet) supported."),
            default => throw new InvalidProtocolException("Invalid protocol."),
        };
    }

    /**
     * 将二进制格式的地址解码为字符串表示
     *
     * @param Version $version 协议版本
     * @param string $address 编码后的地址数据
     * @param string $protocol 地址族和传输协议
     * @return string 解码后的IP地址
     * @throws UnsupportedProtocolException 在不支持的地址族时抛出
     * @throws InvalidProtocolException 在协议无效时抛出
     */
    protected static function decodeAddress(Version $version, string $address, string $protocol): string
    {
        if ($version === Version::V1) {
            return $address;
        }

        return match ($protocol) {
            "\x11", "\x12", "\x21", "\x22" => inet_ntop($address), // TCP4, UDP4, TCP6, UDP6
            "\x31", "\x32" => throw new UnsupportedProtocolException("Unix socket not (yet) supported."), // UNIX_STREAM, UNIX_DGRAM
            default => throw new InvalidProtocolException("Invalid protocol."),
        };
    }

    /**
     * 获取源地址和目标地址的编码数据
     *
     * @return string 编码后的地址数据
     * @throws UnsupportedProtocolException 在地址编码失败时抛出
     */
    protected function getAddresses(): string
    {
        $separator = $this->version === Version::V1 ? " " : "";
        return $this->encodeAddress($this->sourceAddress, $this->addressFamily) . $separator . $this->encodeAddress($this->targetAddress, $this->addressFamily);
    }

    /**
     * 将端口编码为二进制格式
     *
     * @param int $port 端口号
     * @param AddressFamily $addressFamily 地址族
     * @return string 编码后的端口数据
     * @throws UnsupportedProtocolException 在不支持的地址族时抛出
     * @throws InvalidProtocolException 在协议无效时抛出
     */
    protected function encodePort(int $port, AddressFamily $addressFamily): string
    {
        if ($this->version === Version::V1) {
            return (string)$port;
        }

        return match ($addressFamily) {
            AddressFamily::TCP4, AddressFamily::UDP4, AddressFamily::TCP6, AddressFamily::UDP6 => pack('n', $port),
            AddressFamily::UNIX_STREAM, AddressFamily::UNIX_DGRAM => throw new UnsupportedProtocolException("Unix socket not (yet) supported."),
            default => throw new InvalidProtocolException("Invalid protocol."),
        };
    }

    /**
     * 获取源端口和目标端口的编码数据
     *
     * @return string 编码后的端口数据
     * @throws UnsupportedProtocolException 在端口编码失败时抛出
     */
    protected function getPorts(): string
    {
        $separator = $this->version === Version::V1 ? " " : "";
        return $this->encodePort($this->sourcePort, $this->addressFamily) . $separator . $this->encodePort($this->targetPort, $this->addressFamily);
    }

    /**
     * 构造完整的代理协议头部数据
     *
     * @return string 编码后的完整协议头部
     */
    public function constructProxyHeader(): string
    {
        $separator = $this->version === Version::V1 ? "\x20" : "";
        $terminator = $this->version === Version::V1 ? "\r\n" : null;

        return implode($separator, array_filter([
            self::SIG_DATA,
            $this->getVersionCommand(),
            $this->getProtocol(),
            $this->getAddressLength(),
            $this->getAddresses(),
            $this->getPorts(),
            $terminator
        ]));
    }

    /**
     * 将头部转换为字符串
     *
     * @return string 编码后的完整协议头部
     */
    public function __toString(): string
    {
        return $this->constructProxyHeader();
    }

    /**
     * 创建一个代理转发头部
     *
     * @param string $sourceAddress 源地址
     * @param int $sourcePort 源端口
     * @param string $targetAddress 目标地址
     * @param int $targetPort 目标端口
     * @param Version $version 协议版本
     * @return self 创建的头部对象
     */
    public static function createForward4(string $sourceAddress, int $sourcePort, string $targetAddress, int $targetPort, Version $version = Version::V2): self
    {
        $result = new self();
        $result->setVersion($version);
        $result->setSourceAddress($sourceAddress);
        $result->setTargetPort($targetPort);
        $result->setTargetAddress($targetAddress);
        $result->setSourcePort($sourcePort);
        return $result;
    }

    /**
     * 解析协议头部数据
     *
     * @param string $data 引用传递的数据，解析后会移除头部部分
     * @return self|null 解析出的头部对象，或者在解析失败时返回null
     */
    public static function parseHeader(string &$data): ?self
    {
        // 匹配代理协议签名
        if (strncmp($data, self::SIG_DATA, strlen(self::SIG_DATA)) === 0) {
            $result = self::parseVersion2($data);
        } else {
            return null;
        }

        $constructed = $result->constructProxyHeader();
        if (strncmp($constructed, $data, strlen($constructed)) === 0) {
            $data = substr($data, strlen($constructed));
            return $result;
        }

        return null;
    }

    /**
     * 解析V2版本的协议头部
     *
     * @param string $data 数据
     * @return self 解析出的头部对象
     */
    protected static function parseVersion2(string $data): self
    {
        // 第13字节，高4位是版本，低4位是命令
        $versionValue = ord($data[12]) >> 4;
        $commandValue = ord($data[12]) % 16;
        // 第14字节是地址族和传输协议
        $protocolValue = $data[13];

        // 计算地址数据的位置和长度
        $pos = 16; // 跳过12字节签名+1字节版本命令+1字节协议+2字节长度

        // 解析源地址
        $sourceAddress = self::decodeAddress(
            Version::from($versionValue),
            substr($data, $pos, self::$lengths[$protocolValue] / 2 - 2),
            $protocolValue
        );
        $pos += self::$lengths[$protocolValue] / 2 - 2;

        // 解析目标地址
        $targetAddress = self::decodeAddress(
            Version::from($versionValue),
            substr($data, $pos, self::$lengths[$protocolValue] / 2 - 2),
            $protocolValue
        );
        $pos += self::$lengths[$protocolValue] / 2 - 2;

        // 解析端口
        $sourcePort = unpack('n', substr($data, $pos, 2))[1];
        $targetPort = unpack('n', substr($data, $pos + 2, 2))[1];

        // 创建并填充头部对象
        $result = new self();
        $result->setVersion(Version::from($versionValue));
        $result->setCommand(Command::from($commandValue));

        // 注意：这里使用 try/catch 是因为 PHP 枚举不支持从值直接创建，需要通过 from 方法
        try {
            $result->setAddressFamily(AddressFamily::from($protocolValue));
        } catch (\ValueError $e) {
            // 如果无法映射到定义的枚举，就使用默认值
            $result->setAddressFamily(AddressFamily::UNSPECIFIED);
        }

        $result->setSourceAddress($sourceAddress);
        $result->setTargetAddress($targetAddress);
        $result->setSourcePort($sourcePort);
        $result->setTargetPort($targetPort);

        return $result;
    }
}
