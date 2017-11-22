<?php

namespace ylab\rest;

use yii\base\BaseObject;

/**
 * {@inheritdoc}
 *
 * Class for formatting response in format:
 * ```
 * [
 *      'success' => bool,
 *      'data' => array,
 *      'errors' => array,
 * ]
 * ```
 */
class ResponseFormatter extends BaseObject
{
    /**
     * @param bool $success request status
     * @param array $data response data if success == true
     * @param array $errors response errors if success == false
     * @return array formatted response
     */
    public function format($success, array $data = [], array $errors = [])
    {
        return [
            'success' => $success,
            'data' => $data,
            'errors' => $errors,
        ];
    }
}
