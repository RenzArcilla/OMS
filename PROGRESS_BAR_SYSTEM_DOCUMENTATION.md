# Progress Bar System Documentation

## Overview

The Progress Bar System is a dynamic dashboard feature that displays real-time progress bars for applicator parts based on their cumulative output values. The system handles both standard parts (Wire Crimper, Wire Anvil, etc.) and custom parts, with automatic updates and reset functionality.

## System Architecture

### Core Components

1. **Backend API** (`get_dashboard_outputs.php`)
2. **Frontend JavaScript** (`dashboard.js`)
3. **Database Models** (`read_monitor_applicator_and_applicator.php`)
4. **Dashboard View** (`dashboard_applicator.php`)

### Data Flow

```
User Action → Database Update → API Call → JavaScript Update → UI Refresh
```

## Database Structure

### Primary Tables

#### `monitor_applicator`
- **Purpose**: Stores cumulative output values for all applicator parts
- **Key Fields**:
  - `applicator_id`: Foreign key to applicators table
  - `wire_crimper_output`: Cumulative wire crimper output
  - `wire_anvil_output`: Cumulative wire anvil output
  - `insulation_crimper_output`: Cumulative insulation crimper output
  - `insulation_anvil_output`: Cumulative insulation anvil output
  - `slide_cutter_output`: Cumulative slide cutter output
  - `cutter_holder_output`: Cumulative cutter holder output
  - `shear_blade_output`: Cumulative shear blade output
  - `cutter_a_output`: Cumulative cutter A output
  - `cutter_b_output`: Cumulative cutter B output
  - `custom_parts_output`: JSON string storing custom part outputs
  - `total_output`: Total cumulative output (unaffected by resets)

#### `applicator_outputs`
- **Purpose**: Stores individual output records
- **Usage**: Historical tracking and record keeping

## Part Categories and Limits

### Standard Parts

| Part Name | Limit | Status Colors |
|-----------|-------|---------------|
| Wire Crimper | 400K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Wire Anvil | 400K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Insulation Crimper | 400K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Insulation Anvil | 400K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Slide Cutter | 400K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Cutter Holder | 500K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Shear Blade | 500K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Cutter A | 600K | Green (<70%), Yellow (70-90%), Red (>90%) |
| Cutter B | 600K | Green (<70%), Yellow (70-90%), Red (>90%) |

### Custom Parts
- **Limit**: 600K (configurable)
- **Storage**: JSON format in `custom_parts_output` field
- **Dynamic**: Parts can be added/removed through admin interface

## User Scenarios

### Scenario 1: Recording Output

**User Action**: Record 300,000 output for HP-001

**System Response**:
1. All parts in `monitor_applicator` are updated to 300,000
2. Progress bars display:
   - Wire Crimper: 75% (300K/400K)
   - Wire Anvil: 75% (300K/400K)
   - Cutter Holder: 60% (300K/500K)
   - Cutter A: 50% (300K/600K)

### Scenario 2: Resetting Specific Part

**User Action**: Reset Wire Crimper for HP-001

**System Response**:
1. Only `wire_crimper_output` is set to 0
2. Other parts remain unchanged
3. Progress bars display:
   - Wire Crimper: 0% (reset - empty bar)
   - Wire Anvil: 75% (unchanged)
   - Cutter Holder: 60% (unchanged)
   - Cutter A: 50% (unchanged)

### Scenario 3: Adding More Output After Reset

**User Action**: Record 100,000 more output for HP-001

**System Response**:
1. All parts are updated:
   - Wire Crimper: 100,000 (starts from 0)
   - Wire Anvil: 400,000 (300K + 100K)
   - Cutter Holder: 400,000 (300K + 100K)
2. Progress bars display:
   - Wire Crimper: 25% (100K/400K)
   - Wire Anvil: 100% (400K/400K - full)
   - Cutter Holder: 80% (400K/500K)

## API Endpoints

### GET `/SOMS/app/controllers/get_dashboard_outputs.php`

**Purpose**: Fetch progress data for dashboard display

**Parameters**:
- `applicator_id` (optional): Specific applicator ID

**Response Format**:
```json
{
  "success": true,
  "data": [
    {
      "applicator_id": 3,
      "hp_no": "HP003",
      "progress": {
        "wire_crimper": {
          "current": 300000,
          "limit": 400000,
          "percentage": 75.0,
          "status": "yellow"
        },
        "wire_anvil": {
          "current": 0,
          "limit": 400000,
          "percentage": 0,
          "status": "green"
        }
      }
    }
  ]
}
```

## JavaScript Implementation

### ProgressBarManager Class

**Location**: `public/assets/js/dashboard.js`

**Key Methods**:

#### `loadProgressData()`
- Fetches data from API endpoint
- Updates internal `progressData` property
- Triggers progress bar updates

#### `updateAllProgressBars()`
- Processes all applicators in data
- Handles both single and multiple applicator scenarios

#### `updateProgressBar(applicatorId, partName, partData)`
- Updates individual progress bar elements
- Modifies width, color, and text display
- Handles tooltip updates

### HTML Structure

**Progress Bar Container**:
```html
<td data-applicator-id="3" data-part="wire_crimper">
    <div><strong>300,000</strong> / 400K</div>
    <div class="progress-bar">
        <div class="progress-fill" style="width: 75%;"></div>
    </div>
</td>
```

**Data Attributes**:
- `data-applicator-id`: Unique applicator identifier
- `data-part`: Part name for targeting specific progress bars

## Reset Functionality

### Reset Process

1. **User clicks reset button** for specific part
2. **Reset controller** (`reset_applicator.php`) processes request
3. **Model function** (`resetApplicatorPartOutput`) updates database
4. **Specific part output** is set to 0 in `monitor_applicator`
5. **Total output** remains unchanged
6. **Progress bars refresh** to reflect new state

### Reset Controllers

#### `reset_applicator.php`
- Handles reset form submission
- Validates user permissions
- Calls model functions for database updates

#### `update_monitor_applicator.php`
- Contains `resetApplicatorPartOutput()` function
- Updates specific part output to 0
- Preserves total output value

## CSS Styling

### Progress Bar Classes

```css
.progress-bar {
    /* Container styling */
}

.progress-fill {
    /* Fill bar styling */
}

.status-green {
    background-color: #10B981; /* OK status */
}

.status-yellow {
    background-color: #F59E0B; /* Warning status */
}

.status-red {
    background-color: #EF4444; /* Replace status */
}

.warning {
    /* Additional warning styling */
}
```

## Error Handling

### Common Issues

1. **API Errors**:
   - Network connectivity issues
   - Database connection problems
   - Invalid data format

2. **JavaScript Errors**:
   - Missing DOM elements
   - Invalid data structure
   - Selector mismatches

### Debugging

**Console Commands**:
```javascript
// Manual refresh
refreshProgressBars()

// Check manager status
console.log(window.progressBarManager)

// Check data structure
console.log(window.progressBarManager.progressData)
```

## Performance Considerations

### Auto-Refresh
- **Interval**: 30 seconds
- **Purpose**: Keep data current
- **Configurable**: Can be adjusted in `setupAutoRefresh()`

### Data Optimization
- **Selective Updates**: Only updates changed elements
- **Efficient Selectors**: Uses specific CSS selectors
- **Minimal DOM Manipulation**: Updates only necessary properties

## Security Considerations

### Input Validation
- **User Permissions**: Toolkeeper/Admin access required
- **Data Sanitization**: All inputs are validated and sanitized
- **SQL Injection Prevention**: Prepared statements used

### Access Control
- **Authentication**: Session-based authentication required
- **Authorization**: Role-based access control
- **CSRF Protection**: Form tokens for state-changing operations

## Testing Procedures

### Manual Testing Steps

1. **Record Output Test**:
   - Navigate to Record Output page
   - Enter output value (e.g., 300,000)
   - Verify all progress bars update correctly

2. **Reset Test**:
   - Click reset button for specific part
   - Verify only that part shows 0% progress
   - Verify other parts remain unchanged

3. **Cumulative Test**:
   - Record additional output after reset
   - Verify reset part starts counting from 0
   - Verify other parts continue from previous values

### Automated Testing

**API Testing**:
```bash
# Test API endpoint
curl -X GET "http://localhost/SOMS/app/controllers/get_dashboard_outputs.php"
```

**JavaScript Testing**:
```javascript
// Test in browser console
refreshProgressBars()
```

## Maintenance

### Regular Tasks

1. **Monitor Performance**:
   - Check API response times
   - Monitor JavaScript console for errors
   - Verify data accuracy

2. **Update Limits**:
   - Modify part limits in `get_dashboard_outputs.php`
   - Update CSS for new status colors
   - Test with new limit values

3. **Add Custom Parts**:
   - Add parts through admin interface
   - Verify JSON structure in database
   - Test progress bar display

### Troubleshooting

**Progress Bars Not Updating**:
1. Check browser console for JavaScript errors
2. Verify API endpoint is accessible
3. Check database connection
4. Validate HTML structure and data attributes

**Reset Not Working**:
1. Check user permissions
2. Verify database update queries
3. Test reset controller directly
4. Check for JavaScript errors

## Future Enhancements

### Potential Improvements

1. **Real-time Updates**:
   - WebSocket implementation
   - Server-sent events
   - Push notifications

2. **Advanced Analytics**:
   - Trend analysis
   - Predictive maintenance
   - Performance metrics

3. **Mobile Optimization**:
   - Responsive design improvements
   - Touch-friendly controls
   - Offline functionality

4. **Export Features**:
   - Progress report generation
   - Data visualization
   - Historical analysis

## File Structure

```
SOMS/
├── app/
│   ├── controllers/
│   │   ├── get_dashboard_outputs.php      # API endpoint
│   │   ├── record_output.php             # Record output handler
│   │   └── reset_applicator.php           # Reset handler
│   ├── models/
│   │   ├── read_joins/
│   │   │   └── read_monitor_applicator_and_applicator.php
│   │   └── update_monitor_applicator.php  # Reset functions
│   └── views/
│       └── dashboard_applicator.php       # Dashboard view
├── public/
│   └── assets/
│       └── js/
│           └── dashboard.js              # Progress bar manager
└── PROGRESS_BAR_SYSTEM_DOCUMENTATION.md   # This file
```

## Version History

### v1.0 (Current)
- Initial implementation
- Basic progress bar functionality
- Reset capability
- Auto-refresh system
- Custom parts support

### Planned Features
- Real-time updates
- Advanced analytics
- Mobile optimization
- Export functionality

---

**Last Updated**: September 2025
**Maintainer**: Development Team
**Contact**: [Team Contact Information]
