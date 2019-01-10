<?php

namespace ProcessMaker\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

/**
 *  @OA\Schema(
 *    schema="metadata",
 *    @OA\Property(property="filter", type="string"),
 *    @OA\Property(property="sort_by", type="string"),
 *    @OA\Property(property="sort_order", type="string", enum={"ASC", "DESC"}),
 *    @OA\Property(property="count", type="integer"),
 *    @OA\Property(property="total_pages", type="integer"),
 *    
 *    @OA\Property(property="current_page", type="integer"),
 *    @OA\Property(property="form", type="integer"),
 *    @OA\Property(property="last_page", type="integer"),
 *    @OA\Property(property="path", type="string"),
 *    @OA\Property(property="per_page", type="integer"),
 *    @OA\Property(property="to", type="integer"),
 *    @OA\Property(property="total", type="integer"),
 *    @OA\Property(property="in_overdue", type="integer")
 *  )
 */
class TaskCollection extends ResourceCollection
{
    /**
     * Generic collection to add sorting and filtering metadata.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $payload = [
            'data' => $this->collection,
            'meta' => [
                'filter' => $request->input('filter', ''),
                'sort_by' => $request->input('order_by', ''),
                'sort_order' => $request->input('order_direction', ''),
                /**
                 * count: (integer, total items in current response)
                 */
                'count' => $this->resource->count(),
                /**
                 * total_pages: (integer, the total number of pages available, based on per_page and total)
                 */
                'total_pages' => ceil($this->resource->total() / $this->resource->perPage()),
                'in_overdue' => $this->resource->inOverdue
            ]
        ];

        return $payload;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return $this->resource instanceof AbstractPaginator
                    ? (new ApiPaginatedResourceResponse($this))->toResponse($request)
                    : parent::toResponse($request);
    }
}
