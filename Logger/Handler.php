<?php
namespace Cleargo\Integrationframeworks\Logger;

use Monolog\Logger as BaseLogger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = BaseLogger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/integrationframeworks/general.log';
}