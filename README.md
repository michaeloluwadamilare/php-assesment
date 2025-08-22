# php-assesment

Assessment 1

The index page is the entry point of the code implemented which routes you to the registration page. On successful registeration a user  is routed to the login page.

The database configuration are found in the config folder and the database schema is found in the database folder.

Assessment 2

API Usage Examples
GET all tasks:

curl -X GET http://localhost/api/tasks.php

POST new task:

curl -X POST http://localhost/api/tasks.php \
-H "Content-Type: application/json" \
-d '{"task_name":"Test Task","description":"Test Description","user_id":1}'

PUT update task:

curl -X PUT http://localhost/api/tasks.php?id=1 \
-H "Content-Type: application/json" \
-d '{"task_name":"Updated Task","description":"Updated Description","status":"completed"}'

DELETE task:

curl -X DELETE http://localhost/api/tasks.php?id=1