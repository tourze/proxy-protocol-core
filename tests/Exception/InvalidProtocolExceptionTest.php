<?php

namespace Tourze\ProxyProtocol\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\ProxyProtocol\Exception\InvalidProtocolException;

/**
 * @internal
 */
#[CoversClass(InvalidProtocolException::class)]
final class InvalidProtocolExceptionTest extends AbstractExceptionTestCase
{
    public function testInvalidProtocolExceptionIsInstanceOfUnexpectedValueException(): void
    {
        $exception = new InvalidProtocolException('Invalid protocol');

        $this->assertInstanceOf(\UnexpectedValueException::class, $exception);
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
