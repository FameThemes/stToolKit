stToolKit - A drag and drop, responsive page builder that simplifies building your website.
=========


`~Current Version: 1.5~`

====System Requirements====

    memory_limit =  256M
    post_max_size = 32M
    max_input_vars = 3000
    suhosin.post.max_vars = 3000
    suhosin.request.max_vars = 3000

====Change log====

Version 1.8
- Remove Map Item.
- Bug fixes.


Version 1.5
- Fixed bug insert shortcodes in WP 3.9
    Changed file:
        assets/js/pagebuilder.js
- Add Visual editor for builder item text in WP 3.9
    Changed file:
        assets/js/input-items.js

Version 1.3:

- Fixed issue Typing very slowly in shortcode in Chrome
    Changed File: /assets/js/input-items.js
- New selection icon
    Changed File: /assets/js/input-items.js
    Changed File: /assets/css/pagebuilder.css

- Add visual editor for text area items
    Changed file: assets/css/pagebuilder.css
                  assets/js/input-items.js
                  config/builder-items-functions.php
                  inc/class-st-pagebuilder-admin.php
- Template post issue:
    Changed file: /stToolKit/inc/templates.php
- Fix issue link target for button
    Changed file: /stToolKit/inc/shortcodes.php
- Change builder item widget to Sidebar
    Changed file: /config/builder-items.php
- Fixed tab move handle
    Changed file: /inc/builder-input.php



Version 1.2.9
- Fixed issue can't click to save template button
    Changed file: /assets/css/pagebuilder.css

Version 1.2.8
- Fixed email issue for builder item: form
    Changed file: /inc/functions.php

Version 1.2.7
- Add: file: /frontend/js/toolkit.min.js (included 13 js files)

Version 1.2.6
- Fixed bug builder image custom link
    Changed file: inc/shortcodes.php