<?php
namespace deflou\components\templates\contexts;

use deflou\interfaces\templates\contexts\IContextHtml;

class ContextHtmlTrigger extends ContextTrigger implements IContextHtml
{
    use TContextHtml;

    public function __construct(array $config = [])
    {
        $this->initHtmlContext($config);
    }
}
