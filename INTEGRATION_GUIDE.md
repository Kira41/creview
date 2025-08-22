# PHP Integration Guide

This guide will help you integrate the casino review frontend with a PHP backend for dynamic content management.

## 🚀 Quick Setup (5 Minutes)

### Step 1: Database Setup
1. Import the database schema:
```bash
mysql -u your_username -p casino_reviews < database_schema.sql
```

### Step 2: Configure Database Connection
Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');        // Your database host
define('DB_NAME', 'casino_reviews');   // Your database name
define('DB_USER', 'your_username');    // Your database username
define('DB_PASS', 'your_password');    // Your database password
```

### Step 3: Convert HTML to PHP
1. Rename `index.html` to `index.php`
2. Add this at the top of `index.php`:
```php
<?php
require_once 'includes/config.php';
require_once 'includes/casino-card.php';
?>
```

3. Replace the static casino cards section with:
```php
<div class="casinos-container" id="casinos-container">
    <?php
    $casinos = getCasinos();
    foreach ($casinos as $casino) {
        echo renderCasinoCard($casino);
    }
    ?>
</div>
```

### Step 4: Test the Integration
1. Open `index.php` in your browser
2. You should see the sample casinos from the database
3. Test the filtering and search functionality

## 📝 Adding New Casinos

### Method 1: Direct Database Insert
```sql
INSERT INTO casinos (name, slug, type, rating, bonus, features, description, status) 
VALUES (
    'New Casino Name',
    'new-casino-name',
    'online',
    4.5,
    'Welcome bonus description',
    '["Feature 1", "Feature 2", "Feature 3"]',
    'Detailed casino description',
    'active'
);
```

### Method 2: Using PHP Function
```php
$casinoData = [
    'name' => 'New Casino Name',
    'type' => 'online',
    'rating' => 4.5,
    'bonus' => 'Welcome bonus description',
    'features' => ['Feature 1', 'Feature 2', 'Feature 3'],
    'description' => 'Detailed casino description',
    'logo' => 'path/to/logo.png',
    'affiliate_url' => 'https://affiliate-link.com'
];

$casinoId = addCasino($casinoData);
```

## 🔧 Customizing the Frontend

### Adding New Filter Options
1. Update the HTML select options in `index.php`
2. Modify the `applyFilters()` function in `js/main.js`
3. Update the `getCasinos()` function in `includes/casino-card.php`

### Modifying Casino Card Layout
Edit the `renderCasinoCard()` function in `includes/casino-card.php`:
```php
function renderCasinoCard($casino) {
    // Customize the HTML structure here
    $html = '<div class="casino-card">';
    // Add your custom fields and layout
    $html .= '</div>';
    return $html;
}
```

## 🎯 AJAX Functionality

### Load More Casinos
The frontend automatically handles "Load More" functionality. The AJAX endpoint is already configured in `includes/casino-card.php`.

### Real-time Filtering
Filters work in real-time. To enable server-side filtering:
1. Uncomment the AJAX calls in `js/main.js`
2. The PHP endpoints are ready to handle filter requests

## 🛡️ Security Considerations

### Input Sanitization
All user inputs are automatically sanitized using the `sanitizeInput()` function.

### SQL Injection Prevention
The database class uses prepared statements to prevent SQL injection.

### CSRF Protection
CSRF tokens are implemented for form submissions. Use `generateCSRFToken()` and `validateCSRFToken()`.

## 📊 Admin Panel Integration

### Basic Admin Setup
1. Create an admin user:
```sql
INSERT INTO admin_users (username, email, password_hash, role) 
VALUES ('admin', 'admin@yoursite.com', '$2y$10$hash_here', 'admin');
```

2. Implement login functionality in `admin/login.php`
3. Use the provided admin interface in `admin/index.html`

### Admin Functions
The admin panel includes functions for:
- Adding/editing casinos
- Managing reviews
- Viewing statistics
- Site settings

## 🔄 Data Migration

### From Existing Database
If you have existing casino data, create a migration script:
```php
// Example migration from old structure
$oldCasinos = $oldDb->query("SELECT * FROM old_casinos_table");
foreach ($oldCasinos as $oldCasino) {
    $newData = [
        'name' => $oldCasino['casino_name'],
        'rating' => $oldCasino['rating'],
        // Map other fields
    ];
    addCasino($newData);
}
```

### From CSV File
```php
$csvFile = fopen('casinos.csv', 'r');
while (($data = fgetcsv($csvFile)) !== FALSE) {
    $casinoData = [
        'name' => $data[0],
        'type' => $data[1],
        'rating' => $data[2],
        // Map CSV columns to database fields
    ];
    addCasino($casinoData);
}
fclose($csvFile);
```

## 🎨 Styling Customization

### Brand Colors
Update CSS variables in `css/style.css`:
```css
:root {
    --primary-color: #your-color;
    --secondary-color: #your-color;
    --accent-color: #your-color;
}
```

### Logo and Branding
1. Replace the logo in the header
2. Update the site name in `includes/config.php`
3. Modify the favicon and meta tags

## 📈 Performance Optimization

### Database Indexing
The schema includes optimized indexes. For large datasets, consider:
- Adding composite indexes for common filter combinations
- Implementing database caching
- Using read replicas for high traffic

### Caching
Implement caching for:
- Casino listings
- Filter results
- Static content

Example caching implementation:
```php
$cacheKey = 'casinos_' . md5(serialize($filters));
$casinos = getFromCache($cacheKey);
if (!$casinos) {
    $casinos = getCasinos($filters);
    setCache($cacheKey, $casinos, 300); // 5 minutes
}
```

## 🔍 SEO Optimization

### URL Structure
Implement clean URLs using `.htaccess`:
```apache
RewriteEngine On
RewriteRule ^casino/([^/]+)/?$ casino-detail.php?slug=$1 [L,QSA]
RewriteRule ^category/([^/]+)/?$ category.php?slug=$1 [L,QSA]
```

### Meta Tags
Dynamic meta tags are implemented in the casino detail pages:
```php
$casino = getCasinoBySlug($_GET['slug']);
$pageTitle = $casino['name'] . ' Review - ' . SITE_NAME;
$pageDescription = 'Expert review of ' . $casino['name'] . ' casino...';
```

## 🚨 Troubleshooting

### Common Issues

**Database Connection Failed**
- Check database credentials in `config.php`
- Ensure MySQL service is running
- Verify database exists

**Casinos Not Loading**
- Check database table exists and has data
- Verify PHP errors in error log
- Ensure proper file permissions

**Filters Not Working**
- Check JavaScript console for errors
- Verify AJAX endpoints are accessible
- Ensure database indexes exist

**Images Not Displaying**
- Check image paths and permissions
- Verify upload directory exists
- Ensure proper file extensions

### Debug Mode
Enable debug mode in `config.php`:
```php
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📞 Support

For technical support:
1. Check the error logs first
2. Verify database connection and structure
3. Test with sample data
4. Review the documentation

## 🔄 Updates and Maintenance

### Regular Maintenance
- Update casino ratings based on user reviews
- Clean up old page view logs
- Backup database regularly
- Monitor performance metrics

### Version Control
Keep track of customizations for easy updates:
- Use Git for version control
- Document custom modifications
- Test updates in staging environment

---

**This integration guide provides everything needed to get your casino review site running with PHP backend functionality.**

