<?php
namespace WpImporter\Cli;

class ImporterCli{


    /**
     * @var array
     */
    protected $options;

    public function getLongOptions(){
        $longOptions = array(
            "postType::",
            "wpLoad::",
            "woocommerceType::",
            "updateEnabled::",
            "titleField::",
            "updateField::",
            "featuredField::",
            "verbose::",
            "postStatus::",
            "downloadFields::",
            "taxonomysFields::",
            "ignoreFields::",
            "jsonPath:",
            "help"
        );
        return $longOptions;
    }

    public function getDefaultOptions(){
        $defaultOptions = array(
            "postType" => "post",
            "wpLoad" => "wp-load.php",
            "woocommerceType" => false,
            "titleField" => "post_title",
            "featuredField" => false,
            "updateField" => false,
            "verbose" => false,
            "postStatus" => "publish",
            "downloadFields" => false,
            "ignoreFields" => false,
            "taxonomysFields" => false,
            "jsonPath" => "items.json",
        );
        return $defaultOptions;
    }

    public function getUsage(){
        $legend = "Usage: wp-import";
        $legendFields = array(
            "postType" => "post",
            "wpLoad" => "wp-load.php",
            "woocommerceType" => "external",
            "titleField" => "post_title",
            "featuredField" => "myFieldImage",
            "updateField" => "myUpdateField",
            "verbose" => "true/false",
            "postStatus" => "publish",
            "downloadFields" => "field1,field2,field3",
            "ignoreFields" => "field1,field2,field3",
            "taxonomysFields" => "taxonomy1:field1,taxonomy2:field2,taxonomy3:field3",
            "jsonPath" => "items.json",
        );
        foreach($legendFields as $key => $value){
            $legend .= " {$key}=\"{$value}\"";
        }
        return $legend;
    }

    /**
     * @return array
     */
    public function getOptions(){
        if(!isset($this->options)){
            $options = array_merge($this->getDefaultOptions(), getopt("", $this->getLongOptions()));
            foreach($options as $key => $value){
                if($value == "true"){
                    $options[$key] = true;
                }
                if($value == "false"){
                    $options[$key] = false;
                }
            }
            $this->setOptions($options);
        }
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOptionByName($name){
        $options = $this->getOptions();
        return $options[$name];
    }

    /**
     * @return array|bool
     */
    public function getDownloadFields(){
        $downloadFields = $this->getOptionByName("downloadFields");
        if ($downloadFields !== false) {
            $downloadFields = explode(",", $downloadFields);
        }
        return $downloadFields;
    }

    /**
     * @return array|bool
     */
    public function getTaxonomysFields(){
        $taxonomysFields = $this->getOptionByName("taxonomysFields");
        if ($taxonomysFields !== false) {
            $taxonomysFields = explode(",", $taxonomysFields);
            $tmpTaxonomysFields = array();
            foreach($taxonomysFields as $key => $taxonomyField){
                $taxonomyField = explode(":", $taxonomyField);
                $tmpTaxonomysFields[$taxonomyField[0]] = $taxonomyField[1];
            }
            $taxonomysFields = $tmpTaxonomysFields;
        }
        return $taxonomysFields;
    }

    /**
     * @return array|bool
     */
    public function getIgnoreFields(){
        $ignoreFields = $this->getOptionByName("ignoreFields");
        if ($ignoreFields !== false) {
            $ignoreFields = explode(",", $ignoreFields);
        }
        return $ignoreFields;
    }


}