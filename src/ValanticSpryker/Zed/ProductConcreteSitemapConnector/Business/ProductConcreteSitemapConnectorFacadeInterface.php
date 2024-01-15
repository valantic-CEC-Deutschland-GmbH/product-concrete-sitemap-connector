<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business;

interface ProductConcreteSitemapConnectorFacadeInterface
{
    /**
     * Specification:
     * - Creates sitemap XML to be consumed by parent module.
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(): array;
}
