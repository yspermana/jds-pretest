
# JDS Pretest

Sample study case project for pretest in JDS recuitment, build with PHP Lumen Framework.




## Acknowledgements

 - [Lumen](https://lumen.laravel.com/docs/7.x)
 - [Composer](https://getcomposer.org/doc/00-intro.md)


## Installation

Install jds-pretset with composer, run this command in project directory.

```bash
  composer install
```

Setup project environtment in file .env, copy file from .env-sample and modify it to your database setup. Setup JWT key secret with string in bellow    

```bash
  JWT_KEY=jsdPr3Te5t@2022 
```

Run database migration for create table into database

```bash
  php artisan migrate 
```

## API Reference

#### Create authentication id and generate password

```http
  POST /auth/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `nik` | `string` | **Required**. Must be 16 digit |
| `role` | `string` | **Required**. Role of user |

#### Validate access user using nik and password

```http
  POST /auth/verify
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `nik` | `string` | **Required**. Must be 16 digit |
| `password` | `string` | **Required**. Password has been given in register process |

#### Validate access user using nik and password

```http
  POST /auth/claims
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token` | `string` | **Required**. Token has been given in verify process |




## Authors

- [@yspermana](https://github.com/yspermana)

