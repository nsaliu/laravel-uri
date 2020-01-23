<?php

namespace Nsaliu\Uri\Tests;

use Nsaliu\Uri\CurlWrapper;
use PHPUnit\Framework\TestCase;

class CurlWrapperTest extends TestCase
{
    /**
     * @var CurlWrapper
     */
    private $sut;

    protected function setUp(): void
    {
        $this->sut = $this->createMock(CurlWrapper::class);
    }

    public function testGetReturnCode()
    {
        $uri = 'https://github.com';

        $this->sut
            ->expects($this->once())
            ->method('getReturnCode')
            ->with($uri)
            ->willReturn(200);

        $this->assertEquals(200, $this->sut->getReturnCode($uri));
    }
}
