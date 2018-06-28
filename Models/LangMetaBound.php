<?php

namespace Modules\Meta\Models;

use Modules\Lang\Fields\Orm\LangCharField;
use Modules\Meta\Models\MetaBound;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\IntField;
use Phact\Orm\Fields\TextField;

class LangMetaBound extends MetaBound
{
	public static function getFields()
	{
		return [
			'title' => [
				'class' => LangCharField::class,
				'label' => 'Title',
				'editable' => false,
				'null' => true,
				'primaryNull' => true,
				'secondaryNull' => true,
			],
			'description' => [
				'class' => LangCharField::class,
				'label' => 'Description',
				'editable' => false,
				'null' => true,
				'primaryNull' => true,
				'secondaryNull' => true,
			],
			'keywords' => [
				'class' => LangCharField::class,
				'label' => 'Keywords',
				'editable' => false,
				'null' => true,
				'primaryNull' => true,
				'secondaryNull' => true,
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

	public function beforeSave()
	{
		foreach (['title', 'description', 'keywords'] as $field)
			foreach ($this->getField($field)->getAdditionalFields() as $langField => $langFiledInfo)
				$this->{$langField} = $this->cut($this->{$langField});
	}
}
