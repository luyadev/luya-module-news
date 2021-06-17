<?php

namespace luya\news\tests\admin;

use luya\testsuite\cases\NgRestTestCase;

class NgRestArticleTest extends NgRestTestCase
{
    public $modelClass = 'luya\news\models\Article';
 
    public $apiClass = 'luya\news\admin\apis\ArticleController';

    public $controllerClass = 'luya\news\admin\controllers\ArticleController';

    public function getConfigArray()
    {
        return [
            'id' => 'articletest',
            'basePath' => dirname(__DIR__),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
            ],
            'modules' => [
                'newsfrontend' => [
                    'class' => 'luya\news\frontend\Module',
                    'useAppViewPath' => false,
                ],
                'newsadmin' => 'luya\news\admin\Module',
            ],
        ];
    }
}
