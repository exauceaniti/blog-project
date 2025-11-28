// Constantes globales
export const BREAKPOINTS = {
    SM: 640,
    MD: 768,
    LG: 1024,
    XL: 1280,
    XXL: 1536
};

export const EVENTS = {
    THEME_CHANGE: 'theme:change',
    MODAL_OPEN: 'modal:open',
    MODAL_CLOSE: 'modal:close',
    FORM_SUBMIT: 'form:submit',
    AJAX_START: 'ajax:start',
    AJAX_END: 'ajax:end'
};

export const SELECTORS = {
    HEADER: '.site-header',
    FOOTER: '.site-footer',
    MODAL: '[data-modal]',
    DROPDOWN: '[data-dropdown]',
    TOOLTIP: '[data-tooltip]',
    FORM: 'form[data-validate]'
};

export const STORAGE_KEYS = {
    THEME: 'blog-theme',
    SIDEBAR_STATE: 'sidebar-state',
    USER_PREFERENCES: 'user-preferences'
};