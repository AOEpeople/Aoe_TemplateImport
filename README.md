# Aoe_TemplateImport

### Authors:
- [Fabrizio Branca](https://twitter.com/fbrnc)
- Manish Jain

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

#### Placeholders
Supported placeholders for template path and base path:
- Any filter e.g. {{customvar TEMPLATEURL="http://www.example.com/"}}
- ###BASE_URL###
- ###MAGENTO_ROOT###

## Changelog

* 1.0.0: Major rewrite to store configuration and cache in new records
* 0.3.0: Added source cache
* 0.2.3: Added info for empty config or content when in dev mode
* 0.2.1: Support single quotes
* 0.2.0: Added placeholder support
* 0.1.0: Initial release



