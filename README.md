# AddressBook

## Setup Instructions

### Step 1: Setup Database
Import the `db_setup_queries.sql` file into your MySQL database.

### Step 2: Update Database Configuration
Modify the database configuration in `Constants/Config.php` as needed.

#### Example Local Database Configuration:
```php
// Local DB Configuration
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "address-book");
define("DB_ADDRESSES_TABLENAME", "Addresses");
```

### Step 3: Start Server & Run Application
1. Start your PHP and MySQL server.
2. Execute `index.php` in your web browser or terminal.

Your AddressBook application should now be up and running!

