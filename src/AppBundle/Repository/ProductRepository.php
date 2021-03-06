<?php

namespace AppBundle\Repository;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Constants\ErrorConstants;
use Doctrine\DBAL\LockMode;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function fetchProductForOrder($orderItems)
    {
        try {
            $productCodes = array_keys($orderItems);
            $qb = $this->createQueryBuilder('p')
                ->select('p.id', 'p.pricePerUnit', 'p.quantity', 'p.productCode')
                ->where('p.productCode IN (:productCodes) AND p.status = :productStatus')
                ->setParameter('productCodes', $productCodes)
                ->setParameter('productStatus',
                    GeneralConstants::$productStatusMap[GeneralConstants::PRODUCT_STATUS_ACTIVE]);

            $products = $qb->getQuery();

            if (count($products->getArrayResult()) !== count($orderItems)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PRODUCT_QUANTITY);
            }

            $productArray = [];
            foreach ($products->getArrayResult() as $product) {
                if ($product['quantity'] < $orderItems[$product['productCode']]['quantity']) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PRODUCT_QUANTITY);
                }
                $product['quantity'] = $orderItems[$product['productCode']]['quantity'];
                array_push($productArray, $product);
            }
            $products->setLockMode(LockMode::PESSIMISTIC_WRITE)->getArrayResult();

            return $productArray;
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (HttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    public function lockProduct($productIds) {
        $qb = $this->createQueryBuilder('p')->update()
            ->set('p.status', ':productStatus')
            ->where('p.id IN (:productIds)')
            ->setParameter('productStatus',
                GeneralConstants::$productStatusMap[ GeneralConstants::PRODUCT_STATUS_LOCKED])
            ->setParameter('productIds', $productIds);

        $qb->getQuery()->execute();
    }

    /**
     *  Function to FETCH the users data as per provided filters.
     *
     *  @param array $filter
     *  @param array $pagination
     *
     *  @return array
     */
    public function fetchProductListData($filter = [], $pagination = [])
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p.id')
            ->addSelect('p.productCode')
            ->addSelect('p.productName')
            ->addSelect('p.productDescription')
            ->addSelect('p.quantity')
            ->addSelect('p.stockAvialable');

        // Applying Filters.
        $qb = $this->addFilterSortParameters($qb, $filter);
        $qb->setFirstResult(($pagination['page'] - 1) * $pagination['limit'])
            ->setMaxResults($pagination['limit'])
        ;

        return $qb->getQuery()->getArrayResult();
    }

    /**
     *  Function to count the number of records for applied filters and sort
     *  parameters.
     *
     *  @param array $filter
     *
     *  @return integer
     */
    public function countProductRecords($filter)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('count(p.id) as totalRecords')
        ;

        // Applying Filters.
        $qb = $this->addFilterSortParameters($qb, $filter);

        return (int)$qb->getQuery()->getArrayResult()[0]['totalRecords'];
    }

    /**
     *  Function to add Filter and Sort parameters to List Query Builder.
     *
     *  @param QueryBuilder $qb
     *  @param array $filter
     *
     *  @return QueryBuilder
     */
    public function addFilterSortParameters($qb, $filter = [])
    {
        $params = [];

        // Adding Filters to QueryBuilder
        if (isset($filter['productCode'])) {
            $qb->where('p.productCode LIKE :productCode');
            $params['productCode'] = '%'.$filter['productCode'].'%';
        }

        if (isset($filter['productName'])) {
            $qb->andWhere('p.productName LIKE :productName');
            $params['productName'] = '%'.$filter['productName'].'%';
        }

        if (isset($filter['productDescription'])) {
            $qb->andWhere('p.productDescription LIKE :productDescription');
            $params['productDescription'] = '%'.$filter['productDescription'].'%';
        }

        if (isset($filter['quantity'])) {
            $qb->andWhere('p.quantity = :quantity');
            $params['quantity'] = $filter['quantity'];
        }

        if (isset($filter['stockAvialable'])) {
            $qb->andWhere('p.stockAvialable = :stockAvialable');
            $params['stockAvialable'] = $filter['stockAvialable'];
        }

        // Setting the Parameters.
        $qb->setParameters($params);

        return $qb;
    }
}
