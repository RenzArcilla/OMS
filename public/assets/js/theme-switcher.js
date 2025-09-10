/**
 * Theme Switcher for OMS Application
 * Switches between light and dark themes by dynamically changing CSS file sources
 */

class ThemeSwitcher {
    constructor() {
        this.themeKey = 'oms-theme';
        this.lightTheme = 'light';
        this.darkTheme = 'dark';
        this.currentTheme = this.getStoredTheme() || this.lightTheme;
        
        // CSS file mappings
        this.cssFiles = {
            light: {
                base: '/OMS/public/assets/css/base/base.css',
                header: '/OMS/public/assets/css/base/header.css',
                typography: '/OMS/public/assets/css/base/typography.css',
                buttons: '/OMS/public/assets/css/components/buttons.css',
                cards: '/OMS/public/assets/css/components/cards.css',
                sidebar: '/OMS/public/assets/css/components/sidebar.css',
                tables: '/OMS/public/assets/css/components/tables.css',
                modal: '/OMS/public/assets/css/components/modal.css',
                pagination: '/OMS/public/assets/css/components/pagination.css',
                checkbox: '/OMS/public/assets/css/components/checkbox.css',
                // Original template files
                account_settings: '/OMS/public/assets/css/account_settings.css',
                login: '/OMS/public/assets/css/login.css',
                file_upload: '/OMS/public/assets/css/file_upload.css',
                forgot_password: '/OMS/public/assets/css/forgot_password.css',
                forgot_password_confirmation: '/OMS/public/assets/css/forgot_password_confirmation.css',
                home: '/OMS/public/assets/css/home.css',
                landing_page: '/OMS/public/assets/css/landing_page.css',
                logout: '/OMS/public/assets/css/logout.css',
                record_output: '/OMS/public/assets/css/record_output.css',
                signup: '/OMS/public/assets/css/signup.css',
                admin_hero: '/OMS/public/assets/css/admin_hero.css',
                admin_manage_user: '/OMS/public/assets/css/admin_manage_user.css',
                dashboard_machine: '/OMS/public/assets/css/dashboard_machine.css',
                hero: '/OMS/public/assets/css/hero.css'
            },
            dark: {
                base: '/OMS/public/assets/css/dark_base/base.css',
                header: '/OMS/public/assets/css/dark_base/header.css',
                typography: '/OMS/public/assets/css/dark_base/typography.css',
                buttons: '/OMS/public/assets/css/dark_components/buttons.css',
                cards: '/OMS/public/assets/css/dark_components/cards.css',
                sidebar: '/OMS/public/assets/css/dark_components/sidebar.css',
                tables: '/OMS/public/assets/css/dark_components/tables.css',
                modal: '/OMS/public/assets/css/dark_components/modal.css',
                pagination: '/OMS/public/assets/css/dark_components/pagination.css',
                checkbox: '/OMS/public/assets/css/dark_components/checkbox.css',
                // Dark template files (using dark_ui folder)
                account_settings: '/OMS/public/assets/css/dark_ui/dark_account_settings.css',
                login: '/OMS/public/assets/css/dark_ui/dark_login.css',
                file_upload: '/OMS/public/assets/css/dark_ui/dark_file_upload.css',
                forgot_password: '/OMS/public/assets/css/dark_ui/dark_forgot_password.css',
                forgot_password_confirmation: '/OMS/public/assets/css/dark_ui/dark_forgot_password_confirmation.css',
                home: '/OMS/public/assets/css/dark_ui/dark_home.css',
                landing_page: '/OMS/public/assets/css/dark_ui/dark_landing_page.css',
                logout: '/OMS/public/assets/css/dark_ui/dark_logout.css',
                record_output: '/OMS/public/assets/css/dark_ui/dark_record_output.css',
                signup: '/OMS/public/assets/css/dark_ui/dark_signin.css',
                admin_hero: '/OMS/public/assets/css/dark_ui/dark_admin_hero.css',
                admin_manage_user: '/OMS/public/assets/css/dark_ui/dark_admin_manage_user.css',
                dashboard_machine: '/OMS/public/assets/css/dark_ui/dark_dashboard.css',
                hero: '/OMS/public/assets/css/dark_ui/dark_hero.css'
            }
        };
        
        this.init();
    }
    
    init() {
        this.loadThemeToggleCSS();
        this.createThemeLinks();
        this.loadTheme(this.currentTheme);
        this.createToggleButton();
        this.bindEvents();
    }
    
    loadThemeToggleCSS() {
        // Load theme toggle CSS if not already loaded
        if (!document.getElementById('theme-toggle-css')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.id = 'theme-toggle-css';
            link.href = '/OMS/public/assets/css/theme-toggle.css';
            document.head.appendChild(link);
        }
    }
    
    getStoredTheme() {
        try {
            return localStorage.getItem(this.themeKey);
        } catch (e) {
            console.warn('localStorage not available, using default theme');
            return this.lightTheme;
        }
    }
    
    storeTheme(theme) {
        try {
            localStorage.setItem(this.themeKey, theme);
        } catch (e) {
            console.warn('Could not save theme preference to localStorage');
        }
    }
    
    createThemeLinks() {
        // Create link elements for each CSS file type
        const head = document.head;
        
        // First, create links for base and component files
        const baseAndComponentFiles = ['base', 'header', 'typography', 'buttons', 'cards', 'sidebar', 'tables', 'modal', 'pagination', 'checkbox'];
        
        baseAndComponentFiles.forEach(fileType => {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.id = `theme-${fileType}`;
            link.type = 'text/css';
            head.appendChild(link);
        });
        
        // Then, scan for existing CSS links and create theme links for them
        this.scanAndCreateTemplateLinks();
    }
    
    scanAndCreateTemplateLinks() {
        const head = document.head;
        const existingLinks = head.querySelectorAll('link[rel="stylesheet"]');
        
        existingLinks.forEach(link => {
            const href = link.href;
            
            // Check if this is one of our template files
            Object.keys(this.cssFiles.light).forEach(fileType => {
                if (fileType === 'base' || fileType === 'header' || fileType === 'typography' || 
                    fileType === 'buttons' || fileType === 'cards' || fileType === 'sidebar' || 
                    fileType === 'tables' || fileType === 'modal' || fileType === 'pagination' || 
                    fileType === 'checkbox') {
                    return; // Skip base and component files, already handled
                }
                
                const lightPath = this.cssFiles.light[fileType];
                if (href.includes(lightPath.split('/').pop())) {
                    // This is a template file, create a theme link for it
                    if (!document.getElementById(`theme-${fileType}`)) {
                        const themeLink = document.createElement('link');
                        themeLink.rel = 'stylesheet';
                        themeLink.id = `theme-${fileType}`;
                        themeLink.type = 'text/css';
                        head.appendChild(themeLink);
                    }
                }
            });
        });
    }
    
    loadTheme(theme) {
        const files = this.cssFiles[theme];
        if (!files) {
            console.error(`Theme "${theme}" not found`);
            return;
        }
        
        // Update all CSS file sources
        Object.keys(files).forEach(fileType => {
            const link = document.getElementById(`theme-${fileType}`);
            if (link) {
                link.href = files[fileType];
            }
        });
        
        // Also update any existing template CSS links directly
        this.updateExistingTemplateLinks(theme);
        
        this.currentTheme = theme;
        this.storeTheme(theme);
        this.updateToggleButton();
    }
    
    updateExistingTemplateLinks(theme) {
        const head = document.head;
        const existingLinks = head.querySelectorAll('link[rel="stylesheet"]');
        
        existingLinks.forEach(link => {
            const href = link.href;
            
            // Check if this is one of our template files and update it
            Object.keys(this.cssFiles[theme]).forEach(fileType => {
                if (fileType === 'base' || fileType === 'header' || fileType === 'typography' || 
                    fileType === 'buttons' || fileType === 'cards' || fileType === 'sidebar' || 
                    fileType === 'tables' || fileType === 'modal' || fileType === 'pagination' || 
                    fileType === 'checkbox') {
                    return; // Skip base and component files, handled by theme links
                }
                
                const lightPath = this.cssFiles.light[fileType];
                const darkPath = this.cssFiles.dark[fileType];
                
                if (href.includes(lightPath.split('/').pop()) || href.includes(darkPath.split('/').pop())) {
                    // This is a template file, update it directly
                    link.href = this.cssFiles[theme][fileType];
                }
            });
        });
    }
    
    toggleTheme() {
        const newTheme = this.currentTheme === this.lightTheme ? this.darkTheme : this.lightTheme;
        this.loadTheme(newTheme);
    }
    
    createToggleButton() {
        // Check if toggle button already exists
        if (document.getElementById('theme-toggle')) {
            return;
        }
        
        const button = document.createElement('button');
        button.id = 'theme-toggle';
        button.className = 'theme-toggle-btn';
        button.innerHTML = this.currentTheme === this.lightTheme ? '🌙 Dark Mode' : '☀️ Light Mode';
        button.title = `Switch to ${this.currentTheme === this.lightTheme ? 'dark' : 'light'} mode`;
        
        // Button styling is handled by CSS class
        
        document.body.appendChild(button);
    }
    
    updateToggleButton() {
        const button = document.getElementById('theme-toggle');
        if (button) {
            button.innerHTML = this.currentTheme === this.lightTheme ? '🌙 Dark Mode' : '☀️ Light Mode';
            button.title = `Switch to ${this.currentTheme === this.lightTheme ? 'dark' : 'light'} mode`;
        }
    }
    
    bindEvents() {
        const button = document.getElementById('theme-toggle');
        if (button) {
            button.addEventListener('click', () => {
                this.toggleTheme();
            });
        }
    }
    
    // Public method to get current theme
    getCurrentTheme() {
        return this.currentTheme;
    }
    
    // Public method to set theme programmatically
    setTheme(theme) {
        if (this.cssFiles[theme]) {
            this.loadTheme(theme);
        } else {
            console.error(`Theme "${theme}" not found`);
        }
    }
    
    // Public method to re-scan for new template links (useful for dynamic content)
    rescanTemplateLinks() {
        this.scanAndCreateTemplateLinks();
        this.loadTheme(this.currentTheme); // Reapply current theme to new links
    }
}

// Initialize theme switcher when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.themeSwitcher = new ThemeSwitcher();
});

// Also initialize immediately if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.themeSwitcher = new ThemeSwitcher();
    });
} else {
    window.themeSwitcher = new ThemeSwitcher();
}
