# NexEvent - Setup Instructions

## Quick Start Guide

### 1. Database Setup

Make sure MySQL is running, then:

```bash
# Configure your database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexevent
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Create Storage Link

```bash
php artisan storage:link
```

### 4. Start the Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Important Notes

1. **File Storage**: Make sure the `storage/app/public` directory exists and is writable
2. **Database**: All migrations are ready to run - they will create all necessary tables
3. **Policies**: All authorization policies are registered in AppServiceProvider
4. **Routes**: All routes are configured in `routes/web.php`

## Testing the Features

### Create a Test User

1. Register a new user through `/register`
2. By default, users are assigned the "attendee" role
3. To create an organizer, you can either:
   - Update the user role in the database manually
   - Or use a seeder (if available)

### Test Event Creation

1. Login as an organizer
2. Go to Events → Create Event
3. Fill in the form with:
   - Title, description, dates, location
   - Select category and add tags
   - Set visibility (public/private/invite_only)
   - Upload an image URL (optional)

### Test Features

- **Search**: Use the search bar in navigation or events page
- **Wishlist**: Click "Add to Wishlist" on any event
- **Comments**: Post comments on events (must be logged in)
- **Reviews**: Submit reviews after registering for events
- **Chat**: Access group chat from event page
- **Resources**: Organizers can upload resources
- **Announcements**: Organizers can post announcements
- **Certificates**: Generate certificates after checking in

## Troubleshooting

### Migration Errors

If you get migration errors about duplicate columns:
- The migrations check for existing columns
- You can safely ignore errors about existing columns if they already exist

### Missing Views

All views have been created:
- `resources/views/wishlist/index.blade.php`
- `resources/views/announcements/` (index, create, edit)
- `resources/views/resources/` (index, create, show)
- `resources/views/messages/` (conversations, conversation, index)
- `resources/views/certificates/show.blade.php`
- `resources/views/events/invite.blade.php`
- Updated: `resources/views/events/show.blade.php`
- Updated: `resources/views/events/create.blade.php`
- Updated: `resources/views/events/edit.blade.php`
- Updated: `resources/views/events/index.blade.php`
- Updated: `resources/views/layouts/navigation.blade.php`

### Policy Errors

All policies are registered in `app/Providers/AppServiceProvider.php`:
- EventPolicy
- CommentPolicy
- ReviewPolicy
- CertificatePolicy
- WishlistPolicy

## All Features Implemented

✅ User Registration & Login
✅ Role-Based Access Control
✅ Profile Management
✅ Event Creation & Management
✅ Event Categories & Tags
✅ Event Visibility Settings
✅ Announcement Section
✅ Search Functionality
✅ Wishlist System
✅ Event Resources Upload/Display
✅ Chat System (Group & Individual)
✅ Comment Section
✅ Rating & Review System
✅ Certificate Generation
✅ Event Link Sharing
✅ Event Schedule Module
✅ Digital Ticket Generation
✅ Manual Check-in
✅ Analytics Dashboards

Everything is ready to use!

