<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence;

use Generated\Shared\Transfer\StoreTransfer;

interface ProductConcreteSitemapConnectorRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStore
     * @param int $page
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function findActiveConcreteProductUrls(StoreTransfer $currentStore, int $page, int $limit): array;
}
