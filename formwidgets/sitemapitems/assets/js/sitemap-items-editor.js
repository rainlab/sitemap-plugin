/*
 * The sitemap item editor. Provides tools for managing the 
 * sitemap items.
 */
+function ($) { "use strict";
    var SitemapItemsEditor = function (el, options) {
        this.$el = $(el)
        this.options = options

        this.init()
    }

    SitemapItemsEditor.prototype.init = function() {
        var self = this

        this.alias = this.$el.data('alias')
        this.$treevView = this.$el.find('div[data-control="treeview"]')

        this.typeInfo = {}

        // Sitemap item is clicked
        this.$el.on('open.oc.treeview', function(e) {
            return self.onItemClick(e.relatedTarget)
        })

        // Sub item is clicked in the master tabs
        this.$el.on('submenu.oc.treeview', $.proxy(this.onSubItemClick, this))

        this.$el.on('click', 'a[data-control="add-item"]', function(e) {
            self.onCreateItem(e.target)
            return false
        })
    }

    /*
     * Triggered when a sub item is clicked in the editor.
     */
    SitemapItemsEditor.prototype.onSubItemClick = function(e) {
        if ($(e.relatedTarget).data('control') == 'delete-sitemap-item')
            this.onDeleteItem(e.relatedTarget)

        return false
    }

    /*
     * Removes an item
     */
    SitemapItemsEditor.prototype.onDeleteItem = function(link) {
        if (!confirm('Do you really want to delete this sitemap definition?'))
            return

        $(link).trigger('change')
        $(link).closest('li[data-sitemap-item]').remove()

        $(window).trigger('oc.updateUi')

        this.$treevView.treeView('update')
        this.$treevView.treeView('fixSubItems')
    }

    /*
     * Opens the item editor
     */
    SitemapItemsEditor.prototype.onItemClick = function(item, newItemMode) {
        var $item = $(item),
            $container = $('> div', $item),
            self = this

        $container.one('show.oc.popup', function(e){
            $(document).trigger('render')

            self.$popupContainer = $(e.relatedTarget)
            self.$itemDataContainer = $container.closest('li')

            $('input[type=checkbox]', self.$popupContainer).removeAttr('checked')

            self.loadProperties(self.$popupContainer, self.$itemDataContainer.data('sitemap-item'))
            self.$popupForm = self.$popupContainer.find('form')
            self.itemSaved = false

            $('select[name=type]', self.$popupContainer).change(function(){
                self.loadTypeInfo(false, true)
            })

            self.$popupContainer.on('keydown', function(e) {
                if (e.which == 13)
                    self.applySitemapItem()
            })

            $('button[data-control="apply-btn"]', self.$popupContainer).click($.proxy(self.applySitemapItem, self))

            var $updateTypeOptionsBtn = $('<a class="sidebar-control" href="#"><i class="icon-refresh"></i></a>')
            $('div[data-field-name=reference]').addClass('input-sidebar-control').append($updateTypeOptionsBtn)

            $updateTypeOptionsBtn.click(function(){
                self.loadTypeInfo(true)

                return false
            })

            $updateTypeOptionsBtn.keydown(function(ev){
                if (ev.which == 13 || ev.which == 32) {
                    self.loadTypeInfo(true)
                    return false
                }
            })

            var $updateCmsPagesBtn = $updateTypeOptionsBtn.clone(true)
            $('div[data-field-name=cmsPage]').addClass('input-sidebar-control').append($updateCmsPagesBtn)

            self.loadTypeInfo()
        })

        $container.one('hide.oc.popup', function(e) {
            if (!self.itemSaved && newItemMode)
                $item.remove()

            self.$treevView.treeView('update')
            self.$treevView.treeView('fixSubItems')
        })

        $container.popup({
            content: $('script[data-editor-template]', this.$el).html(),
            placement: 'center',
            modal: true,
            closeOnPageClick: true,
            highlightModalTarget: true,
            useAnimation: true,
            width: 600
        })

        return false
    }

    SitemapItemsEditor.prototype.loadProperties = function($popupContainer, properties) {
        this.properties = properties

        var self = this

        $.each(properties, function(property) {
            var $input = $('[name="'+property+'"]', $popupContainer).not('[type=hidden]')

            if ($input.prop('type') !== 'checkbox' ) {
                $input.val(this)
                $input.change()
            } else {
                var checked = !(this == '0' || this == 'false' || this == 0 || this == undefined || this == null)

                checked ? $input.prop('checked', 'checked') : $input.removeAttr('checked')
            }
        })
    }

    SitemapItemsEditor.prototype.loadTypeInfo = function(force, focusList) {
        var type = $('select[name=type]', this.$popupContainer).val()

        var self = this

        if (!force && this.typeInfo[type] !== undefined) {
            self.applyTypeInfo(this.typeInfo[type], type, focusList)
            return
        }

        $.oc.stripeLoadIndicator.show()
        this.$popupForm.request('onGetItemTypeInfo')
            .always(function(){
                $.oc.stripeLoadIndicator.hide()
            })
            .done(function(data){
                self.typeInfo[type] = data.sitemapItemTypeInfo
                self.applyTypeInfo(data.sitemapItemTypeInfo, type, focusList)
            })
    }

    SitemapItemsEditor.prototype.applyTypeInfo = function(typeInfo, type, focusList) {
        var $referenceFormGroup = $('div[data-field-name="reference"]', this.$popupContainer),
            $optionSelector = $('select', $referenceFormGroup),
            $nestingFormGroup = $('div[data-field-name="nesting"]', this.$popupContainer),
            $urlFormGroup = $('div[data-field-name="url"]', this.$popupContainer),
            $cmsPageFormGroup = $('div[data-field-name="cmsPage"]', this.$popupContainer),
            $cmsPageSelector = $('select', $cmsPageFormGroup),
            prevSelectedReference = $optionSelector.val(),
            prevSelectedPage = $cmsPageSelector.val()

        if (typeInfo.references) {
            $optionSelector.find('option').remove()
            $referenceFormGroup.show()

            var iterator = function(options, level, path) {
                $.each(options, function(code) {
                    var $option = $('<option></option>').attr('value', code),
                        offset = Array(level*4).join('&nbsp;'),
                        isObject = $.type(this) == 'object'

                    $option.text(isObject ? this.title : this)

                    var optionPath = path.length > 0
                        ? (path + ' / ' + $option.text())
                        : $option.text()

                    $option.data('path', optionPath)

                    $option.html(offset + $option.html())

                    $optionSelector.append($option)

                    if (isObject)
                        iterator(this.items, level+1, optionPath)
                })
            }

            iterator(typeInfo.references, 0, '')

            $optionSelector.val(prevSelectedReference ? prevSelectedReference : this.properties.reference)
        }
        else {
            $referenceFormGroup.hide()
        }

        if (typeInfo.cmsPages) {
            $cmsPageSelector.find('option').remove()
            $cmsPageFormGroup.show()

            $.each(typeInfo.cmsPages, function(code) {
                var $option = $('<option></option>').attr('value', code)

                $option.text(this).val(code)
                $cmsPageSelector.append($option)
            })

            $cmsPageSelector.val(prevSelectedPage ? prevSelectedPage : this.properties.cmsPage)
        }
        else {
            $cmsPageFormGroup.hide()
        }

        $nestingFormGroup.toggle(typeInfo.nesting !== undefined && typeInfo.nesting)
        $urlFormGroup.toggle(type == 'url')

        $(document).trigger('render')

        if (focusList) {
            var focusElements = [
                $referenceFormGroup,
                $cmsPageFormGroup,
                $('div.custom-checkbox', $nestingFormGroup),
                $('input', $urlFormGroup)
            ]

            $.each(focusElements, function(){
                if (this.is(':visible')) {
                    var $self = this

                    window.setTimeout(function() {
                        if ($self.hasClass('dropdown-field'))
                            $('select', $self).select2('focus', 100)
                        else $self.focus()
                    })

                    return false;
                }
            })
        }
    }

    SitemapItemsEditor.prototype.applySitemapItem = function() {
        var self = this,
            data = {},
            propertyNames = this.$el.data('item-properties'),
            basicProperties = {
                'priority': 1,
                'changefreq': 1,
                'type': 1
            },
            typeInfoPropertyMap = {
                reference: 'references',
                cmsPage: 'cmsPages'
            },
            typeInfo = {},
            validationErrorFound = false

        $.each(propertyNames, function() {
            var propertyName = this,
                $input = $('[name="'+propertyName+'"]', self.$popupContainer).not('[type=hidden]')

            if ($input.prop('type') !== 'checkbox') {
                data[propertyName] = $.trim($input.val())

                if (propertyName == 'type')
                    typeInfo = self.typeInfo[data.type]

                if (data[propertyName].length == 0) {
                    var typeInfoProperty = typeInfoPropertyMap[propertyName] !== undefined ? typeInfoPropertyMap[propertyName] : propertyName

                    if (typeInfo[typeInfoProperty] !== undefined) {

                        $.oc.flashMsg({
                            class: 'error',
                            text: self.$popupForm.attr('data-message-'+propertyName+'-required')
                        })

                        if ($input.prop("tagName") == 'SELECT')
                            $input.select2('focus')
                        else
                            $input.focus()

                        validationErrorFound = true

                        return false
                    }
                }
            } else {
                data[propertyName] = $input.prop('checked') ? 1 : 0
            }
        })

        if (validationErrorFound)
            return

        if (data.type !== 'url') {
            delete data['url']

            $.each(data, function(property) {
                if (property == 'type')
                    return

                var typeInfoProperty = typeInfoPropertyMap[property] !== undefined ? typeInfoPropertyMap[property] : property
                if ((typeInfo[typeInfoProperty] === undefined || typeInfo[typeInfoProperty] === false) 
                    && basicProperties[property] === undefined)
                    delete data[property]
            })
        }  else {
            $.each(propertyNames, function(){
                if (this != 'url' && basicProperties[this] === undefined)
                    delete data[this]
            })
        }

        if (data.type == 'url' && $.trim(data.url).length == 0) {
            $.oc.flashMsg({
                class: 'error',
                text: self.$popupForm.data('messageUrlRequired')
            })

            $('[name=url]', self.$popupContainer).focus()

            return
        }

        var referenceDescription = $.trim($('select[name=type] option:selected', self.$popupContainer).text())

        if (data.type == 'url') {
            referenceDescription += ': ' + $('input[name=url]', self.$popupContainer).val()
        }
        else if (typeInfo.references) {
            referenceDescription += ': ' + $.trim($('select[name=reference] option:selected', self.$popupContainer).data('path'))
        }

        $('> div span.title', self.$itemDataContainer).text(referenceDescription)

        var changeFreqValue = $.trim($('select[name=changefreq] option:selected', self.$popupContainer).text()),
            changePriority = $.trim($('select[name=priority] option:selected', self.$popupContainer).text())

        $('> div span.priority', self.$itemDataContainer).text(changePriority)
        $('> div span.changefreq', self.$itemDataContainer).text(changeFreqValue)

        this.$itemDataContainer.data('sitemap-item', data)
        this.itemSaved = true
        this.$popupContainer.trigger('close.oc.popup')
        this.$el.trigger('change')
    }

    SitemapItemsEditor.prototype.onCreateItem = function(target) {
        var parentList = $(target).closest('li[data-sitemap-item]').find(' > ol'),
            item = $($('script[data-item-template]', this.$el).html())

        if (!parentList.length)
            parentList = $(target).closest('div[data-control=treeview]').find(' > ol')

        parentList.append(item)
        this.$treevView.treeView('update')
        $(window).trigger('oc.updateUi')

        this.onItemClick(item, true)
    }

    SitemapItemsEditor.DEFAULTS = {
    }

    // MENUITEMSEDITOR PLUGIN DEFINITION
    // ============================

    var old = $.fn.sitemapItemsEditor

    $.fn.sitemapItemsEditor = function (option) {
        var args = Array.prototype.slice.call(arguments, 1)
        return this.each(function () {
            var $this   = $(this)
            var data    = $this.data('oc.sitemapitemseditor')
            var options = $.extend({}, SitemapItemsEditor.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.sitemapitemseditor', (data = new SitemapItemsEditor(this, options)))
            else if (typeof option == 'string') data[option].apply(data, args)
        })
    }

    $.fn.sitemapItemsEditor.Constructor = SitemapItemsEditor

    // MENUITEMSEDITOR NO CONFLICT
    // =================

    $.fn.sitemapItemsEditor.noConflict = function () {
        $.fn.sitemapItemsEditor = old
        return this
    }

    // MENUITEMSEDITOR DATA-API
    // ===============

    $(document).on('render', function() {
        $('[data-control="sitemap-item-editor"]').sitemapItemsEditor()
    });
}(window.jQuery);