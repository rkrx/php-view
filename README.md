php-view
========

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba0cd34d-fdd5-41d4-933f-ccce91693c9c/mini.png)](https://insight.sensiolabs.com/projects/ba0cd34d-fdd5-41d4-933f-ccce91693c9c)
[![Build Status](https://travis-ci.org/rkrx/php-view.svg?branch=master)](https://travis-ci.org/rkrx/php-view)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkrx/php-view/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkrx/php-view/?branch=master)

More secure and easy to use templating system for php5.4+. 

Design goals: 

* Interface-driven and dependency-injection-friendly
* Secure by default, unsecure if needed
* Lightweight, easy to understand and stable
* No extra scripting language. Use PHP to write templates.

## Jumpstart

You will need this somewhere to convert a template-file into a string:

```php
$factory = new FileViewFactory(__DIR__.'/path/to/my/template/folder');
$renderer = $factory->create('module');
$renderer->add('myVar', 1234);
$content = $renderer->render('action');
echo $content;
```

`FileViewFactory` implements an interface called `ViewFactory`. You can use this interface to Build your very own Factories that create different renderers and so on. This is especially useful, if you need a way to change the change the implementation some day. This is also useful it you use a Dependency Injection Container to wire your components together:

```php
class MyCtrl {
	/** @var ViewFactory */
	private $viewFactory;

	/**
	 * @param ViewFactory $viewFactory
	 */
	public function __construct(ViewFactory $viewFactory) {
		$this->viewFactory = $viewFactory;
	}

	/**
	 * @return string
	 */
	public function someAction() {
		$content = $this->viewFactory->create('module')
		->add('myVar', 1234)
		->render('action');
		return $content;
	}
}
```

## Use typehinting

In PHP-Templates, you can use typehinting which is recognized by IDEs like PHPStorm, ZendStudio or PDT (and maybe others). 

index.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>
<?php /* @var \Some\Name\Spaced\Object $obj */ ?>
<?php $obj = $this->get('obj') ?>

<div><?= $obj->getName() ?></div>
```

## Enable escaping even for objects and method-calls

Instead of using $renderer->get('obj'), just use  $renderer->getObject('obj').

index.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>
<?php /* @var \Some\Name\Spaced\Object $obj */ ?>
<?php $obj = $this->getObject('obj') ?>

<div><?= $obj->getName() ?></div>
```

## Layout-Support

index.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>

<?php $this->layout('layout', ['title' => 'My Site']) ?>

This will be part of the region "content".

<?php $this->region('left') ?>
This will be part of the region "left".
<?php $this->end() ?>
```

layout.phtml
```php
<?php /* @var \View\Workers\Worker $this */ ?>

<html>
	<head>
		<title>MySite<?php if($this->has('title')): ?> - <?= $this->getString('title') ?><?php endif ?></title>
	</head>
	<body>
		<div id="content">
			<?= $this->get('content') ?>
		</div>
		<div id="left">
			<?= $this->get('left') ?>
		</div>
	</body>
</html>
```

