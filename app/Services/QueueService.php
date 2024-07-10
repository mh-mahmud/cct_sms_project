<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Template;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Hash;
use Validator;

class TemplatesService extends AppService
{
    public function __construct()
    {
        $this->AuditLogService = new AuditLogService();
    }
    
    /**
     * get pagination data
     */
    public function getPagination(){
        // Get list
        $data = Template::where("account_id",\Auth::user()->account_id)->orderBy('created_at', 'DESC')->paginate(config('dashboard_constant.PAGINATION_LIMIT')); 
        foreach($data as $key => $value){
            $value->message = nl2br($value->message);
        }
        return $this->paginationDataFormat($data->toArray());
    }
    /**
     * save data
     * @param array request
     */
    public function save($request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:30',
            'message' => 'required|max:459',
        ]);

        if ($validator->fails()){
            return $this->processServiceResponse(false, $validator->errors()->first(),null);           
        }
        

        // Create or Update 
        $dataObj =  new Template;
        
        // $dataObj->id = strrev(strtotime(date("Y-m-d H:i:s")));
        $templateId = $this->genTemplateId();
        $dataObj->id = $templateId;
        $dataObj->account_id = \Auth::user()->account_id;
        $dataObj->name = $request->input('name');
        $dataObj->message = $request->input('message');
        $dataObj->created_by = \Auth::user()->userid;
        $dataObj->updated_by = \Auth::user()->userid;
        if($dataObj->save()) {
            $dataObj->id = $templateId;      
            $this->AuditLogService->createLog($dataObj, 'A');
            return $this->processServiceResponse(true, "Template Added Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Template Added Failed!",$dataObj);
    }

    /**
     * GENERATE RANDOM TEMPLATE ID
     */
    public function genTemplateId(){
        $id = $this->genRandNum(8);        
        $IdExists = Template::find($id);        
        if($IdExists){
            return $this->genTemplateId();
        }
        return $id;
    }

    /**
     * get details
     * $param int $id
     */
    public function getDetail($id){
        //Get detail
        return Template::findOrFail($id); 

    }

    /**
     * update data
     * @param array request
     */
    public function updateData($request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:30',
            'message' => 'required|max:459',
        ]);

        if ($validator->fails()){
            return $this->processServiceResponse(false, $validator->errors()->first(),null);            
        }
        
        // get detail
        $dataObj = $this->getDetail($request->id);
        
        //$dataObj->account_id = $this->getAccountId();
        $dataObj->name = $request->input('name');
        $dataObj->message = $request->input('message');        
        $dataObj->updated_by = \Auth::user()->userid;
        $dataObjU = $dataObj->getDirty();
        if($dataObj->save()) {            
            $this->AuditLogService->createLog($dataObjU, 'U');
            return $this->processServiceResponse(true, "Template Update Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Template Update Failed!",$dataObj);
    }

    /**
     * delete data
     * @param int $id
     */
    public function delete($id){
        $dataObj = $this->getDetail($id);
       
        if($dataObj->delete()) {
            $this->AuditLogService->createLog($dataObj, 'D');
            return $this->processServiceResponse(true, "Template Deleted Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Template Deleted Failed!",$dataObj);
    }
}
