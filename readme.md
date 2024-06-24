
## Database Schema

All database only includes necessary columns for this test. In practice each table can have more columns.

1. `supermarkets` and `wholesalers` has a many-to-many relationship. One supermarket can purchase stock from multiple wholesalers, and wholesaler can sell stock to multiple supermarkets. The many-to-many relationship are maintained by `supermarket_wholesaler` table.
2. `outlets` has a one-to-one relationship to `wholesaler`. In reverse, `wholesalers` has a has-many relationship to many `outlets`. These relationships are based on the reality: outlets are owned by wholesalers or factories, for selling some long-time stock with a lower price. A wholesaler can have multiple outlets. This relationship is maintained by a foreign key constraint from `outlets.wholesaler_id` to `wholesalers.id`.
3. In this test, I'm able to have the access to all the data in the supply chain. Therefore, I assume that I'm creating a platform for managing data in the whole process, from wholesalers to supermarkets, from wholesalers to outlets. Based on this I choose to manage the stock from `wholesalers`, `supermarkets` and `outlets`. The `stock` table has two columns `owner_type`, `owner_id` which can be used for determine the owner of the stock. There will be more about it, e.g. the comparison of different solutions, how to keep referential integrity, etc., in later part of this readme file. 
4. `users` table is currently isolated with other tables. It is only used for user authentication and authorization now. It has two options for its `roles` column: "admin" or "editor". An admin has all access to all tables while an editor can usually only read data. In practice, it can have columns referring to other tables, indicating a user's access to different operations on different entities.

![Database Schema.png](https://github.com/Roamler-Code-Tests/fmcg-platform-api-KaiyuWei/blob/pure-PHP-solution/storage/Database%20Schema.png?raw=true)


## Codebase
### Routing
The routing function is mainly achieved by `ApiRouter` and some routing parsers. With them, not only static routings, but also dynamic routings such as `\api\supermarket\{id}` can be used, which enhances the flexibility of the routing system.

### Request and Data Processing
The app has some most basic classes for serving requests: 
- `Controller` gets the data from the request, do authentication and some data validations, which ensures the validity of the request and the security of the app. Then it transfer the request data to `Service`. Later it gets processing result from `Service` and respond data or status, based on the processing result, to clients.
- `Service` gets data from `Controllers` and implements some business logic, and interacts with databases via `Model`. It also do some validation that needs to interact with DB, e.g. validating that a email for registering a new user is not registered before.
- `Model` interacts with DB directly, executing sql queries. Thanks to `Service`, it does not have to hold some long, unclean, and DB-irrelevant code in its classes.

