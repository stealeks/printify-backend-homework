# Install

0. Clone project
0. composer install
0. Copy `.env.example` to `.env`
0. Configure via `.env`
0. `/artisan key:generate`
0. `./artisan migrate:refresh --seed`
0. Launch via
    0. `./artisan serve` or
    0. Configure web server to `public` directory
0. Configure `storage` directory permissions (writable by web server)

## Tests

0. Copy `.env.example` to `.env.testing`
0. Configure via `.env.testing`
0. `composer test`

# API
###### (very bad documentation)

## Auth

0. Registration
0. After Login there will be `token` in response
0. Use `token` as `GET` parameter or `Authorization: Bearer {token}` header

## Endpoints

`POST` to `/api/auth/register` with 
```json
{
  "name": "Harry",
  "email": "harry@hogwarts.com",
  "password": "min8CharacterLengthPassword"
}
```
---

`POST` to `/api/auth/login` with 
```json
{
  "email": "harry@hogwarts.com",
  "password": "min8CharacterLengthPassword"
}
```

---
`GET` to `/api/business/classificators/types`

---
`GET` to `/api/business/classificators/colors`

---
`GET` to `/api/business/classificators/sizes`

---
`POST` to `/api/business/products/create` with 
```json
{
  "title": "Regular White Mug",
  "price": "1.99",
  "type_id": "{id from classificators API}",
  "color_id": "{id from classificators API}",
  "size_id": "{id from classificators API}"
}
```
---

`GET` to `/api/business/products/index`

---
`POST` to `/api/business/orders/create` with 
```json
{
  "items": [
    {
      "product_id": "{product_id from products APIs}",
      "quantity": 10
    },
    {
      "product_id": "{another product_id from products APIs}",
      "quantity": 50
    }
  ]
}
```

---
`GET` to `/api/business/orders/index`

`GET` to `/api/business/orders/index/{id from classificators API}`

