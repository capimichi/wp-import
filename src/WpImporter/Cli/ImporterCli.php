<?php
namespace WpImporter\Cli;

class ImporterCli{


    public function getLongOptions(){
        $longOptions = array(
            "postType::",
            "wpLoad::",
            "updateEnabled::",
            "titleField::",
            "updateField::",
            "featuredField::",
            "verbose::",
            "postStatus::",
            "downloadFields::",
            "jsonPath:",
            "help"
        );
        return $longOptions;
    }

    public function getDefaultOptions(){
        $defaultOptions = array(
            "postType" => "post",
            "wpLoad" => "wp-load.php",
            "titleField" => "post_title",
            "featuredField" => false,
            "updateField" => false,
            "verbose" => false,
            "postStatus" => "publish",
            "downloadFields" => false,
            "jsonPath" => "items.json",
        );
        return $defaultOptions;
    }

    public function getUsage(){
        $legend = "Usage: wp-import";
        foreach($this->getDefaultOptions() as $key => $value){
            $legend .= " {$key}=\"{$value}\"";
        }
        return $legend;
    }

    public function getOptions(){
        $options = array_merge($this->getDefaultOptions(), getopt("", $this->getLongOptions()));
        foreach($options as $key => $value){
            if($value == "true"){
                $options[$key] = true;
            }
            if($value == "false"){
                $options[$key] = false;
            }
        }
        return $options;
    }
}