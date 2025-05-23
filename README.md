# Proxy Protocol Core

[English](README.md) | [中文](README.zh-CN.md)

A PHP core library for parsing and generating Proxy Protocol. Supports both V1 (text format) and V2 (binary format) versions of the protocol with basic data structures and parsing capabilities.

## Features

- Support for Proxy Protocol V1 (text format) and V2 (binary format)
- Enum types for safer protocol constant usage
- Complete type hints and detailed documentation
- PSR standard compliant

## Installation

```bash
composer require tourze/proxy-protocol-core
```

## Usage

### Proxy Protocol V1

```php
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\V1Header;

// Create a V1 header
$header = new V1Header();
$header->setVersion(Version::V1);
$header->setProtocol('TCP4');
$header->setSourceAddress(new Address('192.168.1.1', 12345));
$header->setTargetAddress(new Address('192.168.1.2', 80));
```

### Proxy Protocol V2

```php
use Tourze\ProxyProtocol\Enum\AddressFamily;
use Tourze\ProxyProtocol\Enum\Command;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\V2Header;

// Create a V2 header
$header = new V2Header();
$header->setVersion(Version::V2);
$header->setCommand(Command::PROXY);
$header->setAddressFamily(AddressFamily::TCP4);
$header->setSourceAddress('192.168.1.1');
$header->setSourcePort(12345);
$header->setTargetAddress('192.168.1.2');
$header->setTargetPort(80);

// Parse a V2 header
$data = "..."; // Binary data containing Proxy Protocol V2 header
$header = V2Header::parseHeader($data);
```

## References

- [Proxy Protocol Specification](https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt)
