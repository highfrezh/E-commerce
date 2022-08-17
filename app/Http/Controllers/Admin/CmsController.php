<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CmsController extends Controller
{
    public function cmspages()
    {
        Session::put('page','cms_pages');
        $cms_pages = CmsPage::get();
        return view('admin.pages.cms_pages')->with(compact('cms_pages'));
    }

    public function updateCmsPageStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            CmsPage::where('id', $data['page_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'page_id' => $data['page_id']]);
        }
        
    }

    public function addEditCmsPage(Request $request, $id=null)
    {
        if($id==""){
            $title = "Add CMS Page";
            $cmspage = new CmsPage;
            $messsage = "CMS added successfully";
        }else{
            $title = "Edit CMS Page";
            $messsage = "CMS updated successfully";
            $cmspage = CmsPage::find($id);
            // dd($cmspage);die;
        }
        if($request->isMethod('post')){
            $data = $request->all();

              //CMS validation
            $rules = [
                'title' => 'required',
                'url' => 'required',
                'description' => 'required'
            ];

            $customMessages = [ 
                'title.required' => 'Title is required',
                'url.required' => 'URL is required',
                'description.required' => 'Description is required'
            ];
            $this->validate($request,$rules,$customMessages);

            $cmspage->title = $data['title'];
            $cmspage->url = $data['url'];
            $cmspage->description = $data['description'];
            $cmspage->meta_title = $data['meta_title'];
            $cmspage->meta_description = $data['meta_description'];
            $cmspage->meta_keyword = $data['meta_keyword'];
            $cmspage->status = 1;
            $cmspage->save();
            Session::flash('success_message',$messsage);
            return redirect('admin/cms-pages');
        }
        return view('admin.pages.add_edit_cmspage')->with(compact('title','cmspage'));
    }

    public function deleteCmsPage($id){
        //delete attribute
        CmsPage::where('id',$id)->delete();

        $message = "CMS has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
    }
}
