<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Attribute\Depends;
use Codeception\Util\HttpCode;
use PHPUnit\Framework\Attributes\Before;
use Tests\Support\ApiTester;

require_once "CreateEmployeeCest.php";

class DeleteEmployeeCest
{
    public int $employeeId;

    #[Before("deleteExistingEmployeePos")]
    public function preCondition(ApiTester $I): void
    {
        $email = CreateEmployeeCest::_createUniqueEmail($I, 4);
        $requestBody = [
            "name" => "Dboris",
            "email" => $email,
            "position" => "middle",
            "age" => 33
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
            "name" => "Dboris",
            "email" => $email,
            "position" => "middle",
            "age" => "33"
        ]);
    }

    // 08-DELETE-POS  - FAILED
    public function deleteExistingEmployeePos(ApiTester $I): void
    {
        $I->wantToTest("Delete employee with valid id");
        $I->sendDelete('/employee/remove/' . $this->employeeId);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $response = $I->sendGetAsJson('/employee/' . $this->employeeId);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContainsJson([
            "message" => "Employee not found",
        ]);
    }

    // 09-DELETE-NEG  - SKIPPED
    #[Depends('deleteExistingEmployeePos')]
    public function deleteNoneExistingEmployeeNeg(ApiTester $I): void
    {
        $I->wantToTest("Delete non-existing employee and get HttpCode 404.");
        $I->sendDelete('/employee/remove/' . $this->employeeId);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContainsJson(["message" => "Employee not found"]);
    }
}
