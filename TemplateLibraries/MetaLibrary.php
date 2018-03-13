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
 * @date 16/02/17 13:03
 */
namespace Modules\Meta\TemplateLibraries;

use Phact\Main\Phact;
use Phact\Template\Renderer;
use Phact\Template\TemplateLibrary;

class MetaLibrary extends TemplateLibrary
{
    use Renderer;

    /**
     * @name render_meta
     * @kind function
     * @return string
     */
    public static function renderMeta($params)
    {
        $template = isset($params['template']) ? $params['template'] : 'meta/default.tpl';
        return self::renderTemplate($template, Phact::app()->meta->getData());
    }
}