# NexEvent - Next Generation Event Platform

NexEvent is a comprehensive event management platform built with Laravel (PHP) framework. It provides a single, organized system for users to manage, create, and participate in events.

## Features

### Core User Management
- ✅ User Registration & Login with secure authentication
- ✅ Role-Based Access Control (RBAC) - Admin, Organizer, and Attendee roles
- ✅ Profile Management with photo upload

### Event Creation & Management
- ✅ Create Event - Organizers can create events with title, date, venue, and description
- ✅ Edit / Delete Event - Organizers can modify or remove events
- ✅ Event Categories & Tags - Events can be classified and tagged for easy filtering
- ✅ Event Visibility Settings - Events can be public, private, or invite-only
- ✅ Announcement Section - Organizers can post updates for each event
- ✅ Search events - Search functionality available on all pages

### Registration & Ticketing
- ✅ Event Registration - Easy registration and cancellation by attendees
- ✅ Digital Ticket Generation - Unique ticket IDs generated automatically
- ✅ Add to Wishlist - Events and resources can be bookmarked
- ✅ Upload resources - Organizers can upload event materials
- ✅ Show resources - Users can view and add resources to wishlist

### Scheduling & Notifications
- ✅ Event Schedule Module - Detailed timelines with sessions and speakers
- ✅ Event registration/reminder notifications - Automatic confirmations and reminders
- ✅ Upcoming events notifications

### Interaction & Feedback
- ✅ Chat System - Group chat for events and individual messaging
- ✅ Comment Section - Users can post comments, questions, or feedback
- ✅ Rating & Review System - Users can rate and review events after attending

### Analytics & Reporting
- ✅ Dashboard Analytics - Statistics for users, registrations, and events
- ✅ Different dashboards for Admin, Organizer, and Attendee
- ✅ Manual Check-in List - Mark attendees as present during events
- ✅ Certificate Generation - Generate certificates for registered and present attendees
- ✅ Event Link Sharing - Shareable event links

## Technology Stack

- **Backend:** PHP (Laravel Framework)
- **Frontend:** HTML, CSS, Blade Templates
- **Database:** MySQL

## Requirements

- PHP >= 8.2
- Composer
- MySQL >= 5.7 or MariaDB >= 10.3
- Node.js and NPM (for asset compilation, if needed)

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd nexevent
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Environment setup

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Configure database

Edit the `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexevent
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations

Create the database and run migrations:

```bash
php artisan migrate
```

(Optional) Seed the database with demo data:

```bash
php artisan db:seed
```

### 6. Create storage link

Create a symbolic link for storage:

```bash
php artisan storage:link
```

### 7. Start the development server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Usage

### Creating User Accounts

1. Register a new account through the registration page
2. Default role is "Attendee"
3. To create an Organizer account, register and contact an admin to change your role
4. Admin accounts can be created by setting the email in config or through the database

### Event Management (Organizers)

1. Login as an Organizer
2. Navigate to "Create Event" from the dashboard
3. Fill in event details including:
   - Title, description, dates, location
   - Category and tags
   - Visibility settings (public, private, or invite-only)
   - Maximum attendees
4. Add sessions and speakers
5. Upload resources
6. Post announcements
7. Manage check-ins during the event

### Attending Events (Attendees)

1. Browse events from the events page
2. Use search to find specific events
3. Register for events
4. View and download tickets
5. Add events to wishlist
6. Participate in event chat
7. Comment and review events
8. Download resources
9. Generate certificates after attending

## Database Schema

The application includes the following main tables:

- `users` - User accounts with roles
- `events` - Event information
- `event_registrations` - Event registrations
- `tickets` - Digital tickets
- `tags` - Event tags
- `event_tag` - Many-to-many relationship for event tags
- `announcements` - Event announcements
- `event_resources` - Event resources/files
- `wishlists` - User wishlists (polymorphic)
- `messages` - Chat messages (group and individual)
- `comments` - Event comments
- `reviews` - Event reviews with ratings
- `certificates` - Generated certificates
- `sessions` - Event schedule sessions
- `speakers` - Event speakers
- `event_checkins` - Attendee check-ins

## Key Features Implementation

### Event Visibility

- **Public:** Visible to everyone
- **Private:** Visible only to organizer, admins, and registered attendees
- **Invite-only:** Requires invite code to view/register

### Search Functionality

Search is available on all pages through the search bar. It searches event titles, descriptions, and locations.

### Wishlist System

Users can add events and resources to their wishlist. Items can be viewed and managed from the wishlist page.

### Chat System

- **Group Chat:** Available for each event, allowing all participants to communicate
- **Individual Messages:** Users can send direct messages to each other

### Certificate Generation

Certificates are automatically generated for users who:
1. Registered for the event
2. Were checked in during the event

Certificates can be viewed and downloaded from the user's dashboard.

## File Structure

```
nexevent/
├── app/
│   ├── Http/
│   │   └── Controllers/     # All controllers
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Notifications/       # Email notifications
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Web routes
└── public/                 # Public assets
```

## Security Features

- ✅ CSRF protection
- ✅ XSS protection
- ✅ Password hashing
- ✅ Role-based access control
- ✅ Authorization policies
- ✅ SQL injection protection (via Eloquent ORM)

## Contributing

This is a project for Software Engineering course. For questions or issues, please contact the development team.

## License

This project is developed for educational purposes.

## Support

For support, please refer to the Laravel documentation: https://laravel.com/docs
