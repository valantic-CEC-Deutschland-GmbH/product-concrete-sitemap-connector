<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter;

use Generated\Shared\Transfer\SitemapUrlTransfer;

interface FilterInterface
{
    /**
     * Specification:
     *
     * - Filters URLs by specific condition. Returns false upon failure.
     *
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     *
     * @return bool
     */
    public function filter(SitemapUrlTransfer $sitemapUrlTransfer): bool;
}
