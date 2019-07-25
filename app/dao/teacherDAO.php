<?php

namespace app\dao;

class teacherDAO extends baseDAO
{
    protected $table = 'teacher';
    protected $_pk = 'uid';
    protected $_pkCache = true;
}