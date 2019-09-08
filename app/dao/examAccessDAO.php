<?php

namespace app\dao;

class examAccessDAO extends baseDAO
{
    protected $table = 'exam_access';
    protected $_pk = 'token';
    protected $_pkCache = false;
}