<?php

namespace Tourze\ProxyProtocol\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\HeaderInterface;
use Tourze\ProxyProtocol\Model\V1Header;

/**
 * @internal
 */
#[CoversClass(V1Header::class)]
final class V1HeaderTest extends TestCase
{
    private V1Header $header;

    protected function setUp(): void
    {
        parent::setUp();

        $this->header = new V1Header();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals(Version::V1, $this->header->getVersion());
        $this->assertNull($this->header->getSourceAddress());
        $this->assertNull($this->header->getTargetAddress());
        $this->assertNull($this->header->getSourceIp());
        $this->assertNull($this->header->getSourcePort());
    }

    public function testVersionGetterSetter(): void
    {
        $this->header->setVersion(Version::V2);
        $this->assertEquals(Version::V2, $this->header->getVersion());

        $this->header->setVersion(Version::V1);
        $this->assertEquals(Version::V1, $this->header->getVersion());
    }

    public function testProtocolGetterSetter(): void
    {
        $protocol = 'TCP4';
        $this->header->setProtocol($protocol);
        $this->assertEquals($protocol, $this->header->getProtocol());

        $protocol = 'TCP6';
        $this->header->setProtocol($protocol);
        $this->assertEquals($protocol, $this->header->getProtocol());
    }

    public function testSourceAddressGetterSetter(): void
    {
        $address = new Address('192.168.1.1', 8080);
        $this->header->setSourceAddress($address);
        $this->assertEquals($address, $this->header->getSourceAddress());

        $this->header->setSourceAddress(null);
        $this->assertNull($this->header->getSourceAddress());
    }

    public function testTargetAddressGetterSetter(): void
    {
        $address = new Address('192.168.1.2', 80);
        $this->header->setTargetAddress($address);
        $this->assertEquals($address, $this->header->getTargetAddress());

        $this->header->setTargetAddress(null);
        $this->assertNull($this->header->getTargetAddress());
    }

    public function testSourceIpGetter(): void
    {
        $this->assertNull($this->header->getSourceIp());

        $address = new Address('192.168.1.1', 8080);
        $this->header->setSourceAddress($address);
        $this->assertEquals('192.168.1.1', $this->header->getSourceIp());
    }

    public function testSourcePortGetter(): void
    {
        $this->assertNull($this->header->getSourcePort());

        $address = new Address('192.168.1.1', 8080);
        $this->header->setSourceAddress($address);
        $this->assertEquals(8080, $this->header->getSourcePort());
    }

    public function testHeaderInterface(): void
    {
        $this->assertInstanceOf(HeaderInterface::class, $this->header);
    }

    public function testCompleteHeader(): void
    {
        // 设置完整的头部信息
        $this->header->setVersion(Version::V1);
        $this->header->setProtocol('TCP4');
        $this->header->setSourceAddress(new Address('10.0.0.1', 12345));
        $this->header->setTargetAddress(new Address('10.0.0.2', 443));

        // 验证所有字段
        $this->assertEquals(Version::V1, $this->header->getVersion());
        $this->assertEquals('TCP4', $this->header->getProtocol());

        $sourceAddress = $this->header->getSourceAddress();
        $this->assertNotNull($sourceAddress);
        $this->assertEquals('10.0.0.1', $sourceAddress->ip);
        $this->assertEquals(12345, $sourceAddress->port);

        $targetAddress = $this->header->getTargetAddress();
        $this->assertNotNull($targetAddress);
        $this->assertEquals('10.0.0.2', $targetAddress->ip);
        $this->assertEquals(443, $targetAddress->port);

        // 验证接口方法
        $this->assertEquals('10.0.0.1', $this->header->getSourceIp());
        $this->assertEquals(12345, $this->header->getSourcePort());
    }

    public function testToProtocolString(): void
    {
        // 测试完整的协议字符串生成
        $this->header->setProtocol('TCP4');
        $this->header->setSourceAddress(new Address('192.168.1.1', 12345));
        $this->header->setTargetAddress(new Address('192.168.1.2', 80));

        $expected = "PROXY TCP4 192.168.1.1 192.168.1.2 12345 80\r\n";
        $this->assertEquals($expected, $this->header->toProtocolString());
    }

    public function testToProtocolStringWithTcp6(): void
    {
        // 测试 TCP6 协议字符串生成
        $this->header->setProtocol('TCP6');
        $this->header->setSourceAddress(new Address('::1', 12345));
        $this->header->setTargetAddress(new Address('::2', 80));

        $expected = "PROXY TCP6 ::1 ::2 12345 80\r\n";
        $this->assertEquals($expected, $this->header->toProtocolString());
    }

    public function testToProtocolStringWithoutProtocol(): void
    {
        // 测试没有设置协议时返回空字符串
        $this->header->setSourceAddress(new Address('192.168.1.1', 12345));
        $this->header->setTargetAddress(new Address('192.168.1.2', 80));

        $this->assertEquals('', $this->header->toProtocolString());
    }

    public function testToProtocolStringWithoutSourceAddress(): void
    {
        // 测试没有设置源地址时返回空字符串
        $this->header->setProtocol('TCP4');
        $this->header->setTargetAddress(new Address('192.168.1.2', 80));

        $this->assertEquals('', $this->header->toProtocolString());
    }

    public function testToProtocolStringWithoutTargetAddress(): void
    {
        // 测试没有设置目标地址时返回空字符串
        $this->header->setProtocol('TCP4');
        $this->header->setSourceAddress(new Address('192.168.1.1', 12345));

        $this->assertEquals('', $this->header->toProtocolString());
    }
}
