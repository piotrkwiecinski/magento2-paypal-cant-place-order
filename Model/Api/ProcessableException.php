<?php

namespace PiotrKwiecinski\PaypalFix\Model\Api;

use Magento\Paypal\Model\Api\ProcessableException as BaseProcessableException;

class ProcessableException extends BaseProcessableException
{
    const API_TRANSACTION_HAS_BEEN_COMPLETED = 10415;
}
