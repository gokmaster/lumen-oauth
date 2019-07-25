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
The **test-with-postman.pdf** file located in this repository provides examples with screenshots on how to test this API. The steps are also discussed in the following sections below:

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
    "message": "Login successful",
    "user-details": {
        "name": "mike",
        "email": "mike@test.com"
    },
    "auth": {
        "token_type": "Bearer",
        "expires_in": 31622400,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjJjYmYwMGJmZWRhNjcyNjI3MjE5Yjk2Y2VmYzc4YjhlYTg1MTQ5MjliNjM1NmRjNWUzYmIyMjYzOTViZDMwMmIxYmUxZDc2ZmZhMTc4Mzg2In0.eyJhdWQiOiIyIiwianRpIjoiMmNiZjAwYmZlZGE2NzI2MjcyMTliOTZjZWZjNzhiOGVhODUxNDkyOWI2MzU2ZGM1ZTNiYjIyNjM5NWJkMzAyYjFiZTFkNzZmZmExNzgzODYiLCJpYXQiOjE1NjQwMzk1NzksIm5iZiI6MTU2NDAzOTU3OSwiZXhwIjoxNTk1NjYxOTc5LCJzdWIiOiIxMCIsInNjb3BlcyI6WyIqIl19.be1LvjKitEhvHHEmPYIze-czylWI3v1fwNxWd0j5TUUvCVu9yD3rg3wtvv0kWNtCo5wOnfz04Qtsvuxx6VPT72lDG00vyjcgP3sH53JbW_7_wyOQSQiYvCSk8zlHF_KttyTL_G5kA0ULZAPODglU4LWxmx82iFXi3-p2HjZsSHyTmFFylYcVpQ5PaUMPOaiKvkNx93wpgo_dY2wY68in7loEv0WtoA0D0ukbbRncieC0UjkX-86Px2pitMK0Y9Umbp-_v9aXtEjgzvqPZF8IgHgveedcIUfX4VMX-84PDG7kCZm_UJcCi9a93WrmfAt2l1F-Bml5u76U4ly_jERt9YdQMxs674KB7MY_8Jx_8-ktMda69SQ6KnEudS5NPiFqb6RhfYUYzURH2ZwU1vRaMrT48FpafuHJ_3rcN49Icza6Xf-jbuqEFcTB1xnAagik8L34nJFwzLHYWMMHFIcGIXapoVuEl_GWmkWMX9btcfNHYQu9cGZFTMWGCSK4EWuZJ9amJPkNZBnEDYA2Ou8PVmJQXMHMrCYcOvHNIgrXvmE_nLhFOgz-cwVCyflIS1e5oqt9HlZ8avH3CVYKlFNJJkwRsDW6d7kcmmlSNL8_su7SypzTltP78G9T-dkYqAjJa4t7Kd96OyUm_Wx_DtsotJpya4fRdUkPhsANyblCBcA",
        "refresh_token": "def50200b3b0ea5dc930a3fe606e582a1ab10682f297878c13d6296c134d6712c69e9cba2a01eac676950057ec4dce1adbe66bf36ff5aa2b66d405d59c66563652eeaa4cca6f458455930835ef2ff9e4502c0ecd0d371a92961ff626c59e0b4226d449772f7a14df416fee4c5e136d1cd6f08ed28e83fc60a878aa43437d718918b881450e329cfa453b492bdc2586ec095c68003fdae587a7892796c813355c455d8668e2ecbf5dbe148f32788fcb1d9eebae3e6a7499c044a5af8b0e2db8efc93ce7f203ec47a0c0e7a361b3453225b699017ce78fa6f3f01f589a49134e1fd585904983900b779043c6b55fce182a320d4245b7ea24a0db5d509674b598242d04e0fee721353ca43025c4bbe4cf50212b4f321de71bd042e83a7db7202a15a7f77eb3b1369e4f8018f4c6091f5bfbc01dec6703a79511232847431b7ce0ef2b6a9eba09c2499320459a1115e1966e5c70eede22727c4e31e639f919415e83202bb2"
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
    "name": "mike",
    "email": "mike@test.com",
    "created_at": "2019-07-25 07:11:20"
}
```

