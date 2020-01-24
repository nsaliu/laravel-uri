<?php

namespace Nsaliu\Uri\Tests;

use Nsaliu\Uri\CurlWrapper;
use Nsaliu\Uri\Exceptions\HostIsEmptyException;
use Nsaliu\Uri\Exceptions\InvalidUriException;
use Nsaliu\Uri\Exceptions\QueryCannotContainFragmentException;
use Nsaliu\Uri\Exceptions\QueryKeyAlreadyExistsException;
use Nsaliu\Uri\Exceptions\QueryKeyMustHaveAtLeastOneCharException;
use Nsaliu\Uri\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * @var Uri
     */
    private $sut;

    protected function setUp(): void
    {
        $this->sut = new Uri();
    }

    public function testGetScheme()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('https', $this->sut->getScheme());
    }

    public function testGetSchemeMustReturnEmptyString()
    {
        $this->sut->createFromString(
            '//username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('', $this->sut->getScheme());
    }

    public function testGetUsername()
    {
        $this->sut->createFromString(
            'https://username@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('username', $this->sut->getUsername());
    }

    public function testGetUsernameMustReturnEmptyString()
    {
        $this->sut->createFromString(
            'https://test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('', $this->sut->getUsername());
    }

    public function testGetPassword()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('password', $this->sut->getPassword());
    }

    public function testGetPasswordMustReturnEmptyString()
    {
        $this->sut->createFromString(
            'https://username:@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('', $this->sut->getPassword());
    }

    public function testGetAuthorityMustReturnUserInfoHostAndPort()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('username:password@test.test:80', $this->sut->getAuthority());
    }

    public function testGetAuthorityMustReturnUsernameAndNoPasswordHostAndNoPort()
    {
        $this->sut->createFromString(
            'https://username@test.test:443/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('username:@test.test', $this->sut->getAuthority());
    }

    public function testGetAuthorityWithExplicitPort()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:443/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('username:password@test.test:443', $this->sut->getAuthorityWithPort());
    }

    public function testGetUserInfo()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('username:password', $this->sut->getUserInfo());
    }

    public function testGetUserInfoMustReturnNoPassword()
    {
        $this->sut->createFromString(
            'https://username@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('username', $this->sut->getUserInfo());
    }

    public function testGetHost()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('test.test', $this->sut->getHost());
    }

    public function testGetPort()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals(80, $this->sut->getPort());
    }

    public function testIsDefaultPortMustReturnTrue()
    {
        $this->sut
            ->SetScheme('https')
            ->setPort(443);

        $this->assertTrue($this->sut->isDefaultPort());
    }

    public function testIsDefaultPortMustReturnFalse()
    {
        $this->sut
            ->SetScheme('https')
            ->setPort(80);

        $this->assertFalse($this->sut->isDefaultPort());
    }

    public function testGetPath()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('/en-us/path1/path2/page.html', $this->sut->getPath());
    }

    public function testGetPathAsArray()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );
        $this->assertEquals(['en-us', 'path1', 'path2', 'page.html'], $this->sut->getPathAsArray());
    }

    public function testGetQuery()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('arg1=1&arg2=2', $this->sut->getQuery());
    }

    public function testGetQueryAsArray()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals(['arg1'=>'1', 'arg2'=>'2'], $this->sut->getQueryAsArray());
    }

    public function testGetFragment()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('test-test', $this->sut->getFragment());
    }

    public function testGetPathAndQuery()
    {
        $this->sut
            ->setPath('/test/path')
            ->setQuery('arg1=1&arg2=2');

        $this->assertEquals('/test/path?arg1=1&arg2=2', $this->sut->getPathAndQuery());
    }

    public function testSetScheme()
    {
        $this->sut->setScheme('http');
        $this->assertEquals('http', $this->sut->getScheme());
    }

    public function testSetUsername()
    {
        $this->sut->setUsername('username');
        $this->assertEquals('username', $this->sut->getUsername());
    }

    public function testSetPassword()
    {
        $this->sut->setPassword('password');
        $this->assertEquals('password', $this->sut->getPassword());
    }

    public function testSetUserInfo()
    {
        $this->sut->setUserInfo('username');
        $this->assertEquals('username:', $this->sut->getUserInfo());
    }

    public function testSetHost()
    {
        $this->sut->setHost('test.com');
        $this->assertEquals('test.com', $this->sut->getHost());
    }

    public function testSetPort()
    {
        $this->sut->setPort(80);
        $this->assertEquals(80, $this->sut->getPort());
    }

    public function testSetPortMustRisePortOutOfRangeException()
    {
        $this->expectException(\Nsaliu\Uri\Exceptions\PortOutOfRangeException::class);

        $this->sut->setPort(999999);
    }

    public function testSetPath()
    {
        $this->sut->setPath('test/path');
        $this->assertEquals('/test/path', $this->sut->getPath());

        $this->sut->setPath('/test/path');
        $this->assertEquals('/test/path', $this->sut->getPath());
    }

    public function testSetQuery()
    {
        $this->sut->setQuery('arg1=1&arg2=2');
        $this->assertEquals('arg1=1&arg2=2', $this->sut->getQuery());
    }

    public function testSetQueryMustRiseQueryCannotContainFragmentException()
    {
        $this->expectException(QueryCannotContainFragmentException::class);

        $this->sut->setQuery('arg1=1&arg2=2#test');
    }

    public function testSetQueryArray()
    {
        $this->sut->setQueryArray(['arg1' => 1, 'arg2' => 2]);
        $this->assertEquals('arg1=1&arg2=2', $this->sut->getQuery());
    }

    public function testSetQueryArrayMustRiseQueryCannotContainFragmentException()
    {
        $this->expectException(QueryCannotContainFragmentException::class);

        $this->sut->setQueryArray(['arg1' => '1', 'arg2' => '2#fragment']);
    }

    public function testAddQuery()
    {
        $this->sut->addQuery('arg3', '3');

        $this->assertEquals('arg3=3', $this->sut->getQuery());
    }

    public function testAddQueryMustRiseQueryKeyAlreadyExistsException()
    {
        $this->expectException(QueryKeyAlreadyExistsException::class);

        $this->sut->addQuery('arg1', '3');
        $this->sut->addQuery('arg1', '3');
    }

    public function testAddQueryMustRiseQueryCannotContainFragmentException()
    {
        $this->expectException(QueryCannotContainFragmentException::class);

        $this->sut->addQuery('arg1', '3#fragment');
    }

    public function testAddQueryMustRiseQueryKeyMustHaveAtLeastOneCharEception()
    {
        $this->expectException(QueryKeyMustHaveAtLeastOneCharException::class);

        $this->sut->addQuery('', '3');
    }

    public function testChangeQuery()
    {
        $this->sut->changeQuery('arg1', '1');
        $this->sut->changeQuery('arg2', '2');
        $this->sut->changeQuery('arg1', '3');

        $this->assertEquals('arg1=3&arg2=2', $this->sut->getQuery());
    }

    public function testChangeQueryMustRiseQueryCannotContainFragmentException()
    {
        $this->expectException(QueryCannotContainFragmentException::class);

        $this->sut->changeQuery('arg1', '1#fragment');
    }

    public function testChangeQueryMustRiseQueryKeyMustHaveAtLeastOneCharException()
    {
        $this->expectException(QueryKeyMustHaveAtLeastOneCharException::class);

        $this->sut->changeQuery('', '1');
    }

    public function testSetFragment()
    {
        $this->sut->setFragment('fragment');
        $this->assertEquals('fragment', $this->sut->getFragment());
    }

    public function testCreateFromString()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('https', $this->sut->getScheme());
        $this->assertEquals('username', $this->sut->getUsername());
        $this->assertEquals('password', $this->sut->getPassword());
        $this->assertEquals('username:password@test.test:80', $this->sut->getAuthority());
        $this->assertEquals('username:password@test.test:80', $this->sut->getAuthorityWithPort());
        $this->assertEquals('username:password', $this->sut->getUserInfo());
        $this->assertEquals('test.test', $this->sut->getHost());
        $this->assertEquals(80, $this->sut->getPort());
        $this->assertEquals('/en-us/path1/path2/page.html', $this->sut->getPath());
        $this->assertEquals('arg1=1&arg2=2', $this->sut->getQuery());
        $this->assertEquals(['arg1'=>'1', 'arg2'=>'2'], $this->sut->getQueryAsArray());
        $this->assertEquals('test-test', $this->sut->getFragment());
    }

    public function testCreateFromStringMustRiseInvalidUriException()
    {
        $this->expectException(InvalidUriException::class);

        $this->sut->createFromString(
            'https://test:test:test'
        );
    }

    public function testCreateFromStringMustReturnUriInstance()
    {
        $this->expectException(InvalidUriException::class);

        $this->assertSame($this->sut, $this->sut->createFromString('https://test:test:test'));
    }

    public function testCreateFromSetters()
    {
        $this->sut
            ->setScheme('https')
            ->setHost('test.com')
            ->setUsername('username')
            ->setPassword('password')
            ->setPath('/test/path')
            ->setFragment('fragment')
            ->setPort(80)
            ->setQuery('arg1=1&arg2=2');

        $this->assertEquals('https://username:password@test.com:80/test/path?arg1=1&arg2=2#fragment', $this->sut->toString());
    }

    public function testToString()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test',
            $this->sut->toString()
        );
    }

    public function testImplicitCastToString()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test',
            (string)$this->sut
        );
    }

    public function testGetQueryValue()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $this->assertEquals('1', $this->sut->getQueryValue('arg1'));
        $this->assertEquals('2', $this->sut->getQueryValue('arg2'));
    }

    public function testHostIsReachable()
    {
        $this->sut->createFromString('https://github.com');

        $this->assertTrue($this->sut->hostIsReachable());
    }

    public function testHostIsReachableMustRiseHostIsEmptyException()
    {
        $this->expectException(HostIsEmptyException::class);

        $this->assertTrue($this->sut->hostIsReachable());
    }

    public function testEqualsReturnTrue()
    {
        $url = 'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test';

        $this->sut->createFromString($url);

        $this->assertTrue($this->sut->equals($url));
    }

    public function testEqualsReturnFalse()
    {
        $src = 'https://username:password@test.test:80/en-us/path1/path2/page.html';
        $compare = 'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test';

        $this->sut->createFromString($src);

        $this->assertFalse($this->sut->equals($compare));
    }

    public function testGetComponents()
    {
        $this->sut->createFromString(
            'https://username:password@test.test:80/en-us/path1/path2/page.html?arg1=1&arg2=2#test-test'
        );

        $expected = [
            'scheme' => 'https',
            'host' => 'test.test',
            'port' => 80,
            'user' => 'username',
            'pass' => 'password',
            'path' => '/en-us/path1/path2/page.html',
            'query' => 'arg1=1&arg2=2',
            'fragment' => 'test-test',
        ];

        $this->assertEquals($expected, $this->sut->getComponents());
    }
}
