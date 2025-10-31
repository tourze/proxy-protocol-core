# Proxy Protocol Core

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)
[![License](https://img.shields.io/packagist/l/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](#)
[![Build Status](https://img.shields.io/travis/tourze/proxy-protocol-core/master.svg?style=flat-square)](https://travis-ci.org/tourze/proxy-protocol-core)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/proxy-protocol-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/proxy-protocol-core)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)

A PHP core library for parsing and generating Proxy Protocol. Supports both V1 (text format) and V2 (binary format) versions of the protocol with comprehensive data structures and parsing capabilities.

## Features

- **Full Protocol Support**: Complete implementation of Proxy Protocol V1 (text format) and V2 (binary format)
- **Type Safety**: Extensive use of PHP 8.1+ enums and type hints for safer protocol constant usage
- **Exception Handling**: Dedicated exception classes for protocol parsing errors
- **Interface-Driven Design**: Common interface for all protocol versions ensuring consistency
- **Comprehensive Address Support**: Support for IPv4, IPv6, and Unix socket addresses
- **PSR Compliance**: Follows PSR-4 autoloading and PSR-12 coding standards

## Requirements

- PHP 8.1 or higher
- tourze/enum-extra package for enhanced enum functionality

## Installation

```bash
composer require tourze/proxy-protocol-core
```

## Quick Start

### Proxy Protocol V1 (Text Format)

```php
<?php

use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\V1Header;

// Create a V1 header
$header = new V1Header();
$header->setVersion(Version::V1);
$header->setProtocol('TCP4');
$header->setSourceAddress(new Address('192.168.1.1', 12345));
$header->setTargetAddress(new Address('192.168.1.2', 80));

// Generate protocol string
$protocolString = $header->toProtocolString();
echo $protocolString; // PROXY TCP4 192.168.1.1 192.168.1.2 12345 80\r\n
```

### Proxy Protocol V2 (Binary Format)

```php
<?php

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

// Generate binary protocol data
$binaryData = $header->constructProxyHeader();

// Parse a V2 header from binary data
$parsedHeader = V2Header::parseHeader($binaryData);
```

### Exception Handling

```php
<?php

use Tourze\ProxyProtocol\Exception\InvalidProtocolException;
use Tourze\ProxyProtocol\Exception\UnsupportedProtocolException;
use Tourze\ProxyProtocol\Model\V2Header;

try {
    $header = V2Header::parseHeader($invalidData);
} catch (InvalidProtocolException $e) {
    echo "Invalid protocol data: " . $e->getMessage();
} catch (UnsupportedProtocolException $e) {
    echo "Unsupported protocol version: " . $e->getMessage();
}
```

## API Documentation

### Enumerations

- `Version`: Protocol version (V1, V2)
- `Command`: V2 protocol command (PROXY, LOCAL)
- `AddressFamily`: Address family and transport protocol combinations
  - `TCP4`: IPv4 over TCP
  - `UDP4`: IPv4 over UDP
  - `TCP6`: IPv6 over TCP  
  - `UDP6`: IPv6 over UDP
  - `UNIX_STREAM`: Unix socket with stream
  - `UNIX_DGRAM`: Unix socket with datagram
  - `UNSPECIFIED`: Unspecified protocol

### Models

- `HeaderInterface`: Common interface for all protocol headers
- `V1Header`: Proxy Protocol V1 implementation
- `V2Header`: Proxy Protocol V2 implementation  
- `Address`: Address/port pair representation

### Exceptions

- `InvalidProtocolException`: Thrown when protocol data is malformed
- `UnsupportedProtocolException`: Thrown when protocol version is not supported

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## References

- [Proxy Protocol Specification](https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt)
- [HAProxy Documentation](https://www.haproxy.org/download/1.8/doc/configuration.txt)
