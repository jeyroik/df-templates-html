<?php

use deflou\components\applications\AppWriter;
use deflou\components\instances\InstanceService;
use deflou\components\plugins\templates\PluginTemplateHtml;
use deflou\components\plugins\templates\PluginTemplateHtmlEvent;
use deflou\components\plugins\templates\PluginTemplateHtmlNow;
use deflou\components\plugins\templates\PluginTemplateHtmlText;
use deflou\components\templates\contexts\ContextHtmlTrigger;
use deflou\components\templates\TemplateService;
use deflou\components\templates\WithTemplate;
use deflou\components\triggers\ETrigger;
use deflou\components\triggers\values\plugins\PluginEvent;
use deflou\components\triggers\TriggerService;
use deflou\interfaces\templates\contexts\IContextHtml;
use deflou\interfaces\triggers\events\ITriggerEvent;
use extas\components\parameters\Param;
use extas\components\plugins\Plugin;
use extas\components\systems\options\SystemOption;
use extas\components\systems\System;
use extas\interfaces\parameters\IParam;
use tests\ExtasTestCase;
use tests\resources\TestPluginEvent;
use tests\resources\TestRender;

class TemplateHtmlTest extends ExtasTestCase
{
    protected array $libsToInstall = [
        'jeyroik/df-triggers' => ['php', 'php'],
        'jeyroik/df-applications' => ['php', 'json'],
        'jeyroik/extas-system-options' => ['php', 'php'],
        'jeyroik/df-templates' => ['php', 'php']
        //'vendor/lib' => ['php', 'json'] storage ext, extas ext
    ];
    protected bool $isNeedInstallLibsItems = true;
    protected string $testPath = __DIR__;

    public function testEventAndNowTemplate()
    {
        $appService = new AppWriter();
        $app = $appService->createAppByConfigPath(__DIR__ . '/../resources/app.json', true);
        
        $iService = new InstanceService();
        $instance = $iService->createInstanceFromApplication($app, 'test-vendor');

        $tService = new TriggerService();
        $trigger = $tService->createTriggerForInstance($instance, 'test-vendor');
        $trigger->setInstanceId(ETrigger::Operation, $instance->getId());
        $trigger->setApplicationId(ETrigger::Operation, $app->getId());
        $tService->triggers()->update($trigger);
        $trigger = $tService->insertEvent($trigger->getId(), [
            ITriggerEvent::FIELD__NAME => 'test_event'
        ]);

        $system = new System();
        $system->systemOptions()->create(new SystemOption([
            SystemOption::FIELD__NAME => PluginTemplateHtml::SYS_OPTION__HEADER,
            SystemOption::FIELD__VALUE => __DIR__ . '/../resources/header.php'
        ]));
        $system->systemOptions()->create(new SystemOption([
            SystemOption::FIELD__NAME => PluginTemplateHtml::SYS_OPTION__ITEM_BADGE,
            SystemOption::FIELD__VALUE => __DIR__ . '/../resources/item.php'
        ]));
        $system->systemOptions()->create(new SystemOption([
            SystemOption::FIELD__NAME => PluginTemplateHtml::SYS_OPTION__ITEMS_BADGE,
            SystemOption::FIELD__VALUE => __DIR__ . '/../resources/items.php'
        ]));

        $opService = new TemplateService();
        $opService->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginTemplateHtmlEvent::class,
            Plugin::FIELD__STAGE => PluginTemplateHtmlEvent::STAGE,
            Plugin::FIELD__PARAMETERS => [
                PluginTemplateHtmlEvent::PARAM__VIEW_HEADER => [
                    IParam::FIELD__NAME => PluginTemplateHtmlEvent::PARAM__VIEW_HEADER,
                    IParam::FIELD__VALUE => PluginTemplateHtmlEvent::VIEW__DEFAULT
                ],
                PluginTemplateHtmlEvent::PARAM__VIEW_ITEM => [
                    IParam::FIELD__NAME => PluginTemplateHtmlEvent::PARAM__VIEW_ITEM,
                    IParam::FIELD__VALUE => PluginTemplateHtmlEvent::SYS_OPTION__ITEM_BADGE
                ],
                PluginTemplateHtmlEvent::PARAM__VIEW_ITEMS => [
                    IParam::FIELD__NAME => PluginTemplateHtmlEvent::PARAM__VIEW_ITEMS,
                    IParam::FIELD__VALUE => PluginTemplateHtmlEvent::SYS_OPTION__ITEMS_BADGE
                ],
                PluginEvent::NAME => [
                    IParam::FIELD__NAME => PluginEvent::NAME,
                    IParam::FIELD__VALUE => '@event.@item.name'
                ]
            ]
        ]));
        $opService->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginTemplateHtmlNow::class,
            Plugin::FIELD__STAGE => PluginTemplateHtmlNow::STAGE,
            Plugin::FIELD__PARAMETERS => [
                PluginTemplateHtmlNow::PARAM__VIEW_HEADER => [
                    IParam::FIELD__NAME => PluginTemplateHtmlNow::PARAM__VIEW_HEADER,
                    IParam::FIELD__VALUE => __DIR__ . '/../resources/header.php'
                ],
                PluginTemplateHtmlNow::PARAM__VIEW_ITEM => [
                    IParam::FIELD__NAME => PluginTemplateHtmlNow::PARAM__VIEW_ITEM,
                    IParam::FIELD__VALUE => __DIR__ . '/../resources/item.php'
                ],
                PluginTemplateHtmlNow::PARAM__VIEW_ITEMS => [
                    IParam::FIELD__NAME => PluginTemplateHtmlNow::PARAM__VIEW_ITEMS,
                    IParam::FIELD__VALUE => __DIR__ . '/../resources/items.php'
                ]
            ]
        ]));
        $opService->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginTemplateHtmlText::class,
            Plugin::FIELD__STAGE => PluginTemplateHtmlText::STAGE,
            Plugin::FIELD__PARAMETERS => [
                PluginTemplateHtmlText::PARAM__VIEW_HEADER => [
                    IParam::FIELD__NAME => PluginTemplateHtmlText::PARAM__VIEW_HEADER,
                    IParam::FIELD__VALUE => __DIR__ . '/../resources/header.php'
                ],
                PluginTemplateHtmlText::PARAM__VIEW_ITEM => [
                    IParam::FIELD__NAME => PluginTemplateHtmlText::PARAM__VIEW_ITEM,
                    IParam::FIELD__VALUE => __DIR__ . '/../resources/item.php'
                ],
                PluginTemplateHtmlText::PARAM__VIEW_ITEMS => [
                    IParam::FIELD__NAME => PluginTemplateHtmlText::PARAM__VIEW_ITEMS,
                    IParam::FIELD__VALUE => __DIR__ . '/../resources/items.php'
                ],
                PluginTemplateHtmlText::PARAM__TITLE => [
                    IParam::FIELD__NAME => PluginTemplateHtmlText::PARAM__TITLE,
                    IParam::FIELD__VALUE => 'Введите текст для @param.title'
                ]
            ]
        ]));

        $opService->withTemplates()->create(new WithTemplate([
            WithTemplate::FIELD__NAME => 'event',
            WithTemplate::FIELD__TITLE => 'Данные из события',
            WithTemplate::FIELD__DESCRIPTION => 'Подставить данные из события',
            WithTemplate::FIELD__APPLICATION_NAME => 'test',
            WithTemplate::FIELD__APPLY_TO_PARAM => ['*'],
            WithTemplate::FIELD__CLASS => TestPluginEvent::class
        ]));

        $opService->withTemplates()->create(new WithTemplate([
            WithTemplate::FIELD__NAME => 'now',
            WithTemplate::FIELD__TITLE => 'Текущие время и дата',
            WithTemplate::FIELD__DESCRIPTION => 'Подставить текущее время и/или дату',
            WithTemplate::FIELD__APPLICATION_NAME => 'test',
            WithTemplate::FIELD__APPLY_TO_PARAM => ['*'],
            WithTemplate::FIELD__CLASS => TestPluginEvent::class
        ]));

        $opService->withTemplates()->create(new WithTemplate([
            WithTemplate::FIELD__NAME => 'text',
            WithTemplate::FIELD__TITLE => 'Текст',
            WithTemplate::FIELD__DESCRIPTION => 'Вставить какой-либо текст',
            WithTemplate::FIELD__APPLICATION_NAME => 'test',
            WithTemplate::FIELD__APPLY_TO_PARAM => ['*'],
            WithTemplate::FIELD__CLASS => TestPluginEvent::class
        ]));

        $result = $opService->getTemplates($opService->withTemplates(), new ContextHtmlTrigger([
            ContextHtmlTrigger::FIELD__RENDER => new TestRender(),
            ContextHtmlTrigger::FIELD__PARAMS => [
                ContextHtmlTrigger::PARAM__TRIGGER => [
                    IParam::FIELD__NAME => ContextHtmlTrigger::PARAM__TRIGGER,
                    IParam::FIELD__VALUE => $trigger
                ],
                ContextHtmlTrigger::PARAM__FOR => [
                    IParam::FIELD__NAME => ContextHtmlTrigger::PARAM__FOR,
                    IParam::FIELD__VALUE => ETrigger::Operation
                ],
                ContextHtmlTrigger::FIELD__PARAM => [
                    IParam::FIELD__NAME => ContextHtmlTrigger::FIELD__PARAM,
                    IParam::FIELD__VALUE => new Param([
                        Param::FIELD__TITLE => 'Some param'
                    ])
                ]
            ]
        ]));

        $this->assertIsArray($result);
        $this->assertArrayHasKey('event', $result);

        $eventResult = $result['event'];
        $this->assertIsArray($eventResult);
        
        $this->assertArrayHasKey(IContextHtml::RESULT__HEADER, $eventResult, 'Missed header in a result');
        $this->assertEquals(file_get_contents(__DIR__ . '/../resources/event.rendered.header.html'), $eventResult['header']);

        $this->assertArrayHasKey(IContextHtml::RESULT__ITEMS, $eventResult, 'Missed items in a result');
        $this->assertEquals(file_get_contents(__DIR__ . '/../resources/event.rendered.items.html'), $eventResult['items']);

        $this->assertArrayHasKey('now', $result);
        $nowResult = $result['now'];
        $this->assertIsArray($nowResult);
        
        $this->assertArrayHasKey(IContextHtml::RESULT__HEADER, $nowResult, 'Missed header in a result');
        $this->assertEquals(file_get_contents(__DIR__ . '/../resources/now.rendered.header.html'), $nowResult['header']);

        $this->assertArrayHasKey(IContextHtml::RESULT__ITEMS, $nowResult, 'Missed items in a result');
        $this->assertStringContainsString(
            file_get_contents(__DIR__ . '/../resources/now.rendered.items.html'), 
            $nowResult['items']
        );

        $textResult = $result['text'];
        $this->assertIsArray($textResult);
        
        $this->assertArrayHasKey(IContextHtml::RESULT__HEADER, $textResult, 'Missed header in a result');
        $this->assertEquals(file_get_contents(__DIR__ . '/../resources/text.rendered.header.html'), $textResult['header']);

        $this->assertArrayHasKey(IContextHtml::RESULT__ITEMS, $textResult, 'Missed items in a result');
        $this->assertEquals(file_get_contents(__DIR__ . '/../resources/text.rendered.items.html'), $textResult['items']);
    }
}
