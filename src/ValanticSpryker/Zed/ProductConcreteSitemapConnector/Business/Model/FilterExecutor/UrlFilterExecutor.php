<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor;

use Generated\Shared\Transfer\SitemapUrlNodeTransfer;

class UrlFilterExecutor implements UrlFilterExecutorInterface
{
    /**
     * @param array<\ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter\FilterInterface> $urlFilters
     */
    public function __construct(
        protected array $urlFilters,
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapUrlNodeTransfer $sitemapUrlNodeTransfer
     *
     * @return bool
     */
    public function filterUrl(SitemapUrlNodeTransfer $sitemapUrlNodeTransfer): bool
    {
        foreach ($this->urlFilters as $urlFilterer) {
            if (!$urlFilterer->filter($sitemapUrlNodeTransfer)) {
                return false;
            }
        }

        return true;
    }
}
