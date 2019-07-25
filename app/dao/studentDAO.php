<?php

namespace app\dao;

class studentDAO extends baseDAO
{
    protected $table = 'student';
    protected $_pk = 'uid';
    protected $_pkCache = true;
}