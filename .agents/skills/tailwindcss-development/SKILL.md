---
name: tailwindcss-development
description: "Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS."
license: MIT
metadata:
  author: laravel
---

# Tailwind CSS development

## Documentation

<<<<<<< Updated upstream
Use `search-docs` for detailed Tailwind CSS v3 patterns and documentation.
=======
Use `search-docs` for detailed Tailwind CSS v4 patterns and documentation.
>>>>>>> Stashed changes

## Basic usage

- Use Tailwind CSS classes to style HTML. Before introducing new patterns, check and follow the project's existing Tailwind conventions.
- Offer to extract repeated patterns into components that match the project's conventions, such as Blade, JSX, or Vue components.
- Consider class placement, order, priority, and defaults. Remove redundant classes, add classes to parent or child elements carefully to reduce repetition, and group elements logically.

<<<<<<< Updated upstream
## Tailwind CSS v3 specifics

- Always use Tailwind CSS v3 and verify that you use only classes it supports.
- Configure Tailwind in the `tailwind.config.js` file.
- Import Tailwind with the `@tailwind` directives:

<!-- v3 Import Syntax -->
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

## Spacing

When listing items, use gap utilities for spacing. Don't use margins.
=======
## Tailwind CSS v4 specifics

- Always use Tailwind CSS v4 and avoid deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.

### CSS-first configuration

In Tailwind v4, you configure Tailwind in CSS with the `@theme` directive. You don't need a separate `tailwind.config.js` file:

<!-- CSS-First Config -->
```css
@theme {
  --color-brand: oklch(0.72 0.11 178);
}
```

### Import syntax

In Tailwind v4, import Tailwind with a regular CSS `@import` statement instead of the `@tailwind` directives used in v3:

<!-- v4 Import Syntax -->
```diff
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss";
```

### Replaced utilities

Tailwind v4 removed deprecated utilities. Use the replacements shown below. For opacity, drop the `*-opacity-*` class and append the value as a modifier on the existing color: `bg-blue-500 bg-opacity-50` becomes `bg-blue-500/50`, `text-white text-opacity-75` becomes `text-white/75`.

| Deprecated | Replacement |
|------------|-------------|
| bg-opacity-* | bg-{color}/* |
| text-opacity-* | text-{color}/* |
| border-opacity-* | border-{color}/* |
| divide-opacity-* | divide-{color}/* |
| ring-opacity-* | ring-{color}/* |
| placeholder-opacity-* | placeholder-{color}/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |

## Spacing

Use `gap` utilities instead of margins for spacing between siblings:
>>>>>>> Stashed changes

<!-- Gap Utilities -->
```html
<div class="flex gap-8">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

## Dark mode

If existing pages and components support dark mode, new pages and components must support it the same way, typically using the `dark:` variant:

<!-- Dark Mode -->
```html
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
    Content adapts to color scheme
</div>
```

<<<<<<< Updated upstream
## Common patterns

### Flexbox layout

<!-- Flexbox Layout -->
```html
<div class="flex items-center justify-between gap-4">
    <div>Left content</div>
    <div>Right content</div>
</div>
```

### Grid layout

<!-- Grid Layout -->
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div>Card 1</div>
    <div>Card 2</div>
    <div>Card 3</div>
</div>
```

## Verification

1. Check the visual rendering in the browser
2. Test responsive breakpoints
3. If the project uses dark mode, verify it

## Common pitfalls

- Using margins for spacing between siblings instead of gap utilities
- Forgetting to add dark mode variants when the project uses dark mode
- Not checking existing project conventions before adding new utilities
- Overusing inline styles when Tailwind classes would suffice
=======
## Common pitfalls

- Using deprecated v3 utilities, such as `bg-opacity-*` and `flex-shrink-*`
- Using `@tailwind` directives instead of `@import "tailwindcss"`
- Using `tailwind.config.js` instead of the CSS `@theme` directive
- Using margins for spacing between siblings instead of gap utilities
- Forgetting to add dark mode variants when the project uses dark mode
>>>>>>> Stashed changes
