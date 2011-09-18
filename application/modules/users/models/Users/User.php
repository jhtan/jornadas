<?php

class Users_User extends Zend_Db_Table_Row_Abstract
{
    public function getFullname() {
        $fullname = '';
        if (!empty($this->title)) {
            $fullname .= $this->title . ' ';
        }
        if (!empty($this->surname)) {
            $fullname .= $this->surname . ', ';
        }
        if (!empty($this->name)) {
            $fullname .= $this->name . ' ';
        }
        return trim($fullname);
    }
}
