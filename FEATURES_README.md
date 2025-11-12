# New Features Implementation

This document describes the two new features that have been implemented in separate folders as requested.

## Features Implemented

### 1. Add to Wishlist (Events/Resources)
Users can bookmark events or event resources from past events that they are interested in.

**Location:** `app/Http/Controllers/Features/WishlistController.php` and `resources/views/features/wishlist/`

**Features:**
- Users can add past events to their wishlist
- Users can add event resources (from past events) to their wishlist
- Users can view all their wishlisted items in one place
- Users can remove items from their wishlist
- Toggle functionality to add/remove items easily

**Routes:**
- `GET /features/wishlist` - View wishlist
- `POST /features/wishlist/toggle` - Toggle wishlist status
- `DELETE /features/wishlist/{wishlist}` - Remove from wishlist

### 2. Upload Resources
Organizers can upload event materials such as slides, media files, etc. for their events.

**Location:** `app/Http/Controllers/Features/EventResourceController.php` and `resources/views/features/resources/`

**Features:**
- Organizers can upload files (PDF, DOC, PPT, Images, Videos, Audio)
- Maximum file size: 10MB
- Support for different resource types (slides, documents, media, other)
- Organizers can view all resources for an event
- Organizers can delete resources they uploaded
- Users can download resources from past events
- Auto-detection of file types based on extension

**Routes:**
- `GET /features/events/{event}/resources` - List resources for an event
- `GET /features/events/{event}/resources/create` - Upload form
- `POST /features/events/{event}/resources` - Store uploaded resource
- `GET /features/resources/{eventResource}/download` - Download resource
- `DELETE /features/resources/{eventResource}` - Delete resource

## Database Structure

### Tables Created:
1. **events** - Stores event information
   - id, organizer_id, title, description, start_date, end_date, location, status

2. **event_resources** - Stores uploaded resources
   - id, event_id, uploaded_by, title, description, file_path, file_name, file_type, file_size

3. **wishlists** - Stores user wishlist items
   - id, user_id, event_id (nullable), event_resource_id (nullable)

### Models Created:
- `App\Models\Event`
- `App\Models\EventResource`
- `App\Models\Wishlist`

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Features/
│           ├── EventController.php
│           ├── EventResourceController.php
│           └── WishlistController.php
└── Models/
    ├── Event.php
    ├── EventResource.php
    └── Wishlist.php

database/
└── migrations/
    ├── 2025_01_15_000001_create_events_table.php
    ├── 2025_01_15_000002_create_event_resources_table.php
    └── 2025_01_15_000003_create_wishlists_table.php

resources/
└── views/
    └── features/
        ├── events/
        │   ├── index.blade.php
        │   └── show.blade.php
        ├── resources/
        │   ├── create.blade.php
        │   └── index.blade.php
        └── wishlist/
            └── index.blade.php
```

## Setup Instructions

1. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

2. **Create Storage Link (if not already created):**
   ```bash
   php artisan storage:link
   ```

3. **Set Permissions:**
   Ensure the `storage/app/public` directory is writable.

## Usage

### For Organizers:
1. Navigate to a past event
2. Click "Upload Resource" button
3. Fill in the form and upload your file
4. Resources will be available for download by users

### For Users:
1. Browse past events at `/features/events`
2. View event details and resources
3. Click the heart icon to add events/resources to wishlist
4. View your wishlist at `/features/wishlist`
5. Download resources from past events

## Navigation

New navigation links have been added:
- **Past Events** - Browse all past events
- **Wishlist** - View your bookmarked items (requires authentication)

## Notes

- Only past events can be added to wishlist
- Only organizers (and admins) can upload resources
- File uploads are stored in `storage/app/public/event-resources/`
- Maximum file size is 10MB (configurable in controller)
- All existing files remain unchanged as requested

