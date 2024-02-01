<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor;

use Generated\Shared\Transfer\SitemapUrlNodeTransfer;

interface UrlFilterExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapUrlNodeTransfer $sitemapUrlNodeTransfer
     *
     * @return bool
     */
    public function filterUrl(SitemapUrlNodeTransfer $sitemapUrlNodeTransfer): bool;
}
