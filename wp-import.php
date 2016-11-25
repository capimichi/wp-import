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
    ->setTitleField($titleField)
    ->setUpdateField($updateField);
$downloadFields = $cli->getDownloadFields();
if($downloadFields) {
    foreach ($downloadFields as $field) {
        $builder->addDownloadField($field);
    }
}
$termsField = $cli->getTaxonomysFields();
if($termsField) {
    foreach ($termsField as $key => $field) {
        $builder->addTaxonomyField($key, $field);
    }
}
$ignoreFields = $cli->getIgnoreFields();
if($ignoreFields) {
    foreach ($ignoreFields as $field) {
        $builder->addIgnoreField($field);
    }
}
$importer = $builder->build();
$importer->import();