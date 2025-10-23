# Bug Fixes Report

## Overview
This report documents three critical bugs found and fixed in the HTML navigation header code.

---

## Bug #1: Undefined Phone Number (Logic Error / Critical Bug)

### Description
The tooltip contained a phone link with `href="tel:undefined"`, which would fail when users try to call.

### Location
Line 95 in the original code (tooltip section)

### Original Code
```html
<a href="tel:undefined" class="JRYcFv T--q0z base-link -clean">+7 (495) 647-10-00</a>
```

### Fixed Code
```html
<a href="tel:+74956471000" class="JRYcFv T--q0z base-link -clean" aria-label="Позвонить на дополнительный номер">
    +7 (495) 647-10-00
</a>
```

### Impact
- **Severity**: HIGH
- **Type**: Logic Error
- **User Impact**: Users clicking the phone number in the tooltip would get an error instead of initiating a call
- **Fix**: Converted the displayed phone number to proper tel: URI format (`tel:+74956471000`) and added proper ARIA label

---

## Bug #2: Missing Accessibility Attributes (Accessibility / Security)

### Description
Multiple interactive elements lacked proper ARIA attributes, making the site inaccessible to screen reader users and violating WCAG 2.1 standards.

### Issues Found
1. Dropdown buttons missing `aria-expanded` and `aria-haspopup`
2. Dropdown menus missing `role="menu"` and menu items missing `role="menuitem"`
3. Icons not marked as `aria-hidden="true"`
4. External links missing `rel="noopener noreferrer"` (security vulnerability)
5. Interactive elements missing descriptive `aria-label` attributes

### Original Code Example
```html
<button class="_base-button_zbos0_8 ...">
    <span class="_button-icon_zbos0_49">
        <i class="_vi-icon_su8nu_8 vi-icon-arrow-down NTzD6q"></i>
    </span>
    <span class="_label_zbos0_27">Получение и оплата</span>
</button>
```

### Fixed Code Example
```html
<button class="_base-button_zbos0_8 ..."
        aria-expanded="false"
        aria-haspopup="true"
        aria-label="Получение и оплата">
    <span class="_button-icon_zbos0_49">
        <i class="_vi-icon_su8nu_8 vi-icon-arrow-down NTzD6q" aria-hidden="true"></i>
    </span>
    <span class="_label_zbos0_27">Получение и оплата</span>
</button>
```

### Impact
- **Severity**: MEDIUM-HIGH
- **Type**: Accessibility Issue / Security Vulnerability
- **User Impact**: 
  - Screen reader users cannot properly navigate dropdowns
  - Security risk with external links (tabnabbing vulnerability)
  - Violates WCAG 2.1 Level A requirements
- **Fix**: Added comprehensive ARIA attributes and security attributes

---

## Bug #3: Inline Styles and Performance Issues

### Description
Excessive use of inline `style="display:none;"` attributes instead of CSS classes, plus numerous Vue.js framework artifacts (empty comment nodes) that bloat the HTML.

### Issues Found
1. Multiple `style="display:none;"` inline styles
2. Empty comment tags `<!--[-->` and `<!--]-->` throughout (Vue.js artifacts)
3. Inline style attributes that should be in external CSS
4. Tooltip with inline width style instead of CSS class

### Original Code Example
```html
<div class="_drop-content_1e9wp_21" style="display:none;">
    <!--[--><!--]-->
    <div class="_custom-scroll_rv8e1_8" style="">
        <!--[--><!--[-->
        <a href="/courier-delivery/" class="_drop-link_1e9wp_72">Доставка курьером</a>
        <!--]--><!--]-->
    </div>
</div>

<div class="_tooltip_3i352_8" style="width:268px; display:none;">
```

### Fixed Code
```html
/* CSS */
.hidden { display: none; }
.tooltip-wrapper { width: 268px; }

/* HTML */
<div class="_drop-content_1e9wp_21 hidden" role="menu">
    <div class="_custom-scroll_rv8e1_8">
        <a href="/courier-delivery/" class="_drop-link_1e9wp_72" role="menuitem">Доставка курьером</a>
    </div>
</div>

<div class="_tooltip_3i352_8 tooltip-wrapper hidden" role="tooltip">
```

### Impact
- **Severity**: MEDIUM
- **Type**: Performance / Code Quality
- **User Impact**:
  - Larger HTML payload (slower page load)
  - Harder to maintain (styles scattered in HTML)
  - Poor CSS caching (inline styles can't be cached)
  - Framework artifacts bloat DOM size
- **Fix**: 
  - Moved inline styles to CSS classes
  - Removed Vue.js comment artifacts
  - Improved maintainability and performance

---

## Summary

| Bug | Type | Severity | Fixed |
|-----|------|----------|-------|
| Undefined phone number | Logic Error | HIGH | ✅ |
| Missing accessibility attributes | Accessibility/Security | MEDIUM-HIGH | ✅ |
| Inline styles and framework artifacts | Performance/Quality | MEDIUM | ✅ |

## Testing Recommendations

1. **Bug #1**: Test phone links on mobile devices to ensure proper dialing
2. **Bug #2**: Run automated accessibility testing (axe, WAVE) and manual screen reader testing
3. **Bug #3**: Measure page load performance before/after to verify improvement

## Additional Notes

The fixed code maintains all original functionality while improving:
- Accessibility (WCAG 2.1 compliance)
- Security (preventing tabnabbing attacks)
- Performance (reduced HTML size, better CSS caching)
- Maintainability (cleaner code structure)
