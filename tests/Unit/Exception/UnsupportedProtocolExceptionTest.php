<?php

namespace Tourze\ProxyProtocol\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Exception\UnsupportedProtocolException;
use Exception;

class UnsupportedProtocolExceptionTest extends TestCase
{
    public function testUnsupportedProtocolExceptionIsInstanceOfException(): void
    {
        $exception = new UnsupportedProtocolException('Unsupported protocol');
        
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame('Unsupported protocol', $exception->getMessage());
    }

    public function testUnsupportedProtocolExceptionWithCustomMessage(): void
    {
        $message = 'Unix socket not (yet) supported';
        $exception = new UnsupportedProtocolException($message);
        
        $this->assertSame($message, $exception->getMessage());
    }

    public function testUnsupportedProtocolExceptionWithCodeAndPrevious(): void
    {
        $code = 200;
        $previous = new \RuntimeException('Runtime error');
        $message = 'Protocol feature not implemented';
        
        $exception = new UnsupportedProtocolException($message, $code, $previous);
        
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}