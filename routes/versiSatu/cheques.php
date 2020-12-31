<?php

return [

    'GET cheques' => 'cheques/index',
    
    'POST cheques' => 'cheques/create',

    'PUT cheques/{id}' => 'cheques/update',

    'GET cheques/{id}' => 'cheques/view',

    'PUT cheques/verify/{id}' => 'cheques/verify',

     'GET cheques/pdf/{id}' => 'cheques/pdf',

      'GET cheques/print/{id}' => 'cheques/print',

      'GET cheques/record/{id}' => 'cheques/record',

      'PUT cheques/number/{id}' => 'cheques/number',
    
];