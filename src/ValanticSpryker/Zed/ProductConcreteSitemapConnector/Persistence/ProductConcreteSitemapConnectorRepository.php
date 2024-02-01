<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\UrlStorage\Persistence\Map\SpyUrlStorageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Exception\InvalidStoreException;

/**
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorPersistenceFactory getFactory()
 */
class ProductConcreteSitemapConnectorRepository extends AbstractRepository implements ProductConcreteSitemapConnectorRepositoryInterface
{
    protected const MESSAGE_STORE_ID_IS_NOT_AVAILABLE = 'Store id is not available';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStore
     * @param int $page
     * @param int $limit
     *
     * @throws \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Exception\InvalidStoreException
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer>
     */
    public function findActiveConcreteProductUrls(StoreTransfer $currentStore, int $page, int $limit): array
    {
        if (!$currentStore->getIdStore()) {
            throw new InvalidStoreException(self::MESSAGE_STORE_ID_IS_NOT_AVAILABLE);
        }

        $urlEntities = $this->findVisibleProductUrls($currentStore->getIdStore(), $page, $limit);

        return $this->getFactory()
            ->createSitemapUrlMapper()
            ->mapUrlEntitiesToSitemapUrlNodeTransfers($urlEntities);
    }

    /**
     * @param int $idStore
     * @param int $page
     * @param int $urlLimit
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function findVisibleProductUrls(int $idStore, int $page, int $urlLimit): ObjectCollection
    {
        $query = $this->getFactory()
            ->createSpyUrlQuery()
            ->filterByFkResourceProduct(null, Criteria::ISNOTNULL)
            ->filterByFkResourceRedirect(null, Criteria::ISNULL)
            ->joinWithSpyProductConcrete()
            ->useSpyProductConcreteQuery()
                ->joinWithSpyProductAbstract()
                ->useSpyProductAbstractQuery()
                    ->addJoin(
                        [SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, $idStore],
                        [SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractStoreTableMap::COL_FK_STORE],
                        Criteria::INNER_JOIN,
                    )
                ->endUse()
            ->endUse()
            ->addJoin(SpyUrlTableMap::COL_ID_URL, SpyUrlStorageTableMap::COL_FK_URL, Criteria::INNER_JOIN)
            ->withColumn(SpyUrlStorageTableMap::COL_UPDATED_AT, 'updated_at')
            ->setOffset($this->calculateOffsetByPage($page, $urlLimit))
            ->setLimit($urlLimit);

        /** @var \Propel\Runtime\Collection\ObjectCollection $results */
        $results = $query->find();

        return $results;
    }

    /**
     * @param int $page
     * @param int $pageLimit
     *
     * @return int
     */
    protected function calculateOffsetByPage(int $page, int $pageLimit): int
    {
        return ($page - 1) * $pageLimit;
    }
}
