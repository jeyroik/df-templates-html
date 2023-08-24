![tests](https://github.com/jeyroik/df-templates-html/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/df-templates-html/coverage.svg?branch=master)

[![Latest Stable Version](https://poser.pugx.org/jeyroik/df-templates-html/v)](//packagist.org/packages/jeyroik/df-templates-html)
[![Total Downloads](https://poser.pugx.org/jeyroik/df-templates-html/downloads)](//packagist.org/packages/jeyroik/df-templates-html)
[![Dependents](https://poser.pugx.org/jeyroik/df-templates-html/dependents)](//packagist.org/packages/jeyroik/df-templates-html)


# df-templates-html

HTML templates for DeFlou

# usage

1. Install plugins for `deflou.template.html.<plugin.name>` stage.
   1. Ex.: `deflou.template.html.event` for event plugin.
2. Prepare context with `TContextHtml` trait. 
   1. Or use `ContextHtmlTriger` context.
3. Install `with-template` entities.
   1. Ex.: `df-triggers` values plugins.
4. Get templates with `TemplateService`.
   1. See tests for details.
