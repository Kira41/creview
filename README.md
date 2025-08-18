# Casino Review Frontend

A comprehensive, responsive frontend for casino review websites with PHP integration capabilities.

## 🎯 Features

### Frontend Features
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Modern UI/UX**: Professional design with smooth animations and hover effects
- **Interactive Filtering**: Sort and filter casinos by rating, type, and search terms
- **Dynamic Content Loading**: Load more casinos with AJAX functionality
- **Rating System**: Visual rating badges and breakdown displays
- **Mobile Navigation**: Hamburger menu for mobile devices
- **SEO Optimized**: Proper meta tags and semantic HTML structure

### PHP Integration Ready
- **Database Integration**: Pre-built PHP classes for database operations
- **AJAX Endpoints**: Ready-to-use endpoints for dynamic content loading
- **Admin Panel**: Complete admin interface for casino management
- **Content Management**: Easy-to-use functions for adding/editing casinos
- **Security Features**: CSRF protection, input sanitization, and error handling

## 📁 Project Structure

```
casino-review-frontend/
├── index.html              # Main homepage
├── pages/                  # Additional static pages
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── main.js            # Frontend JavaScript functionality
├── php/                   # PHP endpoints and includes
│   ├── fetch_casinos.php
│   ├── fetch_games.php
│   ├── fetch_bonuses.php
│   └── includes/          # Reusable PHP components
│       ├── config.php
│       └── casino-card.php
└── README.md              # This documentation file
```

## 🚀 Quick Start

### 1. Basic Setup (Static HTML)
1. Upload all files to your web server
2. Open `index.html` in your browser
3. The site will work with sample data

### 2. PHP Integration Setup

#### Database Setup
1. Create a MySQL database named `casino_reviews`
2. Run this SQL to create the casinos table:

```sql
CREATE TABLE casinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('online', 'sweepstakes', 'crypto') NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    bonus TEXT,
    features JSON,
    description TEXT,
    logo VARCHAR(255),
    affiliate_url VARCHAR(255),
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Configuration
1. Edit `php/includes/config.php` with your database credentials:
```php
define('DB_HOST', 'your_host');
define('DB_NAME', 'casino_reviews');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

#### Convert to PHP
1. Rename `index.html` to `index.php`
2. Add PHP includes at the top:
```php
<?php
require_once 'php/includes/config.php';
require_once 'php/includes/casino-card.php';
?>
```

3. Replace the static casino cards section with:
```php
<?php
$casinos = getCasinos();
foreach ($casinos as $casino) {
    echo renderCasinoCard($casino);
}
?>
```

## 🎨 Customization

### Colors and Branding
Edit the CSS variables in `css/style.css`:
```css
:root {
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --accent-color: #ffd700;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
}
```

### Adding New Casino Types
1. Update the type filter in `index.html`
2. Add corresponding CSS classes in `style.css`
3. Update the database enum if using PHP integration

### Modifying Rating System
- Edit the `getRatingClass()` and `getRatingLabel()` functions in `config.php`
- Update CSS classes for different rating levels
- Modify the rating breakdown in casino detail pages

## 📱 Responsive Design

The frontend is fully responsive with breakpoints at:
- **Desktop**: 1200px and above
- **Tablet**: 768px - 1199px
- **Mobile**: Below 768px

### Mobile Features
- Collapsible navigation menu
- Touch-optimized buttons and interactions
- Optimized card layouts for small screens
- Swipe-friendly carousel elements

## 🔧 PHP Functions Reference

### Casino Management Functions

#### `getCasinos($filters, $limit, $offset)`
Retrieve casinos with optional filtering and pagination.

**Parameters:**
- `$filters` (array): Filter criteria (rating, type, search)
- `$limit` (int): Number of casinos to return
- `$offset` (int): Starting position for pagination

**Returns:** Array of casino data

#### `addCasino($data)`
Add a new casino to the database.

**Parameters:**
- `$data` (array): Casino information including name, type, rating, etc.

**Returns:** New casino ID

#### `updateCasinoRating($id, $rating)`
Update a casino's rating.

**Parameters:**
- `$id` (int): Casino ID
- `$rating` (float): New rating value

#### `deleteCasino($id)`
Soft delete a casino (sets status to 'deleted').

**Parameters:**
- `$id` (int): Casino ID to delete

### Utility Functions

#### `sanitizeInput($input)`
Sanitize user input to prevent XSS attacks.

#### `formatRating($rating)`
Format rating number for display.

#### `getRatingClass($rating)`
Get CSS class based on rating value.

## 🔒 Security Features

### Input Validation
- All user inputs are sanitized using `htmlspecialchars()`
- SQL injection prevention with prepared statements
- CSRF token validation for forms

### Error Handling
- Custom error handlers for production environments
- Graceful degradation for JavaScript failures
- User-friendly error messages

### File Upload Security
- File type validation
- Size limitations
- Secure file naming

## 🎯 AJAX Endpoints

### Load More Casinos
**Endpoint:** `php/includes/casino-card.php?action=load_more`

**Parameters:**
- `page` (int): Page number
- `rating` (float): Minimum rating filter
- `type` (string): Casino type filter
- `search` (string): Search term

**Response:**
```json
{
    "html": "<div class='casino-card'>...</div>",
    "hasMore": true,
    "totalCount": 156
}
```

### Filter Casinos
**Endpoint:** `php/includes/casino-card.php?action=filter`

**Parameters:**
- `sort` (string): Sort criteria
- `rating` (float): Minimum rating
- `type` (string): Casino type
- `search` (string): Search term

## 🎨 CSS Classes Reference

### Casino Cards
- `.casino-card`: Main card container
- `.casino-header`: Card header with logo and rating
- `.casino-content`: Card content area
- `.casino-actions`: Action buttons container

### Rating Badges
- `.rating-badge.excellent`: Green badge for 4.5+ ratings
- `.rating-badge.good`: Blue badge for 4.0+ ratings
- `.rating-badge.fair`: Orange badge for 3.5+ ratings

### Buttons
- `.btn.btn-primary`: Primary action button
- `.btn.btn-secondary`: Secondary action button
- `.btn.btn-large`: Larger button variant

## 🚀 Performance Optimization

### Image Optimization
- Use WebP format when possible
- Implement lazy loading for casino logos
- Optimize placeholder images

### JavaScript Optimization
- Debounced search functionality
- Throttled scroll events
- Efficient DOM manipulation

### CSS Optimization
- Minified CSS for production
- Critical CSS inlining
- Efficient animations using transforms

## 🔧 Browser Support

- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+
- **Mobile Safari**: 14+
- **Chrome Mobile**: 90+

## 📈 Analytics Integration

### Google Analytics
Add your tracking code to the `<head>` section:
```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_TRACKING_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_TRACKING_ID');
</script>
```

### Event Tracking
The JavaScript includes event tracking for:
- Casino card clicks
- Filter usage
- Search queries
- Load more actions

## 🛠️ Development

### Local Development
1. Use a local web server (XAMPP, WAMP, or MAMP)
2. Enable PHP and MySQL
3. Import the database schema
4. Configure the database connection

### Testing
- Test all filter combinations
- Verify mobile responsiveness
- Check cross-browser compatibility
- Validate HTML and CSS

## 📞 Support

For questions or issues with this frontend:
1. Check the documentation above
2. Review the code comments
3. Test with sample data first
4. Ensure proper PHP/MySQL setup

## 📄 License

This casino review frontend is provided as-is for educational and commercial use. Modify as needed for your specific requirements.

---

**Built with modern web technologies for optimal performance and user experience.**

