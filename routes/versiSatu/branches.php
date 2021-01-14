<?php

return [

    /**
     * @SWG\Get(
     *     path="/v1/branches",
     *     summary="Get list branches",
     *     tags={"Branches"},
     *     @SWG\Response(
     *         response=200,
     *         description="Branches",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Branches")
     *         )
     *     ),
     *     @SWG\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'GET branches' => 'branches/index',

    /**
     * @SWG\Post(
     *     path="/v1/branches",
     *     summary="Create data branches",
     *     tags={"Branches"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Branches",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateBranches"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Data Branches",
     *         @SWG\Schema(ref="#/definitions/Branches")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'POST branches' => 'branches/create',

    /**
     * @SWG\Put(
     *     path="/v1/branches/{id}",
     *     summary="Update data branches",
     *     tags={"Branches"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Branches",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UpdateBranches"),
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Data branches",
     *         @SWG\Schema(ref="#/definitions/Branches")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'PUT branches/{id}' => 'branches/update',


    /**
     * @SWG\Get(
     *     path="/v1/branches/{id}",
     *     summary="Get data branches",
     *     tags={"Branches"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Data branches",
     *         @SWG\Schema(ref="#/definitions/Branches")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
    'GET branches/{id}' => 'branches/view',

      'GET branches/search' => 'branches/search',
       // 'GET branches/search/{q}/{id}' => 'branches/search',
       'GET branches/select/{id}' => 'branches/select',

];