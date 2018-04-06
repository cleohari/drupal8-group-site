# Modules

## clientside_validation

This is the core module, all it does is add data- attributes to the HTML
form elements. If an HTML5 attribute exists it is used as is.

## clientside_validation_jquery

This modules adds the [jQuery Validation Plugin](http://jqueryvalidation.org/),
to install (download the latest version)[http://jqueryvalidation.org/files/jquery-validation-1.14.0.zip]
and extract the dist folder to the js/lib of this sub module.

# Extend

If you need support for other contrib modules, you can add a CvValidator plugin
to that module and it will be picked up by the base module.

If you require custom javascript, you can implement ```clientside_validation_jquery_clientside_validation_validator_info_alter```

