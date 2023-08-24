<?php
namespace deflou\interfaces\templates\contexts;

interface IContextHtml extends IContext
{
    public const NAME = 'html';
    public const FIELD__RENDER = 'render';
    public const FIELD__PARAM = 'param';

    public const PARAM__APPLICATION_NAMES = 'app_names';
    public const PARAM__APPLY_TO = 'apply_to';

    public const RESULT__HEADER = 'header';
    public const RESULT__ITEMS = 'items';
}
