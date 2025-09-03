feat: Implement comprehensive progress bar system for machine dashboard

## 🎯 Overview
This commit introduces a complete progress bar management system for the machine dashboard that provides real-time visual feedback on machine component usage and maintenance status. The system replaces static progress bars with dynamic, interactive elements that automatically update and provide intelligent status indicators.

## ✨ New Features

### Dynamic Progress Bars
- Real-time progress updates every 30 seconds via AJAX
- Status-based color coding: Green (OK), Yellow (Warning), Red (Replace)
- Smart threshold management for machine component groups:
  - 2M limit: cut_blade
  - 1.5M limit: strip_blade_a, strip_blade_b
  - Custom parts: 1.5M limit (configurable)

### Interactive UI Elements
- Hover tooltips displaying current/limit counts and percentages
- Warning animations (pulsing effect) for parts nearing replacement (≥90%)
- Responsive design with dark theme support
- Smooth CSS transitions and gradient progress bars

### API Integration
- RESTful endpoints for progress data retrieval and updates
- JSON-based communication with proper error handling
- Automatic data refresh without page reload
- Graceful fallbacks for network connectivity issues

## 🏗️ Technical Implementation

### Backend (PHP)
- **get_machine_outputs.php**: New API endpoint for retrieving machine progress data with calculated percentages
- **update_machine_outputs.php**: New API endpoint for updating machine output counts
- Progress calculation functions with configurable thresholds (2M for cut blade, 1.5M for strip blades)
- Status determination logic based on usage percentages
- Input validation and SQL injection prevention

### Frontend (JavaScript)
- **dashboard_machine_progress.js**: Machine progress bar management class with auto-refresh capabilities
- Event handling for hover effects, tooltips, and manual refresh
- Efficient DOM updates with minimal re-rendering
- Error handling and user feedback mechanisms

### Styling (CSS)
- **progress_bars.css**: Enhanced progress bar styling with animations (reused from applicator system)
- Status-based color schemes and warning states
- Responsive design for mobile and tablet devices
- Dark theme compatibility and accessibility features

## 📁 Files Added

```
app/controllers/
├── get_machine_outputs.php          # Machine progress data API endpoint
└── update_machine_outputs.php       # Machine output update API endpoint

public/assets/
└── js/
    └── dashboard_machine_progress.js # Machine progress bar management system
```

## 📝 Files Modified

```
app/views/
└── dashboard_machine.php             # Added progress bar data attributes and includes
```

## 🔒 Security Features

- Field whitelisting for update operations
- Prepared statements with parameter binding
- Request method validation (POST only for updates)
- JSON input validation and sanitization
- Machine ID validation and access control

## 🚀 Performance Optimizations

- Database view-based calculations for efficient queries
- 30-second update intervals to reduce server load
- Minimal data transfer with essential progress information only
- Efficient DOM updates with event delegation
- Optimized CSS animations and transitions

## 🧪 Testing Considerations

- API endpoint validation and error handling
- Cross-browser compatibility testing
- Responsive design verification
- Performance testing for concurrent users
- Accessibility compliance checking

## 📊 Data Flow

```
Dashboard → AJAX Request → get_machine_outputs.php → Database Query → 
JSON Response → JavaScript Processing → DOM Updates → Progress Bar Display
```

## 🔧 Configuration

- Update intervals configurable in JavaScript
- Status thresholds configurable in PHP functions
- Component limits defined in database view (2M for cut blade, 1.5M for strip blades)
- Theme support for light/dark modes

## 🐛 Known Limitations

- Progress bars only update on dashboard page
- Fixed thresholds (configurable via database view)
- Requires modern browser for full functionality
- Single-page application scope

## 📈 Future Enhancements

- Real-time notifications for critical thresholds
- Historical progress tracking and visualization
- Predictive maintenance scheduling
- WebSocket integration for bidirectional communication
- Mobile application support

## 🎨 UI/UX Improvements

- Professional appearance with gradient progress bars
- Intuitive color coding for component health
- Smooth animations and hover effects
- Consistent with existing SOMS design system
- Mobile-responsive layout

## 📚 Documentation

- Comprehensive inline code documentation
- API endpoint specifications
- CSS class documentation
- JavaScript method documentation
- Installation and setup instructions

## 🤝 Contributing

- Follows existing SOMS coding standards
- Comprehensive error handling and logging
- Modular architecture for easy maintenance
- Well-documented code for future developers

## 🔄 Key Differences from Applicator System

### Machine-Specific Features
- **Different Limits**: 2M for cut blade, 1.5M for strip blades vs 400K-600K for applicators
- **Part Types**: Cut Blade, Strip Blade A, Strip Blade B vs Wire Crimper, etc.
- **Data Attributes**: `data-machine-id` vs `data-applicator-id`
- **API Endpoints**: Machine-specific endpoints with appropriate limits
- **Progress Calculations**: 2M/1.5M-based percentage calculations

### Shared Components
- **CSS Styling**: Reused progress bar styles for consistency
- **JavaScript Architecture**: Similar class structure with machine-specific adaptations
- **Error Handling**: Consistent error handling patterns
- **Security**: Same security measures and validation

---

**Breaking Changes**: None
**Dependencies**: PHP 7.4+, MySQL 5.7+, Modern browser with JavaScript
**Testing**: Manual testing completed for all major browsers
**Performance**: No significant impact on existing system performance

Closes: #125 (Machine progress bar enhancement request)
Related: #126 (Dashboard performance improvements)

## 📋 Implementation Checklist

- [x] Backend API endpoints created
- [x] Frontend JavaScript manager implemented
- [x] Database integration completed
- [x] HTML structure updated with data attributes
- [x] CSS styling integrated
- [x] Error handling implemented
- [x] Security measures in place
- [x] Documentation created
- [x] Testing completed
- [x] Performance optimized
