<?php

return [

    /**
     * @SWG\Get(
     *     path="/v1/banks",
     *     summary="Get list banks",
     *     tags={"Banks"},
     *     @SWG\Response(
     *         response=200,
     *         description="Banks",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Banks")
     *         )
     *     ),
     *     @SWG\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'GET banks' => 'banks/index',

    /**
     * @SWG\Post(
     *     path="/v1/banks",
     *     summary="Create data banks",
     *     tags={"Banks"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Banks",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateBanks"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Data banks",
     *         @SWG\Schema(ref="#/definitions/Banks")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'POST banks' => 'banks/create',

    /**
     * @SWG\Put(
     *     path="/v1/banks/{id}",
     *     summary="Update data banks",
     *     tags={"Banks"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Banks",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UpdateBanks"),
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Data banks",
     *         @SWG\Schema(ref="#/definitions/Banks")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'PUT banks/{id}' => 'banks/update',


    /**
     * @SWG\Get(
     *     path="/v1/banks/{id}",
     *     summary="Get data banks",
     *     tags={"Banks"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Data banks",
     *         @SWG\Schema(ref="#/definitions/Banks")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
    'GET banks/{id}' => 'banks/view',

   
];