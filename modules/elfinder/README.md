## ISSUES
Embedding without iframe can cause conflict with bootstrap.js. https://github.com/Studio-42/elFinder/issues/740
Add line to the layout to fix it
```php

im\elfinder\ElFinderAsset::noConflict($this);

```