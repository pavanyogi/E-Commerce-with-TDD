<?php
namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Product;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use AppBundle\Constants\GeneralConstants;

class ProductRepositoryTest extends BaseRepositoryTest
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        $productRepo = $this->entityManager->getRepository(Product::class);
        $lockProductTestCases = (new RepositoryTestCase())
            ->lockProductTestCase();
        foreach ($lockProductTestCases as $key => $value) {
            $product = $productRepo->findOneById($value['productIds']);
            $product->setStatus(GeneralConstants::$productStatusMap[ GeneralConstants::PRODUCT_STATUS_ACTIVE]);
        }
        $this->entityManager->flush();
        parent::tearDown();
    }

    /**
     * @test
     * @dataProvider fetchProductListDataProvider
     */
    public function fetchProductListDataTest($filter, $pagination) {
        $products = $this->entityManager
            ->getRepository(Product::class)
            ->fetchProductListData($filter, $pagination);
        if($this->count($products)>0) {
            $this->assertNotNull($products);
        } else {
            $this->assertNull($products);
        }
    }

    public function fetchProductListDataProvider() {
        $fetchProductListTestCase = (new RepositoryTestCase())->productRepfetchProductListTestCase();

        return $fetchProductListTestCase;
    }

    /**
     * @test
     * @dataProvider countProductRecordsDataProvider
     */
    public function countProductRecordsTest($filter) {
        $productCount = $this->entityManager
            ->getRepository(Product::class)
            ->countProductRecords($filter);
        $this->assertInternalType("int", $productCount);
    }

    public function countProductRecordsDataProvider() {
        $countProductRecordsTestCase = (new RepositoryTestCase())->countProductRecordsTestCase();

        return $countProductRecordsTestCase;
    }

    /**
     * @test
     * @dataProvider fetchProductForOrderTestDataProvider
     */
    public function fetchProductForOrderTest($orderItem) {
        $this->entityManager->getConnection()->beginTransaction();
        $productArray = $this->entityManager
            ->getRepository(Product::class)
            ->fetchProductForOrder($orderItem);
        $this->entityManager->getConnection()->commit();
        $this->assertNotNull($productArray);
    }

    public function fetchProductForOrderTestDataProvider() {
        $fetchProductForOrderTestCase = (new RepositoryTestCase())->fetchProductForOrderTestCase();

        return $fetchProductForOrderTestCase;
    }

    /**
     * @test
     * @dataProvider fetchProductForOrderInvalidQuantityDataProvider
     */
    public function fetchProductForOrderTestThrowExceptionForInvalidQuantity($orderItem) {
        $this->expectException(UnprocessableEntityHttpException::class);
        $this->entityManager
            ->getRepository(Product::class)
            ->fetchProductForOrder($orderItem);
    }

    public function fetchProductForOrderInvalidQuantityDataProvider() {
        $fetchProductForOrderTestCase = (new RepositoryTestCase())->fetchProductForOrderInvalidQuantityTestCase();

        return $fetchProductForOrderTestCase;
    }

    /**
     * @test
     * @dataProvider fetchProductForOrderInvalidProductCodeDataProvider
     */
    public function fetchProductForOrderTestThrowExceptionForInvalidProductCode($orderItem) {
        $this->expectException(UnprocessableEntityHttpException::class);
        $this->entityManager
            ->getRepository(Product::class)
            ->fetchProductForOrder($orderItem);
    }

    public function fetchProductForOrderInvalidProductCodeDataProvider() {
        $fetchProductForOrderInvalidProductCodeTestCase = (new RepositoryTestCase())
            ->fetchProductForOrderInvalidProductCodeTestCase();

        return $fetchProductForOrderInvalidProductCodeTestCase;
    }

    /**
     * @test
     * @dataProvider lockProductDataProvider
     */
    public function lockProductTestCase($productIds) {
        $this->entityManager
            ->getRepository(Product::class)
            ->lockProduct($productIds);
        $this->assertTrue(true);
    }

    public function lockProductDataProvider() {
        $lockProductTestCase = (new RepositoryTestCase())
            ->lockProductTestCase();

        return $lockProductTestCase;
    }
}