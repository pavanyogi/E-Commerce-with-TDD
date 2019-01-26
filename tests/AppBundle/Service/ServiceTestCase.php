<?php
/**
 *  ServiceTestCase Class for providing the test case to service class.
 *
 *  @category ServiceTestCase
 *  @author Prafulla Meher
 */
namespace Tests\AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\PropertyAccess\PropertyAccess;
use AppBundle\Entity\User;

class ServiceTestCase
{
    public function getUserTestCase() {
        // Making the testcase0
        $testData0['inputData'] = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['user'] = $this->createObjectFromArray($testData0['inputData'], User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getInvalidCredentialUserTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['credentials'] = [
            'username' => 'testUser',
            'password' => '123',
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    /**
     *  Function to fill Array data into the Class Object.
     *
     *  @param array $data
     *  @param string $className
     *  @param object $object
     *
     *  @return object
     *  @throws \Exception
     */
    public function createObjectFromArray($data, $className, $object)
    {
        try {
            // Checking the Object and Class Name Provided
            if (!empty($object) && !$object instanceof $className) {
                throw new \Exception('Invalid parameters provided to function ' . __FUNCTION__);
            }

            if (empty($object)) {
                $resourceClass = new \ReflectionClass($className);
                if (!$resourceClass->isInstantiable()) {
                    throw new \Exception($className . ' class name passed to function ' . __FUNCTION__ .
                        ' is not instantiable');
                }

                $object = $resourceClass->newInstance();
            }

            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            // filling Array data to Object.
            // Note: All the properties of the class should be should be available
            // to be set by Setters.
            foreach ($data as $attribute => $value) {
                if (!$propertyAccessor->isWritable($object, $attribute)) {
                    throw new \Exception('Invalid array data provided to function ' . __FUNCTION__);
                }
                $propertyAccessor->setValue($object, $attribute, $value);
            }

            return $object;
        } catch (\Exception $ex) {

        }
    }
}