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

namespace D3\OxLogIQ_Sentry;

use D3\OxLogIQ\Release\ReleaseServiceInterface;
use D3\OxLogIQ_Sentry\Interfaces\ConfigurationInterface;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Config\ConfigFile;
use Sentry\Event as SentryEvent;
use Sentry\Tracing\SamplingContext;

class Configuration implements ConfigurationInterface
{
    public const CONFIGVAR_SENTRY_DSN       = 'oxlogiq_sentryDsn';

    protected ConfigFile $factsConfigFile;

    public function __construct(protected ReleaseServiceInterface $releaseService)
    {
        $this->factsConfigFile = new ConfigFile();
    }

    public function hasSentryDsn(): bool
    {
        $dsn = $this->getSentryDsn();

        return isset($dsn) && strlen(trim($dsn));
    }

    public function getSentryDsn(): ?string
    {
        return $this->factsConfigFile->getVar(self::CONFIGVAR_SENTRY_DSN);
    }

    public function getSentryOptions(): array
    {
        return [
            'dsn' => $this->getSentryDsn(),
            'enable_logs' => true,
            'traces_sampler' => $this->getSentryTracesSampleRate(),
            'environment' => Registry::getConfig()->getActiveShop()->isProductiveMode() ?
                'production' : // @codeCoverageIgnore
                'development',  // @codeCoverageIgnore
            'release' => $this->getRelease(),
            'before_send' => $this->beforeSendToSentry(),
            'prefixes' => [
                realpath(
                    rtrim(Registry::getConfig()->getConfigParam('sShopDir'), DIRECTORY_SEPARATOR).
                    DIRECTORY_SEPARATOR.'..'
                ).DIRECTORY_SEPARATOR,
            ],
        ];
    }

    public function getRelease(): string
    {
        return $this->releaseService->getRelease();
    }

    protected function getSentryTracesSampleRate(): callable
    {
        return function (SamplingContext $context): float {
            if ($context->getParentSampled()) {
                return 1.0;
            }
            return 0.25;
        };
    }

    protected function beforeSendToSentry(): callable
    {
        return function (SentryEvent $event): ? SentryEvent {
            return $event;
        };
    }
}
