<?php
/**
 * Contains the IssueController.php class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-17
 *
 */


namespace Konekt\Stift\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Stift\Models\IssueProxy;

class IssueController extends BaseController
{
    public function index()
    {
        return view('stift::issue.index', [
            'issues' => IssueProxy::all()
        ]);
    }

}