<?php
namespace WpImporter;

/**
 * Class ImporterBuilder
 * @package WpImporter
 */
class ImporterBuilder{

    /**
     * @var Importer
     */
    protected $importer;

    /**
     * ImporterBuilder constructor.
     */
    public function __construct()
    {
        $this->importer = new Importer();
    }

    /**
     * @param string $path
     * @return ImporterBuilder $this
     */
    public function setWploadPath($path){
        $this->importer->setWploadPath($path);
        return $this;
    }

    /**
     * @param string $path
     * @return ImporterBuilder $this
     */
    public function setJsonPath($path){
        $this->importer->setJsonPath($path);
        return $this;
    }

    /**
     * @param string $field
     * @return ImporterBuilder $this
     */
    public function setTitleField($field){
        $this->importer->setTitleField($field);
        return $this;
    }

    /**
     * @param string $field
     * @return ImporterBuilder $this
     */
    public function setUpdateField($field){
        $this->importer->setUpdateField($field);
        return $this;
    }

    /**
     * @param $type
     * @return ImporterBuilder $this
     */
    public function setPostType($type){
        $this->importer->setPostType($type);
        return $this;
    }

    /**
     * @param bool $enabled
     * @return ImporterBuilder $this
     */
    public function setUpdateEnabled($enabled){
        $this->importer->setUpdateEnabled($enabled);
        return $this;
    }

    /**
     * @param string $status
     * @return ImporterBuilder $this
     */
    public function setPostStatus($status){
        $this->importer->setPostStatus($status);
        return $this;
    }

    /**
     * @param bool $verbose
     * @return ImporterBuilder $this
     */
    public function setVerbose($verbose){
        $this->importer->setVerbose($verbose);
        return $this;
    }

    /**
     * @param string $field
     * @return ImporterBuilder $this
     */
    public function addDownloadField($field){
        $fields = $this->importer->getDownloadFields();
        $fields[] = $field;
        $this->importer->setDownloadFields($fields);
        return $this;
    }

    public function validate(){

    }

    /**
     * @return Importer
     */
    public function build(){
        $this->validate();
        return $this->importer;
    }
}