<?php

namespace Tourze\ProxyProtocol\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\Version;

class VersionTest extends TestCase
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

    public function testGetLabel(): void
    {
        $this->assertSame('版本1 (文本格式)', Version::V1->getLabel());
        $this->assertSame('版本2 (二进制格式)', Version::V2->getLabel());
    }

    /**
     * 测试版本枚举的所有值
     *
     * @dataProvider versionProvider
     */
    public function testVersionValues(int $value, Version $expectedVersion): void
    {
        $this->assertSame($expectedVersion, Version::from($value));
        $this->assertSame($value, $expectedVersion->value);
    }

    public function versionProvider(): array
    {
        return [
            'V1' => [1, Version::V1],
            'V2' => [2, Version::V2],
        ];
    }
}