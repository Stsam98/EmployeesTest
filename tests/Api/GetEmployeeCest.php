<?php

declare(strict_types=1);

namespace Tests\Api;


use Codeception\Attribute\Depends;
use Codeception\Util\HttpCode;
use PHPUnit\Framework\Attributes\Before;
use Tests\Support\ApiTester;

class GetEmployeeCest
{
    public int $employeeId;

    #[Before("getExistingEmployee")]
    public function preCondition(ApiTester $I): void
    {

        $requestBody = [
            "name" => "boris",
            "email" => "borisov@yandex.ru",
            "position" => "middle",
            "age" => 29
        ];
        $response = $I->sendPostAsJson('/employee/add', $requestBody);

        $I->seeResponseMatchesJsonType([
            'id' => 'integer'
        ]);
        $this->employeeId = $response['id'];

    }

    // 06-GET-POS FAILED
    public function getExistingEmployee(ApiTester $I): void
    {
        $response = $I->sendGetAsJson('/employee/' . $this->employeeId);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            "id" => $this->employeeId,
            "name" => "boris",
            "email" => "borisov@yandex.ru",
            "position" => "middle",
            "age" => 29
        ]);
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'email' => 'string',
            'position' => 'string',
            'age' => 'integer'
        ]);
    }

    #[Depends('Tests\Api\DeleteEmployeeCest:deleteExistingEmployeePos')]
    // 07-GET-NEG SKIPPED
    public function getNonExistentEmployee(ApiTester $I): void
    {
        $response = $I->sendGetAsJson('/employee/' . $this->employeeId);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseMatchesJsonType([
            'message' => 'string'
        ]);
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
}
