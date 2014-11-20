# Sitemap generator plugin

This plugin will a generate `sitemap.xml` file in OctoberCMS based on desired CMS pages and others.

## Viewing the sitemap

Once this plugin is installed and the sitemap has been configured. The sitemap can be viewed by accessing the file relative to the website base path. For example, if the website is hosted at http://octobercms.com/ it can be viewed by opening this URL:

    http://octobercms.com/sitemap.xml

## Managing a sitemap definition

The sitemap is managed by selecting Sitemap from the Settings area of the back-end. There is a single sitemap definition for each theme and it will be created automatically.

A sitemap definition can contain multiple items and each item has a number of properties. There are common properties for all item types, and some properties depend on the item type. The common item properties are **Priority** and **Change frequency**. The Priority defines the priority of this item relative to other items in the sitemap. The Change frequency defines how frequently the page is likely to change.

#### Standard item types
The available item types depend on the installed plugins, but there are three basic item types that are supported out of the box.

###### URL {.subheader}
Items of this type are links to a specific fixed URL. That could be an URL of an or internal page. Items of this type don't have any other properties - just the title and URL.

###### Static page {.subheader}
Items of this type refer to static pages. The static page should be selected in the **Reference** drop-down list described below.

###### All static pages {.subheader}
Items of this type expand to create links to all static pages defined in the theme. 

#### Custom item types
Other plugins can supply new item types. For example, the [Blog plugin](http://octobercms.com/plugin/rainlab-blog) by [RainLab](http://octobercms.com/author/RainLab) supplies two more types:

###### Blog category {.subheader}
An item of this type represents a link to a specific blog category. The category should be selected in the **Reference** drop-down. This type also requires selecting a **CMS page** that outputs a blog category.

###### All blog categories {.subheader}
An item of this time expands into multiple items representing all blog existing categories. This type also requires selecting a **CMS page**.

#### Definition item properties
Depending on the selected item time you might need to provide other properties of the item. The available properties are described below.

###### Reference {.subheader}
A drop-down list of objects the item should refer to. The list content depends on the item type. For the **Static page** item type the list displays all static pages defined in the system. For the **Blog category** item type the list displays a list of blog categories.

###### Allow nested items {.subheader}
This checkbox is available only for item types that suppose nested objects. For example, static pages are hierarchical, and this property is available for the **Static page** item type. On the other hand, blog categories are not hierarchical, and the checkbox is hidden.

###### CMS Page {.subheader}
This drop-down is available for item types that require a special CMS page to refer to. For example, the **Blog category** item type requires a CMS page that hosts the `blogPosts` component. The CMS Page drop-down for this item type will only display pages that include this component.

---

The Sitemap plugin works *out of the box* and does not require any direct development to operate.

##### Registering new sitemap definition item types

The Sitemap plugin shares the same events for registering item types as the [Pages plugin](http://octobercms.com/plugin/rainlab-pages). See the documentation provided by this plugin for more information.

A small addition is required when resolving items, via the following event:

* `pages.menuitem.resolveItem` event handler "resolves" a menu item information and returns the actual item URL, title, an indicator whether the item is currently active, and subitems, if any.

##### Resolving items

When resolving an item, each item should return an extra key in the array called `mtime`. This should be a Date object (see `Carbon\Carbon`) or a timestamp value compatible with PHP's `date()` function and represent the last time the link was modified.

Expected result format:

```
Array (
    [url] => http://example.com/blog/category/another-category
    [mtime] => Carbon::now(),
    [items] => Array (
        [0] => Array  (
            [url] => http://example.com/blog/category/another-category
            [mtime] => Carbon::now(),
        )

        [1] => Array (
                [url] => http://example.com/blog/category/news
                [mtime] => Carbon::now(),
        )
    )
)
```