
---------------------------------------------------------------------------------
01-POST-POS

Name: Create new employee with valid values.
Requirement: №4
Module: Employee
Submodule: Create an employee
Steps:
1. Send post request /api/v1/employee/add
Request body example:
{
"name": "string",
"email": "string@domain.ru",
"position": "string",
"age": 0
}
2. Get the ID of the created user from the returned json
3. Send get request /api/v1/employee/{id}
Expected result:
1. HTTP Status: 201
2. Get some id in json format, for example: {"id": 0 }
3. User added to database.
---------------------------------------------------------------------------------
02-POST-NEG

Name: Create without required fields
Requirement №4.2
Module: Employee
Submodule: Create an employee
Steps: 
Make 4 attempts to register an employee without one of the required fields:
{
"name": "string",
"email": "string@domain.ru",
"position": "string",
"age": 0
}
Send 4 post request /api/v1/employee/add
1. without name
2. without email
3. without position
4. without age
   Expected result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 400
4. HTTP Status: 400

---------------------------------------------------------------------------------
03-POST-NEG

Name: Mask email
Requirement №4.2
Module: Employee
Submodule: Create an employee
Steps:
Make post requests /api/v1/employee/add with invalid emails for check mask email "user_name@server_name.domain"
Expected result:
HTTP Status: 400

---------------------------------------------------------------------------------
04-POST-NEG

Name: Create with empty fields
Requirement №4.1, №4.3, №4.4
Module: Employee
Submodule: Create an employee
Steps: Fields "name", "age", "position", "email" cannot be empty
Make 4 post requests /api/v1/employee/add with:
1. name: null
2. position: null
3. age: null
4. email: null
Expected result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 400
4. HTTP Status: 400
---------------------------------------------------------------------------------
05-POST-NEG

Name: Create with incorrect types
Requirement №4.1, №4.3, №4.4
Module: Employee
Submodule: Create an employee
Steps: 
Fields "name", "age", "position" must be of the correct type 
({"name":"string", "age":"integer", "position":"string"}).
Make 3 post requests /api/v1/employee/add with:
1. name: not string
2. position: not string
3. age: not integer
Expected result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 400
---------------------------------------------------------------------------------
06-GET-POS

Name: Get an employee using an id.
Requirement №3.
Module: Employee
Submodule: Get an employee
Preparation:
1. Send post request /api/v1/employee/add
   Request body example:
   {
   "name": "string",
   "email": "string@domain.ru",
   "position": "string",
   "age": 0
   }
2. Get the ID of the created user from the returned json
Steps: 
1. Send get request /api/v1/employee/{id}
Expected result:
The response body in JSON format is returned from the server:
{
"id": "integer",
"name": "string",
"email": "string",
"position": "string",
"age": "integer"
}
HTTP Status: 200
---------------------------------------------------------------------------------
07-GET-NEG

Name: Get a non-existent employee using an id
Requirement №3.
Module: Employee
Submodule: Get an employee
Preparation:
1. Send post request /api/v1/employee/add
   Request body example:
   {
   "name": "string",
   "email": "string@domain.ru",
   "position": "string",
   "age": 0
   }
2. Get the ID of the created user from the returned json
3. Send delete request /api/v1/employee/remove/{id}
Steps: 
1. Send get request /api/v1/employee/{id}
Expected result:
The response body in JSON format is returned from the server:
{
"message": "string",
}
HTTP Status: 404
---------------------------------------------------------------------------------
08-DELETE-POS

Name: Delete an employee by id
Requirement №5.
Module: Employee
Submodule: Delete an employee
Preparation:
1. Send post request /api/v1/employee/add
   Request body example:
   {
   "name": "string",
   "email": "string@domain.ru",
   "position": "string",
   "age": 0
   }
2. Get the ID of the created user from the returned json
Steps: 
1. Send delete request /api/v1/employee/remove/{id}
2. Send get request /api/v1/employee/{id}
Expected result:
1. HTTP Status: 204
2. HTTP Status: 404
---------------------------------------------------------------------------------
09-DELETE-NEG

Name: Delete a non-existent employee
Requirement №5.
Module: Employee
Submodule: Delete an employee
Preparation:
1. Send post request /api/v1/employee/add
   Request body example:
   {
   "name": "string",
   "email": "string@domain.ru",
   "position": "string",
   "age": 0
   }
2. Get the ID of the created user from the returned json
3. Send delete request /api/v1/employee/remove/{id}
   Steps:
1. Send delete request /api/v1/employee/remove/{id}
   Expected result:
1. HTTP Status: 404
   The response body in JSON format is returned from the server:
   {
   "message": "Employee not found",
   }
---------------------------------------------------------------------------------
10-POST-NEG

Name: Create with non-unique email
Requirement №4.
Module: Employee
Submodule: Create an employee
Preparation:
1. Send post request /api/v1/employee/add
   Request body example:
   {
   "name": "string",
   "email": "string@domain.ru",
   "position": "string",
   "age": 0
   }
2. Get the ID of the created user from the returned json
Steps: 
1. Repeat step №1 from preparation.
Expected result:
1. HTTP Status: 400

