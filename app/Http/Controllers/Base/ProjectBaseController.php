<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\AssessmentBaseController;
use App\Http\Controllers\GlobalFunctionController;
use App\Models\Assessment\Assessment;
use App\Models\Goods;
use App\Models\Item\ItemCode;
use App\Models\Item\ItemDetail;
use App\Models\SubHead;
use App\User;
use Session;

use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;
use App\Models\Assessment\AssessmentDetails;
use App\Models\Assessment\AssessmentDocument;
use App\Models\Weighbridge\Weighbridge;

use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DB;
use Alert;

class ProjectBaseController extends Controller
{
    private $assessment;
    public $user;
    public $manifest;
    public $truckEntryReg;
    private $assessmentDetails;
    private $subHead;
    private $itemDetail;
    private $itemCode;
    private $goods;
    private $globalFunctionController;
    private $assessmentBaseController;
    private $assessmentDocument;
    public $weighbridge;



    public function __construct(GlobalFunctionController $globalFunctionController, Manifest $manifest,
                                TruckEntryReg $truckEntryReg, AssessmentBaseController $assessmentBaseController,
                                AssessmentDetails $assessmentDetails, SubHead $subHead,
                                ItemDetail $itemDetail, Goods $goods, ItemCode $itemCode,User $user,
                                Assessment $assessment,AssessmentDocument $assessmentDocument,Weighbridge $weighbridge)
    {
        $this->middleware('auth');

        $this->globalFunctionController = $globalFunctionController;
        $this->assessmentBaseController = $assessmentBaseController;


        $this->manifest = $manifest;
        $this->user = $user;
        $this->truckEntryReg = $truckEntryReg;
        $this->assessmentDetails = $assessmentDetails;
        $this->subHead = $subHead;
        $this->itemDetail = $itemDetail;
        $this->goods = $goods;
        $this->itemCode = $itemCode;
        $this->assessment = $assessment;
        $this->assessmentDocument = $assessmentDocument;
        $this->weighbridge = $weighbridge;
    }

}
