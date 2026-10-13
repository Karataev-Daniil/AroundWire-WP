# AroundWire WordPress theme

Custom WordPress theme of the AroundWire marketing site: a home page, dozens of landing pages for
trade professionals built from versioned sections, "near me" job pages and a blog with search,
topic filters and AJAX loading.

Version 2.0 is a full rewrite of the original theme: modular PHP, escaped output, nonce and
capability checks everywhere, vanilla JavaScript instead of jQuery and Splide instead of Slick.

## Requirements

- WordPress 6.4+, PHP 8.1+
- Advanced Custom Fields Pro (page content and the "Block versions" box)
- Contact Form 7, MailPoet (forms), Yoast SEO (optional, exported with pages)

## Structure

```
functions.php            constants and module includes
inc/
  helpers.php            asset URLs, ACF wrappers, escaping helpers, inline SVG
  settings.php           Settings → AroundWire (GTM, Jivo, phone, form IDs, Brevo link)
  setup.php              theme supports, feeds off, security headers
  sections.php           landing section registry and the "Block versions" ACF group
  assets.php             per-template styles and scripts
  post-types.php         faqs, blog, faq_category, blog_tag
  blog.php, search.php   post cards, related pages, views counter, search helpers
  seo.php, reviews.php   head tags, document titles, review data and schema
  ajax.php               blog "load more" and topic filter endpoints
  admin/                 block version presets, related jobs metabox
  import-export/         blog, FAQ and page import/export (under each post type menu)
page-templates/          home, near me, sitemaps
landing-layout.php       landing pages: sections rendered in the chosen versions
template-parts/
  sections/              one file per landing section, all versions inside
  components/, blog/, header/, footer/, home/
assets/
  css/{base,layout,components,templates,sections/<section>/vN.css}
  js/{core,components,sections,templates,admin}
  vendor/splide/         Splide 4.1.4
```

## Landing pages

A page with the **Landing Page** template is assembled from nine sections (hero, benefits, steps,
clientele, video, start work, FAQ, reviews, "Let's meet"). The **Block versions** box picks a
version of each section and of the menu and footer; combinations can be saved as presets.
Only the stylesheets of the chosen versions are loaded.

## Development

```bash
composer install && composer lint   # WordPress Coding Standards
npm install && npm run lint:js      # ESLint
```

Scripts are plain ES2022 files loaded with `defer`; shared helpers live on `window.AW`
(`assets/js/core/core.js`). There is no build step.
