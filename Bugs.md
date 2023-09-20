01-POST-POS - PASSED
---------------------------------
---------------------------------
02-POST-NEG - FAILED
---------------------------------
Steps:
Make 4 attempts to register an employee without one of the required fields (name, email, position, age)
1.   
[
     "email" => "borisov@yandex.ru",
     "position" => "middle",
     "age" => 29
     ]
2.  
[
    "name" => "boris",
    "position" => "middle",
    "age" => 29
    ]
3.  
[
    "name" => "boris",
    "email" => "borisov@yandex.ru",
    "age" => 29
    ]
4.   
[
     "name" => "boris",
     "email" => "borisov@yandex.ru",
     "position" => "middle",
     ]
Expected result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 400
4. HTTP Status: 400
   Actual result:
1. HTTP Status: 201
2. HTTP Status: 400
3. HTTP Status: 201
4. HTTP Status: 500
---------------------------------
03-POST-NEG - PASSED
---------------------------------
04-POST-NEG - FAILED
---------------------------------
Case: Fields "name", "age", "position", "email" cannot be empty
Steps: 
Make 4 attempts to register an employee with null fields (name, email, position, age)
1.
[
    "name" => "",
    "email" => 'examplin@yandex.ru',
    "position" => "middle",
    "age" => 29
]
2.
[
    "name" => "George",
    "email" => 'examplin@yandex.ru',
    "position" => "",
    "age" => 29
]
3.
[
    "name" => "George",
    "email" => 'examplin@yandex.ru',
    "position" => "middle",
    "age" => null
]
4.
[
    "name" => "George",
    "email" => '',
    "position" => "middle",
    "age" => 29
]
Expected result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 400
4. HTTP Status: 400
Actual result:
1. HTTP Status: 201
2. HTTP Status: 201
3. HTTP Status: 201
4. HTTP Status: 400
---------------------------------
05-POST-NEG - FAILED
---------------------------------
Steps:
Make 3 attempts to register an employee with incorrect data types (name/position/age)
1.
[

            "name" => 123,
            "email" => 'examplin@yandex.ru',
            "position" => "middle",
            "age" => 29
        ]
2.
[

            "name" => "George",
            "email" => 'examplin@yandex.ru',
            "position" => 123,
            "age" => 29
        ]
3.
[

            "name" => "George",
            "email" => 'examplin@yandex.ru',
            "position" => "middle",
            "age" => "SomeWord"
        ]
Expected result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 400
   Actual result:
1. HTTP Status: 400
2. HTTP Status: 400
3. HTTP Status: 201
---------------------------------
06-GET-POS - FAILED
---------------------------------
Get information about an employee using a unique id record.
Steps:
1. Create a employee
2. Get the id of the created employee
3. Get request /api/v1/employee/{id}
Expected result:
{
"id": "integer",
"name": "string",
"email": "string",
"position": "string",
"age": "integer"
}
HTTP Status: 201
Actual result:
{
Actual result:
"id": "integer",
"name": "string",
"email": "string",
"position": "string",
"age": "string"
}
HTTP Status: 201

---------------------------------
07-GET-NEG  - SKIPPED
---------------------------------
Case: Get a non-existent employee by id
To get a non-existent user, you must first delete the existing one and check that there really is no such user.
This cannot be done because the application has a bug and the user cannot be deleted.
---------------------------------
08-DELETE-POS - FAILED
---------------------------------
Case: Delete an employee by id
1. Send delete request /api/v1/employee/remove/{id}
2. Send get request /api/v1/employee/{id}
   Expected result:
1. HTTP Status: 204
2. HTTP Status: 404
Actual result:
1. HTTP Status: 204
2. HTTP Status: 204

---------------------------------
09-DELETE-NEG - SKIPPED
---------------------------------
Case: Delete a non-existent employee by id
To delete a non-existent user, you must first delete the existing one and check that there really is no such user. 
This cannot be done because the application has a bug and the user cannot be deleted.
---------------------------------
10-POST-NEG - FAILED
---------------------------------
Case: Create new employee with non-unique email.
1. Create an employee with post request /api/v1/employee/add
   Request body:
   {
   "name": "string",
   "email": "string@domain.ru",
   "position": "string",
   "age": 0
   }
2. Repeat first step with same request body 
Expected result:
HTTP Status: 400
Actual result:
HTTP Status: 201