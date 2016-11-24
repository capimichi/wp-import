#!/usr/bin/php
<?php
use WpImporter\Cli\ImporterCli;
use WpImporter\ImporterBuilder;

require_once "src/autoload.php";

$cli = new ImporterCli();
$options = $cli->getOptions();
if (isset($options['help'])) {
    echo $cli->getUsage() . "\n";
    die();
}
extract($options);
$builder = new ImporterBuilder();
$builder->setJsonPath($jsonPath)
    ->setWploadPath($wpLoad)
    ->setPostType($postType)
    ->setVerbose($verbose)
    ->setTitleField($titleField);
$builder->setUpdateField($updateField);
if ($downloadFields !== false) {
    $downloadFields = explode(",", $downloadFields);
    foreach($downloadFields as $field){
        $builder->addDownloadField($field);
    }
}
$importer = $builder->build();
$importer->import();