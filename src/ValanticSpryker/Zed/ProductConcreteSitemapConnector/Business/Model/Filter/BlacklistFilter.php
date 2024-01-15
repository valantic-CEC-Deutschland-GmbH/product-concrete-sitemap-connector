<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter;

use Generated\Shared\Transfer\SitemapUrlTransfer;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig;

class BlacklistFilter implements FilterInterface
{
    /**
     * @param \Spryker\Zed\ProductList\Business\ProductListFacadeInterface $productListFacade
     * @param \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig $config
     */
    public function __construct(
        protected ProductListFacadeInterface $productListFacade,
        protected ProductConcreteSitemapConnectorConfig $config,
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     *
     * @return bool
     */
    public function filter(SitemapUrlTransfer $sitemapUrlTransfer): bool
    {
        if (!$this->config->filterConcreteProductsByBlackLists()) {
            return true;
        }

        if (!$sitemapUrlTransfer->getResourceId()) {
            return true;
        }

        $blackListIdsTheProductIsIn = $this->productListFacade
            ->getProductBlacklistIdsByIdProduct(
                $sitemapUrlTransfer->getResourceId(),
            );

        return count($blackListIdsTheProductIsIn) === 0;
    }
}
