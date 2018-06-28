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
 * @date 16/02/17 08:45
 */

namespace Modules\Meta\Components;

use Modules\Main\TemplateLibraries\MainLibrary;
use Modules\Meta\Components\MetaComponent;
use Modules\Meta\Models\LangMetaBound;
use Modules\Meta\Models\LangMetaTemplate;
use Modules\Meta\Models\LangMetaUrl;
use Modules\Meta\Models\MetaBound;
use Modules\Meta\Models\MetaTemplate;
use Modules\Meta\Models\MetaUrl;
use Phact\Main\Phact;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\DateField;
use Phact\Orm\Fields\FileField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\NumericField;
use Phact\Orm\Model;

class LangMetaComponent extends MetaComponent
{

	public function useTemplate($key, $params = [])
	{
		$template = $this->getTemplate($key);
		if ($params instanceof Model) {
			$params = $this->prepareModelParams($params);
		}
		if ($template) {
			$this->templateUsed = true;
			foreach (['title', 'description', 'keywords'] as $name) {
				$this->{$name} = strtr($template->{$name}, $params);
			}
		}
	}

	public function useModel(Model $model)
	{
		$boundClass = $this->getModelBoud($model);
		$bound = $boundClass::fetch($model);
		if ($bound) {
			foreach (['title', 'description', 'keywords'] as $field) {
				$this->{$field} = $bound->getField($field)->getValue();
			}
		}
		if (method_exists($model, 'getAbsoluteUrl')) {
			$this->setCanonical($model->getAbsoluteUrl());
		}
	}

	public function useModelOrTemplate(Model $model, $tmpName = null)
	{
		$tmpName = $tmpName ?? get_class($model);
		$template = $this->getTemplate($tmpName);
		$params = $this->prepareModelParams($model);

		$boundClass = $this->getModelBoud($model);
		$bound = $boundClass::fetch($model);

		foreach (['title', 'description', 'keywords'] as $field) {
			$this->{$field} = $bound
				&& ($boundVal = $bound->getField($field)->getValue())
				? $boundVal
				: ($template
					? strtr($template->{$field}, $params)
					: ''
				);
		}

		if (method_exists($model, 'getAbsoluteUrl')) {
			$this->setCanonical($model->getAbsoluteUrl());
		}
	}

	public function getModelBoud(Model $model) {
		$fullModelClass = get_class($model);
		if (preg_match('/^Modules\\\\(?P<module>\w+)\\\\Models\\\\(?P<model>\w+)/', $fullModelClass, $matches)) {
			$module = $matches['module'];
			$class = $matches['model'];
			$fullAdminClass = "Modules\\{$module}\\Admin\\{$class}Admin";

			$admin = new $fullAdminClass;
			$bounds = $admin->getBoundAdmins();

			if (!isset($bounds['meta'])) {
				throw new \Exception("Meta bounds not found in {$fullAdminClass}", 1);
			}

			return (new $bounds['meta'])->getModel();
		}
	}

	public function getData()
	{
		$fallback = true;
		if (!$this->templateUsed) {
			$url = Phact::app()->request->getPath();
			$meta = LangMetaUrl::objects()->filter([
				'url' => $url
			])->get();
			if ($meta) {
				$fallback = false;
				foreach (['title', 'description', 'keywords'] as $field) {
					$this->{$field} = $meta->getField($field)->getValue();
				}
				$this->setCanonical($meta->url);
			}
		}
		if ($fallback && $this->breadcrumbsFallback && !$this->getTitle()) {
			$breadcrumbs = Phact::app()->hasComponent('breadcrumbs') ? Phact::app()->breadcrumbs : null;
			if ($breadcrumbs) {
				$list = $breadcrumbs->get();
				$items = [];
				foreach ($list as $item) {
					$items[] = $item['name'];
				}
				$items = array_reverse($items);
				$delimiter = Phact::app()->settings->get('Meta.delimiter');
				$this->setTitle(implode($delimiter, $items));
			}
		}
		return parent::getData();
	}

	public function getTemplate($key) {
		return LangMetaTemplate::objects()->filter([
			'key' => $key
		])->get() ?? MetaTemplate::objects()->filter([
			'key' => $key
		])->get();
	}

	public function prepareModelParams($model)
	{
		$params = parent::prepareModelParams($model);

		foreach ($params as &$param) {
			$param = MainLibrary::clean($param);
		}

		return $params;
	}

}
