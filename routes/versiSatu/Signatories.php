<?php

return [

    /**
     * @SWG\Get(
     *     path="/v1/signatories",
     *     summary="Get list signatories",
     *     tags={"Signatories"},
     *     @SWG\Response(
     *         response=200,
     *         description="Signatories",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Signatories")
     *         )
     *     ),
     *     @SWG\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'GET signatories' => 'signatories/index',

    /**
     * @SWG\Post(
     *     path="/v1/signatories",
     *     summary="Create data signatories",
     *     tags={"Signatories"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Signatories",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateSignatories"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Data signatories",
     *         @SWG\Schema(ref="#/definitions/Signatories")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'POST signatories' => 'signatories/create',

    /**
     * @SWG\Put(
     *     path="/v1/signatories/{id}",
     *     summary="Update data signatories",
     *     tags={"Signatories"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Signatories",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UpdateSignatories"),
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Data signatories",
     *         @SWG\Schema(ref="#/definitions/Signatories")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'PUT signatories/{id}' => 'signatories/update',


    /**
     * @SWG\Get(
     *     path="/v1/signatories/{id}",
     *     summary="Get data signatories",
     *     tags={"Signatories"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Data signatories",
     *         @SWG\Schema(ref="#/definitions/Signatories")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
    'GET signatories/{id}' => 'signatories/view',

];