<?php

namespace Modules\Meta\Models;

use Modules\Lang\Fields\Orm\LangCharField;
use Modules\Meta\Models\LangMetaTemplate;
use Phact\Orm\Fields\CharField;

class LangMetaTemplateAutoKey extends LangMetaTemplate
{
	public static function getFields()
	{
		$fields = parent::getFields();
		return array_merge_recursive($fields, [
			'key' => [
				'editable' => false,
				'null' => true,
			]
		]);
	}

	public static function getTableName()
	{
		return (new parent)->getTableName();
	}
}
