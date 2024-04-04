<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface;

/**
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\ProductConcreteSitemapConnectorFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig getConfig()
 */
class ProductConcreteSitemapCreatorPlugin extends AbstractPlugin implements SitemapCreatorPluginInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(): array
    {
        return $this->getFacade()
            ->createSitemapXml();
    }
}
