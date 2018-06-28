<?php

namespace Modules\Meta\Models;

use Modules\Lang\Fields\Orm\LangCharField;
use Phact\Orm\Fields\CharField;

class LangMetaUrl extends MetaModel
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
			'url' => [
				'class' => CharField::class,
				'label' => 'URL'
			]
		];
	}
}
