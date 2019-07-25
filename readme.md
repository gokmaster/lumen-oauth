# Lumen Authentication API (with Passport)

This is a simple example to show a Lumen Authentication API which works with Passport (OAuth 2.0).

It uses service provider from : https://github.com/dusterio/lumen-passport 

### Installation
Clone this repository. Run the following commands:

```sh
$ cd lumen-oauth
$ cp .env.example .env
$ composer update
$ php artisan key:generate
```

Create a database and and modify the `.env` file with your database details like following example:

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yourDatabaseName
DB_USERNAME=root
DB_PASSWORD=
```

Now run the following commands:

```sh
$ php artisan migrate --seed
$ php artisan passport:install
```
You will get this set of messages:
```sh
Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client Secret: aW1qHacErLrFquQfjoAuVIO0cWnlKNXM5LDhXjLi
Password grant client created successfully.
Client ID: 2
Client Secret: dYl1Fgom4LjdkOkvZmhhSMdFDZGQLVnapFokWtMW
```

You should see two client ID and secret pairs.
The first one is Personal Access Client and second one is Password Grant Client.
The Password Grant Client will allow us to generate new tokens for users.

### Testing the API

#### Register User
Using Postman, send a **POST** request to `{{url}}/api/register` with following **form-data** in **Body**:

```
name = anyname
email = example@test.com
password = anystring
```

You should get following response if registration was successful:

```json
{
    "status": "success",
    "message": "User registration successful"
}
```

#### User Login
Using Postman, send a **POST** request to `{{url}}/api/login` with following **form-data** in **Body**:

```
email = Email-you-registered-above
password = Password-you-registered-above
```

You should get a response like the one below if login was successful:

```json
{
    "status": "success",
    "message": "Login successful",
    "user-details": {
        "name": "Mark",
        "email": "mark@test.com"
    },
    "auth": {
        "token_type": "Bearer",
        "expires_in": 31622400,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc2OTMzMzU3MzFhMTdjMDlkYWI2ZmUwNmE4N2M0MTdkZTU2ODQxOWI1OTk1YWI3NGNlYmIyZTQxZTdiYWJiNzQ2ZDM5NjRiMDhhMDdkYWE5In0.eyJhdWQiOiIyIiwianRpIjoiNzY5MzMzNTczMWExN2MwOWRhYjZmZTA2YTg3YzQxN2RlNTY4NDE5YjU5OTVhYjc0Y2ViYjJlNDFlN2JhYmI3NDZkMzk2NGIwOGEwN2RhYTkiLCJpYXQiOjE1NjQwMzEyMTMsIm5iZiI6MTU2NDAzMTIxMywiZXhwIjoxNTk1NjUzNjEzLCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.QuISFTMivMiceLhQECLDwzutnFPvfIfUqBJ5aAOWissVqK1biPcNkL_ceC2piLt6CZUpnxOwNNaSwTan0IRDS-5pUUeEZEZfOYUZzM-WQ6SPFHEIsg2Td6RvUIDxQtp8L8nhHAF9cO83taEDZX6bxga9UEUU3hnFodK5qdmIn0gHzdyhSJQ002lJGLK8uUn_0IvXLOZuL6_X01OWCQYOpDtzGvVb-rh3yhJM2jo2lRarwYEnRueLMyaKEORGtUlwCh-_OdiXnUn2qp4pU1czjD3rJ0nA-ZnZE3c27ZEYhjD1kgJ5_Iaw0pkxELzgqo53JzS9c88xZDNLYPgkHnNau2GyQvdZiZrpFvc2EZX4gwI6b2YxTDAWFqEBYfKZWSli6FOG1nYpavRd_nsp_mq9o8VtAbwEdqfXTqJoL85VJRscF78YV7OSptneymYje_vDAGZZHOPQ8OGNLdcsGucDC1-pDdyL8Bhl5TAYn4R_UjucNmTtY93WYuz2guN0Um2HITCHBbhvxyQmTBL4TRhjLoMO-Ic7rEyGP3R8nLrp8i0PRcySFhGwVbC2pRZi_QZsIowbksmce2I_QCO-wuFE_5CrBXiJfikNZfxgFC-YAzBf02aoaHpqbHNeF-0Be4ocAKCIZJdtgOzMHgBORVdeo03ZuZ8hhHTdlqbN6Ow88p0",
        "refresh_token": "def50200a0fdf697ed3b41c3e82b94d724c1f736daadec47fa723d29c2c7b7ea873637a506b26bcca1544b373ef9e7b3d98c7a00dbdd6fd3d0c5a172110d25a1a551fbff5e1b50029da972bb511c0c6ba2123667af21f0b325b603de69784d3474ad1ed1e48f59d0fec3b909b3818f31ca0413b584294005704150f127aa4b545a8c3196e2c35e33c4aeabde124b58a935428d675a561cab0da89914801db9983f0f2bdab4ef821c41034c2dc2ced696e1863c1ba96c5731d65eec60f4946a0342f86d85a843ac08c62e28dd5541b02943fd53f223b08f36ac83c23fdf0e0fe8f6e7fd9a5cef58ec1142404aa9cea26f145732f24e322a1bd43182a289088d62736cc5fcbfdd3a01c7d930e4e1a74e155ecc0294858cb90850a0b82550553a8f580cd2d33ef08d6399d8cc90fc0346361387e8b6e9048661f507d9127c6e425f2d0e25465cc45c4710eb9738d1813a04292564aa5e3b1efc71f8f0148119c21fdb0d"
    }
}
```

#### Get User Details
Using Postman, send a **GET** request to `{{url}}/api/users/{{user_id}}` with following key-values in **Headers**:

```
authorization = Bearer your-access-token-from-user-login
```

You should get a response like the one below if access-token was verified successful:

```json
{
    "name": "Mark",
    "email": "mark@test.com",
    "created_at": "2019-07-23 15:55:20"
}
```
