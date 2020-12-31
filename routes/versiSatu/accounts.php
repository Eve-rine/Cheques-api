<?php

return [

      /**
     * @SWG\Get(
     *     path="/v1/accounts",
     *     summary="Get list acounts",
     *     tags={"Accounts"},
     *     @SWG\Response(
     *         response=200,
     *         description="Accounts",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Accounts")
     *         )
     *     ),
     *     @SWG\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'GET accounts' => 'accounts/index',

    /**
     * @SWG\Post(
     *     path="/v1/accounts",
     *     summary="Create data accounts",
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data accounts",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateAccounts"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Data accounts",
     *         @SWG\Schema(ref="#/definitions/Accounts")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
      'POST accounts' => 'accounts/create',

    /**
     * @SWG\Put(
     *     path="/v1/accounts/{id}",
     *     summary="Update data accounts",
     *     tags={"Accounts","Signatories"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Data Accounts",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UpdateAccounts"),
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Data accounts",
     *         @SWG\Schema(ref="#/definitions/Accounts")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
 
    'PUT accounts/update/{id}' => 'accounts/update',


    /**
     * @SWG\Get(
     *     path="/v1/accounts/{id}",
     *     summary="Get data accounts",
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         ref="#/parameters/id"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Data accounts",
     *         @SWG\Schema(ref="#/definitions/Accounts")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
 
    'GET accounts/{id}' => 'accounts/view',

    /**
     * @SWG\Get(
     *     path="/v1/accounts/search",
     *     summary="Search accounts",
     *     tags={"Accounts"},
          *     @SWG\Response(
     *         response=200,
     *         description="Accounts",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Accounts")
     *         )
     *     ),

     *     @SWG\Response(
     *         response=422,
     *         description="Record not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     )
     * )
     */
     'GET accounts/search' => 'accounts/search',

     'POST accounts/upload/{id}' => 'accounts/upload',
   
];
