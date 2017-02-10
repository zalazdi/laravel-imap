# laravel-imap

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)

## Install

1. Via Composer

``` bash
composer require zalazdi/laravel-imap
```

2. Copy `vendor\zalazdi\laravel-imap\config\imap.php` to `config\imap.php`. Edit to change host, username, password.


3. Add this line to `config\app.php` into providers section:
```
Zalazdi\LaravelImap\Providers\LaravelServiceProvider::class,
```

## Usage

Example usage: 

```php
use Zalazdi\LaravelImap\Client;
use Zalazdi\LaravelImap\Mailbox;

// ...

$client = new Client();
$client->connect();

$mailboxes = $client->getMailboxes();
foreach($mailboxes as $mailbox) {
    dump($mailbox->getMessages());
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email zalazdi@gmail.com instead of using the issue tracker.

## Credits

- [Zalazdi](http://github.com/zalazdi)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/zalazdi/laravel-imap.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/zalazdi/laravel-imap/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/zalazdi/laravel-imap.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/zalazdi/laravel-imap.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zalazdi/laravel-imap.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/zalazdi/laravel-imap
[link-travis]: https://travis-ci.org/zalazdi/laravel-imap
[link-scrutinizer]: https://scrutinizer-ci.com/g/zalazdi/laravel-imap/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/zalazdi/laravel-imap
[link-downloads]: https://packagist.org/packages/zalazdi/laravel-imap
[link-author]: https://github.com/zalazdi
