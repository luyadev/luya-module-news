<p align="center">
  <img src="https://raw.githubusercontent.com/luyadev/luya/master/docs/logo/luya-logo-0.2x.png" alt="LUYA Logo"/>
</p>

# News Module

[![LUYA](https://img.shields.io/badge/Powered%20by-LUYA-brightgreen.svg)](https://luya.io)
[![Latest Stable Version](https://poser.pugx.org/luyadev/luya-module-news/v/stable)](https://packagist.org/packages/luyadev/luya-module-news)
[![Build Status](https://travis-ci.org/luyadev/luya-module-news.svg?branch=master)](https://travis-ci.org/luyadev/luya-module-news)
[![Maintainability](https://api.codeclimate.com/v1/badges/7ba026bc02e251fa1d63/maintainability)](https://codeclimate.com/github/luyadev/luya-module-news/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7ba026bc02e251fa1d63/test_coverage)](https://codeclimate.com/github/luyadev/luya-module-news/test_coverage)
[![Total Downloads](https://poser.pugx.org/luyadev/luya-module-news/downloads)](https://packagist.org/packages/luyadev/luya-module-news)
[![Slack Support](https://img.shields.io/badge/Slack-luyadev-yellowgreen.svg)](https://slack.luya.io/)

The news module will provided you a basic news system with categories and tags.

## Installation

For the installation of modules Composer is required.

```sh
composer require luyadev/luya-module-news
```

### Configuration

After installation via Composer include the module to your configuration file within the modules section.

```php
'modules' => [
    // ...
    'news' => [
    	'class' => 'luya\news\frontend\Module',
    	'useAppViewPath' => false, // When enabled the views will be looked up in the @app/views folder, otherwise the views shipped with the module will be used.
    ],
    'newsadmin' => 'luya\news\admin\Module',
]
```

### Initialization 

After successfully installation and configuration run the migrate, import and setup command to initialize the module in your project.

1.) Migrate your database.

```sh
./vendor/bin/luya migrate
```

2.) Import the module and migrations into your LUYA project.

```sh
./vendor/bin/luya import
```

3.) Add the permissions to your group (Under "System -> Groups" in the LUYA admin interface)

You will now be able to edit and add news articles.

With version 2.0 of news module, the admin queue is required in order to switch the visibility of the news based on time, therefore read [LUYA Queue](https://luya.io/guide/app-queue) or enable:

```php
'modules' => [
    'admin' => [
        'class' => 'luya\admin\Module',
        // ...
        'autoBootstrapQueue' => true,
    ]
]
```

## Example Views

As the module will try to render a view for the news overview, here is what this could look like this in a very basic way:

#### views/news/default/index.php

```php
<?php
use yii\widgets\LinkPager;

/* @var $this \luya\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
?>
<h2>Latest News Articles</h2>
<?php foreach($provider->models as $item): ?>
    <?php /* @var $item \luya\news\models\Article */ ?>
    <pre>
        <?php print_r($item->toArray()); ?>
    </pre>
    <p>
        <a href="<?= $item->detailUrl; ?>">News Detail Link</a>
    </p>
<?php endforeach; ?>

<?= LinkPager::widget(['pagination' => $provider->pagination]); ?>
```

#### views/news/default/detail.php

```php
<?php
/* @var $this \luya\web\View */
/* @var $model \luya\news\models\Article */
?>
<h1><?= $model->title; ?></h1>
<pre>
<?php print_r($model->toArray()); ?>
</pre>
```

The above examples will just dump all the data from the model active records.
