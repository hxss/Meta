<?php

namespace Modules\Meta\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Meta\Models\LangMetaTemplate;

class LangMetaTemplateAdmin extends Admin
{
	public function getSearchColumns()
	{
		return ['title', 'keywords', 'description', 'key'];
	}

	public function getModel()
	{
		return new LangMetaTemplate;
	}

	public static function getName()
	{
		return 'Meta templates localised';
	}

	public static function getItemName()
	{
		return 'Meta templates localised';
	}
}
