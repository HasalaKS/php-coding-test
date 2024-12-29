
# Online Support System

A Laravel and Vue.js application designed to helps service providers and sellers to
provide after-sales support for their customers.


## Features

- Customers can open a ticket by providing the necessary details.
- Customers receive an email after the ticket is created.
- Support agent login and logout functionality.
- Support agents can view all tickets created by customers.
- Agents can reply to the tickets.
- Customers receive an email after the support agent replies to the ticket.
- Search for ticket details using the ticket reference number.
- Pagination support for the ticket management grid.
- Mobile responsiveness.


## Prerequisites

Ensure you have the following installed:

- PHP <= 8.3
- Composer
- Node.js <= 20.18.1
- npm
- MySQL or any other database supported by Laravel
## Installation

Step 1: Clone the Repository
-
```bash
git clone https://github.com/HasalaKS/php-coding-test.git
cd online_support_system
```

Step 2: Install PHP Dependencies
-
```bash
composer install
```

Step 3: Install Node.js Dependencies
-
```bash
npm install
```

Step 4: Set Up Environment File
-
Duplicate the .env.example file and rename it to .env Configure your database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_support_system
DB_USERNAME=root
DB_PASSWORD=
```
Configure your email credentials:
```bash
MAIL_MAILER=smtp
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<<YourUserName>>
MAIL_PASSWORD=<<YourPassword>>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<<YourEmailAddress>>
MAIL_FROM_NAME="Online support system"
```

Step 5: Generate Application Key
-
```bash
php artisan key:generate
```

Step 6: Run Migrations
-
```bash
php artisan migrate
```

Step 7: Run command
-
Run the following command to create a support agent record in the database. Replace {name}, {email}, and {password} with the appropriate values.
```bash
php artisan create:agent {name} {email} {password}'
```

Step 8: Start the Application
-
```bash
php artisan serve
npm run dev
```
