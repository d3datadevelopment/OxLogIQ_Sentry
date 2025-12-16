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

namespace D3\OxLogIQ_Sentry\Tests\Providers;

use D3\LoggerFactory\LoggerFactory;
use D3\OxLogIQ\MonologConfiguration;
use D3\OxLogIQ_Sentry\Configuration;
use D3\OxLogIQ_Sentry\Providers\SentryHandlerProvider;
use D3\TestingTools\Development\CanAccessRestricted;
use Generator;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionException;

#[Small]
#[CoversMethod(SentryHandlerProvider::class, 'register')]
class SentryHandlerProviderTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @throws ReflectionException
     */
    #[Test]
    #[DataProvider('registerDataProvider')]
    public function testRegister(bool $dsnGiven, int $invocation): void
    {
        $monologConfigurationMock = $this->getMockBuilder(MonologConfiguration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getLogLevel'])
            ->getMock();
        $monologConfigurationMock->expects(self::exactly($invocation))->method('getLogLevel')->willReturn('error');

        $configurationMock = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hasSentryDsn', 'getSentryOptions'])
            ->getMock();
        $configurationMock->method('hasSentryDsn')->willReturn($dsnGiven);
        $configurationMock->method('getSentryOptions')->willReturn([]);

        $sut = new SentryHandlerProvider(
            $monologConfigurationMock,
            $configurationMock,
        );

        $factoryMock = $this->getMockBuilder(LoggerFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addOtherHandler'])
            ->getMock();
        $factoryMock->expects(self::exactly($invocation))->method('addOtherHandler');

        $this->callMethod($sut, 'register', [$factoryMock]);
    }

    public static function registerDataProvider(): Generator
    {
        yield 'no dsn' => [false, 0];
        yield 'given dsn' => [true, 2];
    }
}
