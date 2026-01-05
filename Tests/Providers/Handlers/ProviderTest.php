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

namespace D3\OxLogIQ_Sentry\Tests\Providers\Handlers;

use D3\LoggerFactory\LoggerFactory;
use D3\OxLogIQ\MonologConfiguration;
use D3\OxLogIQ_Sentry\Configuration;
use D3\OxLogIQ_Sentry\Providers\Handlers\Provider;
use D3\TestingTools\Development\CanAccessRestricted;
use Generator;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionException;

#[Small]
#[CoversMethod(Provider::class, 'isActive')]
#[CoversMethod(Provider::class, 'provide')]
class ProviderTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @throws ReflectionException
     * @dataProvider isActiveDataProvider
     */
    #[DataProvider('isActiveDataProvider')]
    public function testIsActive($hasHttpEndpoint): void
    {
        $monologConfigurationMock = $this->getMockBuilder(MonologConfiguration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configurationMock = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hasSentryDsn'])
            ->getMock();
        $configurationMock->expects(self::once())->method('hasSentryDsn')->willReturn($hasHttpEndpoint);

        $sut = oxNew(Provider::class, $monologConfigurationMock, $configurationMock);

        $this->assertSame(
            $hasHttpEndpoint,
            $this->callMethod(
                $sut,
                'isActive',
            )
        );
    }

    public static function isActiveDataProvider(): Generator
    {
        yield [false];
        yield [true];
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testProvide(): void
    {
        $monologConfigurationMock = $this->getMockBuilder(MonologConfiguration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getLogLevel'])
            ->getMock();
        $monologConfigurationMock->expects(self::exactly(2))->method('getLogLevel')->willReturn('error');

        $configurationMock = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSentryOptions'])
            ->getMock();
        $configurationMock->method('getSentryOptions')->willReturn([]);

        $sut = new Provider(
            $monologConfigurationMock,
            $configurationMock,
        );

        $factoryMock = $this->getMockBuilder(LoggerFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addOtherHandler'])
            ->getMock();
        $factoryMock->expects(self::exactly(2))->method('addOtherHandler');

        $this->callMethod($sut, 'provide', [$factoryMock]);
    }
}
