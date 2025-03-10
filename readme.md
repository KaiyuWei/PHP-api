## Api Setup
### Using Docker-compose
- Boost the server: `docker-compose up -d`.
- Run all the test cases: `docker-compose exec app composer test`. The tests prepare data by themselves, so you do not have to worry about that.
- If you need, you can enter the server's shell by running: `docker-compose exec app /bin/bash`.
- Before you start your playing with the APIs, you can run `docker-compose exec app composer seed:all` to easily seed dummy data into all the tables. Remember that every time you run this seeder, all tables are truncated first to preventing value conflicts. For being authenticated, you need to add an admin user by running: `docker-compose exec app composer add:admin`, they you get the authentication token printed on you termina.
- Swagger UI and documentation available in http://localhost:8000/public/swagger-ui/ after the server is set up. Use the token you just obtained to get authenticated as an admin. You can interact with all available APIs there.  

### Manually
- Add your database config in the required variables in `.env.example` and add the environment variables into your `.env` file.
- run `composer install` if necessary.
- run `php -S localhost:8000` to start the server.
- run all the migration scripts in `/database/migrations` by `composer run:migrations` for creating database and tables needed in your database. You need to make sure that the `DB_DATABASE` you set in `.env` file is the same as the database name used in the `000_create_databasse.sql` file. There are also some seeders available in `/database/seeders` folder for seeding some dummy data for testing.
- Api documentation generated by Swagger are available in http://localhost:8000/public/swagger-ui/ . You can also use this UI to test all available APis. For getting authenticated, there are two APis for regietration and login. You can get a token by registering ad login. However, a user can only be registered as `tranee` for safety reasons. You can manually change the role in you `users` table. 
- There can be some compatible problems. Please let me know then. If I have more time I would create a Docker image and a docker-compose file for better use.

## Test Cases
There are some unit tests and feature tests. In unit tests, for keep the functions isolation, I use Mock and Reflection in PHP unit. In feature tests which need to interact with the database, I create quite thorough data preparation. You do not need to inject the data by yourself: they are handled by some sql seeder scripts. Just click and run.

For time limitation, I did not write full complete test cases for every clases and APis. Mostly I test the API via the swagger UI. Besides, I do write some test cases for some casses, available in `\tests` folder. What I would like to specially mention is the test cases for the most complicated one in thie assignment: `\api\stock\index`, the one that handles filtering, sorting, ordering, pagination... All you can think. The test cases are available in `feature\StockIndexApiTest`. There are some really thorough and comprehensive test cases for it. I ran all the tests before I submitted this file, just to make sure that all of them works.

## Database Schema

All database only includes necessary columns for this test. In practice each table can have more columns.

- `supermarkets` and `wholesalers` has a many-to-many relationship. One supermarket can purchase stock from multiple wholesalers, and wholesaler can sell stock to multiple supermarkets. The many-to-many relationship are maintained by `supermarket_wholesaler` table.
- `outlets` has a one-to-one relationship to `wholesaler`. In reverse, `wholesalers` has a has-many relationship to many `outlets`. These relationships are based on the reality: outlets are owned by wholesalers or factories, for selling some long-time stock with a lower price. A wholesaler can have multiple outlets. This relationship is maintained by a foreign key constraint from `outlets.wholesaler_id` to `wholesalers.id`.
- In this test, I'm able to have the access to all the data in the supply chain. Therefore, I assume that I'm creating a platform for managing data in the whole process, from wholesalers to supermarkets, from wholesalers to outlets. Based on this I choose to manage the stock from `wholesalers`, `supermarkets` and `outlets`. The `stock` table has two columns `owner_type`, `owner_id` which can be used for determine the owner of the stock. There will be more about it, e.g. the comparison of different solutions, how to keep referential integrity, etc., in later part of this readme file. 
- `users` table is currently isolated with other tables. It is only used for user authentication and authorization now. It has two options for its `roles` column: "admin" or "trainee". An admin has all access to all tables while an trainee can usually only read data. In practice, it can have columns referring to other tables, indicating a user's access to different operations on different entities.

![Database Schema.png](https://github.com/Roamler-Code-Tests/fmcg-platform-api-KaiyuWei/blob/pure-PHP-solution/storage/Database%20Schema.png?raw=true)


## Codebase
### Routing
The routing function is mainly achieved by `ApiRouter` and some routing parsers. With them, not only static routings, but also dynamic routings such as `\api\supermarket\{id}` can be used, which enhances the flexibility of the routing system.

### Apis
For time reasons, I did not create CRUD for all classes. There are APIs for CRUD operations for `Product` and `Supermarket` available. However, to adding these apis for `Wholesaler` and `Outlet`are just some similar and repetitive works. So you can take the apis for `Supermarket` and `Product` as an example of how I would deal with such requirements.

### Requests and Data Processing
The app has some most basic classes for serving requests: 
- `Controller` gets the data from the request, do authentication and some data validations, which ensures the validity of the request and the security of the app. Then it transfer the request data to `Service`. Later it gets processing result from `Service` and respond data or status, based on the processing result, to clients.
- `Service` gets data from `Controllers` and implements some business logic, and interacts with databases via `Model`. It also do some validation that needs to interact with DB, e.g. validating that a email for registering a new user is not registered before.
- `Model` interacts with DB directly, executing sql queries. Thanks to `Service`, it does not have to hold some long, unclean, and DB-irrelevant code in its classes.

#### Validator
`Validator` is used for checking the validity of data sent by request. With them, invalid data, dangerous data, not-allowed data... will be rejected with corresponding error messages and status code.

Validation handles these cases:
1. Invalid Input Types: Ensure that all inputs are of the expected type (e.g., string, array...). 
2. Missing Required Fields: Check for the presence of all required fields in the request body. 
3. Boundary Values: Test for minimum and maximum acceptable values for fields, e.g. longest name or email string allowed. 
4. Non-existent Resources: Handle cases where the requested resource does not exist.
5. Duplicate Resources. Prevent creation of resources that already exist (unique constraints). 
6. Sensitive Data Exposure: Only allowed fields can be queried, filtered.

#### Filtering, Sorting and Pagination
`QueryFilter` and `QuerySorter` handle filtering and sorting respectively. As for pagination, it involves some calculation of the query offset, together with `LIMIT` in sql queries. You can find the interesting logic in methods of `StockService` class. For time limitation I only applied filtering, sorting and pagination to `\api\stock\index`. 

### Authentication and Authorization
- When a user logs in, a token is generated, stored in the `token` column of `users` table, and send to the client in response. This token can is used for authentication. Exception for "Register" and "Login" apis, All the other CRUD options need user authentication.
- `role` column in `users` table is for authorization. Currently there are two roles for users: `admin` and `trainee`.

Authentication handles these cases:
1. Missing or Invalid Tokens: Ensure that authentication tokens are provided and are valid.
2. Insufficient Permissions: Check that current user is an `admin` 

### Database Optimization
- On `stock` table, a composite index of `product_id`, `owner_id`, and `owner_type` is added. Since I store stock from all owners in one table, many queries can benefit from this index. You can imagine that many we have a log of needs for checking a specific owner's stock. This index makes it almost like we are using a single table only for a specific owner to use.
- For benefiting from the index more, all queries using these fields for `WHERE` clauses should write in the same order of the index columns. The order of the columns are not random: the column with the most unique values goes to the first place. By doing this we can quickly cut out rows that are not needed in query tables.
- **Cache** is also applied by `CacheHelper` class. The cache files are stored in `\storage\cache` folder. For now I only use the cache in the most complicated index query, cause it is apparently needed a lot and really complicated to be executed.
- Use transactions when we need to run a group of related operations. They should either succeed or fail together, stick to the ACID principles.
- Btw, all the queries are executed with parameter binding, so SQL injection attack can be prevented.

### Inventory level track
Three apis `api/stock/supermarket/{id}`, `api/stock/outlet/{id}`, `api/stock/wholesaler/{id}` are provided to retrieve the inventory level of different types of stock owners. Thanks to the dynamic routing system I write, and the index mentioned above, these apis should have pretty good performance.

### Order processing
An api `/api/purchase/supermarket` is provided for placing order in a supermarket. The order processing followings the discipline: we always want to send out the products with the earliest entry-stock time (in other words, the "oldest" stock). Thus everytime an order is received, we query for available stocks, sort them by ascending `entry_time`, and then start calculate and consuming them. There are some complex but interesting logic in `SupermarketStockService` class for process the data. 

In practice, if a supermarket's stock is, or almost run out, it should place orders to its wholesalers. This involves more complicated inventory strategies that is beyond technical topics. Besides, I did not write more code for placing orders to wholesalers when a supermarket's stock is ran out, since this is also another competitive work as the one we currently have. 

## Discussion About System Designing

### Database Design

#### Another design patter

Supermarkets, outlets, and wholesalers, each of them has their own stock table. In such a way, the stock tables can have foreign key `owner_id` referring to their owner tables. This ensures the referential integrity. On the opposite, by using a unified table you cannot directly set foreign key constraint to owner tables, because the `owner_id` column includes ids from different owner tables.

The reason why I choose to manage all stock data in a unified table:
1. Simplified Schema: With a single table to manage stock, the database schema becomes simpler. We don’t need separate tables for supermarket stock and wholesaler stock, reducing complexity.
2. Easier Maintenance: With a unified table, we only need to maintain and update one table instead of multiple tables. This reduces the amount of code you need to write and maintain.
3. Streamlined Queries: Queries related to stock management become simpler and more efficient because we don't have to join or union multiple tables to get a complete view of stock.
4. Consistency: A unified table helps maintain consistency in how stock data is stored and accessed. It reduces the likelihood of discrepancies between different stock tables.
5. Scalability: It can be easier to scale and manage a single table, particularly with indexing and partitioning, compared to managing multiple tables.

As for the referential integrity, we can solve the problem by setting some triggers. Everytime a row is going to be inserted, updated or deleted, a trigger is set to automatically do the check to see if the owner exists and decides should the operation be allowed to continue. ***This is almost the same as how we use a foreign key. That's why I still draw some connections in the above database schema between different owners and the stock table, even though there's actually not such foreign keys exist.*** 

I've not written the trigger mechanism yet, thus for now the referential integrity is not complete. It can happen that we delete an owner but its stock is still kept in the database. In production this is definitely not allowed.

## Coding style
I'm a fan of shorter functions, so I always try to write functions as short as possible. This is inspired by Martin Robert's book "Clean Code". I really benefit from it alot. Writing shorter functions makes the code easy to follow and read.

I also try to isolate the functions of classes, just like what I did with the functions of `Controller`, `Service` and `Model`. This makes the code structure clean and easy to maintain.
Besides, I try to use more descriptive names, though sometimes they can get long. For me clearance is better than ambiguity.
