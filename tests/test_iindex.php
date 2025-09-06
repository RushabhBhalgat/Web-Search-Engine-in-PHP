<?php
require 'classes\SimpleIndex.class.php';

$index = new SimpleIndex(); // uses default INDEXLOCATION

$index->clearIndex();

$docs = [
    [1, 10, 500],
    [2, 20, 600],
];

$index->storeDocuments('apple', $docs);
print_r($index->getDocuments('apple'));

$index->storeDocuments('banana', [[3,5,100],[4,8,200]]);
print_r($index->getDocuments('banana'));
