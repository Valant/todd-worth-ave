<?php

/**
 * Class YA_MetaObject
 * Construct object with vessel data, seek for missing fields in post meta
 */
class YA_MetaObject {

    private $__postID = null;
    private $__postMeta = null;

    /**
     * YA_MetaObject constructor.
     * @param $postID
     * @param array $data
     */
    public function __construct($postID, $data=null)
    {
        $this->__postID = $postID;
        if ($data) {
            if (is_array($data)) {
                foreach ($data as $k => $v) $this->$k = $v;
            }
        }
    }

    public function __get($key)
    {
        if (!property_exists($this, $key)) {
            $this->_checkMeta();
            if (array_key_exists($this->__postMeta[$key])) {
                if (is_array($this->__postMeta[$key]) && count($this->__postMeta[$key]) === 1) {
                    $this->$key = $this->__postMeta[$key][0];
                } else {
                    $this->$key = $this->__postMeta[$key];
                }
            } else {
                $this->$key = null;
            }
        }
        return $this->$key;
    }

    private function _checkMeta()
    {
        if ($this->__postMeta === null) {
            $this->__postMeta = get_post_meta($this->__postID);
        }
    }

    public function __isset($key)
    {
        $this->_checkMeta();
        return array_key_exists($this->__postMeta[$key]) || property_exists($this, $key);
    }

}