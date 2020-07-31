<?php

namespace app\controllers;

use yii\redis\Connection;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $redis = [
            'status' => true,
            'dsn' => '123',
            'info' => 111,
        ];

        return $this->render(
            'index',
            [
                'services' => [
                    'Redis' => $this->redisInfo(),
                    'Mongo' => $this->mongoInfo(),
                    'Mysql' => $this->mysqlInfo(),
                    'Postgres' => $this->postgresInfo(),
                ],
            ]
        );
    }

    protected function redisInfo(): array
    {
        /** @var Connection $redis */
        $redis = \Yii::$app->redis;

        try {
            $status = true;
            $keyName = uniqid();
            $value = mt_rand();

            $redis->set($keyName, $value);
            $keys = $redis->keys('*');

            $info = sprintf(
                'Was set value "%s" for key "%s".<br>Current keys count: %d',
                $value,
                $keyName,
                count($keys)
            );
        } catch (\Throwable $e) {
            $info = 'Exception: ' . $e->getMessage();
            $status = false;
        }

        return [
            'status' => $status,
            'dsn' => $redis->getConnectionString(),
            'info' => $info,
        ];
    }

    protected function mongoInfo(): array
    {
        /** @var \yii\mongodb\Connection $mongo */
        $mongo = \Yii::$app->mongodb;

        try {
            $status = true;

            $document = ['name' => uniqid()];
            $collection = $mongo->getCollection('collection');
            $collection->insert($document);

            $info = sprintf(
                'Was added document %s in sample collection.<br>Current collection size: %d',
                json_encode($document),
                $collection->count()
            );
        } catch (\Throwable $e) {
            $info = 'Exception: ' . $e->getMessage();
            $status = false;
        }

        return [
            'status' => $status,
            'dsn' => $mongo->dsn,
            'info' => $info,
        ];
    }

    protected function mysqlInfo(): array
    {
        /** @var \yii\db\Connection $mysql */
        $mysql = \Yii::$app->db;

        try {
            $status = true;

            $value = uniqid();

            $mysql->createCommand('INSERT INTO test (value) VALUES (:value)', ['value' => $value])->execute();

            $info = sprintf(
                'Was added row with value "%s" in sample table.<br>Current table rows count: %d',
                $value,
                $mysql->createCommand('SELECT COUNT(id) FROM test')->queryScalar()
            );
        } catch (\Throwable $e) {
            $info = 'Exception: ' . $e->getMessage();
            $status = false;
        }

        return [
            'status' => $status,
            'dsn' => $mysql->dsn,
            'info' => $info,
        ];
    }

    protected function postgresInfo(): array
    {
        /** @var \yii\db\Connection $postgres */
        $postgres = \Yii::$app->postgres;

        try {
            $status = true;

            $value = uniqid();

            $postgres->createCommand('INSERT INTO test (value) VALUES (:value)', ['value' => $value])->execute();

            $info = sprintf(
                'Was added row with value "%s" in sample table.<br>Current table rows count: %d',
                $value,
                $postgres->createCommand('SELECT COUNT(id) FROM test')->queryScalar()
            );
        } catch (\Throwable $e) {
            $info = 'Exception: ' . $e->getMessage();
            $status = false;
        }

        return [
            'status' => $status,
            'dsn' => $postgres->dsn,
            'info' => $info,
        ];
    }
}
