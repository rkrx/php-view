php-view
========

[![Build Status](https://travis-ci.org/rkrx/php-view.svg?branch=master)](https://travis-ci.org/rkrx/php-view)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkrx/php-view/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkrx/php-view/?branch=master)

More secure and easy to use templating system for php5.4+. 

Design goals: 

* Interface-driven and dependency-injection-friendly
* Secure by default, unsecure if needed
* Lightweight, easy to understand and stable
* No extra scripting language. Use PHP to write templates.

## Use typehinting

In PHP-Templates, you can use typehinting which is recognized by IDEs like PHPStorm, ZendStudio or PDT (and maybe others). 

index.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>
<?php /* @var \Some\Name\Spaced\Object $obj */ ?>
<?php $obj = $this->get('obj') ?>

<div><?= $obj->getName() ?></div>
```

## Enable string-escapting even for objects through proxies

Instead of using $renderer->get('obj'), just use  $renderer->getObject('obj').

index.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>
<?php /* @var \Some\Name\Spaced\Object $obj */ ?>
<?php $obj = $this->getString('obj') ?>

<div><?= $obj->getName() ?></div>
```

## Layout-Support

index.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>

<?php $this->layout('layout', ['title' => 'My Site']) ?>
```

layout.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>

<html>
	<head>
		<title>MySite<?php if($this->has('title')): ?> - <?= $this->getString('title') ?><?php endif ?></title>
	</head>
	<body>
		<?php $this->get('content') ?>
	</body>
</html>
```

