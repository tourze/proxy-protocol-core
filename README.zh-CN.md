# Proxy Protocol Core

这是一个用于解析和生成代理协议（Proxy Protocol）的 PHP 核心库。支持代理协议的 V1 和 V2 版本的基本数据结构和解析功能。

## 功能特性

- 支持代理协议 V1（文本格式）和 V2（二进制格式）
- 提供枚举类型以便更安全的使用协议常量
- 完整的类型提示和详细注释
- 符合 PSR 标准

## 安装

```bash
composer require tourze/proxy-protocol-core
```

## 使用方法

### 代理协议 V1

```php
use Tourze\ProxyProtocol\Enum\Version;
use Tourze\ProxyProtocol\Model\Address;
use Tourze\ProxyProtocol\Model\V1Header;

// 创建一个 V1 头部
$header = new V1Header();
$header->setVersion(Version::V1);
$header->setProtocol('TCP4');
$header->setSourceAddress(new Address('192.168.1.1', 12345));
$header->setTargetAddress(new Address('192.168.1.2', 80));
```

### 代理协议 V2

```php
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

// 解析一个 V2 头部
$data = "..."; // 包含 Proxy Protocol V2 头部的二进制数据
$header = V2Header::parseHeader($data);
```

## 参考资料

- [Proxy Protocol 规范](https://www.haproxy.org/download/1.8/doc/proxy-protocol.txt)
