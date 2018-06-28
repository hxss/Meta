<?php

namespace Modules\Meta\Admin;

use Modules\Admin\Contrib\BoundAdmin;
use Modules\Meta\Forms\LangMetaBoundForm;
use Modules\Meta\Forms\MetaBoundForm;
use Modules\Meta\Interfaces\DefaultMetaInterface;
use Modules\Meta\Models\LangMetaBound;

class LangMetaBoundAdmin extends MetaBoundAdmin
{
	public static $ownerAttribute = 'bound';

	public function getSearchColumns()
	{
		return ['title', 'keywords', 'description'];
	}

	public function getModel()
	{
		return new LangMetaBound;
	}

	public static function getName()
	{
		return 'Metadata localised';
	}

	public static function getItemName()
	{
		return 'Metadata localised';
	}

	public function fetchModel($ownerInstance)
	{
		return LangMetaBound::getOrCreate($ownerInstance);
	}

	public function getForm()
	{
		return new LangMetaBoundForm();
	}

	public function save($form, $ownerInstance)
	{
		$safe = [
			'object_pk' => $ownerInstance->pk,
			'object_class' => $ownerInstance->className()
		];
		$titleCurrLangName = $form->getModel()->getField('title')->getCurrentLangName();
		if (!$form->getField($titleCurrLangName)->getValue()) {
			if ($ownerInstance instanceof DefaultMetaInterface) {
				$safe[$titleCurrLangName] = $ownerInstance->getMetaTitle();
				if (!$form->getField('description')->getValue()) {
					$safe['description'] = $ownerInstance->getMetaDescription();
				}
				if (!$form->getField('keywords')->getValue()) {
					$safe['keywords'] = $ownerInstance->getMetaKeywords();
				}
			} else {
				$safe[$titleCurrLangName] = (string) $ownerInstance;
			}
		}
		$form->save($safe);
	}
}
