<?php

use Waryway\MicroServiceEngine\BaseRouter;
use PHPUnit\Framework\TestCase;

class BaseRouterTest extends TestCase
{


    public function testBaseRouter_Default()
    {
        $baseRouter = new BaseRouter();

        // Check the dispatcher
        $reflectionBaseRouter = new ReflectionObject($baseRouter);
        $reflectionBaseRouterDispatcher = $reflectionBaseRouter->getProperty('dispatcher');
        $reflectionBaseRouterDispatcher->setAccessible(true);
        $actualDispatcher = $reflectionBaseRouterDispatcher->getValue($baseRouter);

        $this->assertInstanceOf('FastRoute\Dispatcher', $actualDispatcher, 'Expecting a fastroute dispatcher');

        // check the default route
        $reflectionDispatcher = new ReflectionObject($actualDispatcher);
        $reflectionRouteMap = $reflectionProperty = $reflectionDispatcher->getProperty('staticRouteMap');
        $reflectionRouteMap->setAccessible(true);
        $actualMap = $reflectionRouteMap->getValue($actualDispatcher);
        $this->assertArrayHasKey('GET', $actualMap,'Expected a GET route');
        $this->assertCount(1, $actualMap, 'Expected only a GET route');
        $this->assertArrayHasKey('/credit', $actualMap['GET'], 'The \'credit\' route is the only one pre-defined on the engine.');
        $this->assertCount(1, $actualMap['GET'], 'Expected only a single GET route');
        $this->assertEquals($actualMap['GET']['/credit'], 'credit', 'Expecting the path \'credit\' to be default');

        // check the static enabled is false.
        $reflectionBaseRouterStaticAssetsEnabled = $reflectionBaseRouter->getProperty('staticAssetEnabled');
        $reflectionBaseRouterStaticAssetsEnabled->setAccessible(true);
        $this->assertFalse($reflectionBaseRouterStaticAssetsEnabled->getValue($baseRouter), 'Expected static assets to be disabled by default');
    }

    public function testSetStaticAssetPath()
    {
        $mockRouter = $this->getMockBuilder(BaseRouter::class)
            ->setMethods(['setStaticAssetPath'])
            ->getMock();
        $mockRouter->expects($this->once())->method('setStaticAssetPath')->with('somePath');

        $reflectionMockRouter = new ReflectionObject($mockRouter);
        $reflectionMockRoutersetStaticAssetPath = $reflectionProperty = $reflectionMockRouter->getMethod('setStaticAssetPath');
        $reflectionMockRoutersetStaticAssetPath->setAccessible(true);

        $reflectionMockRoutersetStaticAssetPath->invokeArgs($mockRouter, ['somePath']);
    }
}
