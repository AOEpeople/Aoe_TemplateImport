# Aoe_TemplateImport

### Authors:
- Manish Jain
- [Fabrizio Branca](https://twitter.com/fbrnc)

Aoe_TemplateImport module allow to use custom html file and render the placeholders.

This module is useful when you have non-magento frontend and magento is being used only for few things like cart/checkout.
In this case, instead of modifying the header/footer in magento to match the frontend site, use html file which contains header and footer with placeholders.

### Template Example:
```
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>Cart Template</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <!-- ###head### --><!-- ###/head### -->
    </head>
    <body>
        <div id="header">
            <h1>Hello World</h1>
        </div>
        
        <!-- ###content### -->
        <div>Sample Content that will NOT be included in the rendered page, but replaced with the content of the 'content' block</div>
        <!-- ###/content### -->
        
        <div id="footer">
            <p>Customized Footer</p>
        </div>
    </body>
</html>
```

### Configuration
```
Admin > System > Configuration > GENERAL > Design > Aoe Template Import
```

#### Format:
```
[regex pattern for full action name];[template path];[base path];[cache lifetime in secs]
```

#### Example:
```
- checkout_cart_index;/var/www/project/cart.html;;60
- checkout_cart_.*;/var/www/project/cart.html;;60
- .*;/var/www/project/default.html;;60
```

#### Placeholders
Supported placeholders for template path and base path:
- ###BASE_URL###
- ###MAGENTO_ROOT###

## Changelog

* 0.2.2: Added info for empty config or content when in dev mode
* 0.2.1: Support single quotes
* 0.2.0: Added placeholder support
* 0.1.0: Initial release



