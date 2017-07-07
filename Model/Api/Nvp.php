<?php

namespace PiotrKwiecinski\PaypalFix\Model\Api;

use Magento\Paypal\Model\Api\Nvp as BaseNvp;

class Nvp extends BaseNvp
{
    /**
     * {@inheritdoc}
     */
    protected function _handleCallErrors($response)
    {
        $errors = $this->_extractErrorsFromResponse($response);
        if (empty($errors)) {
            return;
        }

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error['message'];
            $this->_callErrors[] = $error['code'];
        }
        $errorMessages = implode(' ', $errorMessages);

        $exceptionLogMessage = sprintf(
            'PayPal NVP gateway errors: %s Correlation ID: %s. Version: %s.',
            $errorMessages,
            isset($response['CORRELATIONID']) ? $response['CORRELATIONID'] : '',
            isset($response['VERSION']) ? $response['VERSION'] : ''
        );
        $this->_logger->critical($exceptionLogMessage);

        if (in_array((string)ProcessableException::API_TRANSACTION_HAS_BEEN_COMPLETED, $this->_callErrors)) {
            return;
        }

        $exceptionPhrase = __('PayPal gateway has rejected request. %1', $errorMessages);

        /** @var \Magento\Framework\Exception\LocalizedException $exception */
        $firstError = $errors[0]['code'];
        $exception = $this->_isProcessableError($firstError)
            ? $this->_processableExceptionFactory->create(
                ['phrase' => $exceptionPhrase, 'code' => $firstError]
            )
            : $this->_frameworkExceptionFactory->create(
                ['phrase' => $exceptionPhrase]
            );

        throw $exception;
    }
}
