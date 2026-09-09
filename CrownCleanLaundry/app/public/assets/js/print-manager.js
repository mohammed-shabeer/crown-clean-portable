/**
 * Print Manager Module
 * Handles all printing operations with configured printer settings
 * 
 * Usage:
 * 1. Include this script in your print page
 * 2. Initialize with: PrintManager.init(config)
 * 3. Call PrintManager.print() to execute print
 */

const PrintManager = (function() {
    'use strict';
    
    // Default configuration
    const defaultConfig = {
        printer_name: '',
        paper_size: 'A4',
        paper_width: 210,
        paper_height: 297,
        orientation: 'portrait',
        margin_top: 10,
        margin_bottom: 10,
        margin_left: 10,
        margin_right: 10,
        copies: 1,
        auto_print: false,
        print_header_footer: false,
        color_mode: 'monochrome',
        scale: 100
    };
    
    let config = {...defaultConfig};
    let printCount = 0;
    let isInitialized = false;
    
    /**
     * Initialize the print manager with configuration
     * @param {Object} userConfig - Printer configuration from server
     */
    function init(userConfig = {}) {
        config = {...defaultConfig, ...userConfig};
        isInitialized = true;
        printCount = 0;
        
        // Apply dynamic CSS for print settings
        applyPrintStyles();
        
        console.log('PrintManager initialized with config:', config);
    }
    
    /**
     * Apply dynamic CSS styles for printing
     */
    function applyPrintStyles() {
        // Remove existing dynamic print styles
        const existingStyle = document.getElementById('dynamic-print-styles');
        if (existingStyle) {
            existingStyle.remove();
        }
        
        const style = document.createElement('style');
        style.id = 'dynamic-print-styles';
        style.textContent = generatePrintCSS();
        document.head.appendChild(style);
    }
    
    /**
     * Generate CSS for @page rules based on configuration
     */
    function generatePrintCSS() {
        let css = '@page {\n';
        
        // Paper size
        if (config.paper_size === 'custom' || ['80mm', '58mm'].includes(config.paper_size)) {
            css += `    size: ${config.paper_width}mm ${config.paper_height}mm;\n`;
        } else {
            css += `    size: ${config.paper_size} ${config.orientation};\n`;
        }
        
        // Margins
        css += `    margin: ${config.margin_top}mm ${config.margin_right}mm ${config.margin_bottom}mm ${config.margin_left}mm;\n`;
        
        css += '}\n';
        
        // Print media query styles
        css += '@media print {\n';
        css += '    body {\n';
        css += '        -webkit-print-color-adjust: exact !important;\n';
        css += '        print-color-adjust: exact !important;\n';
        css += '        color-adjust: exact !important;\n';
        css += '    }\n';
        
        // Hide headers and footers if configured
        if (!config.print_header_footer) {
            css += '    @page { margin-top: 0; }\n';
            css += '    @page:first { margin-top: 0; }\n';
        }
        
        // Scale
        if (config.scale !== 100) {
            const scale = config.scale / 100;
            css += '    html {\n';
            css += `        transform: scale(${scale});\n`;
            css += '        transform-origin: top left;\n';
            css += '    }\n';
        }
        
        css += '}\n';
        
        return css;
    }
    
    /**
     * Execute print operation
     * @param {boolean} closeAfter - Whether to close window after printing
     */
    function print(closeAfter = true) {
        if (!isInitialized) {
            console.warn('PrintManager not initialized. Using default settings.');
            init();
        }
        
        if (config.auto_print) {
            silentPrint(closeAfter);
        } else {
            normalPrint(closeAfter);
        }
    }
    
    /**
     * Silent print - attempts to print without showing dialog
     * Note: Browser security restrictions may still show the dialog
     * @param {boolean} closeAfter - Whether to close window after printing
     */
    function silentPrint(closeAfter) {
        // Execute print
        printWithCopies(closeAfter);
    }
    
    /**
     * Normal print with dialog
     * @param {boolean} closeAfter - Whether to close window after printing
     */
    function normalPrint(closeAfter) {
        window.print();
        if (closeAfter) {
            handlePostPrint();
        }
    }
    
    /**
     * Print multiple copies
     * Note: Most browsers handle copies internally, but we provide this for custom handling
     * @param {boolean} closeAfter - Whether to close window after printing
     */
    function printWithCopies(closeAfter) {
        // Most browsers handle multiple copies through the print dialog
        // For auto-print scenarios, we trigger print once and rely on CSS/browser settings
        window.print();
        
        if (closeAfter) {
            handlePostPrint();
        }
    }
    
    /**
     * Handle post-print actions (closing window, etc.)
     */
    function handlePostPrint() {
        // Use multiple event listeners for better browser compatibility
        
        // Method 1: afterprint event
        const afterPrintHandler = () => {
            setTimeout(() => {
                window.close();
            }, 500);
        };
        window.addEventListener('afterprint', afterPrintHandler, { once: true });
        
        // Method 2: focus event (fallback for when print dialog closes)
        const focusHandler = () => {
            setTimeout(() => {
                window.close();
            }, 500);
        };
        window.addEventListener('focus', focusHandler, { once: true });
        
        // Method 3: Timeout fallback (for browsers that don't support events well)
        setTimeout(() => {
            // Check if window is still open after 10 seconds
            if (!window.closed) {
                // Don't force close, let user handle it
                console.log('Print completed. Window can be closed.');
            }
        }, 10000);
    }
    
    /**
     * Get current configuration
     * @returns {Object} Current printer configuration
     */
    function getConfig() {
        return {...config};
    }
    
    /**
     * Update configuration
     * @param {Object} newConfig - New configuration values
     */
    function updateConfig(newConfig) {
        config = {...config, ...newConfig};
        applyPrintStyles();
    }
    
    /**
     * Print preview (opens print dialog without auto-closing)
     */
    function preview() {
        const originalAutoPrint = config.auto_print;
        config.auto_print = false;
        window.print();
        config.auto_print = originalAutoPrint;
    }
    
    /**
     * Check if WebUSB is supported (for direct printer communication)
     * Note: This is an experimental feature
     * @returns {boolean}
     */
    function isWebUSBSupported() {
        return 'usb' in navigator;
    }
    
    /**
     * Get paper size dimensions
     * @param {string} size - Paper size name
     * @returns {Object} Width and height in mm
     */
    function getPaperDimensions(size) {
        const dimensions = {
            'A4': { width: 210, height: 297 },
            'A5': { width: 148, height: 210 },
            'Letter': { width: 216, height: 279 },
            'Legal': { width: 216, height: 356 },
            '80mm': { width: 80, height: 297 },
            '58mm': { width: 58, height: 297 }
        };
        
        return dimensions[size] || dimensions['A4'];
    }
    
    // Public API
    return {
        init: init,
        print: print,
        preview: preview,
        getConfig: getConfig,
        updateConfig: updateConfig,
        isWebUSBSupported: isWebUSBSupported,
        getPaperDimensions: getPaperDimensions
    };
})();

// Auto-initialize if config is provided globally
if (typeof window.printerConfig !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        PrintManager.init(window.printerConfig);
    });
}
