
(function ($) {
  Drupal.color = {
    logoChanged: false,
    callback: function(context, settings, form, farb, height, width) {
      // Change the logo to be the real one.
      if (!this.logoChanged) {
        $('#preview #preview-logo img').attr('src', Drupal.settings.color.logo);
        this.logoChanged = true;
      }
      // Remove the logo if the setting is toggled off. 
      if (Drupal.settings.color.logo == null) {
        $('div').remove('#preview-logo');
      }

      // Solid background.
      $('#preview', form).css('backgroundColor', $('#palette input[name="palette[base]"]', form).val());

      // Text preview.
      $('#preview .preview-content', form).css('color', $('#palette input[name="palette[text]"]', form).val());
      $('#preview #preview-content a', form).css('color', $('#palette input[name="palette[link]"]', form).val());

      // menu link.
      $('#preview #preview-main-menu #preview-main-menu-links a', form).css('color', $('#palette input[name="palette[link]"]', form).val());
      $('#preview #preview-main-menu #preview-main-menu-links a.active', form).css('color', $('#palette input[name="palette[linkhover]"]', form).val());
      
      // banner background.
      $('#preview #preview-banner-area', form).css('background-color', $('#palette input[name="palette[bannerbg]"]', form).val());

      // Footer columns background.
      $('#preview #preview-footer-columns-wrapper', form).css('background-color', $('#palette input[name="palette[footercolumnsbg]"]', form).val());
      $('#preview #preview-footer-columns-wrapper, #preview #preview-footer-wrapper', form).css('color', $('#palette input[name="palette[footercolumnscolor]"]', form).val());
      $('#preview #preview-footer-columns-wrapper a, #preview #preview-footer-wrapper a', form).css('color', $('#palette input[name="palette[footercolumnslink]"]', form).val());
      
      // Footer background.
      $('#preview #preview-footer-wrapper', form).css('background-color', $('#palette input[name="palette[footerbg]"]', form).val());


    }
  };
})(jQuery);
