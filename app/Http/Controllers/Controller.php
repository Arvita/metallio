<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function getMessage($index = null)
    {
      $message = ['insert.success'  => 'Data has been saved successfully.',
                  'insert.failed'   => 'Data could not save.',
                  'update.success'  => 'Successfully replaced.',
                  'update.failed'   => 'Data failed to replace.',
                  'delete.success'  => 'Data deleted Successfully',
                  'delete.failed'   => 'Unable to delete data',
                  'delete.prevent'  => 'The data could not be deleted because of an association with another data.',
                  'data.notfound'   => 'It seems we can not find what you are looking for..',
                ];
      return ($message[$index])? $message[$index] : 'Message has not been defined.';
    }
}

