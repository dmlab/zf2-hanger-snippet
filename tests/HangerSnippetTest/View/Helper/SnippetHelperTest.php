<?php

namespace HangerSnippet\View\Helper;

use Zend\View\Renderer\PhpRenderer;

/**
 * Class SnippetHelperTest
 * @author Leonardo Grasso <me@leonardograsso.com>
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class SnippetHelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The Helper
     * @var \HangerSnippet\View\Helper\SnippetHelper
     */
    public $helper;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->helper = new SnippetHelper();
        $view = new PhpRenderer();
        $view->resolver()->addPath(__DIR__ . '/_files/modules');
        $this->helper->setView($view);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($this->helper);
    }

    /**
     * Test Append Snippet
     */
    public function testAppendSnippet()
    {
        $this->helper->appendSnippet('appended', 'hanger-snippet/test', ['foo' => 'ABC']);
        $this->assertCount(1, $this->helper->getSnippets());
    }

    /**
     * Test Enable Snippet
     */
    public function testEnableSnippet()
    {
        $this->helper->appendSnippet('test', 'hanger-snippet/test', ['foo' => 'ABC']);
        $this->helper->setEnabled('test');
        $this->assertTrue($this->helper->getEnabledSnippets()['test']);
    }

    /**
     * Test Enable All Snippets
     */
    public function testEnableAllSnippets()
    {
        foreach ($this->snippetsProvider() as $snippet) {
            $this->helper->appendSnippet($snippet[0], $snippet[1], $snippet[2]);
        }
        $this->helper->setEnableAll();


        foreach ($this->snippetsProvider() as $snippet) {
            $this->assertTrue($this->helper->getEnabledSnippets()[$snippet[0]]);
        }
    }

    /**
     * Test Disable All Snippets
     */
    public function testDisableAllSnippets()
    {
        foreach ($this->snippetsProvider() as $snippet) {
            $this->helper->appendSnippet($snippet[0], $snippet[1], $snippet[2]);
        }
        $this->helper->setDisableAll();


        foreach ($this->snippetsProvider() as $snippet) {
            $this->assertFalse($this->helper->getEnabledSnippets()[$snippet[0]]);
        }
    }

    /**
     * Test Disable Snippet
     */
    public function testDisableSnippet()
    {
        $this->helper->appendSnippet('test', 'hanger-snippet/test', ['foo' => 'ABC']);
        $this->helper->setDisabled('test');
        $this->assertFalse($this->helper->getEnabledSnippets()['test']);
    }

    /**
     * @dataProvider snippetsProvider
     */
    public function testRender($name, $template, $values, $expected)
    {
        $this->helper->appendSnippet($name, $template, $values);
        $return = $this->helper->renderSnippet($name);
        $this->assertEquals($expected, $return);
    }

    /**
     * Test Render All
     */
    public function testRenderAll()
    {
        $snippetExpected = [];
        foreach ($this->snippetsProvider() as $snippet) {
            $this->helper->appendSnippet($snippet[0], $snippet[1], $snippet[2]);
            $snippetExpected[] = $snippet[3];
        }
        $expected = implode(PHP_EOL, $snippetExpected);
        $return = $this->helper->render();
        $this->assertEquals($expected, $return);
    }


    /**
     * @dataProvider snippetsProvider
     */
    public function testToString($name, $template, $values, $expected)
    {
        $this->helper->appendSnippet($name, $template, $values);
        $return = $this->helper->toString();
        $this->assertEquals($expected, $return);
    }


    /**
     * @dataProvider snippetsProvider
     */
    public function testInvoke($name, $template, $values, $expected)
    {
        $this->helper->appendSnippet($name, $template, $values);
        $invoke = $this->helper->__invoke();
        $this->assertInstanceOf('\HangerSnippet\View\Helper\SnippetHelper', $invoke);
        $this->assertCount(1, $invoke->getSnippets());
    }


    /**
     * Snippets Data Provider
     * @return array
     */
    public function snippetsProvider()
    {
        $testSnippet = <<<HTML
<script type="text/javascript">
    <!--
    var foo = 'abc';
    -->
</script>
HTML;

        $anotherTestSnippet = <<<HTML
<script type="text/javascript">
    <!--
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'FOOOOOOO', 'BAAAAAAAARRRRR');
    ga('send', 'pageview');
    -->
</script>
HTML;

        return [
            [
                'test',
                'hanger-snippet/test',
                [
                    'foo' => 'abc'
                ],
                $testSnippet
            ],
            [
                'another',
                'hanger-snippet/anothertest',
                [
                    'foo' => 'FOOOOOOO',
                    'bar' => 'BAAAAAAAARRRRR'
                ],
                $anotherTestSnippet
            ]
        ];
    }
}
