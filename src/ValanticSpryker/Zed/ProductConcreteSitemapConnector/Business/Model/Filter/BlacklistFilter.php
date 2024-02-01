<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter;

use Generated\Shared\Transfer\SitemapUrlNodeTransfer;
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
     * @param \Generated\Shared\Transfer\SitemapUrlNodeTransfer $sitemapUrlNodeTransfer
     *
     * @return bool
     */
    public function filter(SitemapUrlNodeTransfer $sitemapUrlNodeTransfer): bool
    {
        if (!$this->config->filterConcreteProductsByBlackLists()) {
            return true;
        }

        if (!$sitemapUrlNodeTransfer->getResourceId()) {
            return false;
        }

        $blackListIdsTheProductIsIn = $this->productListFacade
            ->getProductBlacklistIdsByIdProduct(
                $sitemapUrlNodeTransfer->getResourceId(),
            );

        return count($blackListIdsTheProductIsIn) === 0;
    }
}
