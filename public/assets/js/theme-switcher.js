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
                checkbox: '/OMS/public/assets/css/components/checkbox.css'
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
                checkbox: '/OMS/public/assets/css/dark_components/checkbox.css'
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
        
        Object.keys(this.cssFiles.light).forEach(fileType => {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.id = `theme-${fileType}`;
            link.type = 'text/css';
            head.appendChild(link);
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
        
        this.currentTheme = theme;
        this.storeTheme(theme);
        this.updateToggleButton();
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
