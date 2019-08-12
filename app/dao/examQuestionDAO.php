<?php

namespace app\dao;

class examQuestionDAO extends baseDAO
{
    protected $table = 'exam_question';
    protected $_pk = 'id';
    protected $_pkCache = true;
}