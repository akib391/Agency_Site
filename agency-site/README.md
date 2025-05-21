# TechAgency Marketing Website

A complete agency marketing website built with PHP, MySQL, and Bootstrap 5. The site showcases multiple services and includes both public-facing pages and an admin panel for managing services and orders.

## Features

- Responsive design built with Bootstrap 5
- PHP & MySQL backend
- Automatic database setup and initialization
- Database connection error handling
- Public pages:
  - Homepage with featured services
  - Services page with filtering by category
  - Service details page
  - About Us page
  - Contact page
  - Buy Services page with form submission
- Admin Panel:
  - Dashboard with key metrics
  - Service management (CRUD operations)
  - Order management
  - Authentication system

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, etc.)
- XAMPP, WAMP, MAMP, or similar local development environment

## Installation

1. Clone or download this repository to your web server's root directory (e.g., `htdocs` for XAMPP).

2. **Important**: Check the .htaccess file and ensure the auto_prepend_file path correctly points to your init.php file with the absolute path:

   ```
   php_value auto_prepend_file "C:/xampp/htdocs/agency-site/init.php"
   ```

   Update this path if your installation directory is different.

3. Access the website:
   - The database, tables, and sample data will be created automatically on first access.
   - Frontend: `http://localhost/agency-site/`
   - Admin Panel: `http://localhost/agency-site/admin/login.php`
4. Alternatively, you can manually initialize the database by visiting:
   - `http://localhost/agency-site/setup_database.php`

## Admin Login

Default admin credentials:

- Username: `admin`
- Password: `admin123`

## Directory Structure

```
agency-site/
├── admin/                  # Admin panel files
│   ├── includes/           # Admin includes
│   ├── index.php           # Admin dashboard
│   ├── login.php           # Admin login
│   ├── logout.php          # Admin logout
│   ├── orders.php          # Order management
│   └── services.php        # Service management
├── css/                    # Stylesheets
│   └── style.css           # Main CSS file
├── images/                 # Image assets
├── includes/               # Shared PHP includes
│   ├── db_connect.php      # Database connection
│   ├── db_setup.php        # Database setup function
│   ├── footer.php          # Site footer
│   └── header.php          # Site header
├── js/                     # JavaScript files
│   └── script.js           # Main JS file
├── about.php               # About page
├── buy-service.php         # Buy service form page
├── contact.php             # Contact page
├── index.php               # Homepage
├── portfolio.php           # Portfolio page (coming soon)
├── service-details.php     # Service details page
├── services.php            # Services listing page
├── setup_database.php      # Database setup script
└── README.md               # This file
```

## Database Setup

The website includes an automatic database setup feature that:

1. Checks if the database exists, creates it if it doesn't
2. Creates necessary tables if they don't exist
3. Inserts default admin user if not present
4. Adds sample service data if the services table is empty

This happens automatically when you first visit the site. If the database connection fails, user-friendly error messages are displayed instead of PHP errors.

## Customization

- **Images**: Replace placeholder images with your own by adding them to the `images` directory
- **Content**: Update content in the PHP files
- **Styling**: Modify the CSS in `css/style.css`
- **Database Settings**: Update database connection parameters in `includes/db_connect.php`

## Troubleshooting

- **Admin Login Error**: If you encounter "Failed to open stream: No such file or directory" errors when trying to access the admin login, check that the auto_prepend_file path in .htaccess is correctly set to the absolute path of your init.php file.
- **Database Connection Issues**: If the database fails to connect, ensure your MySQL server is running and the credentials in includes/db_connect.php are correct.
- **Permission Issues**: Make sure your web server has write permissions to create and modify the database files.

## Notes

- This is a demonstration site for showcasing an agency's services
- The payment form does not process actual payments; it only simulates form submission
- The "Coming Soon" pages (Portfolio) can be expanded with actual content as needed

## License

This project is intended for educational purposes. Feel free to use it as a starting point for your own projects.
