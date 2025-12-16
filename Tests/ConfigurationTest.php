<?php

/**
 * Copyright (c) D3 Data Development (Inh. Thomas Dartsch)
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * https://www.d3data.de
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author    D3 Data Development - Daniel Seifert <info@shopmodule.com>
 * @link      https://www.oxidmodule.com
 */

declare(strict_types=1);

namespace D3\OxLogIQ_Sentry\Tests;

use D3\OxLogIQ\Release\ReleaseService;
use D3\OxLogIQ_Sentry\Configuration;
use D3\TestingTools\Development\CanAccessRestricted;
use Generator;
use OxidEsales\Facts\Config\ConfigFile;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Sentry\Event;
use Sentry\Tracing\SamplingContext;

#[Small]
#[CoversMethod(Configuration::class, 'hasSentryDsn')]
#[CoversMethod(Configuration::class, 'getSentryDsn')]
#[CoversMethod(Configuration::class, 'getSentryOptions')]
#[CoversMethod(Configuration::class, 'getRelease')]
#[CoversMethod(Configuration::class, 'getSentryTracesSampleRate')]
#[CoversMethod(Configuration::class, 'beforeSendToSentry')]
class ConfigurationTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @throws ReflectionException
     */
    #[Test]
    #[DataProvider('hasSentryDsnDataProvider')]
    public function testHasSentryDsn($dsn, $isset, $expected): void
    {
        $releaseMock = $this->getMockBuilder(ReleaseService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factsMock = $this->getMockBuilder(ConfigFile::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVar'])
            ->getMock();
        $factsMock->method('getVar')->with(
            $this->identicalTo(Configuration::CONFIGVAR_SENTRY_DSN)
        )->willReturn($dsn);

        $sut = new Configuration($releaseMock, $factsMock);

        self::assertSame(
            $isset,
            $this->callMethod($sut, 'hasSentryDsn')
        );
        self::assertSame(
            $expected,
            $this->callMethod($sut, 'getSentryDsn')
        );
    }

    public static function HasSentryDsnDataProvider(): Generator
    {
        yield 'not set' => [null, false, null];
        yield 'set' => ['dsnFixture', true, 'dsnFixture'];
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testGetSentryOptions(): void
    {
        $sut = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSentryDsn', 'getRelease', 'getSentryTracesSampleRate', 'beforeSendToSentry'])
            ->getMock();
        $sut->expects(self::atLeastOnce())->method('getSentryDsn')->willReturn('dsnFixture');
        $sut->expects(self::atLeastOnce())->method('getRelease')->willReturn('2020-05-20_19:58:12');
        $sut->expects(self::atLeastOnce())->method('getSentryTracesSampleRate');
        $sut->expects(self::atLeastOnce())->method('beforeSendToSentry');

        $return = $this->callMethod($sut, 'getSentryOptions');

        $this->assertIsIterable($return);
        $this->assertArrayHasKey('dsn', $return);
        $this->assertArrayHasKey('environment', $return);
        $this->assertArrayHasKey('release', $return);
        $this->assertArrayHasKey('prefixes', $return);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testGetRelease(): void
    {
        $releaseMock = $this->getMockBuilder(ReleaseService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRelease'])
            ->getMock();
        $releaseMock->method('getRelease')->willReturn('2020-05-20_19:58:12');

        $factsMock = $this->getMockBuilder(ConfigFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sut = new Configuration($releaseMock, $factsMock);

        $this->assertSame(
            '2020-05-20_19:58:12',
            $this->callMethod($sut, 'getRelease')
        );
    }

    #[Test]
    #[DataProvider('getSentryTracesSampleRateDataProvider')]
    public function testGetSentryTracesSampleRate(bool $parentSampled, $expected): void
    {
        $releaseMock = $this->getMockBuilder(ReleaseService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factsMock = $this->getMockBuilder(ConfigFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new class (
            $releaseMock,
            $factsMock
        ) extends Configuration {
            public function call(): callable
            {
                return $this->getSentryTracesSampleRate();
            }
        };

        $callable = $obj->call();

        $context = new SamplingContext();
        $context->setParentSampled($parentSampled);
        $this->assertSame($expected, $callable($context));
    }

    public static function getSentryTracesSampleRateDataProvider(): Generator
    {
        yield 'parentSampled' => [true, 1.0];
        yield 'no parentSampled' => [false, 0.25];
    }

    #[Test]
    public function testBeforeSendToSentry(): void
    {
        $releaseMock = $this->getMockBuilder(ReleaseService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factsMock = $this->getMockBuilder(ConfigFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new class (
            $releaseMock,
            $factsMock
        ) extends Configuration {
            public function call(): callable
            {
                return $this->beforeSendToSentry();
            }
        };

        $callable = $obj->call();
        $event = Event::createEvent();

        $this->assertSame($event, $callable($event));
    }
}
