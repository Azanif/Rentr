# Rentr - Property Rental System

A web-based property rental system built with PHP and MySQL.

## Features

- User Registration & Authentication
- Property Listings
- User Roles (Tenant, Landlord, Admin)
- Responsive Design
- Secure Password Hashing

## Setup Instructions

1. **Requirements**
   - XAMPP/WAMP/MAMP (PHP 7.4+ & MySQL 5.7+)
   - Web Browser (Chrome, Firefox, etc.)

2. **Database Setup**
   - Create a new MySQL database named `rentrdb`
   - Import the `database_setup_updated.sql` file

3. **Configuration**
   - Update `config.php` with your database credentials if needed
   - Ensure the `images` directory is writable for file uploads

4. **Access the Application**
   - Open `http://localhost/rentr` in your browser
   - Register a new account or use existing credentials to login

## Project Structure

```
rentr/
├── config.php          # Database configuration
├── index.php          # Home page
├── login.php          # User login
├── register.php       # User registration
├── dashboard.php      # User dashboard
├── logout.php         # Logout handler
├── images/            # Uploaded property images
└── README.md          # This file
```

## Features in Detail

### User Authentication
- Secure login/logout system
- Password hashing using PHP's `password_hash()`
- Session management
- Remember me functionality

### Database Schema
- Users (tenants, landlords, admins)
- Properties
- Bookings
- Payments
- Reviews

## Security Considerations
- Prepared statements to prevent SQL injection
- Password hashing
- Input validation
- CSRF protection (to be implemented)
- XSS prevention using `htmlspecialchars()`

## Future Enhancements
- Email verification
- Password reset functionality
- Admin dashboard
- Property search and filtering
- Payment gateway integration

## Contributing

1. Fork the repository
2. Create a new branch for your feature
3. Commit your changes
4. Push to the branch
5. Create a Pull Request
