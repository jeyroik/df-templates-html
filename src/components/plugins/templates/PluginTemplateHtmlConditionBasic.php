<?php
namespace deflou\components\plugins\templates;

use deflou\components\triggers\values\plugins\PluginNow;
use deflou\interfaces\templates\contexts\IContextHtml;
use extas\interfaces\parameters\IParam;

/**
 * In a plugin conf:
 * {
 *  "class": "gosp\\webhooks\\components\\plugins\\triggers\\PluginTemplateHtmlConditionBasic",
 *  "stage": "deflou.template.html.basic_condition",
 *  "params": {
 *      "header": { // PluginTemplateHtmlEvent::PARAM__VIEW_HEADER
 *          "name": "header",
 *          "value": "/path/to/header/view"
 *      },
 *      "item": {// PluginTemplateHtmlEvent::PARAM__VIEW_ITEM
 *          "name": "item",
 *          "value": "/path/to/item/view"
 *      },
 *      "items": {// PluginTemplateHtmlEvent::PARAM__VIEW_ITEMS
 *          "name": "items",
 *          "value": "/path/to/items/view"
 *      }
 *  }
 * }
 * 
 * In a context:
 * {
 *  "name": "html", // deflou\interfaces\triggers\ITemplateHtml::NAME
 *  "params": {
 *      "render": {// deflou\interfaces\triggers\ITemplateHtml::FIELD__RENDER
 *          "name": "render",
 *          "value": <render>
 *      },
 *      "param": {// current operation param object
 *          "name": "param",
 *          "value": <param>
 *      }
 *  }
 * }
 */
class PluginTemplateHtmlConditionBasic extends PluginTemplateHtml 
{
    public const STAGE = self::STAGE__PREFIX . 'basic_conditions';
    
    protected function renderEachItem($templateData, $contextParam, $render, $data): array
    {
        $items = [];

        foreach ($templateData as $condition) {
            $curData = [
                IContextHtml::FIELD__PARAM => $contextParam,
                IParam::FIELD__NAME => $condition->getName(),
                IParam::FIELD__TITLE => $condition->getTitle(),
                IParam::FIELD__DESCRIPTION => $condition->getDescription()
            ];
            $this->applyItemData($data, $curData);
            $items[] = $render->render($this->itemViewPath, $curData);
        }

        return $items;
    }
}
