# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shengamo/zmsms.svg?style=flat-square)](https://packagist.org/packages/shengamo/zmsms)
[![Total Downloads](https://img.shields.io/packagist/dt/shengamo/zmsms.svg?style=flat-square)](https://packagist.org/packages/shengamo/zmsms)
![GitHub Actions](https://github.com/shengamo/zmsms/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require shengamo/zmsms
```

## Usage

The process of the plugin is first to check if you have enough balance to send out the number of SMS's you would like to send out.
If you have enough, the messages will be sent but if you do not have enough remaining you will receive a response of "Insufficient balance".
### Configuration
Publish the configuration file:
```php
php artisan vendor:publish --provider="Shengamo\Zmsms\ZmsmsServiceProvider"
```

Add the following environment variables to your `.env` file:

```php
ZMSMS_GATEWAY_BASE_URL=https://zmsms.online/api/v1/
ZMSMS_GATEWAY_USERNAME=your_user_name
ZMSMS_GATEWAY_PASSWORD=password
```

### Sending SMS
Example usage on how to send SMS from your app

```php
use Shengamo\Zmsms\Facades\Zmsms;

Zmsms::sendSMS(senderId, Message, ['zambia_mobile number e.g. 0760123456']);
```
Ensure your sender ID is already registered on zmSMS or the package will return an error.

```php
use Shengamo\Zmsms\Facades\Zmsms;

Zmsms::sendSMS('Shengamo', 'Hello from Zmsms!', ['0971977252', '0776639088']);
```


### Checking Balance
```php
use Shengamo\Zmsms\Facades\Zmsms;

// Example usage to check SMS balance
$balance = Zmsms::getBalance();
echo "Current SMS balance: " . $balance['response_description'];
```

### Testing

```bash
vendor/bin/phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mo@shengamo.com instead of using the issue tracker.

## Credits

-   [Mo Malenga](https://github.com/shengamo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
