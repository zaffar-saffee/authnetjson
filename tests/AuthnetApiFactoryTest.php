<?php

/*
 * This file is part of the AuthnetJSON package.
 *
 * (c) John Conde <stymiee@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JohnConde\Authnet;

class AuthnetApiFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $login;
    private $transactionKey;

    protected function setUp()
    {
        $this->login          = 'test';
        $this->transactionKey = 'test';
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getWebServiceURL
     */
    public function testGetWebServiceUrlProductionServer()
    {
        $server           = AuthnetApiFactory::USE_PRODUCTION_SERVER;
        $reflectionMethod = new \ReflectionMethod('\JohnConde\Authnet\AuthnetApiFactory', 'getWebServiceURL');
        $reflectionMethod->setAccessible(true);
        $url              = $reflectionMethod->invoke(null, $server);

        $this->assertEquals($url, 'https://api.authorize.net/xml/v1/request.api');
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getWebServiceURL
     */
    public function testGetWebServiceUrlDevelopmentServer()
    {
        $server           = AuthnetApiFactory::USE_DEVELOPMENT_SERVER;
        $reflectionMethod = new \ReflectionMethod('\JohnConde\Authnet\AuthnetApiFactory', 'getWebServiceURL');
        $reflectionMethod->setAccessible(true);
        $url              = $reflectionMethod->invoke(null, $server);

        $this->assertEquals($url, 'https://apitest.authorize.net/xml/v1/request.api');
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getWebServiceURL
     */
    public function testGetWebServiceUrlAkamaiServer()
    {
        $server           = AuthnetApiFactory::USE_AKAMAI_SERVER;
        $reflectionMethod = new \ReflectionMethod('\JohnConde\Authnet\AuthnetApiFactory', 'getWebServiceURL');
        $reflectionMethod->setAccessible(true);
        $url              = $reflectionMethod->invoke(null, $server);

        $this->assertEquals($url, 'https://api2.authorize.net/xml/v1/request.api');
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getWebServiceURL
     * @expectedException \JohnConde\Authnet\AuthnetInvalidServerException
     */
    public function testGetWebServiceUrlBadServer()
    {
        $server           = 99;
        $reflectionMethod = new \ReflectionMethod('\JohnConde\Authnet\AuthnetApiFactory', 'getWebServiceURL');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke(null, $server);
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getJsonApiHandler
     * @uses              \JohnConde\Authnet\AuthnetJsonRequest
     * @expectedException \JohnConde\Authnet\AuthnetInvalidCredentialsException
     */
    public function testExceptionIsRaisedForInvalidCredentialsLogin()
    {
        $server  = AuthnetApiFactory::USE_DEVELOPMENT_SERVER;
        $request = AuthnetApiFactory::getJsonApiHandler(null, $this->transactionKey, $server);
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getJsonApiHandler
     * @uses              \JohnConde\Authnet\AuthnetJsonRequest
     * @expectedException \JohnConde\Authnet\AuthnetInvalidCredentialsException
     */
    public function testExceptionIsRaisedForInvalidCredentialsTransactionKey()
    {
        $server  = AuthnetApiFactory::USE_DEVELOPMENT_SERVER;
        $request = AuthnetApiFactory::getJsonApiHandler($this->login, null, $server);
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getJsonApiHandler
     * @expectedException \JohnConde\Authnet\AuthnetInvalidServerException
     */
    public function testExceptionIsRaisedForAuthnetInvalidServer()
    {
        $authnet = AuthnetApiFactory::getJsonApiHandler($this->login, $this->transactionKey, null);
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getJsonApiHandler
     * @uses              \JohnConde\Authnet\AuthnetJsonRequest
     */
    public function testCurlWrapperProductionResponse()
    {
        $server  = AuthnetApiFactory::USE_PRODUCTION_SERVER;
        $request = AuthnetApiFactory::getJsonApiHandler($this->login, $this->transactionKey, $server);

        $this->assertInstanceOf('JohnConde\Authnet\CurlWrapper', new CurlWrapper());
    }

    /**
     * @covers            \JohnConde\Authnet\AuthnetApiFactory::getJsonApiHandler
     * @uses              \JohnConde\Authnet\AuthnetJsonRequest
     */
    public function testCurlWrapperDevelopmentResponse()
    {
        $server  = AuthnetApiFactory::USE_DEVELOPMENT_SERVER;
        $request = AuthnetApiFactory::getJsonApiHandler($this->login, $this->transactionKey, $server);

        $this->assertInstanceOf('JohnConde\Authnet\CurlWrapper', new CurlWrapper());
    }
}