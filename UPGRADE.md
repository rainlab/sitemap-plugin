# Upgrading from Sitemap to Tailor

As of October CMS v3.2 a [page finder widget](https://docs.octobercms.com/3.x/element/form/widget-pagefinder.html) can be used to define sitemap definitions using a graphical interface. This feature supercedes the Sitemap plugin and introduces multisite support for sitemaps.

The demo theme shipped with October CMS includes a sitemap CMS page (`themes/demo/sitemap.htm`) and a sitemap settings admin panel page provided by a Tailor blueprint.

## Example Blueprint Definition

To build a new sitemap, create blueprint with the following example content. It uses a handle of `Site\Sitemap` and is a `structure` type with one level of depth. The navigation targets the settings area of the admin panel. The fields include a `pagefinder` form widget, `priority` and `changefreq` fields.

```yaml
handle: Site\Sitemap
type: structure
name: Sitemap
drafts: false
pagefinder: false

structure:
    maxDepth: 1

navigation:
    parent: settings
    icon: icon-sitemap
    description: Specify pages to appear in the sitemap for your website.
    category: CATEGORY_CMS

fields:
    reference:
        label: Reference
        type: pagefinder

    priority:
        label: Priority
        commentAbove: The priority of this URL relative to other URLs on your site.
        type: radio
        inlineOptions: true
        options:
            '0.1': '0.1'
            '0.2': '0.2'
            '0.3': '0.3'
            '0.4': '0.4'
            '0.5': '0.5'
            '0.6': '0.6'
            '0.7': '0.7'
            '0.8': '0.8'
            '0.9': '0.9'
            '1.0': '1.0'

    changefreq:
        commentAbove: How frequently the page is likely to change.
        label: Change Frequency
        type: radio
        inlineOptions: true
        options:
            always: Always
            hourly: Hourly
            daily: Daily
            weekly: Weekly
            monthly: Monthly
            yearly: Yearly
            never: Never

    nesting:
        label: Include nested items
        shortLabel: Nesting
        comment: Nested items could be generated dynamically by supported page references.
        type: checkbox

    replace:
        label: Replace this item with its generated children
        comment: Use this checkbox to push generated menu items to the same level with this item. This item itself will be hidden.
        type: checkbox
        column: false
        scope: false
        trigger:
            action: disable|empty
            field: nesting
            condition: unchecked
```

## CMS Page Definition

To show the sitemap on the frontend, create a CMS page with the following example content. It uses a URL of `/sitemap.xml` and responds with a `application/xml` content header. The collection component is used to import the sitemap blueprint. The `render_sitemap_item` is used to recursively display the sitemap links.

```twig
title = "Sitemap"
url = "/sitemap.xml"

[resources]
headers[Content-Type] = 'application/xml'

[collection sitemap]
handle = "Site\Sitemap"
==
{% macro render_sitemap_item(item, reference, isRoot) %}
    {% import _self as nav %}
    {% set hideRootItem = isRoot and item.replace %}
    {% if reference.url and not hideRootItem %}
        <url>
            <loc>{{ reference.url }}</loc>
            <lastmod>{{ reference.mtime|date('c') }}</lastmod>
            <changefreq>{{ item.changefreq }}</changefreq>
            <priority>{{ item.priority }}</priority>
        </url>
    {% endif %}
    {% if reference.items %}
        {% for child in reference.items %}
            {{ nav.render_sitemap_item(item, child) }}
        {% endfor %}
    {% endif %}
{% endmacro %}
{% import _self as nav %}
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="https://www.w3.org/1999/xhtml"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
>
    {% for item in sitemap %}
        {{ nav.render_sitemap_item(
            item,
            link(item.reference, { nesting: item.nesting }),
            true
        ) }}
    {% endfor %}
</urlset>
```

## Building the Sitemap

Navigate to **Settings → Sitemap** to start building the sitemap entries.

## Viewing the Sitemap

Just like this plugin, the sitemap can be viewed by accessing the file relative to the website base path. For example, if the website is hosted at https://octobercms.com it can be viewed by opening this URL:

```
https://octobercms.com/sitemap.xml
```

## See Also

- [Page Finder Form Widget](https://docs.octobercms.com/3.x/element/form/widget-pagefinder.html)
- [Collection Component](https://docs.octobercms.com/3.x/cms/components/collection.html)
