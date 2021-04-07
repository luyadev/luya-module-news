<?php

namespace luya\news\tests\frontend\controllers;

use luya\news\frontend\controllers\DefaultController;
use luya\news\models\Article;
use luya\news\tests\NewsTest;
use luya\testsuite\fixtures\NgRestModelFixture;

class DefaultControllerTest extends NewsTest
{
    public function afterSetup()
    {
        parent::afterSetup();
        $this->articleFixture = new NgRestModelFixture([
            'modelClass' => Article::class,
        ]);
    }

    public function testIndex()
    {
        $ctrl = new DefaultController('default', $this->app->getModule('newsfrontend'));
        $ctrl->layout = false;
        $this->assertNotEmpty($ctrl->actionIndex());
    }
}