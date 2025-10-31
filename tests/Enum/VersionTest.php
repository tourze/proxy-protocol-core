<?php

namespace Tourze\ProxyProtocol\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use Tourze\ProxyProtocol\Enum\Version;

/**
 * @internal
 */
#[CoversClass(Version::class)]
final class VersionTest extends AbstractEnumTestCase
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
     */
    #[DataProvider('versionProvider')]
    public function testVersionValues(int $value, Version $expectedVersion): void
    {
        $this->assertSame($expectedVersion, Version::from($value));
        $this->assertSame($value, $expectedVersion->value);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function versionProvider(): array
    {
        return [
            'V1' => [1, Version::V1],
            'V2' => [2, Version::V2],
        ];
    }

    public function testToArray(): void
    {
        $result = Version::V1->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertSame(1, $result['value']);
        $this->assertSame('版本1 (文本格式)', $result['label']);
    }
}
