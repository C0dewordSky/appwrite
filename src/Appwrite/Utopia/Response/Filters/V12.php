<?php

namespace Appwrite\Utopia\Response\Filters;

use Appwrite\Utopia\Response;
use Appwrite\Utopia\Response\Filter;
use Exception;

class V12 extends Filter
{
    // Convert 0.13 Data format to 0.12 format
    public function parse(array $content, string $model): array
    {
        $parsedResponse = $content;

        switch ($model) {
            case Response::MODEL_ERROR_DEV:
            case Response::MODEL_ERROR:
                $parsedResponse = $this->parseError($content);
                break;

            case Response::MODEL_SESSION:
                $parsedResponse = $this->parseSession($content);
                break;
            case Response::MODEL_SESSION_LIST:
                $parsedResponse = $this->parseSessionList($content);
                break;

            case Response::MODEL_FILE:
                $parsedResponse = $this->parseFile($content);
                break;
            case Response::MODEL_FILE_LIST:
                $parsedResponse = $this->parseFileList($content);
                break;

            case Response::MODEL_FUNCTION:
                $parsedResponse = $this->parseFunction($content);
                break;
            case Response::MODEL_FUNCTION_LIST:
                $parsedResponse = $this->parseFunctionList($content);
                break;

            case Response::MODEL_DEPLOYMENT:
                $parsedResponse = $this->parseDeployment($content);
                break;
            case Response::MODEL_DEPLOYMENT_LIST:
                $parsedResponse = $this->parseDeploymentList($content);
                break;

            case Response::MODEL_EXECUTION:
                $parsedResponse = $this->parseExecution($content);
                break;
            case Response::MODEL_EXECUTION_LIST:
                $parsedResponse = $this->parseExecutionList($content);
                break;

            case Response::MODEL_USAGE_BUCKETS:
                $parsedResponse = $this->parseUsageBuckets($content);
                break;

            case Response::MODEL_USAGE_STORAGE:
                $parsedResponse = $this->parseUsageStorage($content);
                break;
        }

        return $parsedResponse;
    }

    protected function parseError(array $content)
    {
        unset($content['type']);
        return $content;
    }

    protected function parseSession(array $content)
    {
        $content['providerToken'] = $content['providerAccessToken'];
        unset($content['providerAccessToken']);

        unset($content['providerAccessTokenExpiry']);

        unset($content['providerRefreshToken']);

        return $content;
    }

    protected function parseSessionList(array $content)
    {
        $sessions = $content['sessions'];
        $parsedResponse = [];
        foreach ($sessions as $document) {
            $parsedResponse[] = $this->parseSession($document);
        }
        $content['sessions'] = $parsedResponse;
        return $content;
    }

    protected function parseFile(array $content)
    {
        unset($content['bucketId']);
        unset($content['chunksTotal']);
        unset($content['chunksUploaded']);

        return $content;
    }

    protected function parseFileList(array $content)
    {
        $files = $content['files'];
        $parsedResponse = [];
        foreach ($files as $document) {
            $parsedResponse[] = $this->parseFile($document);
        }
        $content['files'] = $parsedResponse;
        return $content;
    }

    protected function parseFunction(array $content)
    {
        $content['tag'] = $content['deployment'];
        unset($content['deployment']);
        return $content;
    }

    protected function parseFunctionList(array $content)
    {
        $functions = $content['functions'];
        $parsedResponse = [];
        foreach ($functions as $document) {
            $parsedResponse[] = $this->parseFunction($document);
        }
        $content['functions'] = $parsedResponse;
        return $content;
    }

    protected function parseDeployment(array $content)
    {
        $content['functionId'] = $content['resourceId'];
        $content['command'] = $content['entrypoint'];
        return $content;
    }

    protected function parseDeploymentList(array $content)
    {
        $deployments = $content['deployments'];
        $parsedResponse = [];
        foreach ($deployments as $document) {
            $parsedResponse[] = $this->parseDeployment($document);
        }
        $content['deployments'] = $parsedResponse;
        return $content;
    }

    protected function parseUsageBuckets(array $content)
    {
        unset($content['filesStorage']);
    }

    protected function parseUsageStorage(array $content)
    {
        $content['storage'] = $content['filesStorage'];
        unset($content['filesStorage']);

        $content['files'] = $content['tagsStorage'];
        unset($content['tagsStorage']);

        unset($content['filesCount']);
        unset($content['bucketsCount']);
        unset($content['bucketsCreate']);
        unset($content['bucketsRead']);
        unset($content['bucketsUpdate']);
        unset($content['bucketsDelete']);
        unset($content['filesCount']);
        unset($content['bucketsDelete']);
        unset($content['filesCreate']);
        unset($content['filesRead']);
        unset($content['filesUpdate']);
        unset($content['filesDelete']);
    }

    protected function parseExecution($content) {
        $content['exitCode'] = $content['statusCode'];
        unset($content['statusCode']);

        return $content;
    }

    protected function parseExecutionList($content) {
        $executions = $content['executions'];
        $parsedResponse = [];
        foreach ($executions as $document) {
            $parsedResponse[] = $this->parseExecution($document);
        }
        $content['executions'] = $parsedResponse;
        return $content;
    }
}