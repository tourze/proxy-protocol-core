<?php

namespace Tourze\ProxyProtocol\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\AddressFamily;
use Tourze\ProxyProtocol\Enum\Command;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\HeaderInterface;
use Tourze\ProxyProtocol\Model\V2Header;

/**
 * @internal
 */
#[CoversClass(V2Header::class)]
final class V2HeaderTest extends TestCase
{
    private V2Header $header;

    protected function setUp(): void
    {
        parent::setUp();

        $this->header = new V2Header();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals(Version::V2, $this->header->getVersion());
        $this->assertEquals(Command::PROXY, $this->header->getCommand());
        $this->assertEquals(AddressFamily::TCP4, $this->header->getAddressFamily());
        $this->assertNull($this->header->getRawSourceAddress());
        $this->assertNull($this->header->getRawTargetAddress());
        $this->assertNull($this->header->getSourceAddress());
        $this->assertNull($this->header->getTargetAddress());
        $this->assertNull($this->header->getSourcePort());
        $this->assertNull($this->header->getTargetPort());
        $this->assertNull($this->header->getSourceIp());
    }

    public function testVersionGetterSetter(): void
    {
        $this->header->setVersion(Version::V1);
        $this->assertEquals(Version::V1, $this->header->getVersion());

        $this->header->setVersion(Version::V2);
        $this->assertEquals(Version::V2, $this->header->getVersion());
    }

    public function testCommandGetterSetter(): void
    {
        $this->header->setCommand(Command::LOCAL);
        $this->assertEquals(Command::LOCAL, $this->header->getCommand());

        $this->header->setCommand(Command::PROXY);
        $this->assertEquals(Command::PROXY, $this->header->getCommand());
    }

    public function testAddressFamilyGetterSetter(): void
    {
        $this->header->setAddressFamily(AddressFamily::TCP6);
        $this->assertEquals(AddressFamily::TCP6, $this->header->getAddressFamily());

        $this->header->setAddressFamily(AddressFamily::UDP4);
        $this->assertEquals(AddressFamily::UDP4, $this->header->getAddressFamily());
    }

    public function testRawSourceAddressGetterSetter(): void
    {
        $address = '192.168.1.1';
        $this->header->setSourceAddress($address);
        $this->assertEquals($address, $this->header->getRawSourceAddress());

        $this->header->setSourceAddress(null);
        $this->assertNull($this->header->getRawSourceAddress());
    }

    public function testRawTargetAddressGetterSetter(): void
    {
        $address = '192.168.1.2';
        $this->header->setTargetAddress($address);
        $this->assertEquals($address, $this->header->getRawTargetAddress());

        $this->header->setTargetAddress(null);
        $this->assertNull($this->header->getRawTargetAddress());
    }

    public function testSourcePortGetterSetter(): void
    {
        $port = 12345;
        $this->header->setSourcePort($port);
        $this->assertEquals($port, $this->header->getSourcePort());

        $this->header->setSourcePort(null);
        $this->assertNull($this->header->getSourcePort());
    }

    public function testTargetPortGetterSetter(): void
    {
        $port = 443;
        $this->header->setTargetPort($port);
        $this->assertEquals($port, $this->header->getTargetPort());

        $this->header->setTargetPort(null);
        $this->assertNull($this->header->getTargetPort());
    }

    public function testSourceAddressGetter(): void
    {
        $this->assertNull($this->header->getSourceAddress());

        $ipAddress = '192.168.1.1';
        $port = 8080;

        // 先设置源地址和端口
        $this->header->setSourceAddress($ipAddress);
        $this->header->setSourcePort($port);

        // 获取对象化的地址
        $address = $this->header->getSourceAddress();
        $this->assertNotNull($address);
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($ipAddress, $address->ip);
        $this->assertEquals($port, $address->port);
    }

    public function testTargetAddressGetter(): void
    {
        $this->assertNull($this->header->getTargetAddress());

        $ipAddress = '192.168.1.2';
        $port = 80;

        // 先设置目标地址和端口
        $this->header->setTargetAddress($ipAddress);
        $this->header->setTargetPort($port);

        // 获取对象化的地址
        $address = $this->header->getTargetAddress();
        $this->assertNotNull($address);
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($ipAddress, $address->ip);
        $this->assertEquals($port, $address->port);
    }

    public function testSourceIpGetter(): void
    {
        $this->assertNull($this->header->getSourceIp());

        $address = '192.168.1.1';
        $this->header->setSourceAddress($address);
        $this->assertEquals($address, $this->header->getSourceIp());
    }

    public function testHeaderInterface(): void
    {
        $this->assertInstanceOf(HeaderInterface::class, $this->header);
    }

    public function testCompleteHeader(): void
    {
        // 设置完整的头部信息
        $this->header->setVersion(Version::V2);
        $this->header->setCommand(Command::PROXY);
        $this->header->setAddressFamily(AddressFamily::TCP4);
        $this->header->setSourceAddress('10.0.0.1');
        $this->header->setSourcePort(12345);
        $this->header->setTargetAddress('10.0.0.2');
        $this->header->setTargetPort(443);

        // 验证所有字段
        $this->assertEquals(Version::V2, $this->header->getVersion());
        $this->assertEquals(Command::PROXY, $this->header->getCommand());
        $this->assertEquals(AddressFamily::TCP4, $this->header->getAddressFamily());
        $this->assertEquals('10.0.0.1', $this->header->getRawSourceAddress());
        $this->assertEquals(12345, $this->header->getSourcePort());
        $this->assertEquals('10.0.0.2', $this->header->getRawTargetAddress());
        $this->assertEquals(443, $this->header->getTargetPort());

        // 验证接口方法
        $this->assertEquals('10.0.0.1', $this->header->getSourceIp());

        // 验证对象化的地址
        $sourceAddress = $this->header->getSourceAddress();
        $this->assertNotNull($sourceAddress);
        $this->assertEquals('10.0.0.1', $sourceAddress->ip);
        $this->assertEquals(12345, $sourceAddress->port);

        $targetAddress = $this->header->getTargetAddress();
        $this->assertNotNull($targetAddress);
        $this->assertEquals('10.0.0.2', $targetAddress->ip);
        $this->assertEquals(443, $targetAddress->port);
    }

    public function testCreateForward4Method(): void
    {
        $sourceAddress = '192.168.1.1';
        $sourcePort = 12345;
        $targetAddress = '192.168.1.2';
        $targetPort = 443;

        // 使用工厂方法创建头部
        $header = V2Header::createForward4($sourceAddress, $sourcePort, $targetAddress, $targetPort);

        // 验证字段
        $this->assertEquals(Version::V2, $header->getVersion());
        $this->assertEquals($sourceAddress, $header->getRawSourceAddress());
        $this->assertEquals($sourcePort, $header->getSourcePort());
        $this->assertEquals($targetAddress, $header->getRawTargetAddress());
        $this->assertEquals($targetPort, $header->getTargetPort());

        // 测试版本参数
        $header = V2Header::createForward4($sourceAddress, $sourcePort, $targetAddress, $targetPort, Version::V1);
        $this->assertEquals(Version::V1, $header->getVersion());
    }

    public function testConstructProxyHeader(): void
    {
        // 设置简单的头部信息用于测试头部构造
        $this->header->setVersion(Version::V2);
        $this->header->setCommand(Command::PROXY);
        $this->header->setAddressFamily(AddressFamily::TCP4);
        $this->header->setSourceAddress('127.0.0.1');
        $this->header->setSourcePort(12345);
        $this->header->setTargetAddress('127.0.0.1');
        $this->header->setTargetPort(80);

        // 构造头部
        $headerData = $this->header->constructProxyHeader();

        // 验证头部开始部分是否正确
        $this->assertSame(0, strpos($headerData, V2Header::SIG_DATA), 'Header should start with signature');

        // 验证头部长度，IPv4/TCP头部总长度应该是 16(基本) + 12(IPv4地址和端口) = 28字节
        $this->assertEquals(28, strlen($headerData), 'Header length for TCP4 should be 28 bytes');
    }

    public function testToString(): void
    {
        $header = new V2Header();
        $header->setSourceAddress('192.168.1.1');
        $header->setSourcePort(12345);
        $header->setTargetAddress('192.168.1.2');
        $header->setTargetPort(443);

        $this->assertEquals($header->constructProxyHeader(), (string) $header);
    }

    public function testParseHeader(): void
    {
        // 创建一个标准的头部对象
        $originalHeader = new V2Header();
        $originalHeader->setSourceAddress('192.168.1.1');
        $originalHeader->setSourcePort(12345);
        $originalHeader->setTargetAddress('192.168.1.2');
        $originalHeader->setTargetPort(443);

        // 获取二进制格式的头部数据
        $headerData = $originalHeader->constructProxyHeader();

        // 追加一些模拟有效载荷
        $fullData = $headerData . 'some payload data';

        // 解析头部
        $parseResult = V2Header::parseHeader($fullData);

        // 验证解析结果
        $this->assertNotNull($parseResult['header'], 'Header should be parsed successfully');
        $parsedHeader = $parseResult['header'];
        $this->assertEquals('192.168.1.1', $parsedHeader->getRawSourceAddress());
        $this->assertEquals(12345, $parsedHeader->getSourcePort());
        $this->assertEquals('192.168.1.2', $parsedHeader->getRawTargetAddress());
        $this->assertEquals(443, $parsedHeader->getTargetPort());

        // 验证对象化的地址
        $sourceAddress = $parsedHeader->getSourceAddress();
        $this->assertNotNull($sourceAddress);
        $this->assertEquals('192.168.1.1', $sourceAddress->ip);
        $this->assertEquals(12345, $sourceAddress->port);

        // 验证剩余数据
        $this->assertEquals('some payload data', $parseResult['remaining']);
    }
}
