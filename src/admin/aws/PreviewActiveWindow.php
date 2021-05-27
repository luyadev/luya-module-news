<?php

namespace luya\news\admin\aws;

use luya\admin\ngrest\base\ActiveWindow;
use luya\cms\helpers\Url;
use luya\news\admin\Module;

/**
 * Preview Active Window
 * 
 * @since 3.1.0
 * @author Basil Suter <git@nadar.io>
 */
class PreviewActiveWindow extends ActiveWindow
{
    public $module = 'newsadmin';

    /**
     * {@inheritDoc}
     */
    public function defaultLabel()
    {
        return Module::t('preview');
    }

    /**
     * {@inheritDoc}
     */
    public function defaultIcon()
    {
        return 'preview';    
    }

    /**
     * {@inheritDoc}
     */
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