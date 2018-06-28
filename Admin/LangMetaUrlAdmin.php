<?php

namespace Modules\Meta\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Meta\Models\LangMetaUrl;
use Modules\Meta\Models\MetaUrl;

class LangMetaUrlAdmin extends Admin
{
	public function getSearchColumns()
	{
		return ['title', 'keywords', 'description', 'url'];
	}

	public function getModel()
	{
		return new LangMetaUrl;
	}

	public static function getName()
	{
		return 'Meta URL localised';
	}

	public static function getItemName()
	{
		return 'Meta URL localised';
	}
}
