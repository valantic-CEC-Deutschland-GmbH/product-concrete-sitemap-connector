<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor;

use Generated\Shared\Transfer\SitemapUrlTransfer;

interface UrlFilterExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     *
     * @return bool
     */
    public function filterUrl(SitemapUrlTransfer $sitemapUrlTransfer): bool;
}
