<?php
namespace deflou\components\templates\contexts;

use deflou\interfaces\templates\contexts\IContextHtml;
use extas\interfaces\parameters\IParam;

/**
 * config = [
 *      render => <Render object>
 * ];
 */
trait TContextHtml
{
    protected function initHtmlContext(array $config = []): void
    {
        if (!isset($config[IContextHtml::FIELD__PARAMS])) {
            $config[IContextHtml::FIELD__PARAMS] = [];
        }

        if (isset($config[IContextHtml::FIELD__RENDER])) {
            $config[IContextHtml::FIELD__PARAMS][IContextHtml::FIELD__RENDER] = [
                IParam::FIELD__NAME => IContextHtml::FIELD__RENDER,
                IParam::FIELD__TITLE => 'Render',
                IParam::FIELD__VALUE => $config[IContextHtml::FIELD__RENDER]
            ];
        }

        $config[IContextHtml::FIELD__NAME] = IContextHtml::NAME;

        parent::__construct($config);
    }
}
