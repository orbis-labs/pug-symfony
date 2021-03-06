# Pug-Symfony
[![Latest Stable Version](https://poser.pugx.org/orbis-labs/pug-symfony/v/stable.png)](https://packagist.org/packages/orbis-labs/pug-symfony)
[![Build Status](https://travis-ci.org/orbis-labs/pug-symfony.svg?branch=master)](https://travis-ci.org/pug-php/pug-symfony)
[![StyleCI](https://styleci.io/repos/61784988/shield?style=flat)](https://styleci.io/repos/61784988)
[![Code Climate](https://codeclimate.com/github/pug-php/pug-symfony/badges/gpa.svg)](https://codeclimate.com/github/orbis-labs/pug-symfony)

Pug template engine for Symfony

## Install
In the root directory of your Symfony project, open a terminal and enter:
```shell
composer require orbis-labs/pug-symfony
```

Add in **app/config/services.yml**:
```yml
services:
    templating.engine.pug:
        class: PugBundle\PugTemplateEngine
        arguments: ["@kernel"]
```

Add jade in the templating.engines setting in **app/config/config.yml**:
```yml
...
    templating:
        engines: ['pug', 'twig', 'php']
```

## Configure

You can set pug options by accessing the container (from controller or from the kernel) in Symfony.
```php
$services = $kernel->getContainer();
$pug = $services->get('templating.engine.pug');
$pug->setOptions(array(
  'pretty' => true,
  'pugjs' => true,
  // ...
));
// You can get the Pug engine to call any method available in pug-php
$pug->getEngine()->share('globalVar', 'foo');
$pug->getEngine()->addKeyword('customKeyword', $bar);
```
See the options in the pug-php README: https://github.com/pug-php/pug
And methods directly available on the service: https://github.com/pug-php/pug-symfony/blob/master/src/Jade/JadeSymfonyEngine.php

## Usage
Create jade views by creating files with .pug extension
in **app/Resources/views** such as contact.html.pug with
some Jade like this:
```pug
h1
  | Hello
  =name
```
Then call it in your controller:
```php
/**
 * @Route("/contact")
 */
public function contactAction()
{
    return $this->render('contact/contact.html.pug', [
        'name' => 'Bob',
    ]);
}
```

## Deployment

In production, you better have to pre-render all your templates to improve performances. To do that, you have to add Pug\PugSymfonyBundle\PugSymfonyBundle in your registered bundles.

In **app/AppKernel.php**, in the ```registerBundles()``` method, add the Pug bundle:
```php
public function registerBundles()
{
    $bundles = [
        ...
        new PugBundle\PugBundle(),
    ];
```

This will make the ```assets:publish``` command available, now each time you deploy your app, enter the command below:
```php bin/console assets:publish --env=prod```
