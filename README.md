# Annotation Scannable

Implement a Laravel scanner that can scan by namespace or path and instantiate classes annotated with specific annotations based on PHP 8.0's annotation feature.

[![GitHub Tag](https://img.shields.io/github/v/tag/dependencies-packagist/annotation-larscan)](https://github.com/dependencies-packagist/annotation-larscan/tags)
[![Total Downloads](https://img.shields.io/packagist/dt/annotation/larscan?style=flat-square)](https://packagist.org/packages/annotation/larscan)
[![Packagist Version](https://img.shields.io/packagist/v/annotation/larscan)](https://packagist.org/packages/annotation/larscan)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/annotation/larscan)](https://github.com/dependencies-packagist/annotation-larscan)
[![Packagist License](https://img.shields.io/github/license/dependencies-packagist/annotation-larscan)](https://github.com/dependencies-packagist/annotation-larscan)

## Installation

You can install the package via [Composer](https://getcomposer.org/):

```bash
composer require annotation/larscan
```

## Usage

```php
use Annotation\Scannable\Annotations\Scan;
use Annotation\Scannable\Annotations\ScanFile;
use Annotation\Scannable\Annotations\ScanNamespace;
use Annotation\Scannable\Annotations\ScanPath;
use Annotation\Scannable\Annotations\ScanPackageNamespace;
use Annotation\Scannable\Contracts\Scannable;

#[ScanPackageNamespace(['GuzzleHttp'])]

#[ScanNamespace(['Illuminate\Support\Arr'])]
#[ScanNamespace(['Illuminate\Support*'])]

#[ScanPath(__DIR__.'/../Http/')]
#[ScanPath(new \RecursiveDirectoryIterator(__DIR__.'/../Http/Controllers'))]

#[ScanFile(__FILE__)]
#[ScanFile(new \SplFileInfo(__DIR__ . '/AppServiceProvider.php'))]

#[Scan('Illuminate\Support\Arr')]
#[Scan(['Illuminate\Support\Arr'])]
#[Scan(new \ReflectionClass('Illuminate\Support\Arr'))]
class AppServiceProvider extends ServiceProvider implements Scannable
{
    //
}
```

```php
public function scan(Scanned $scannable): void
{
    $scannable->using(ScanNamespace::class, function (ScanNamespace $scanNamespace) {
        dump($scanNamespace->getReflectionClass());
    });
    Scan::using(ScanNamespace::class, function (ScanNamespace $scanNamespace) {
        dump($scanNamespace->getReflectionClass());
    });
}
```

## License

Nacosvel Contracts is made available under the MIT License (MIT). Please see [License File](LICENSE) for more information.
