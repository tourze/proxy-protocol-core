<?php

namespace Tourze\ProxyProtocol\Tests\Model;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\V1Header;

class V1HeaderTest extends TestCase
{
    private V1Header $header;

    protected function setUp(): void
    {
        $this->header = new V1Header();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals(Version::V1, $this->header->getVersion());
        $this->assertNull($this->header->getSourceAddress());
        $this->assertNull($this->header->getTargetAddress());
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
        $this->assertEquals('10.0.0.1', $this->header->getSourceAddress()->ip);
        $this->assertEquals(12345, $this->header->getSourceAddress()->port);
        $this->assertEquals('10.0.0.2', $this->header->getTargetAddress()->ip);
        $this->assertEquals(443, $this->header->getTargetAddress()->port);
    }
}
