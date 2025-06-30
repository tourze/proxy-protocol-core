<?php

namespace Tourze\ProxyProtocol\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\AddressFamily;

class AddressFamilyTest extends TestCase
{
    public function testAddressFamilyEnum(): void
    {
        $this->assertSame("\x00", AddressFamily::UNSPECIFIED->value);
        $this->assertSame("\x11", AddressFamily::TCP4->value);
        $this->assertSame("\x12", AddressFamily::UDP4->value);
        $this->assertSame("\x21", AddressFamily::TCP6->value);
        $this->assertSame("\x22", AddressFamily::UDP6->value);
        $this->assertSame("\x31", AddressFamily::UNIX_STREAM->value);
        $this->assertSame("\x32", AddressFamily::UNIX_DGRAM->value);

        // 测试从值创建枚举
        $this->assertSame(AddressFamily::TCP4, AddressFamily::from("\x11"));
        $this->assertSame(AddressFamily::UDP6, AddressFamily::from("\x22"));

        // 测试无效值
        $this->expectException(\ValueError::class);
        AddressFamily::from("\xFF");
    }

    /**
     * 测试特定枚举值创建的特殊情况
     *
     * @dataProvider addressFamilyProvider
     */
    public function testAddressFamilyValues(string $rawValue, AddressFamily $expectedEnum): void
    {
        $this->assertSame($expectedEnum, AddressFamily::from($rawValue));
    }

    public function addressFamilyProvider(): array
    {
        return [
            'UNSPECIFIED' => ["\x00", AddressFamily::UNSPECIFIED],
            'TCP4' => ["\x11", AddressFamily::TCP4],
            'UDP4' => ["\x12", AddressFamily::UDP4],
            'TCP6' => ["\x21", AddressFamily::TCP6],
            'UDP6' => ["\x22", AddressFamily::UDP6],
            'UNIX_STREAM' => ["\x31", AddressFamily::UNIX_STREAM],
            'UNIX_DGRAM' => ["\x32", AddressFamily::UNIX_DGRAM],
        ];
    }

    public function testGetLabel(): void
    {
        $this->assertSame('未指定协议', AddressFamily::UNSPECIFIED->getLabel());
        $this->assertSame('IPv4 over TCP', AddressFamily::TCP4->getLabel());
        $this->assertSame('IPv4 over UDP', AddressFamily::UDP4->getLabel());
        $this->assertSame('IPv6 over TCP', AddressFamily::TCP6->getLabel());
        $this->assertSame('IPv6 over UDP', AddressFamily::UDP6->getLabel());
        $this->assertSame('Unix SOCK_STREAM', AddressFamily::UNIX_STREAM->getLabel());
        $this->assertSame('Unix SOCK_DGRAM', AddressFamily::UNIX_DGRAM->getLabel());
    }
}