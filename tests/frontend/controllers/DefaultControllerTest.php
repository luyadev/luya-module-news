<?php

namespace luya\news\tests\frontend\controllers;

use luya\news\frontend\controllers\DefaultController;
use luya\news\models\Article;
use luya\news\models\Cat;
use luya\news\tests\NewsTest;
use luya\testsuite\fixtures\NgRestModelFixture;
use luya\testsuite\traits\AdminDatabaseTableTrait;

class DefaultControllerTest extends NewsTest
{
    use AdminDatabaseTableTrait;

    public function afterSetup()
    {
        parent::afterSetup();

        $this->articleFixture = new NgRestModelFixture([
            'modelClass' => Article::class,
            'fixtureData' => [
                1 => [
                    'id' => 1,
                    'title' => 'Title',
                    'text' => 'Text',
                    'is_online' => 1,
                    'is_deleted' => 0,
                    'author' => 'foo',
                ]
            ]
        ]);

        $this->catFixture = new NgRestModelFixture([
            'modelClass' => Cat::class,
            'fixtureData' => [
                1 => [
                    'id' => 1,
                    'title' => 'Titel'
                ]
            ]
        ]);

        $this->createAdminLangFixture([
            1 => [
                'id' => 1,
                'name' => 'de',
                'short_code' => 'de',
                'is_default' => 1,
                'is_deleted' => 0,
            ]
        ]);
    }

    public function testActions()
    {
        $ctrl = new DefaultController('default', $this->app->getModule('newsfrontend'));

        $model = Article::findOne(['id' => 1]);
        
        $ctrl->layout = false;
        $this->assertNotEmpty($ctrl->actionIndex());
        $this->assertNotEmpty($ctrl->actionArchive());
        $this->assertNotEmpty($ctrl->actionCategories(1));
        $this->assertNotEmpty($ctrl->actionCategory(1));
        $this->assertNotEmpty($ctrl->actionDetail(1, 'Title'));
        $this->assertNotEmpty($ctrl->actionPreview(1, $model->getPreviewHash()));
    }
}