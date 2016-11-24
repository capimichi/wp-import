#!/usr/bin/php
<?php
use WpImporter\ImporterBuilder;
require_once "src/autoload.php";

$longOptions = array(
    "type::",
    "wpLoad::",
    "updateEnabled::",
    "titleField::",
    "updateField::",
    "verbose::",
    "postStatus::",
    "download::",
    "jsonPath:",
    "help"
);
$defaultOptions = array(
    "type" => "post",
    "wpLoad" => "wp-load.php",
    "titleField" => "post_title",
    "updateEnabled" => false,
    "updateField" => "",
    "verbose" => false,
    "postStatus" => "publish",
    "download" => false,
    "jsonPath" => "items.json",
);
$options = array_merge($defaultOptions, getopt("", $longOptions));
if(isset($options['help'])){
    echo "Usage: wp-import";
    foreach($defaultOptions as $key => $value){
        echo " --{$key}=\"{$value}\"";
    }
    echo "\n";
    die();
}
foreach($options as $key => $value){
    if($value == "true"){
        $options[$key] = true;
    }
    if($value == "false"){
        $options[$key] = false;
    }
}
extract($options);
$builder = new ImporterBuilder();
$builder->setJsonPath($jsonPath)
        ->setWploadPath($wpLoad)
        ->setPostType($type)
        ->setVerbose($verbose)
        ->setTitleField($titleField);

if($updateEnabled){
    $builder->setUpdateEnabled(true)->setUpdateField($updateField);
} else {
    $builder->setUpdateEnabled(false);
}
if($download !== false){
    $download = explode(",", $download);
}
$importer = $builder->build();
$importer->import();