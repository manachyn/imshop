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

Relevance
http://blog.qbox.io/optimizing-search-results-in-elasticsearch-with-scoring-and-boosting
https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-scroll.html#scroll-scan


Elasticsearch
http://www.sitepoint.com/introduction-to-elasticsearch-in-php/
http://elastica.io/getting-started/search-documents.html

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
$this->query = array (
                'bool' => array (
                    'must' => array (
                        0 => array (
                            'bool' => array (
                                'should' => array (
                                    0 => array ( 'term' => array ( 'status' => '1', ), ),
                                    1 => array ( 'match' => array ( 'title' => 'Test', ), ),
                                ),
                            ),
                        ),
                        1 => array ( 'term' => array ( 'category' => 3, ), ),
                    ),
                ),
            );

            $this->aggregations  =array (
                'all_filtered' => array (
                    'global' => new \stdClass(),
                    'aggs' => array (
                        '0000000055c7196f00007f3af0c9ca7b_filtered' => array (
                            'filter' => array (
                                'bool' => array (
                                    'must' => array (
                                        0 => array (
                                            'bool' => array (
                                                'should' => array (
                                                    0 => array ( 'term' => array ( 'status' => '1', ), ),
                                                    1 => array ( 'term' => array ( 'title' => 'Test', ), ),
                                                ),
                                            ),
                                        ),
                                        1 => array (
                                            'term' => array ( 'categories' => 3, ),
                                        ),
                                    ),
                                ),
                            ),
                            'aggs' => array (
                                'category' => array ( 'terms' => array ( 'field' => 'category', 'min_doc_count' => 0, ), ),
                            ),
                        ),
                        '0000000055c719a200007f3af0c9ca7b_filtered' => array (
                            'filter' => array (
                                'bool' => array (
                                    'must' => array (
                                        0 => array (
                                            'bool' => array (
                                                'should' => array (
                                                    0 => array ( 'term' => array ( 'status' => '1', ), ),
                                                    1 => array ( 'query' => array ('match' => array ( 'title' => 'Test'))),
                                                ),
                                            ),
                                        ),
                                        1 => array ( 'term' => array ( 'category' => 3, ), ),
                                    ),
                                ),
                            ),
                            'aggs' => array (
                                'hdd_facet_term' => array (
                                    'terms' => array (
                                        'field' => 'hdd',
                                        'min_doc_count' => 0,
                                        'include' => array ( 0 => '320', 1 => '500', 2 => '750', 3 => '1000', ),
                                    ),
                                ),
                                'hdd_facet_range' => array (
                                    'range' => array (
                                        'field' => 'hdd',
                                        'ranges' => array ( 0 => array ( 'from' => '1000', 'key' => '1000-*', ), ),
                                    ),
                                ),
                                'status' => array ( 'terms' => array ( 'field' => 'status', 'min_doc_count' => 0, ), ),
                            ),
                        ),
                    ),
                ),
            );