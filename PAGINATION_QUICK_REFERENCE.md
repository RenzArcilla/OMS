# Pagination Quick Reference Guide

## Quick Start

### 1. URL Parameters
```
?page=2&items_per_page=20&search=HP001&description=SIDE&type=BIG
```

### 2. API Endpoints
```
GET /SOMS/app/controllers/get_disabled_applicators.php
GET /SOMS/app/controllers/get_disabled_records.php
GET /SOMS/app/controllers/get_disabled_machines.php
```

### 3. JavaScript Files
```html
<script src="../../public/assets/js/disabled_applicators_pagination.js"></script>
<script src="../../public/assets/js/disabled_records_pagination.js"></script>
<script src="../../public/assets/js/disabled_machines_pagination.js"></script>
```

## Key Functions

### Core Functions
- `loadDisabledApplicators()` - Load data for applicators
- `loadDisabledRecords()` - Load data for records
- `loadDisabledMachines()` - Load data for machines
- `applyDisabledApplicatorFilters()` - Apply filters (debounced)
- `applyDisabledRecordFilters()` - Apply filters (debounced)
- `applyDisabledMachineFilters()` - Apply filters (debounced)
- `goToPage(page)` - Navigate to specific page
- `changeItemsPerPage(value)` - Change items per page

### URL Management
- `updateURL()` - Update browser URL without reload
- `updateURLRecords()` - Update URL for records table
- `updateURLMachines()` - Update URL for machines table

## Database Functions

### Applicators
```php
getDisabledApplicatorsCount($search, $description, $type)
getFilteredApplicators($limit, $offset, $search, $description, $type, $is_active)
```

### Records
```php
getDisabledRecordsCount($search, $shift)
getFilteredRecords($limit, $offset, $search, $shift, $is_active)
```

### Machines
```php
getDisabledMachinesCount($search, $description)
getFilteredMachines($limit, $offset, $search, $description, $is_active)
```

## State Variables

### Applicators
```javascript
let currentPage = 1;
let itemsPerPage = 10;
let currentSearch = '';
let currentDescription = 'ALL';
let currentType = 'ALL';
```

### Records
```javascript
let currentPageRecords = 1;
let itemsPerPageRecords = 10;
let currentSearchRecords = '';
let currentShiftRecords = 'ALL';
```

### Machines
```javascript
let currentPageMachines = 1;
let itemsPerPageMachines = 10;
let currentSearchMachines = '';
let currentDescriptionMachines = 'ALL';
```

## CSS Classes

### Pagination Controls
- `.pagination-container` - Main container
- `.pagination-info` - Info text area
- `.pagination-controls` - Navigation controls
- `.pagination-numbers` - Page number links
- `.pagination-btn` - Button styling
- `.pagination-current` - Current page
- `.pagination-prev` - Previous button
- `.pagination-next` - Next button
- `.pagination-ellipsis` - Ellipsis (...)

### Loading States
- `.loading-spinner` - Loading animation

## Error Handling

### Backend Errors
```json
{
  "success": false,
  "error": "Error message"
}
```

### Frontend Errors
- Network errors show "Failed to load" message
- Empty results show "No results found"
- Console logging for debugging

## Testing Checklist

- [ ] Basic pagination navigation
- [ ] Search with pagination
- [ ] Filter with pagination
- [ ] URL parameter persistence
- [ ] Browser back/forward navigation
- [ ] Items per page selection
- [ ] Error handling
- [ ] Loading states
- [ ] Mobile responsiveness

## Common Issues & Solutions

### Pagination Not Showing
- Check if `totalPages > 1`
- Verify API response has pagination data
- Check JavaScript console for errors

### Search Not Working
- Verify debounce timing (300ms)
- Check API endpoint accessibility
- Ensure search parameters are being sent

### URL Not Updating
- Check browser compatibility
- Verify `window.history.pushState()` support
- Check for JavaScript errors

### Data Not Loading
- Verify API endpoint URLs
- Check network connectivity
- Verify database permissions
- Check PHP error logs

## Performance Tips

### Database
- Use indexed columns for filtering
- Limit result sets appropriately
- Use prepared statements

### Frontend
- Debounce search inputs
- Minimize DOM manipulation
- Use efficient selectors
- Cache pagination state

## Security Notes

- All inputs are validated and sanitized
- SQL injection prevention via prepared statements
- XSS protection via proper escaping
- CSRF protection maintained
- Admin-only access for disabled records

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (with polyfills if needed)
- Mobile browsers supported
- JavaScript ES6+ features used
