<?php

namespace Tourze\ProxyProtocol\Tests\Integration\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Enum\Command;

class CommandTest extends TestCase
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
     * 测试在实际代理协议环境中使用Command枚举
     */
    public function testCommandIntegration(): void
    {
        // 测试在协议头部中使用Command
        $proxyCommand = Command::PROXY;
        $localCommand = Command::LOCAL;

        // 模拟协议头部构造中的命令使用
        $versionCommandByte = chr((2 << 4) + $proxyCommand->value);
        $this->assertSame("\x21", $versionCommandByte); // 版本2，命令1

        $versionCommandByte = chr((2 << 4) + $localCommand->value);
        $this->assertSame("\x20", $versionCommandByte); // 版本2，命令0
    }
}