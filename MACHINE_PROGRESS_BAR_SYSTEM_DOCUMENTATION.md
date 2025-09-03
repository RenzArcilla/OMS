# Machine Progress Bar System Documentation

## Overview

The Machine Progress Bar System is a dynamic dashboard feature that displays real-time progress bars for machine parts based on their cumulative output values. The system handles both standard parts (Cut Blade, Strip Blade A, Strip Blade B) and custom parts, with automatic updates and reset functionality.

## System Architecture

### Core Components

1. **Backend API** (`get_machine_outputs.php`)
2. **Frontend JavaScript** (`dashboard_machine_progress.js`)
3. **Database Models** (`read_monitor_machine_and_machine.php`)
4. **Dashboard View** (`dashboard_machine.php`)

### Data Flow

```
User Action → Database Update → API Call → JavaScript Update → UI Refresh
```

## Database Structure

### Primary Tables

#### `monitor_machine`
- **Purpose**: Stores cumulative output values for all machine parts
- **Key Fields**:
  - `machine_id`: Foreign key to machines table
  - `cut_blade_output`: Cumulative cut blade output
  - `strip_blade_a_output`: Cumulative strip blade A output
  - `strip_blade_b_output`: Cumulative strip blade B output
  - `custom_parts_output`: JSON string storing custom part outputs
  - `total_machine_output`: Total cumulative output (unaffected by resets)

#### `machine_outputs`
- **Purpose**: Stores individual output records
- **Usage**: Historical tracking and record keeping

## Part Categories and Limits

### Standard Parts

| Part Name | Limit | Status Colors |
|-----------|-------|---------------|
| Cut Blade | 2M | Green (<70%), Yellow (70-90%), Red (>90%) |
| Strip Blade A | 1.5M | Green (<70%), Yellow (70-90%), Red (>90%) |
| Strip Blade B | 1.5M | Green (<70%), Yellow (70-90%), Red (>90%) |

### Custom Parts
- **Limit**: 1.5M (configurable)
- **Storage**: JSON format in `custom_parts_output` field
- **Dynamic**: Parts can be added/removed through admin interface

## User Scenarios

### Scenario 1: Recording Output

**User Action**: Record 500,000 output for HP-001

**System Response**:
1. All parts in `monitor_machine` are updated to 500,000
2. Progress bars display:
   - Cut Blade: 25% (500K/2M)
   - Strip Blade A: 33.33% (500K/1.5M)
   - Strip Blade B: 33.33% (500K/1.5M)

### Scenario 2: Resetting Specific Part

**User Action**: Reset Cut Blade for HP-001

**System Response**:
1. Only `cut_blade_output` is set to 0
2. Other parts remain unchanged
3. Progress bars display:
   - Cut Blade: 0% (reset - empty bar)
   - Strip Blade A: 33.33% (unchanged)
   - Strip Blade B: 33.33% (unchanged)

### Scenario 3: Adding More Output After Reset

**User Action**: Record 200,000 more output for HP-001

**System Response**:
1. All parts are updated:
   - Cut Blade: 200,000 (starts from 0)
   - Strip Blade A: 700,000 (500K + 200K)
   - Strip Blade B: 700,000 (500K + 200K)
2. Progress bars display:
   - Cut Blade: 10% (200K/2M)
   - Strip Blade A: 46.67% (700K/1.5M)
   - Strip Blade B: 46.67% (700K/1.5M)

## API Endpoints

### GET `/SOMS/app/controllers/get_machine_outputs.php`

**Purpose**: Fetch progress data for dashboard display

**Parameters**:
- `machine_id` (optional): Specific machine ID

**Response Format**:
```json
{
  "success": true,
  "data": [
    {
      "machine_id": 3,
      "control_no": "HP003",
      "progress": {
                 "cut_blade": {
           "current": 500000,
           "limit": 2000000,
           "percentage": 25,
           "status": "green"
         },
        "strip_blade_a": {
          "current": 0,
          "limit": 1500000,
          "percentage": 0,
          "status": "green"
        }
      }
    }
  ]
}
```

## JavaScript Implementation

### MachineProgressBarManager Class

**Location**: `public/assets/js/dashboard_machine_progress.js`

**Key Methods**:

#### `loadProgressData()`
- Fetches data from API endpoint
- Updates internal `progressData` property
- Triggers progress bar updates

#### `updateAllProgressBars()`
- Processes all machines in data
- Handles both single and multiple machine scenarios

#### `updateProgressBar(machineId, partName, partData)`
- Updates individual progress bar elements
- Modifies width, color, and text display
- Handles tooltip updates

### HTML Structure

**Progress Bar Container**:
```html
<td data-machine-id="3" data-part="cut_blade">
    <div><strong>500,000</strong> / 2M</div>
    <div class="progress-bar">
        <div class="progress-fill" style="width: 25%;"></div>
    </div>
</td>
```

**Data Attributes**:
- `data-machine-id`: Unique machine identifier
- `data-part`: Part name for targeting specific progress bars

## Reset Functionality

### Reset Process

1. **User clicks reset button** for specific part
2. **Reset controller** (`reset_machine.php`) processes request
3. **Model function** (`resetMachinePartOutput`) updates database
4. **Specific part output** is set to 0 in `monitor_machine`
5. **Total output** remains unchanged
6. **Progress bars refresh** to reflect new state

### Reset Controllers

#### `reset_machine.php`
- Handles reset form submission
- Validates user permissions
- Calls model functions for database updates

#### `update_monitor_machine.php`
- Contains `resetMachinePartOutput()` function
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
    background-color: #4CAF50; /* OK status */
}

.status-yellow {
    background-color: #FF9800; /* Warning status */
}

.status-red {
    background-color: #F44336; /* Replace status */
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
refreshMachineProgressBars()

// Check manager status
console.log(window.machineProgressBarManager)

// Check data structure
console.log(window.machineProgressBarManager.progressData)
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
   - Enter output value (e.g., 500,000)
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
curl -X GET "http://localhost/SOMS/app/controllers/get_machine_outputs.php"
```

**JavaScript Testing**:
```javascript
// Test in browser console
refreshMachineProgressBars()
```

## Maintenance

### Regular Tasks

1. **Monitor Performance**:
   - Check API response times
   - Monitor JavaScript console for errors
   - Verify data accuracy

2. **Update Limits**:
   - Modify part limits in `get_machine_outputs.php`
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
│   │   ├── get_machine_outputs.php      # API endpoint
│   │   ├── record_output.php             # Record output handler
│   │   └── reset_machine.php             # Reset handler
│   ├── models/
│   │   ├── read_joins/
│   │   │   └── read_monitor_machine_and_machine.php
│   │   └── update_monitor_machine.php    # Reset functions
│   └── views/
│       └── dashboard_machine.php          # Dashboard view
├── public/
│   └── assets/
│       └── js/
│           └── dashboard_machine_progress.js  # Progress bar manager
└── MACHINE_PROGRESS_BAR_SYSTEM_DOCUMENTATION.md   # This file
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

**Last Updated**: December 2024
**Maintainer**: Development Team
**Contact**: [Team Contact Information]
