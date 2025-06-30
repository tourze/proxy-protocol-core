<?php

namespace Tourze\ProxyProtocol\Exception;

use Exception;

/**
 * 不支持的协议异常
 * 
 * 当尝试使用不支持的协议类型时抛出此异常
 */
class UnsupportedProtocolException extends Exception
{
}