<?php

namespace Tourze\ProxyProtocol\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tourze\ProxyProtocol\Model\Address;

/**
 * @internal
 */
#[CoversClass(Address::class)]
final class AddressTest extends TestCase
{
    public function testConstructor(): void
    {
        $ip = '192.168.1.1';
        $port = 8080;

        $address = new Address($ip, $port);

        $this->assertSame($ip, $address->ip);
        $this->assertSame($port, $address->port);
    }

    public function testCreateMethod(): void
    {
        $ip = '10.0.0.1';
        $port = 443;

        $address = Address::create($ip, $port);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertSame($ip, $address->ip);
        $this->assertSame($port, $address->port);
    }

    #[DataProvider('validAddressProvider')]
    public function testWithValidAddresses(string $ip, int $port): void
    {
        $address = new Address($ip, $port);

        $this->assertSame($ip, $address->ip);
        $this->assertSame($port, $address->port);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function validAddressProvider(): array
    {
        return [
            'IPv4 local' => ['127.0.0.1', 80],
            'IPv4 standard' => ['192.168.10.1', 8080],
            'IPv4 with port 0' => ['8.8.8.8', 0],
            'IPv4 with max port' => ['1.1.1.1', 65535],
            'IPv6 localhost' => ['::1', 80],
            'IPv6 standard' => ['2001:db8::1', 443],
            'IPv6 expanded' => ['2001:0db8:0000:0000:0000:0000:0000:0001', 22],
        ];
    }
}
