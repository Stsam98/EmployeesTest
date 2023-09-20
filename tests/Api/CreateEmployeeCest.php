<?php

declare(strict_types=1);

namespace Tests\Api;

require_once 'vendor/autoload.php';

use Codeception\Attribute\Depends;
use Codeception\Example;
use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;

class CreateEmployeeCest
{
    public int $employeeId;

    public string $randomEmail;

    public function createEmployeeValid(ApiTester $I): void
    {
        $this->randomEmail = $this->_createUniqueEmail($I, 3);

        $I->wantToTest("Create new employee with valid values");

        $requestBody = [
            "name" => "boris",
            "email" => $this->randomEmail,
            "position" => "middle",
            "age" => 29
        ];
        $response = $I->sendPostAsJson('/employee/add', $requestBody);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer'
        ]);
        $this->employeeId = $response['id'];
        $response = $I->sendGetAsJson('/employee/' . $this->employeeId);
        $I->seeResponseContainsJson([
            "id" => $this->employeeId,
            "name" => "boris",
            "email" => $this->randomEmail,
            "position" => "middle",
            "age" => 29
        ]);
    }

    // 01-POST-POS - PASSED

    /**
     * Generate unique email
     * @param ApiTester $I
     * @param int $attempts
     * @return string
     */
    public static function _createUniqueEmail(ApiTester $I, int $attempts): string
    {
        $attempts -= 1;
        // get all employees
        $response = $I->sendGet('/employees/all');
        $jsonArray = json_decode($response, true);
        // randomly generate email
        $randomString = uniqid('sam_', true);
        $randomEmail = $randomString . '@example.com';

        // check if email is exist - generate again
        foreach ($jsonArray as $item) {
            if (isset($item['email']) && $item['email'] === $randomEmail) {
                if ($attempts == 0) return "erroremail";
                return self::_createUniqueEmail($I, $attempts);
            }
        }
        return $randomEmail;
    }

    // 02-POST-NEG- FAILED

    /** @dataProvider emptyFieldsProvider */
    public function createEmployeeWithoutFields(ApiTester $I, Example $requestBody): void
    {
        $I->wantToTest("Create new employee without required fields. Expected result: code 400");
        $I->sendPostAsJson('/employee/add', $requestBody[0]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /** @dataProvider emailProvider */
    public function createEmployeeInvalidEmail(ApiTester $I, Example $email): void
    {
        $I->wantToTest("Create employee with different invalid emails. Expected result: code 400");
        $requestBody = [

            "name" => "Konstantin",
            "email" => $email[0],
            "position" => "middle",
            "age" => 29
        ];
        $I->sendPostAsJson('/employee/add', $requestBody);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string'
        ]);
    }

    // 03-POST-NEG- PASSED

    /** @dataProvider fieldsProvider */
    public function createEmployeeEmptyFields(ApiTester $I, Example $requestBody): void
    {
        $I->wantToTest("Create new employee with empty name/position/age/email and get code 400'");
        $I->sendPostAsJson('/employee/add', $requestBody[0]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseMatchesJsonType([
            'message' => 'string'
        ]);
    }

    /** @dataProvider invalidTypesProvider */
    public function createEmployeeInvalidTypes(ApiTester $I, Example $requestBody): void
    {
        $I->wantToTest("Create new employee with incorrect data types (name/position/age) and get code 400'");

        $I->sendPostAsJson('/employee/add', $requestBody[0]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string'
        ]);
    }

    //  04-POST-NEG - FAILED
    #[Depends('createEmployeeValid')]
    public function createEmployeeNonUniqueEmail(ApiTester $I): void
    {
        $I->wantToTest("Create new employee with non-unique email");
        $requestBody = [
            "name" => "boris",
            "email" => $this->randomEmail,
            "position" => "middle",
            "age" => 29
        ];
        $response = $I->sendPostAsJson('/employee/add', $requestBody);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * Delete employee after the work
     * @param ApiTester $I
     * @return void
     */
    public function postCondition(ApiTester $I): void
    {
        $I->sendDelete('/employee/remove/' . $this->employeeId);
    }

    //   05-POST-NEG - FAILED
    protected function emptyFieldsProvider(): iterable
    {
        yield
        [
            [
                "email" => "borisov@yandex.ru",
                "position" => "middle",
                "age" => 29
            ]
        ];
        yield [
            [
                "name" => "boris",
                "position" => "middle",
                "age" => 29
            ]];
        yield [
            [
                "name" => "boris",
                "email" => "borisov@yandex.ru",
                "age" => 29
            ]];
        yield [
            [
                "name" => "boris",
                "email" => "borisov@yandex.ru",
                "position" => "middle",
            ]];
    }

    protected function emailProvider(): iterable
    {
        yield ['examplin'];
        yield ['examplin@'];
        yield ['examplin@.'];
        yield ['examplin@yandex.'];
    }

    //  10-POST-NEG - FAILED
    protected function fieldsProvider(): iterable
    {
        yield
        [
            [
                "name" => "",
                "email" => 'examplin@yandex.ru',
                "position" => "middle",
                "age" => 29
            ]
        ];
        yield [
            [
                "name" => "George",
                "email" => 'examplin@yandex.ru',
                "position" => "",
                "age" => 29
            ]];
        yield [
            [
                "name" => "George",
                "email" => 'examplin@yandex.ru',
                "position" => "middle",
                "age" => null
            ]];
        yield [
            [
                "name" => "George",
                "email" => '',
                "position" => "middle",
                "age" => 29
            ]];
    }

    protected function invalidTypesProvider(): iterable
    {
        yield [[

            "name" => 123,
            "email" => 'examplin@yandex.ru',
            "position" => "middle",
            "age" => 29
        ]];
        yield [[

            "name" => "George",
            "email" => 'examplin@yandex.ru',
            "position" => 123,
            "age" => 29
        ]];
        yield [[

            "name" => "George",
            "email" => 'examplin@yandex.ru',
            "position" => "middle",
            "age" => "SomeWord"
        ]];
    }
}
