<?php
/**
 * Tencent is pleased to support the open source community by making Biny available.
 * Copyright (C) 2017 THL A29 Limited, a Tencent company. All rights reserved.
 * Licensed under the BSD 3-Clause License (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at
 * https://opensource.org/licenses/BSD-3-Clause
 * Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
 * Created by PhpStorm.
 * User: billge
 * Date: 15-7-29
 * Time: 上午11:18
 */

namespace biny\lib;
use App;
use ReflectionClass;
use ReflectionException;

/**
 * Class Model
 * @package biny\lib
 * @property app\model\user $user
 * @property app\model\student $student
 * @property app\model\teacher $teacher
 * @property app\model\college $college
 * @property app\model\classes $classes
 * @property app\model\course $course
 * @property app\model\classesCourse $classesCourse
 * @property app\model\question $question
 * @property app\model\questionItem $questionItem
 * @property app\model\exam $exam
 * @property app\model\examAccess $examAccess
 * @property app\model\examQuestion $examQuestion
 * @property app\model\examClasses $examClasses
 * @property app\model\examResult $examResult
 *
 * @method app\model\user user($id)
 * @method app\model\student student($id)
 * @method app\model\teacher teacher($id)
 * @method app\model\college college($id)
 * @method app\model\classes classes($id)
 * @method app\model\course course($id)
 * @method app\model\classesCourse classesCourse($id)
 * @method app\model\question question($id)
 * @method app\model\questionItem questionItem($id)
 * @method app\model\exam exam($id)
 * @method app\model\examAccess examAccess($id)
 * @method app\model\examQuestion examQuestion($id)
 * @method app\model\examClasses examClasses($id)
 * @method app\model\examResult examResult($id)
 */
class Model
{
    /**
     * 获取单例模型
     * @param $name
     * @return mixed
     * @throws BinyException
     * @throws ReflectionException
     */
    public function __get($name)
    {
        return $this->create($name);
    }

    /**
     * 获取单例模型
     * @param $name
     * @param $params
     * @return mixed
     * @throws BinyException
     * @throws ReflectionException
     */
    public function __call($name, $params)
    {
        return $this->create($name, $params);
    }

    /**
     * 模型获取
     * @param $class
     * @param array $params
     * @return mixed
     * @throws BinyException
     * @throws ReflectionException
     */
    private function create($class, $params=[])
    {
        $autoConfig = App::$base->config->get('namespace', 'autoload');
        if (!isset($autoConfig[$class])){
            $config = Autoload::loading();
            $autoConfig = $config['namespace'];
        }
        $class = isset($autoConfig[$class]) ? $autoConfig[$class] : $class;
        if (is_callable([$class, 'init'])){
            return call_user_func_array([$class, 'init'], $params);
        } else {
            $class = new ReflectionClass($class);
            return $class->newInstanceArgs($params);
        }
    }

}