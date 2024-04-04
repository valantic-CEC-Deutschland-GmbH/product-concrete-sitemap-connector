<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\ProductConcreteSitemapConnector\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\SitemapFileBuilder;
use Generated\Shared\DataBuilder\SitemapUrlNodeBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;
use ValanticSpryker\Shared\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConstants;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Communication\Plugin\ProductConcreteSitemapCreatorPlugin;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepository;
use ValanticSprykerTest\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorTester;

class ProductConcreteSitemapCreatorPluginTest extends Unit
{
    private const METHOD_FIND_ACTIVE_CONCRETE_PRODUCT_URLS = 'findActiveConcreteProductUrls';
    private const METHOD_CREATE_SITEMAP_XML_FILE_TRANSFER = 'createSitemapXmlFileTransfer';
    private const METHOD_GET_CURRENT_STORE = 'getCurrentStore';
    private const METHOD_GET_STORE_FACADE = 'getStoreFacade';
    private const METHOD_GET_SITEMAP_SERVICE = 'getSitemapService';
    private const METHOD_GET_PRODUCT_LIST_FACADE = 'getProductListFacade';
    private const METHOD_GET_REPOSITORY = 'getRepository';
    private const METHOD_GET_PRODUCT_BLACKLIST_IDS_BY_ID_PRODUCT = 'getProductBlacklistIdsByIdProduct';
    private const STORE_DE = 'DE';

    protected ProductConcreteSitemapConnectorTester $tester;

    private ProductConcreteSitemapCreatorPlugin $sut;

    /**
     * @var \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    private ProductConcreteSitemapConnectorRepository $repositoryMock;

    /**
     * @var \ValanticSpryker\Service\Sitemap\SitemapServiceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private SitemapServiceInterface $sitemapServiceMock;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private ProductListFacadeInterface $productListFacadeMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ProductConcreteSitemapCreatorPlugin();

        $this->mockStoreFacade();
        $this->mockSitemapService();
        $this->mockProductListFacade();
        $this->mockRepository();

        $facade = $this->tester->getFacade();
        $facade->setFactory($this->tester->getFactory())
            ->setRepository($this->repositoryMock);
        $this->sut->setFacade($facade);
    }

    /**
     * @return void
     */
    public function testCreateSitemapXml(): void
    {
        $url1 = (new SitemapUrlNodeBuilder(['url' => '/test1']))->build();
        $url2 = (new SitemapUrlNodeBuilder(['url' => '/test2']))->build();
        $this->repositoryMock->expects($this->exactly(2))
            ->method(self::METHOD_FIND_ACTIVE_CONCRETE_PRODUCT_URLS)
            ->willReturnOnConsecutiveCalls([$url1, $url2], []);

        $sitemapFileTransfer = (new SitemapFileBuilder(['yvesBaseUrl' => 'test1.test']))->build();
        $this->sitemapServiceMock->expects($this->exactly(2))
            ->method(self::METHOD_CREATE_SITEMAP_XML_FILE_TRANSFER)
            ->withConsecutive(
                [[$url1, $url2], 1, self::STORE_DE, ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE],
                [[], 2, self::STORE_DE, ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE],
            )
            ->willReturnOnConsecutiveCalls($sitemapFileTransfer, null);

        $sitemapList = $this->sut->createSitemapXml();

        $this->assertCount(1, $sitemapList);
        $this->assertEquals($sitemapFileTransfer, $sitemapList[0]);
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlExecutesBlacklistFilter(): void
    {
        $this->tester->setConfig(SitemapConstants::SITEMAP_USE_BLACKLISTS, true);

        $url1 = (new SitemapUrlNodeBuilder(['url' => '/test1', 'resourceId' => 1]))->build();
        $url2 = (new SitemapUrlNodeBuilder(['url' => '/test2', 'resourceId' => 2]))->build();
        $url3 = (new SitemapUrlNodeBuilder(['url' => '/test3', 'resourceId' => 3]))->build();
        $url4 = (new SitemapUrlNodeBuilder(['url' => '/test4']))->build();
        $this->repositoryMock->expects($this->exactly(2))
            ->method(self::METHOD_FIND_ACTIVE_CONCRETE_PRODUCT_URLS)
            ->willReturnOnConsecutiveCalls([$url1, $url2, $url3, $url4], []);

        $this->productListFacadeMock->expects($this->exactly(3))
            ->method(self::METHOD_GET_PRODUCT_BLACKLIST_IDS_BY_ID_PRODUCT)
            ->withConsecutive([1], [2], [3])
            ->willReturn([], [1], []);

        $sitemapFileTransfer = (new SitemapFileBuilder(['yvesBaseUrl' => 'test1.test']))->build();
        $filteredUrlList = [$url1, $url3];
        $this->sitemapServiceMock->expects($this->exactly(2))
            ->method(self::METHOD_CREATE_SITEMAP_XML_FILE_TRANSFER)
            ->withConsecutive(
                [$filteredUrlList, 1, self::STORE_DE, ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE],
                [[], 2, self::STORE_DE, ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE],
            )
            ->willReturnOnConsecutiveCalls($sitemapFileTransfer, null);

        $sitemapList = $this->sut->createSitemapXml();

        $this->assertCount(1, $sitemapList);
        $this->assertEquals($sitemapFileTransfer, $sitemapList[0]);
    }

    /**
     * @return void
     */
    private function mockStoreFacade(): void
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(self::STORE_DE)
            ->setIdStore(1);

        $storeFacadeMock = $this->createMock(StoreFacadeInterface::class);
        $storeFacadeMock->method(self::METHOD_GET_CURRENT_STORE)
            ->willReturn($storeTransfer);

        $this->tester->mockFactoryMethod(self::METHOD_GET_STORE_FACADE, $storeFacadeMock);
    }

    /**
     * @return void
     */
    private function mockSitemapService(): void
    {
        $this->sitemapServiceMock = $this->createMock(SitemapServiceInterface::class);
        $this->tester->mockFactoryMethod(self::METHOD_GET_SITEMAP_SERVICE, $this->sitemapServiceMock);
    }

    /**
     * @return void
     */
    private function mockProductListFacade(): void
    {
        $this->productListFacadeMock = $this->createMock(ProductListFacadeInterface::class);
        $this->tester->mockFactoryMethod(self::METHOD_GET_PRODUCT_LIST_FACADE, $this->productListFacadeMock);
    }

    /**
     * @return void
     */
    private function mockRepository(): void
    {
        $this->repositoryMock = $this->createMock(ProductConcreteSitemapConnectorRepository::class);
        $this->tester->mockFactoryMethod(self::METHOD_GET_REPOSITORY, $this->repositoryMock);
    }
}
