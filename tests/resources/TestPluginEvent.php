<?php
namespace tests\resources;

use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\templates\contexts\IContextTrigger;
use deflou\interfaces\templates\IDispatcher;
use deflou\interfaces\templates\IWithTemplate;
use deflou\interfaces\triggers\ITrigger;
use deflou\components\triggers\ETrigger;

class TestPluginEvent implements IDispatcher
{
    /**
     * 
     *
     * @param  IWithTemplate $templated
     * @param  IContextTrigger|IContext      $context
     * @return array
     */
    public function getTemplateData(IWithTemplate $templated, IContext $context): array
    {
        $params = $context->buildParams();

        /**
         * @var ITrigger $trigger
         */
        $trigger = $params->buildOne($context::PARAM__TRIGGER)->getValue();

        /**
         * @var ETrigger $et
         */
        $et = $params->buildOne($context::PARAM__FOR)->getValue();

        $instance = $trigger->getInstance($et);
        $subject = $instance->buildEvents()->buildOne($trigger->buildEvent()->getName());

        return $subject->buildParams()->buildAll();
    }
}
