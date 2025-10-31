<?php

namespace Tourze\ProxyProtocol\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use Tourze\ProxyProtocol\Enum\Command;

/**
 * @internal
 */
#[CoversClass(Command::class)]
final class CommandTest extends AbstractEnumTestCase
{
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

    public function testGetLabel(): void
    {
        $this->assertSame('本地连接', Command::LOCAL->getLabel());
        $this->assertSame('代理连接', Command::PROXY->getLabel());
    }

    /**
     * 测试命令枚举的所有值
     */
    #[DataProvider('commandProvider')]
    public function testCommandValues(int $value, Command $expectedCommand): void
    {
        $this->assertSame($expectedCommand, Command::from($value));
        $this->assertSame($value, $expectedCommand->value);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function commandProvider(): array
    {
        return [
            'LOCAL' => [0, Command::LOCAL],
            'PROXY' => [1, Command::PROXY],
        ];
    }

    public function testToArray(): void
    {
        $result = Command::LOCAL->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertSame(0, $result['value']);
        $this->assertSame('本地连接', $result['label']);
    }
}
