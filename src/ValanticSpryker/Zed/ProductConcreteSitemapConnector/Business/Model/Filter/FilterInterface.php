<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter;

use Generated\Shared\Transfer\SitemapUrlNodeTransfer;

interface FilterInterface
{
    /**
     * Specification:
     *
     * - Filters URLs by specific condition. Returns false upon failure.
     *
     * @param \Generated\Shared\Transfer\SitemapUrlNodeTransfer $sitemapUrlNodeTransfer
     *
     * @return bool
     */
    public function filter(SitemapUrlNodeTransfer $sitemapUrlNodeTransfer): bool;
}
