<?php

namespace luya\news\admin\aws;

use luya\admin\ngrest\base\ActiveWindow;
use luya\cms\helpers\Url;
use luya\news\admin\Module;

class PreviewActiveWindow extends ActiveWindow
{
    public $module = 'newsadmin';

    /**
     * Default label if not set in the ngrest model.
     *
     * @return string The name of of the ActiveWindow. This is displayed in the CRUD list.
     */
    public function defaultLabel()
    {
        return Module::t('preview');
    }

    /**
     * Default icon if not set in the ngrest model.
     *
     * @var string The icon name from goolges material icon set (https://material.io/icons/)
     */
    public function defaultIcon()
    {
        return 'preview';    
    }

    public function getTitle()
    {
        return $this->model->title;
    }
    
    public function index()
    {
        return $this->render("index", [
            'model' => $this->model,
            'url' => Url::toModuleRoute('news', ['news/default/preview', 'id' => $this->model->id, 'hash' => $this->model->previewHash, 'time' => time()], true)
        ]);
    }
}