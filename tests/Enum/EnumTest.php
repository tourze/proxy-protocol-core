<?php

namespace Tourze\ProxyProtocol\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\AddressFamily;
use Tourze\ProxyProtocol\Enum\Command;
use Tourze\ProxyProtocol\Enum\Version;

class EnumTest extends TestCase
{
    public function testVersionEnum(): void
    {
        $this->assertSame(1, Version::V1->value);
        $this->assertSame(2, Version::V2->value);

        // 测试从值创建枚举
        $this->assertSame(Version::V1, Version::from(1));
        $this->assertSame(Version::V2, Version::from(2));

        // 测试无效值
        $this->expectException(\ValueError::class);
        Version::from(3);
    }

    public function testCommandEnum(): void
    {
        $this->assertSame(0, Command::LOCAL->value);
        $this->assertSame(1, Command::PROXY->value);

        // 测试从值创建枚举
        $this->assertSame(Command::LOCAL, Command::from(0));
        $this->assertSame(Command::PROXY, Command::from(1));

        // 测试无效值
        $this->expectException(\ValueError::class);
        Command::from(2);
    }

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
}
