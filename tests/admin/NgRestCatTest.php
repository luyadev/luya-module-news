<?php

namespace luya\news\tests\admin;

use luya\testsuite\cases\NgRestTestCase;

class NgRestCatTest extends NgRestTestCase
{
    public $modelClass = 'luya\news\models\Cat';
 
    public $apiClass = 'luya\news\admin\apis\CatController';

    public $controllerClass = 'luya\news\admin\controllers\CatController';

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
