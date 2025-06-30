<?php

namespace Tourze\ProxyProtocol\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Exception\InvalidProtocolException;
use UnexpectedValueException;

class InvalidProtocolExceptionTest extends TestCase
{
    public function testInvalidProtocolExceptionIsInstanceOfUnexpectedValueException(): void
    {
        $exception = new InvalidProtocolException('Invalid protocol');
        
        $this->assertInstanceOf(UnexpectedValueException::class, $exception);
        $this->assertSame('Invalid protocol', $exception->getMessage());
    }

    public function testInvalidProtocolExceptionWithCustomMessage(): void
    {
        $message = 'Custom invalid protocol message';
        $exception = new InvalidProtocolException($message);
        
        $this->assertSame($message, $exception->getMessage());
    }

    public function testInvalidProtocolExceptionWithCodeAndPrevious(): void
    {
        $code = 100;
        $previous = new \Exception('Previous exception');
        $message = 'Protocol validation failed';
        
        $exception = new InvalidProtocolException($message, $code, $previous);
        
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}