<?php
/**
 * Function to automatically set up the database and tables if they don't exist
 * Returns an array with status information
 */
function setup_database($servername, $username, $password, $dbname)
{
    $result = [
        'success' => false,
        'message' => '',
        'details' => []
    ];

    try {
        // Create connection without database
        $conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Try to create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($conn->query($sql) === TRUE) {
            $result['details'][] = "Database checked/created successfully";
        } else {
            throw new Exception("Error creating database: " . $conn->error);
        }

        // Select the database
        $conn->select_db($dbname);

        // Create services table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS services (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            category VARCHAR(50) NOT NULL,
            featured TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        if ($conn->query($sql) === TRUE) {
            $result['details'][] = "Table 'services' checked/created successfully";
        } else {
            throw new Exception("Error creating table 'services': " . $conn->error);
        }

        // Create orders table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS orders (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            service_id INT(11) UNSIGNED,
            full_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            address TEXT,
            service_name VARCHAR(255) NOT NULL,
            status VARCHAR(50) DEFAULT 'pending',
            message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
        )";

        if ($conn->query($sql) === TRUE) {
            $result['details'][] = "Table 'orders' checked/created successfully";
        } else {
            throw new Exception("Error creating table 'orders': " . $conn->error);
        }

        // Create contact messages table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            status VARCHAR(20) DEFAULT 'unread',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($conn->query($sql) === TRUE) {
            $result['details'][] = "Table 'contact_messages' checked/created successfully";
        } else {
            throw new Exception("Error creating table 'contact_messages': " . $conn->error);
        }

        // Create admin users table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS admin_users (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($conn->query($sql) === TRUE) {
            $result['details'][] = "Table 'admin_users' checked/created successfully";
        } else {
            throw new Exception("Error creating table 'admin_users': " . $conn->error);
        }

        // Insert default admin user if it doesn't exist
        $check_admin = "SELECT * FROM admin_users WHERE username = 'admin'";
        $admin_result = $conn->query($check_admin);

        if ($admin_result->num_rows == 0) {
            $default_admin_password = password_hash('admin123', PASSWORD_DEFAULT);
            $sql = "INSERT INTO admin_users (username, password, email) 
                    VALUES ('admin', '$default_admin_password', 'admin@techagency.com')";

            if ($conn->query($sql) === TRUE) {
                $result['details'][] = "Default admin user created";
            } else {
                throw new Exception("Error creating default admin user: " . $conn->error);
            }
        } else {
            $result['details'][] = "Admin user already exists";
        }

        // Insert sample services data if services table is empty
        $check_services = "SELECT COUNT(*) as count FROM services";
        $services_result = $conn->query($check_services);
        $row = $services_result->fetch_assoc();

        if ($row['count'] == 0) {
            // Sample service data
            $sample_services = [
                [
                    'name' => 'Website Development',
                    'description' => 'Professional website development using the latest technologies. We create responsive, fast, and SEO-friendly websites tailored to your business needs.',
                    'category' => 'web',
                    'featured' => 1
                ],
                [
                    'name' => 'Mobile App Development',
                    'description' => 'Custom mobile application development for iOS and Android platforms. We build intuitive, feature-rich, and high-performance apps that engage users.',
                    'category' => 'mobile',
                    'featured' => 1
                ],
                [
                    'name' => 'AI Consulting',
                    'description' => 'Expert consultation on implementing AI solutions for your business. We help you harness the power of artificial intelligence to optimize operations and drive growth.',
                    'category' => 'ai',
                    'featured' => 1
                ],
                [
                    'name' => 'E-commerce Solutions',
                    'description' => 'Complete e-commerce solutions including website development, payment gateway integration, inventory management, and more.',
                    'category' => 'web',
                    'featured' => 0
                ],
                [
                    'name' => 'SEO Optimization',
                    'description' => 'Comprehensive SEO services to improve your website\'s visibility on search engines and drive more organic traffic.',
                    'category' => 'web',
                    'featured' => 0
                ],
                [
                    'name' => 'Cloud Solutions',
                    'description' => 'Cloud infrastructure setup, migration, and management services. We help you leverage the power of cloud computing for scalability and reliability.',
                    'category' => 'cloud',
                    'featured' => 0
                ]
            ];

            // Insert sample services
            foreach ($sample_services as $service) {
                $sql = "INSERT INTO services (name, description, category, featured) 
                        VALUES (
                            '{$service['name']}', 
                            '{$service['description']}', 
                            '{$service['category']}', 
                            {$service['featured']}
                        )";

                if ($conn->query($sql) !== TRUE) {
                    $result['details'][] = "Error inserting sample service '{$service['name']}': " . $conn->error;
                }
            }

            $result['details'][] = "Sample services data inserted successfully";
        } else {
            $result['details'][] = "Services data already exists";
        }

        // Success!
        $result['success'] = true;
        $result['message'] = "Database setup completed successfully";

    } catch (Exception $e) {
        $result['success'] = false;
        $result['message'] = "Database setup failed: " . $e->getMessage();
    } finally {
        // Close connection if it exists
        if (isset($conn) && $conn) {
            $conn->close();
        }
    }

    return $result;
}
?>