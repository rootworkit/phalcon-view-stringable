# phalcon-view-stringable
Phalcon view class that adds string rendering ability for Phalcon 2.

## Installation

Install composer in a common location or in your project:

```bash
curl -s http://getcomposer.org/installer | php
```

Create the composer.json file as follows:

```json
{
    "require": {
        "rootwork/phalcon-view-stringable": "dev-master"
    }
}
```

Run the composer installer:

```bash
php composer.phar install
```

## Usage

### Loading the view service
```php
// app/config/services.php
use Rootwork\Phalcon\Mvc\View\Stringable as View;

$di->setShared('view', function () use ($config) {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/app/views/');
    $view->setOptions([
        'stringCompilePath' => APP_PATH . '/app/views/string-compile',
        'stringCompileExt'  => '.phtml',
    ]);
    $view->registerEngines([
        'string'    => 'Phalcon\Mvc\View\Engine\Volt',
        '.phtml'    => 'Phalcon\Mvc\View\Engine\Php',
    ]);

    return $view;
});
```

### Rendering string templates
```php
// In controller, model, or anywhere Phalcon Di is available
$view       = $this->getDI()->getShared('view');
$template   = "The quick {{ color }} {{ animal }} jumps over the
               lazy {{ pet }}.";

$view->color    = 'brown';
$view->animal   = 'fox';
$view->pet      = 'dog';

// Simple, immediate rendering
echo $view->renderString($template);

// Hierarchical rendering
$view
```
