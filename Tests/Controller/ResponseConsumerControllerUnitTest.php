<?php

namespace Madeyski\EpuapBundle\Tests\Controller;


use Madeyski\EpuapBundle\Controller\ResponseConsumerController;
use Madeyski\EpuapBundle\Security\Authentication\EpuapResponseConsumerInterface;
use Madeyski\EpuapBundle\Settings\CommonSignatureProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ResponseConsumerControllerUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EpuapResponseConsumerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $consumer;

    /**
     * @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $router;

    public function setUp()
    {
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $this->consumer = $this->getMock('Madeyski\EpuapBundle\Security\Authentication\EpuapResponseConsumerInterface');
    }

    public function test_on_successful_login_should_redirect()
    {
        $controller = new ResponseConsumerController($this->consumer,
            $this->router,
            array(CommonSignatureProvider::KEY_URL_COLLECTION => array(CommonSignatureProvider::ROUTE_POST_LOGIN_REDIRECT => '/')));

        $this->consumer->expects($this->once())
            ->method('consumeArtifact')
            ->will($this->returnValue(true));

        $result = $controller->consumeAction(new Request(array('SAMLart' => 123)));

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $result);
    }

    public function test_on_unsuccesfull_should_throw_exception()
    {

    }
}
