<?php

namespace Tourze\ProxyProtocol\Exception;

use UnexpectedValueException;

/**
 * 无效协议异常
 * 
 * 当协议格式无效或不正确时抛出此异常
 */
class InvalidProtocolException extends UnexpectedValueException
{
}