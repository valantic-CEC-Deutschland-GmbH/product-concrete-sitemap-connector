<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Creator;

use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;
use ValanticSpryker\Shared\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConstants;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Exception\InvalidStoreException;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor\UrlFilterExecutorInterface;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepositoryInterface;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig;

class ProductConcreteSitemapCreator
{
    protected const MESSAGE_STORE_NAME_IS_NOT_AVAILABLE = 'Store name is not available';

    /**
     * @param \ValanticSpryker\Service\Sitemap\SitemapServiceInterface $sitemapService
     * @param \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepositoryInterface $repository
     * @param \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig $config
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor\UrlFilterExecutorInterface $urlFilterExecutor
     */
    public function __construct(
        protected SitemapServiceInterface $sitemapService,
        protected ProductConcreteSitemapConnectorRepositoryInterface $repository,
        protected ProductConcreteSitemapConnectorConfig $config,
        protected StoreFacadeInterface $storeFacade,
        protected UrlFilterExecutorInterface $urlFilterExecutor,
    ) {
    }

    /**
     * @throws \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Exception\InvalidStoreException
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(): array
    {
        $urlLimit = $this->config->getSitemapUrlLimit();
        $sitemapList = [];
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $page = 1;

        if (!$currentStoreTransfer->getName()) {
            throw new InvalidStoreException(static::MESSAGE_STORE_NAME_IS_NOT_AVAILABLE);
        }

        do {
            $urlList = $this->repository->findActiveConcreteProductUrls(
                $currentStoreTransfer,
                $page,
                $urlLimit,
            );

            $urlList = $this->filterUrls($urlList);

            $sitemapTransfer = $this->sitemapService->createSitemapXmlFileTransfer(
                array_values($urlList),
                $page,
                $currentStoreTransfer->getName(),
                ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE,
            );

            if ($sitemapTransfer !== null) {
                $sitemapList[] = $sitemapTransfer;
            }

            $page++;
        } while ($sitemapTransfer !== null);

        return $sitemapList;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer> $urlList
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer>
     */
    protected function filterUrls(array $urlList): array
    {
        foreach ($urlList as $key => $url) {
            if (!$this->urlFilterExecutor->filterUrl($url)) {
                unset($urlList[$key]);
            }
        }

        return $urlList;
    }
}
