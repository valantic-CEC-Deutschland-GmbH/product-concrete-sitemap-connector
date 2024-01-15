<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor;

use Generated\Shared\Transfer\SitemapUrlTransfer;

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
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     *
     * @return bool
     */
    public function filterUrl(SitemapUrlTransfer $sitemapUrlTransfer): bool
    {
        foreach ($this->urlFilters as $urlFilterer) {
            if (!$urlFilterer->filter($sitemapUrlTransfer)) {
                return false;
            }
        }

        return true;
    }
}
