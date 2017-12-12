<?php

namespace Cleargo\Integrationframeworks\Model;


class Cron
{
    protected $_logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_logger = $logger;
    }

    public function orderCreateAndUpdate() {
        $this->_logger->debug("Start of orderCreateAndUpdate");

        $this->_logger->debug("End of orderCreateAndUpdate");
        return $this;
    }
}