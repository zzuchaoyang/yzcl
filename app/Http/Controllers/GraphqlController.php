<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use Folklore\GraphQL\GraphQLController as BaseController;
use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;

class GraphqlController extends BaseController
{
    public function query(Request $request, $graphql_schema = null)
    {
        $isBatch = !$request->has('query');
        $inputs  = $request->all();

        if (is_null($graphql_schema)) {
            $graphql_schema = config('graphql.schema');
        }

        if (!$isBatch) {
            $data = $this->executeQuery($graphql_schema, $inputs);
        } else {
            $data = [];
            foreach ($inputs as $input) {
                $data[] = $this->executeQuery($graphql_schema, $input);
            }
        }

        $headers = config('graphql.headers', []);
        $options = config('graphql.json_encoding_options', 0);

        $errors     = !$isBatch ? array_get($data, 'errors', []) : [];
        $authorized = array_reduce($errors, function ($authorized, $error) {
            return !$authorized
            || in_array(array_get($error, 'message'), ['Unauthenticated', 'Unauthorized']) ? false : true;
        }, true);

        if (!$authorized) {
            $data['code'] = 50014;
        } elseif (!empty($errors)) {
            // 20400 为可继续执行的错误代码，不致命
            $data['code'] = str_contains($errors[0]['message'], '30200') ? 30200 : 30000;
        } else {
            $data['code'] = 20000;
        }

        return response()->json($data, 200, $headers, $options);
    }
}
