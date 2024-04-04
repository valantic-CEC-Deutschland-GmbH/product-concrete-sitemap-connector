<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\ProductConcreteSitemapConnectorBusinessFactory getFactory()
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepositoryInterface getRepository()
 */
class ProductConcreteSitemapConnectorFacade extends AbstractFacade implements ProductConcreteSitemapConnectorFacadeInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(): array
    {
        return $this->getFactory()
            ->createProductSitemapCreator()
            ->createSitemapXml();
    }
}
