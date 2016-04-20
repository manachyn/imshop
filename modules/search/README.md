http://www.slideshare.net/evolvingweb/creating-a-userfriendly-search-ui-with-drupal-presentation-at-drupalcamp-toronto-2014
http://xandeadx.ru/blog/drupal/238
http://xandeadx.ru/blog/drupal/768
http://habrahabr.ru/post/229905/
http://habrahabr.ru/post/175527/
https://www.drupal.org/project/search_api

Magento
http://www.manadev.com/user-guides/general/what-is-layered-navigation-in-magento
http://www.manadev.com/seo-layered-navigation-plus

PrestaShop
http://www.templatemonster.com/help/ru/prestashop-1-6-x-how-to-manage-the-layered-navigation-module.html

https://www.youtube.com/watch?v=XJ_THN5bQQ0


Faceting
https://cwiki.apache.org/confluence/display/solr/Overview+of+Searching+in+Solr
http://www.slideshare.net/lucenerevolution/seeley-solr-facetseurocon2011
Ranges
https://www.drupal.org/project/facetapi_ranges
http://www.webwash.net/tutorials/using-search-api-ranges-module-drupal-7

http://localhost:9200/_search
http://localhost:9200/products_index/_mapping/product
http://localhost:9200/products_index/product/_search
http://localhost:9200/products_index/product/_search?q=brand:Apple
http://localhost:9200/products_index/_analyze?field=product.brand&text=Apple
http://localhost:9200/_analyze?analyzer=standard&text=this is a test
curl -XGET 'localhost:9200/products_index/_analyze?pretty=true&field=product.brand' -d 'Apple'
Elasticsearch and case sensitive term queries
https://medium.com/@lefloh/elasticsearch-and-case-sensitive-term-queries-6f6c516aebed#.bd2al6rxz
http://techblog.realestate.com.au/implementing-autosuggest-in-elasticsearch/


Relevance
http://blog.qbox.io/optimizing-search-results-in-elasticsearch-with-scoring-and-boosting
https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-scroll.html#scroll-scan


Elasticsearch
http://www.sitepoint.com/introduction-to-elasticsearch-in-php/
http://elastica.io/getting-started/search-documents.html
Suggestions
https://www.elastic.co/blog/you-complete-me


Zend search
http://framework.zend.com/manual/2.2/en/modules/zendsearch.lucene.searching.html
http://framework.zend.com/manual/current/en/modules/zendsearch.lucene.searching.html

Parser
http://codehackit.blogspot.com/2011/08/expression-parser-in-php.html
https://github.com/symfony/expression-language
https://github.com/angeloskath/php-nlp-tools/blob/master/src/NlpTools/Tokenizers/RegexTokenizer.php
https://nikic.github.io/2011/10/23/Improving-lexing-performance-in-PHP.html
http://php-nlp-tools.com/documentation/tokenizers.html

Query language
https://lucidworks.com/blog/why-not-and-or-and-not/

FSM
http://pear.github.io/FSM/


//text:"test1 test2"~10^100 OR test3
//$searchQuery = \ZendSearch\Lucene\Search\QueryParser::parse($queryParam);
//http://imshop.loc/laptops/status=1%20or%20title=Test

http://imshop.loc/laptops/text=Apples%20phone

http://kempe.net/blog/2015/02/25/elasticsearch-query-full-text-search.html

http://imshop.loc/laptops/status=1%20or%20title=Test