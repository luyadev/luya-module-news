<?php

namespace luya\news\models;

use Yii;
use yii\helpers\Inflector;
use luya\helpers\Url;
use luya\news\admin\Module;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\traits\SoftDeleteTrait;
use luya\admin\traits\TaggableTrait;
use luya\admin\aws\TaggableActiveWindow;
use luya\admin\buttons\DuplicateActiveButton;
use luya\admin\models\User;
use luya\news\admin\aws\PreviewActiveWindow;
use luya\web\LinkInterface;

/**
 * News Article
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $cat_id
 * @property string $image_id
 * @property array $image_list
 * @property array $file_list
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property integer $timestamp_create
 * @property integer $timestamp_update
 * @property boolean $is_deleted
 * @property boolean $is_online
 * @property string $teaser_text
 * @property string $detailUrl Return the link to the detail url of a news item.
 * @property string $author
 * @property LinkInterface $link
 * @property string $authorName
 * @property Cat $cat
 * @property User $createUser
 * @property User $updateUser
 * @property string $previewHash
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Article extends NgRestModel
{
    use SoftDeleteTrait;
    use TaggableTrait;
    
    public $i18n = ['title', 'text', 'teaser_text', 'image_list', 'link'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_article';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'eventBeforeInsert']);
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'eventBeforeUpdate']);
    }

    public function eventBeforeUpdate()
    {
        $this->update_user_id = Yii::$app->adminuser->getId();
        $this->timestamp_update = time();
    }
    
    public function eventBeforeInsert()
    {
        $this->create_user_id = Yii::$app->adminuser->getId();
        $this->update_user_id = Yii::$app->adminuser->getId();
        $this->timestamp_update = time();
        if (empty($this->timestamp_create)) {
            $this->timestamp_create = time();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['title', 'text', 'image_list', 'file_list', 'teaser_text', 'author'], 'string'],
            [['cat_id', 'create_user_id', 'update_user_id', 'timestamp_create', 'timestamp_update'], 'integer'],
            [['cat_id'], 'exist', 'targetClass' => Cat::class, 'targetAttribute' => 'id'],
            [['is_deleted', 'is_online'], 'boolean'],
            [['image_id', 'link'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Module::t('article_title'),
            'text' => Module::t('article_text'),
            'teaser_text' => Module::t('teaser_text'),
            'cat_id' => Module::t('article_cat_id'),
            'image_id' => Module::t('article_image_id'),
            'timestamp_create' => Module::t('article_timestamp_create'),
            'is_online' => Module::t('article_is_online'),
            'image_list' => Module::t('article_image_list'),
            'file_list' => Module::t('article_file_list'),
            'author' => 'Author',
            'link' => 'Link',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'title' => 'text',
            'teaser_text' => ['textarea', 'markdown' => true],
            'text' => ['textarea', 'markdown' => true],
            'image_id' => 'image',
            'is_online'  => ['toggleStatus', 'scheduling' => true],
            'timestamp_create' => 'datetime',
            'is_display_limit' => 'toggleStatus',
            'image_list' => 'imageArray',
            'file_list' => 'fileArray',
            'cat_id' => ['selectModel', 'modelClass' => Cat::class, 'valueField' => 'id', 'labelField' => 'title'],
            'author' => 'text',
            'link' => 'link',
        ];
    }

    /**
     *
     * @return string
     */
    public function getDetailUrl()
    {
        return Url::toRoute(['/news/default/detail', 'id' => $this->id, 'title' => Inflector::slug($this->title)]);
    }

    /**
     * Get image object.
     *
     * @return \luya\admin\image\Item|boolean
     */
    public function getImage()
    {
        return Yii::$app->storage->getImage($this->image_id);
    }
    
    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-news-article';
    }
    
    /**
     * @inheritdoc
     */
    public function ngRestAttributeGroups()
    {
        return [
            [['timestamp_create', 'is_online'], 'Time', 'collapsed'],
            [['image_id', 'image_list', 'file_list'], 'Media'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            [['list'], ['cat_id', 'title', 'timestamp_create', 'is_online', 'image_id']],
            [['create', 'update'], ['cat_id', 'title', 'teaser_text', 'text', 'author', 'link', 'timestamp_create', 'is_online', 'image_id', 'image_list', 'file_list']],
            [['delete'], true],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function ngRestActiveWindows()
    {
        return [
            [
                'class' => TaggableActiveWindow::class,
            ],
            [
                'class' => PreviewActiveWindow::class,
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function ngRestActiveButtons()
    {
        return [
            [
                'class' => DuplicateActiveButton::class,
            ],
        ];
    }

    /**
     * Returns only available News items.
     * 
     * @param false|int $limit
     * @return Article
     */
    public static function getAvailableNews($limit = false)
    {
        $q = self::find()
            ->andWhere(['is_online' => true])
            ->orderBy('timestamp_create DESC');
        
        if ($limit) {
            $q->limit($limit);
        }
        
        $articles = $q->all();

        return $articles;
    }

    /**
     * @return Cat
     */
    public function getCat()
    {
        return $this->hasOne(Cat::class, ['id' => 'cat_id']);
    }
    
    /**
     * Returns the Category name
     *
     * @return string
     * @deprecated Since version 2.0 will be removed in 3.0
     */
    public function getCategoryName()
    {
        return $this->cat->title;
    }

    /**
     * Created User Relation
     *
     * @return User
     * @since 3.0
     */
    public function getCreateUser()
    {
        return $this->hasOne(User::class, ['id' => 'create_user_id']);
    }

    /**
     * Updated user Relation
     *
     * @return User
     * @since 3.0
     */
    public function getUpdateUser()
    {
        return $this->hasOne(User::class, ['id' => 'update_user_id']);
    }

    /**
     * Returns the Author Name.
     * 
     * If an author name is defined, the author is returned, otherwise the first name and last name
     * of the person which has created the article will be returned.
     *
     * @return string
     * @since 3.0
     */
    public function getAuthorName()
    {
        return $this->author ? $this->author : $this->createUser->firstname . ' ' . $this->createUser->lastname;
    }

    /**
     * Generate A preview hash with most static values
     *
     * @return string
     * @since 3.1.0
     */
    public function getPreviewHash()
    {
        return md5($this->timestamp_create . $this->create_user_id . $this->id);
    }
}
