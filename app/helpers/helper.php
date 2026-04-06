<?php


use App\Consts\ResponseMessageConsts;
use Carbon\Carbon;

define('PAGINATION_COUNT', 10);

define('GRACE_PERIOD_DAYS', 3);


function successResponse($data = null, $message = null, $attribute = 'data', $additional = [])
{
    $response = [
        'success' => true,
        'message' => $message ? $message : ResponseMessageConsts::SUCCESS_MESSAGE(),
        'errors' => null,
    ];
    if ($data instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
        // Get the underlying collection (could be paginator or simple collection)
        $collection = $data->resource;

        if ($collection instanceof \Illuminate\Pagination\AbstractPaginator) {
            $paginated = $collection->toArray();

            $response[$attribute] = $data;
            $response['links'] = extractPaginationLinks($paginated);
            $response['meta'] = extractPaginationMeta($paginated);
        } else {
            $response[$attribute] = $data;
        }
    } else {
        $response[$attribute] = $data;
    }

    // Merge additional data into response
    if (!empty($additional)) {
        $response = array_merge($response, $additional);
    }

    return response()->json($response, 200);
}
function extractPaginationLinks(array $paginated): array
{
    return [
        'first' => $paginated['first_page_url'] ?? null,
        'last' => $paginated['last_page_url'] ?? null,
        'prev' => $paginated['prev_page_url'] ?? null,
        'next' => $paginated['next_page_url'] ?? null,
    ];
}

function extractPaginationMeta(array $paginated): array
{
    return [
        'current_page' => $paginated['current_page'] ?? null,
        'from' => $paginated['from'] ?? null,
        'last_page' => $paginated['last_page'] ?? null,
        'path' => $paginated['path'] ?? null,
        'per_page' => $paginated['per_page'] ?? null,
        'to' => $paginated['to'] ?? null,
        'total' => $paginated['total'] ?? null,
    ];
}

function errorResponse($msg = "حدث خطأ أثناء تنفيذ العملية", $status = 500)
{
    return failureResponse($msg, $status);
}


function failureResponse($errors = null, $status = 400)
{
    $flattenedErrors = $errors ? collect($errors)->flatten()->toArray() : [ResponseMessageConsts::FAILURE_MESSAGE()];

    return response()->json([
        'success' => false,
        'message' => $flattenedErrors[0] ?? null,
        'errors' => $flattenedErrors,
        'data' => null,
    ], $status);
}

function notFoundResponse($errors = null)
{
    $flattenedErrors = $errors ? collect($errors)->flatten()->toArray() : [ResponseMessageConsts::NOT_FOUND_MESSAGE()];

    return response()->json([
        'success' => false,
        'message' => $flattenedErrors[0] ?? null,
        'errors'  => $flattenedErrors,
        'data'    => null,
    ], 404);
}

function errValidationResponse($errors = null)
{
    $flattenedErrors = $errors ? collect($errors)->flatten()->toArray() : [ResponseMessageConsts::FAILURE_MESSAGE()];

    return response()->json([
        'success' => false,
        'message' => $flattenedErrors[0] ?? null,
        'errors'  => $flattenedErrors,
        'data'    => null,
    ], 400);
}

function expiredResponse($errors = null)
{
    $flattenedErrors = $errors ? collect($errors)->flatten()->toArray() : [ResponseMessageConsts::EXPIRED()];

    return response()->json([
        'success' => false,
        'message' => $flattenedErrors[0] ?? null,
        'errors'  => $flattenedErrors,
        'data'    => null,
    ], 400);
}

