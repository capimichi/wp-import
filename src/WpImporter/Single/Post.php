<?php
namespace WpImporter\Single;

use WpImporter\Single\Field\Field;

class Post{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var string
     */
    protected $titleField;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|bool
     */
    protected $woocommerceType;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var bool
     */
    protected $verbose;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->setFields(array());
        $this->setType("post");
        $this->setStatus("publish");
        $this->setVerbose(false);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getTitleField()
    {
        return $this->titleField;
    }

    /**
     * @param string $titleField
     */
    public function setTitleField($titleField)
    {
        $this->titleField = $titleField;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return bool|string
     */
    public function getWoocommerceType()
    {
        return $this->woocommerceType;
    }

    /**
     * @param bool|string $woocommerceType
     */
    public function setWoocommerceType($woocommerceType)
    {
        $this->woocommerceType = $woocommerceType;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getFieldValueByName($name){
        foreach($this->getFields() as $field){
            if($field->getKey() == $name){
                return $field->getValue();
            }
        }
        return false;
    }

    /**
     * Save to db
     */
    public function save(){
        if(!isset($this->id)){
            $id = wp_insert_post(
                array(
                    "post_type" => $this->getType(),
                    "post_title" => $this->getFieldValueByName($this->getTitleField()),
                    "post_name" => $this->getFieldValueByName($this->getTitleField()),
                    "post_status" => $this->getStatus()
                )
            );
            $this->setId($id);
        } else {
            wp_update_post(array(
                "ID" => $this->getId(),
                "post_status" => $this->getStatus()
            ));
        }
        $this->woocommerceProduct();

        foreach($this->getFields() as $field){
            $field->save();
        }
    }

    protected function woocommerceProduct(){
        if($this->getWoocommerceType()) {
            wp_set_object_terms($this->getId(), $this->getWoocommerceType(), 'product_type');
            if(empty(get_post_meta($this->getId(), "_visibility", true))){
                update_post_meta($this->getId(), '_visibility', 'visible');
            }
            if(empty(get_post_meta($this->getId(), "_stock_status", true))){
                update_post_meta($this->getId(), '_stock_status', 'instock');
            }
            if(empty(get_post_meta($this->getId(), "_edit_last", true))){
                update_post_meta($this->getId(), '_edit_last', '1');
            }
            if(empty(get_post_meta($this->getId(), "_edit_lock", true))){
                update_post_meta($this->getId(), '_edit_lock', '1480243594:1');
            }
            if(empty(get_post_meta($this->getId(), "total_sales", true))){
                update_post_meta($this->getId(), 'total_sales', 0);
            }
            if(empty(get_post_meta($this->getId(), "_downloadable", true))){
                update_post_meta($this->getId(), '_downloadable', "no");
            }
            if(empty(get_post_meta($this->getId(), "_virtual", true))){
                update_post_meta($this->getId(), '_virtual', "no");
            }
            if(empty(get_post_meta($this->getId(), "_featured", true))){
                update_post_meta($this->getId(), '_featured', "no");
            }
            if(empty(get_post_meta($this->getId(), "_manage_stock", true))){
                update_post_meta($this->getId(), '_manage_stock', "no");
            }
            if(empty(get_post_meta($this->getId(), "_backorders", true))){
                update_post_meta($this->getId(), '_backorders', "no");
            }
            if(empty(get_post_meta($this->getId(), "_purchase_note", true))){
                update_post_meta($this->getId(), '_purchase_note', "");
            }
            if(empty(get_post_meta($this->getId(), "_weight", true))){
                update_post_meta($this->getId(), '_weight', "");
            }
            if(empty(get_post_meta($this->getId(), "_length", true))){
                update_post_meta($this->getId(), '_length', "");
            }
            if(empty(get_post_meta($this->getId(), "_width", true))){
                update_post_meta($this->getId(), '_width', "");
            }
            if(empty(get_post_meta($this->getId(), "_height", true))){
                update_post_meta($this->getId(), '_height', "");
            }
            if(empty(get_post_meta($this->getId(), "_sku", true))){
                update_post_meta($this->getId(), '_sku', "");
            }
            if(empty(get_post_meta($this->getId(), "_sale_price_dates_from", true))){
                update_post_meta($this->getId(), '_sale_price_dates_from', "");
            }
            if(empty(get_post_meta($this->getId(), "_sale_price_dates_to", true))){
                update_post_meta($this->getId(), '_sale_price_dates_to', "");
            }
            if(empty(get_post_meta($this->getId(), "_sold_individually", true))){
                update_post_meta($this->getId(), '_sold_individually', "");
            }
            if(empty(get_post_meta($this->getId(), "_stock", true))){
                update_post_meta($this->getId(), '_stock', "");
            }
            if(empty(get_post_meta($this->getId(), "_product_image_gallery", true))){
                update_post_meta($this->getId(), '_product_image_gallery', "");
            }
            if(empty(get_post_meta($this->getId(), "_upsell_ids", true))){
                update_post_meta($this->getId(), '_upsell_ids', "a:0:{}");
            }
            if(empty(get_post_meta($this->getId(), "_crosssell_ids", true))){
                update_post_meta($this->getId(), '_crosssell_ids', "a:0:{}");
            }
            if(empty(get_post_meta($this->getId(), "_product_attributes", true))){
                update_post_meta($this->getId(), '_product_attributes', "a:0:{}");
            }
            if(empty(get_post_meta($this->getId(), "_product_version", true))){
                update_post_meta($this->getId(), '_product_version', "2.6.8");
            }
        }
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * @param boolean $verbose
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }
}