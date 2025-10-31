# Proxy Protocol Core

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)
[![License](https://img.shields.io/packagist/l/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](#)
[![Build Status](https://img.shields.io/travis/tourze/proxy-protocol-core/master.svg?style=flat-square)](https://travis-ci.org/tourze/proxy-protocol-core)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/proxy-protocol-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/proxy-protocol-core)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/proxy-protocol-core.svg?style=flat-square)](https://packagist.org/packages/tourze/proxy-protocol-core)

一个用于解析和生成代理协议（Proxy Protocol）的 PHP 核心库。支持 V1（文本格式）和 V2（二进制格式）两个版本，提供完整的数据结构和解析功能。

## 功能特性

- **完整协议支持**：完整实现 Proxy Protocol V1（文本格式）和 V2（二进制格式）
- **类型安全**：广泛使用 PHP 8.1+ 枚举和类型提示，提供更安全的协议常量使用
- **异常处理**：专用的异常类处理协议解析错误
- **接口驱动设计**：所有协议版本使用统一接口，确保一致性
- **全面地址支持**：支持 IPv4、IPv6 和 Unix socket 地址
- **PSR 合规**：遵循 PSR-4 自动加载和 PSR-12 编码标准

## 系统要求

- PHP 8.1 或更高版本
- tourze/enum-extra 包（用于增强枚举功能）

## 安装

```bash
composer require tourze/proxy-protocol-core
```

## 快速开始

### 代理协议 V1（文本格式）

```php
<?php

use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\V1Header;

// 创建一个 V1 头部
$header = new V1Header();
$header->setVersion(Version::V1);
$header->setProtocol('TCP4');
$header->setSourceAddress(new Address('192.168.1.1', 12345));
$header->setTargetAddress(new Address('192.168.1.2', 80));

// 生成协议字符串
$protocolString = $header->toProtocolString();
echo $protocolString; // PROXY TCP4 192.168.1.1 192.168.1.2 12345 80\r\n
```

### 代理协议 V2（二进制格式）

```php
<?php

use Tourze\ProxyProtocol\Enum\AddressFamily;
use Tourze\ProxyProtocol\Enum\Command;
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\V2Header;

// 创建一个 V2 头部
$header = new V2Header();
$header->setVersion(Version::V2);
$header->setCommand(Command::PROXY);
$header->setAddressFamily(AddressFamily::TCP4);
$header->setSourceAddress('192.168.1.1');
$header->setSourcePort(12345);
$header->setTargetAddress('192.168.1.2');
$header->setTargetPort(80);

// 生成二进制协议数据
$binaryData = $header->constructProxyHeader();

// 从二进制数据解析 V2 头部
$parsedHeader = V2Header::parseHeader($binaryData);
```

### 异常处理

```php
<?php

use Tourze\ProxyProtocol\Exception\InvalidProtocolException;
use Tourze\ProxyProtocol\Exception\UnsupportedProtocolException;
use Tourze\ProxyProtocol\Model\V2Header;

try {
    $header = V2Header::parseHeader($invalidData);
} catch (InvalidProtocolException $e) {
    echo "无效的协议数据: " . $e->getMessage();
} catch (UnsupportedProtocolException $e) {
    echo "不支持的协议版本: " . $e->getMessage();
}
```

## API 文档

### 枚举类型

- `Version`：协议版本（V1、V2）
- `Command`：V2 协议命令（PROXY、LOCAL）
- `AddressFamily`：地址族和传输协议组合
  - `TCP4`：IPv4 over TCP
  - `UDP4`：IPv4 over UDP
  - `TCP6`：IPv6 over TCP
  - `UDP6`：IPv6 over UDP
  - `UNIX_STREAM`：Unix socket 流式传输
  - `UNIX_DGRAM`：Unix socket 数据报传输
  - `UNSPECIFIED`：未指定协议

### 模型类

- `HeaderInterface`：所有协议头部的通用接口
- `V1Header`：Proxy Protocol V1 实现
- `V2Header`：Proxy Protocol V2 实现
- `Address`：地址/端口对表示

### 异常类

- `InvalidProtocolException`：协议数据格式错误时抛出
- `UnsupportedProtocolException`：协议版本不支持时抛出

## 贡献指南

请参阅 [CONTRIBUTING.md](CONTRIBUTING.md) 了解如何为本项目做出贡献。

## 许可证

MIT 许可证。详情请查看 [许可证文件](LICENSE)。

## 参考资料

- [Proxy Protocol 规范](https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt)
- [HAProxy 文档](https://www.haproxy.org/download/1.8/doc/configuration.txt)
