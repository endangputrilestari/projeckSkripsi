<?php

class AdminSettingModel extends CI_Model
{
    protected $table = 'settings';
    protected $key = 'setting_id';

    public function getSetting($column, $value)
    {
        $this->db->where($column, $value);
        $this->db->where('status', 1);
        $result = $this->db->get('settings');
        return ($result->num_rows() == 1) ? objToArr($result->row(0)) : $this->emptyObject('settings');
    }

    public function getSettingsByCategory($category)
    {
        $this->db->where('category', $category);
        $this->db->order_by('setting_id', 'ASC');
        $this->db->from($this->table);
        $query = $this->db->get();
        return objToArr($query->result());
    }

    public function updateSetting($data)
    {
        foreach ($data as $k => $d) {
            $d = removeUselessLineBreaks($d);
            $this->db->where('key', $k);
            $this->db->update('settings', array('value' => $d));
        }
    }

    public static function updateCssVariables($data)
    {
        $existing = array(
            'body-bg' => setting('body-bg'),
            'main-menu-bg' => setting('main-menu-bg'),
            'main-banner-bg' => setting('main-banner-bg'),
            'main-banner-height' => setting('main-banner-height'),
            'breadcrumb-image' => setting('breadcrumb-image'),
            'main-banner' => setting('main-banner'),
        );
        $updated2 = array();
        foreach ($existing as $key => $val) {
            if (issetVal($data, $key)) {
                $updated2[$key] = issetVal($data, $key);
            } else {
                $updated2[$key] = $val;
            }
        }

        $cssVars2 = ":root {\n";
        foreach ($updated2 as $key => $value) {
            $cssVars2 .= '--'.$key.':'.$value.";\n";
        }
        $cssVars2 .= "}";

        //Writing to file and update in db setting
        $variable_file_path = FCPATH.'/assets/front/'.viewPrfx(true).'/css/variables.css';
        writeToFile($variable_file_path, $cssVars2);
    }
}