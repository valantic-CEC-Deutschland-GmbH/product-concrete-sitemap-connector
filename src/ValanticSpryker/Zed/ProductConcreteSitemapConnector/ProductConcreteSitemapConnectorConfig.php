<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;

class ProductConcreteSitemapConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }

    /**
     * @return int
     */
    public function getSitemapUrlLimit(): int
    {
        return $this->get(SitemapConstants::SITEMAP_URL_LIMIT, 50_000);
    }

    /**
     * @return bool
     */
    public function filterConcreteProductsByBlackLists(): bool
    {
        return $this->get(SitemapConstants::SITEMAP_USE_BLACKLISTS, false);
    }
}
