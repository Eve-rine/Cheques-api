<?php

return [

    /**
     * @SWG\Get(
     *     path="/v1/regions",
     *     summary="Get list regions",
     *     tags={"Regions"},
     *     @SWG\Response(
     *         response=200,
     *         description="Regions",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Regions")
     *         )
     *     ),
     *     @SWG\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'GET regions' => 'regions/index',

    /**
     * @SWG\Post(
     *     path="/v1/regions",
     *     summary="Create data regions",
     *     tags={"Regions"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Regions",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateRegions"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Data regions",
     *         @SWG\Schema(ref="#/definitions/Regions")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'POST regions' => 'regions/create',

    /**
     * @SWG\Put(
     *     path="/v1/regions/{id}",
     *     summary="Update data regions",
     *     tags={"Regions"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Regions",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UpdateRegions"),
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Data regions",
     *         @SWG\Schema(ref="#/definitions/Regions")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'PUT regions/{id}' => 'regions/update',


    /**
     * @SWG\Get(
     *     path="/v1/regions/{id}",
     *     summary="Get data regions",
     *     tags={"Regions"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Data regions",
     *         @SWG\Schema(ref="#/definitions/Regions")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
    'GET regions/{id}' => 'regions/view',

    /**
     * @SWG\Delete(
     *     path="/v1/regions/{id}",
     *     summary="Delete data regions",
     *     tags={"Regions"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Status Delete",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
    'DELETE regions/{id}' => 'regions/delete',
];