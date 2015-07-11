/*
 * Handles the Pages main page.
 */
+function ($) { "use strict";
    var SitemapDefinitions = function () {
        this.init()
    }

    SitemapDefinitions.prototype.init = function() {
        /*
         * Bind event handlers
         */
        var self = this

        /*
         * Handle the sitemap saving
         */
        $(document).on('oc.beforeRequest', '#sitemapForm', function(e, data) {
            return self.onSaveSitemapItems(this, e, data)
        })

        /*
         * Compact tab pane
         */
        var $primaryPanel = $('.control-tabs.primary-tabs')
        $('.tab-pane', $primaryPanel).addClass('pane-compact')
    }

    /*
     * Triggered before sitemap is saved
     */
    SitemapDefinitions.prototype.onSaveSitemapItems = function(form, e, data) {
        var items = [],
            $items = $('div[data-control=treeview] > ol > li', form)

        var iterator = function(items) {
            var result = []

            $.each(items, function() {
                var item = $(this).data('sitemap-item')

                var $subitems = $('> ol >li', this)
                if ($subitems.length)
                    item['items'] = iterator($subitems)

                result.push(item)
            })

            return result
        }

        data.options.data['itemData'] = iterator($items)
    }

    $(document).ready(function(){
        $.oc.sitemapDefinitions = new SitemapDefinitions()
    })

}(window.jQuery);