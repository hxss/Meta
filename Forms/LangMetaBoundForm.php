<?php

namespace Modules\Meta\Forms;

use Modules\Manage\Forms\GroupModelForm;
use Modules\Meta\Models\LangMetaBound;
use Modules\Meta\Models\MetaBound;

class LangMetaBoundForm extends GroupModelForm
{
	public function getModel()
	{
		return new LangMetaBound;
	}
}
