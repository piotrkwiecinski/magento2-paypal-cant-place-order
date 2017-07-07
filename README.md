# PayPal place order bugfix
Bugfix for PayPal can't place order fix.

Installation via composer:

```
composer require "piotrkwiecinski/magento2-paypal-cant-place-order: 0.1.0"
```

Run from Magento 2 root directory:

for enable module:
```
php -f bin/magento module:enable PiotrKwiecinski_PayPalFix
```

for update system:
```
php -f bin/magento setup:upgrade
```
