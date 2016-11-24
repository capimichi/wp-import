#!/usr/bin/php
<?php
use WpImporter\ImporterBuilder;

require_once "src/autoload.php";

$builder = new ImporterBuilder();
$jsonpath = $argv[1];
$importer = $builder->setJsonPath($jsonpath)
        ->setWploadPath("wp-load.php")
        ->setPostType("prova")
        ->setUpdateField("post_title")
        ->build();
$importer->import();