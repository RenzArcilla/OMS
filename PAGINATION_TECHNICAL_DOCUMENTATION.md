# Pagination Implementation Technical Documentation

## Overview
This document describes the implementation of pagination functionality for the "Recently Deleted Applicators", "Recently Deleted Outputs", and "Recently Deleted Machines" tables in the SOMS application. The implementation provides AJAX-based pagination with URL parameter support, search integration, and filter persistence.

## Architecture

### 1. Backend Architecture

#### Controllers
- **`app/controllers/get_disabled_applicators.php`**
  - Handles paginated requests for disabled applicators
  - Parameters: `page`, `items_per_page`, `search`, `description`, `type`
  - Returns JSON with data and pagination metadata

- **`app/controllers/get_disabled_records.php`**
  - Handles paginated requests for disabled records/outputs
  - Parameters: `page`, `items_per_page`, `search`, `shift`
  - Returns JSON with data and pagination metadata

- **`app/controllers/get_disabled_machines.php`**
  - Handles paginated requests for disabled machines
  - Parameters: `page`, `items_per_page`, `search`, `description`
  - Returns JSON with data and pagination metadata

#### Models
- **`app/models/read_applicators.php`**
  - Added `getDisabledApplicatorsCount()` function
  - Supports search and filter counting for pagination calculations

- **`app/models/read_joins/record_and_outputs.php`**
  - Added `getDisabledRecordsCount()` function
  - Uses existing `getFilteredRecords()` function with `is_active = 0`
  - Supports search and shift filter counting

- **`app/models/read_machines.php`**
  - Added `getDisabledMachinesCount()` function
  - Uses existing `getFilteredMachines()` function with `is_active = 0`
  - Supports search and description filter counting

### 2. Frontend Architecture

#### JavaScript Files
- **`public/assets/js/disabled_applicators_pagination.js`**
  - Handles pagination for Recently Deleted Applicators table
  - Manages URL parameters and state persistence
  - Integrates with existing search and filter functionality

- **`public/assets/js/disabled_records_pagination.js`**
  - Handles pagination for Recently Deleted Outputs table
  - Similar functionality to applicators pagination
  - Manages different filter types (shift vs description/type)

- **`public/assets/js/disabled_machines_pagination.js`**
  - Handles pagination for Recently Deleted Machines table
  - Similar functionality to applicators pagination
  - Manages description filter (AUTOMATIC, SEMI-AUTOMATIC)

#### Views
- **`app/views/recently_deleted_applicator.php`**
  - Updated with dynamic pagination controls
  - Includes items per page selector
  - Maintains existing search and filter inputs

- **`app/views/recently_deleted_outputs_table.php`**
  - Updated with dynamic pagination controls
  - Includes items per page selector
  - Maintains existing search and shift filter inputs

- **`app/views/recently_deleted_machine.php`**
  - Updated with dynamic pagination controls
  - Includes items per page selector
  - Maintains existing search and description filter inputs

## API Endpoints

### 1. Get Disabled Applicators
```
GET /SOMS/app/controllers/get_disabled_applicators.php
```

**Parameters:**
- `page` (int): Current page number (default: 1)
- `items_per_page` (int): Items per page (5-50, default: 10)
- `search` (string): Search term for HP number, terminal number, etc.
- `description` (string): Filter by description (ALL, SIDE, END, CLAMP, STRIP AND CRIMP)
- `type` (string): Filter by wire type (ALL, BIG, SMALL)

**Response:**
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "total_records": 47,
    "items_per_page": 10,
    "showing_from": 1,
    "showing_to": 10
  },
  "empty_db": false
}
```

### 2. Get Disabled Records
```
GET /SOMS/app/controllers/get_disabled_records.php
```

**Parameters:**
- `page` (int): Current page number (default: 1)
- `items_per_page` (int): Items per page (5-50, default: 10)
- `search` (string): Search term for record ID, HP numbers, etc.
- `shift` (string): Filter by shift (ALL, 1st, 2nd, NIGHT)

**Response:**
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "total_pages": 3,
    "total_records": 25,
    "items_per_page": 10,
    "showing_from": 1,
    "showing_to": 10
  },
  "empty_db": false
}
```

### 3. Get Disabled Machines
```
GET /SOMS/app/controllers/get_disabled_machines.php
```

**Parameters:**
- `page` (int): Current page number (default: 1)
- `items_per_page` (int): Items per page (5-50, default: 10)
- `search` (string): Search term for control number, model, maker, etc.
- `description` (string): Filter by description (ALL, AUTOMATIC, SEMI-AUTOMATIC)

**Response:**
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "total_pages": 4,
    "total_records": 35,
    "items_per_page": 10,
    "showing_from": 1,
    "showing_to": 10
  },
  "empty_db": false
}
```

## URL Parameter System

### Supported URL Parameters
The pagination system supports flexible URL parameters that can be combined:

```
?page=2&items_per_page=20&search=HP001&description=SIDE&type=BIG
```

### Parameter Details
- **`page`**: Page number (1-based indexing)
- **`items_per_page`**: Number of items per page (5, 10, 20, 50)
- **`search`**: Search term for filtering records
- **`description`**: Applicator/Machine description filter
- **`type`**: Wire type filter (for applicators only)
- **`shift`**: Shift filter (for records only)

### URL Examples
```
# Basic pagination
localhost/SOMS/app/views/dashboard_applicator.php?page=2

# Pagination with search
localhost/SOMS/app/views/dashboard_applicator.php?page=3&search=HP001

# Pagination with filters
localhost/SOMS/app/views/dashboard_applicator.php?page=1&description=SIDE&type=BIG

# Complete example
localhost/SOMS/app/views/dashboard_applicator.php?page=2&items_per_page=20&search=HP001&description=SIDE&type=BIG
```

## JavaScript Functions

### Core Functions

#### `loadDisabledApplicators()` / `loadDisabledRecords()` / `loadDisabledMachines()`
- Fetches paginated data via AJAX
- Updates table content and pagination controls
- Handles loading states and error conditions

#### `applyDisabledApplicatorFilters()` / `applyDisabledRecordFilters()` / `applyDisabledMachineFilters()`
- Debounced function (300ms delay)
- Updates current filters and resets to page 1
- Triggers data reload with new filters

#### `updatePaginationControls(pagination)`
- Updates pagination info text
- Enables/disables previous/next buttons
- Generates page number links with ellipsis

#### `updateURL()` / `updateURLRecords()` / `updateURLMachines()`
- Updates browser URL without page reload
- Uses `window.history.pushState()`
- Preserves all current parameters

### State Management
```javascript
// Global state variables for each table
let currentPage = 1;
let itemsPerPage = 10;
let totalPages = 1;
let totalRecords = 0;
let currentSearch = '';
let currentDescription = 'ALL';
let currentType = 'ALL';
```

## Database Queries

### Applicators Count Query
```sql
SELECT COUNT(*) FROM applicators 
WHERE is_active = 0
AND (hp_no LIKE :search OR terminal_no LIKE :search OR serial_no LIKE :search OR invoice_no LIKE :search)
AND description = :description
AND wire = :type
```

### Records Count Query
```sql
SELECT COUNT(*) FROM records r
LEFT JOIN applicator_outputs ao1 ON r.record_id = ao1.record_id AND r.applicator1_id = ao1.applicator_id
LEFT JOIN applicator_outputs ao2 ON r.record_id = ao2.record_id AND r.applicator2_id = ao2.applicator_id
LEFT JOIN machine_outputs mo ON r.record_id = mo.record_id
LEFT JOIN applicators a1 ON r.applicator1_id = a1.applicator_id
LEFT JOIN applicators a2 ON r.applicator2_id = a2.applicator_id
LEFT JOIN machines m ON r.machine_id = m.machine_id
WHERE r.is_active = 0
AND (r.record_id LIKE :search OR r.last_updated LIKE :search OR a1.hp_no LIKE :search OR a2.hp_no LIKE :search OR m.control_no LIKE :search)
AND r.shift = :shift
```

### Machines Count Query
```sql
SELECT COUNT(*) FROM machines 
WHERE is_active = 0
AND (control_no LIKE :search OR model LIKE :search OR serial_no LIKE :search OR invoice_no LIKE :search)
AND description = :description
```

## Error Handling

### Backend Error Handling
- Input validation for all parameters
- SQL injection prevention via prepared statements
- Exception handling with JSON error responses
- Parameter sanitization and type casting

### Frontend Error Handling
- Network error handling with user-friendly messages
- Loading states with spinner indicators
- Fallback to empty state when no data
- Console logging for debugging

## Performance Considerations

### Database Optimization
- Indexed queries on `is_active`, `record_id`, `hp_no`, `control_no`
- Efficient COUNT queries for pagination calculations
- Prepared statements for query optimization

### Frontend Optimization
- Debounced search to prevent excessive API calls
- Efficient DOM manipulation
- Minimal re-renders using targeted updates

## Security Features

### Input Validation
- Parameter type casting and validation
- SQL injection prevention
- XSS protection via proper escaping
- CSRF protection maintained

### Access Control
- Admin-only access for disabled records
- Session-based authentication
- Proper authorization checks

## Integration Points

### Existing Functionality
- Restore functionality preserved
- Event delegation maintained
- Search and filter integration
- Modal system compatibility

### CSS Integration
- Uses existing pagination CSS classes
- Responsive design maintained
- Consistent styling with existing UI

## Testing Considerations

### Test Cases
1. **Basic Pagination**
   - Navigate between pages
   - Verify correct data display
   - Check pagination controls

2. **Search Integration**
   - Search with pagination
   - Verify search results across pages
   - Test search with filters

3. **Filter Integration**
   - Apply filters with pagination
   - Verify filter persistence
   - Test multiple filter combinations

4. **URL Parameters**
   - Direct URL access with parameters
   - Browser back/forward navigation
   - Bookmark functionality

5. **Error Scenarios**
   - Network failures
   - Invalid parameters
   - Empty result sets

## Deployment Notes

### File Dependencies
- Ensure all JavaScript files are included in the correct order
- Verify CSS files are loaded
- Check database permissions for new queries

### Configuration
- No additional configuration required
- Uses existing database structure
- Compatible with current authentication system

## Future Enhancements

### Potential Improvements
1. **Server-side caching** for frequently accessed data
2. **Infinite scroll** as an alternative to pagination
3. **Export functionality** for paginated data
4. **Advanced filtering** with date ranges
5. **Bulk operations** on paginated data

### Scalability Considerations
- Database query optimization for large datasets
- Pagination caching strategies
- Load balancing considerations
- Memory usage optimization

## Troubleshooting

### Common Issues
1. **Pagination not showing**: Check if total pages > 1
2. **Search not working**: Verify JavaScript console for errors
3. **URL parameters not updating**: Check browser compatibility
4. **Data not loading**: Verify API endpoint accessibility

### Debug Information
- Browser console logging for JavaScript errors
- Network tab for API request/response inspection
- Database query logging for performance issues
- Error response handling for backend issues
