<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 16/02/17 13:34
 */
namespace Modules\Meta\Models;

use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\IntField;
use Phact\Orm\Fields\TextField;
use Phact\Orm\Model;

class MetaBound extends MetaModel
{
    public static function getFields()
    {
        return [
            'title' => [
                'class' => CharField::class,
                'label' => 'Title',
                'null' => true
            ],
            'description' => [
                'class' => CharField::class,
                'label' => 'Description',
                'null' => true
            ],
            'keywords' => [
                'class' => CharField::class,
                'label' => 'Keywords',
                'null' => true
            ],
            'object_class' => [
                'class' => CharField::class,
                'label' => 'Model class',
                'null' => true,
                'editable' => false
            ],
            'object_pk' => [
                'class' => IntField::class,
                'label' => 'Model id',
                'null' => true,
                'editable' => false
            ],
        ];
    }

    public static function fetch($object)
    {
        return static::objects()->filter([
            'object_pk' => $object->id,
            'object_class' => $object->className()
        ])->get();
    }

    public static function getOrCreate($object)
    {
        $model = static::fetch($object);
        if (!$model) {
            $model = new static();
            $model->object_pk = $object->id;
            $model->object_class = $object->className();
        }
        return $model;
    }

    public function beforeSave()
    {
        parent::beforeSave();
        $this->title = $this->cut($this->title);
        $this->description = $this->cut($this->description);
        $this->keywords = $this->cut($this->keywords);
    }

    public function cut($text)
    {
        return mb_substr($text, 0, 250, 'UTF-8') . (mb_strlen($text, 'UTF-8') > 250 ? '...' : '');
    }
}
