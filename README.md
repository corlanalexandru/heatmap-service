# heatmap-service

**Commands to run the application(from root directory)**

- `docker-compose up -d --build --force-recreate`
- `docker-compose run --rm php74-service composer install`
- `docker-compose run --rm php74-service php bin/console doctrine:database:create`
- `docker-compose run --rm php74-service php bin/console doctrine:migrations:migrate`

**Provide starter testing data for the application(from root directory)**
- `docker-compose run --rm php74-service php bin/console database:provide:junk:data`

**Run tests and code analysis(from root directory)**
- `composer run-tests` - unit tests defined in tests/ directory
- `composer run-code-analyse` - run code analyse on level 5 - https://phpstan.org/user-guide/rule-levels

**REST API - Documentation**

**STORE CUSTOMER VISIT -** `POST`

- Endpoint : http://localhost:8080/api/visit
- Body(Mandatory) :
    ```yaml
    {
         "url" : "https://www.example.ro/product/4",
         "type" : "product",
         "customer" : "1234567890"
    } 
    ```
- Description: If the customer unique identifier is not found in database it will create a new customer
- Response Success : 
    - Body:
        ```yaml 
            {
                 "message": "Resource created!"
            }
        ```
    - Status code: `201`
    
- Response Fail : 
    - Body: 
        ```yaml 
        {
             "message": "Validation Failed!",
             "errors": [
                 "The key customer must be specified and not empty!"
             ]
        }
       ```
    - Status code: `400`
    

**GET CUSTOMER JOURNEY -** `GET`

- Endpoint : http://localhost:8080/api/customer/{UID}/journey
- Parameters : `{UID}` - unique identifier for customer
- Optional GET parameters:
    - `from` - any string considered true by strtotime function. Example `from=2021-05-15`
    - `until` - any string considered true by strtotime function. Example `from=2021-05-16`
    - `limit` - if not set, default limit is 100
- Description: Returns a JSON response with the list of visit from client journey
- Response Success : 
    - Body: 
        ```yaml
            [
                 {
                     "url": "https://www.example.com/product/81",
                     "parameters": null,
                     "fullUrl": "https://www.example.com/product/81",
                     "type": {
                         "id": 1,
                         "name": "Product"
                     },
                     "createdAt": "2021-05-16T13:06:55+00:00"
                 }
            ]
        ```
    - Status code: `200`
    
- Response Fail : 
    - Body: 
        ```yaml
            {
                 "message": "Resource not found!"
            }
        ```
    - Status code: `404`
    - Description: If customer UID is not found in database, the response will be 404 with the given body
    

**GET HITS FOR EACH TYPE IN DATABASE -** `GET`

- Endpoint : http://localhost:8080/api/types/hits
- Optional GET parameters:
    - `from` - any string considered true by strtotime function. Example `from=2021-05-15`
    - `until` - any string considered true by strtotime function. Example `from=2021-05-16`
- Description: Returns a JSON response with the list containing each type and hits count
- Response Success : 
    - Body: 
        ```yaml
          [
             {
                 "hits": 9,
                 "name": "Product"
             },
             {
                 "hits": 2,
                 "name": "Category"
             },
             {
                 "hits": 2,
                 "name": "Checkout"
             },
             {
                 "hits": 2,
                 "name": "Homepage"
             },
             {
                 "hits": 0,
                 "name": "Static page"
             }
         ]
        ```
    - Status code: `200`
    
- Response Fail : 
    - Body: 
        `[]`
    - Status code: `200`
    - Description: Empty response if there are no registered types in database
    
**GET HITS FOR EACH LINK IN DATABASE -** `GET`

- Endpoint : http://localhost:8080/api/links/hits
- Optional GET parameters:
    - `from` - any string considered true by strtotime function. Example `from=2021-05-15`
    - `until` - any string considered true by strtotime function. Example `from=2021-05-16`
    - `exact` - DEFAULT VALUE `true` - if the value is set to true matching is done by fullUrl(contains GET parameters,fragments, etc), if set to false matching is done by url without GET and fragment parameters
- Description: Returns a JSON response with the list containing each distinct link from database and hits count
- Response Success : 
    - Body:
        ```yaml 
        [
             {
                 "hits": 10,
                 "link": "https://www.example.com/product/1"
             },
             {
                 "hits": 5,
                 "link": "https://www.example.ro/asdsa/asdas/2342/asdas/123?askdks=12312"
             },
             {
                 "hits": 3,
                 "link": "https://www.example.com/product/65"
             },
             {
                 "hits": 3,
                 "link": "https://www.example.ro/product/4"
             },
             {
                 "hits": 2,
                 "link": "https://www.example.com/product/31"
             },
             {
                 "hits": 2,
                 "link": "https://www.example.com/product/95"
             },
             {
                 "hits": 2,
                 "link": "https://www.example.com/product/74"
             },
             {
                 "hits": 2,
                 "link": "https://www.example.ro/ddd/asdas/2342/asdaaas/123?askdks=12312&askdjaskdjas"
             }
         ]
        ```
    - Status code: `200`
    
- Response Fail : 
    - Body: 
        `[]`
    - Status code: `200`
    - Description: Empty response if there are no registered links in database
    
**GET CUSTOMERS WITH SIMILAR JOURNEY -** `GET`

- Endpoint : http://localhost:8080/api/customers-journey/similar/{UID}
- Parameters : `{UID}` - unique identifier for customer
- Optional GET parameters:
    - `limit` - if not set, default limit is 3 SIMILAR CUSTOMERS
- Description: Returns a JSON response with the list of CUSTOMERS with similar journey
- Response Success : 
    - Body: 
        ```yaml
        [
             {
                 "customerId": "7",
                 "customerUid": "123456789",
                 "userJourney": "https://www.example.ro/product/1,https://www.example.ro/product/2,https://www.example.ro/product/3,https://www.example.ro/product/4,https://www.example.ro/product/5,https://www.example.ro/product/7",
                 "searchJourney": "https://www.example.ro/product/1,https://www.example.ro/product/2,https://www.example.ro/product/3,https://www.example.ro/product/4"
             },
             {
                 "customerId": "8",
                 "customerUid": "1234567890",
                 "userJourney": "https://www.example.ro/product/1,https://www.example.ro/product/2,https://www.example.ro/product/3,https://www.example.ro/product/4",
                 "searchJourney": "https://www.example.ro/product/1,https://www.example.ro/product/2,https://www.example.ro/product/3,https://www.example.ro/product/4"
             }
         ]
        ```
    - Status code: `200`
    
- Response Fail : 
    - Body:
     ```yaml
        {
             "message": "Resource not found!"
        }
    ```
    - Status code: `404`
    - Description: If customer UID is not found in database, the response will be 404 with the given body
    
- Response Fail : 
    - Body: 
        `[]`
    - Status code: `200`
    - Description: If there are no CUSTOMERS matching the criteria
    
