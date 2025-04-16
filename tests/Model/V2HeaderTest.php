<?php

namespace Tourze\ProxyProtocol\Tests\Model;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\AddressFamily;
use Tourze\ProxyProtocol\Enum\Command;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\V2Header;

class V2HeaderTest extends TestCase
{
    private V2Header $header;

    protected function setUp(): void
    {
        $this->header = new V2Header();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals(Version::V2, $this->header->getVersion());
        $this->assertEquals(Command::PROXY, $this->header->getCommand());
        $this->assertEquals(AddressFamily::TCP4, $this->header->getAddressFamily());
        $this->assertNull($this->header->getSourceAddress());
        $this->assertNull($this->header->getTargetAddress());
        $this->assertNull($this->header->getSourcePort());
        $this->assertNull($this->header->getTargetPort());
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

    public function testSourceAddressGetterSetter(): void
    {
        $address = '192.168.1.1';
        $this->header->setSourceAddress($address);
        $this->assertEquals($address, $this->header->getSourceAddress());

        $this->header->setSourceAddress(null);
        $this->assertNull($this->header->getSourceAddress());
    }

    public function testTargetAddressGetterSetter(): void
    {
        $address = '192.168.1.2';
        $this->header->setTargetAddress($address);
        $this->assertEquals($address, $this->header->getTargetAddress());

        $this->header->setTargetAddress(null);
        $this->assertNull($this->header->getTargetAddress());
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
        $this->assertEquals('10.0.0.1', $this->header->getSourceAddress());
        $this->assertEquals(12345, $this->header->getSourcePort());
        $this->assertEquals('10.0.0.2', $this->header->getTargetAddress());
        $this->assertEquals(443, $this->header->getTargetPort());
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
        $this->assertEquals($sourceAddress, $header->getSourceAddress());
        $this->assertEquals($sourcePort, $header->getSourcePort());
        $this->assertEquals($targetAddress, $header->getTargetAddress());
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
        $this->assertTrue(strpos($headerData, V2Header::SIG_DATA) === 0, 'Header should start with signature');

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

        $this->assertEquals($header->constructProxyHeader(), (string)$header);
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
        $parsedHeader = V2Header::parseHeader($fullData);

        // 验证解析结果
        $this->assertNotNull($parsedHeader, 'Header should be parsed successfully');
        $this->assertEquals('192.168.1.1', $parsedHeader->getSourceAddress());
        $this->assertEquals(12345, $parsedHeader->getSourcePort());
        $this->assertEquals('192.168.1.2', $parsedHeader->getTargetAddress());
        $this->assertEquals(443, $parsedHeader->getTargetPort());

        // 验证剩余数据
        $this->assertEquals('some payload data', $fullData);
    }
}
