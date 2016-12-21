<?php

namespace Madeyski\EpuapBundle\Tests\Security\Authentication;


use Madeyski\EpuapBundle\Request\ArtifactResolverInterface;
use Madeyski\EpuapBundle\Security\Authentication\EpuapResonseConsumer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EpuapResponseConsumerUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventDispatcher;

    /**
     * @var ArtifactResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $artifactResolver;

    public function setUp()
    {
        parent::setUp();

        $this->eventDispatcher = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->artifactResolver = $this->createMock('Madeyski\EpuapBundle\Request\ArtifactResolverInterface');
    }

    public function test_event_is_dipatched_on_success()
    {
        $this->artifactResolver->expects($this->once())
            ->method('resolve')
            ->will($this->returnValue('dummyUserName'));

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $consumer = new EpuapResonseConsumer($this->artifactResolver, $this->eventDispatcher);
        $consumer->consumeArtifact('dummyArtifact');
    }
}
