# SimpleAntiSpam

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)

this Package is focused in a simple field validation 

Actually using the open api from [stopforumspam.com](stopforumspam.com)

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practices by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require juliocapuano/simple-antispam
```

## Usage

``` php

use juliocapuano\SimpleAntiSpam;

try{

    if(SimpleAntiSpam::isSpamEmail($email))
        throw new \Exception('email is invalid or in black list')

    if(SimpleAntiSpam::isSpamIp($ip))
        throw new \Exception('ip is invalid or in black list')
    
    if(SimpleAntiSpam::isUrlInText($content))
        throw new \Exception('is span text')

    // sendEmailOrSomeElse();

} catch (Exception $e){

    // manageError($e)

}
    
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email juliocapuano@gmail.com instead of using the issue tracker.

## Credits

- [Julio Capuano][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/juliocapuano/simple-antispam.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/juliocapuano/simple-antispam/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/juliocapuano/simple-antispam.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/juliocapuano/simple-antispam.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/juliocapuano/simple-antispam.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/juliocapuano/simple-antispam
[link-travis]: https://travis-ci.org/juliocapuano/simple-antispam
[link-scrutinizer]: https://scrutinizer-ci.com/g/juliocapuano/simple-antispam/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/juliocapuano/simple-antispam
[link-downloads]: https://packagist.org/packages/juliocapuano/simple-antispam
[link-author]: https://github.com/juliocapuano
[link-contributors]: ../../contributors
