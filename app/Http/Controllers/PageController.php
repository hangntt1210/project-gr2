<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Session;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Type;
use App\Models\Material;
use App\Models\Orders;
use App\Models\OrderItem;

class PageController extends Controller
{
    //
    public function getIndex(){
        $tatcasp = Product::where('price','<>',0)->get();//lay tat ca sp
        //dd($tatcasp);
        $tatca_donhang = Orders::where('id_user', Auth::id())->count();
    	return view('page.trangchu',compact('tatcasp', 'tatca_donhang'));
    }

    public function getLoai($type){
        $sp_theoloai = Product::where('id_type',$type)->get();//lay sp theo loai: id_type=bien truyen vao $type
        return view('page.loai_sanpham',compact('sp_theoloai'));
    }
    public function getChatlieu($type){
        $sp_theochatlieu = Product::where('id_material',$type)->get();//lay sp theo loai: id_material=bien truyen vao $type
        return view('page.loaichatlieu',compact('sp_theochatlieu'));
    }

    public function getChitiet(Request $req){//cach2 cua getLoai
        $sanpham = Product::where('id',$req->id)->first();
        $sp_tuongtu = Product::where('id_type',$sanpham->id_type)->paginate(3);
        //de lay cmt:
        $item = OrderItem::with('orders')->where('id_product', $req->id)->whereNotNull('cmt')->get();
        return view('page.chitiet',compact('sanpham','sp_tuongtu', 'item'));
    }

    public function getSearch(Request $req){//name=key trong header.blade.php
        $product = Product::where('name','like','%'.$req->key.'%')->get();
        return view('page.search',compact('product'));
    }

    public function getAllOrder(){
        $tatca_don = Orders::where('id_user', Auth::id())->get();
        //$tatca_don = Orders::where('id_user', Auth::id())->orderBy('id', 'desc');
        $dem_don = Orders::where('id_user', Auth::id())->count();
        return view('page.ordered',compact('tatca_don', 'dem_don'));
    }

    public function getDetailOrder($id){
        $detailOrder = Orders::with('user')->where('id', $id)->firstOrFail();
        $listProduct = OrderItem::with('product')->where('id_order', $id)->get();
        return view('page.detail_ordered', compact('detailOrder', 'listProduct'));
    }

    public function getCommentOrder($id){
        $detailOrder = Orders::where('id', $id)->firstOrFail();
        $listProduct = OrderItem::where('id_order', $id)->get();
        return view('page.comment', compact('detailOrder', 'listProduct'));
    }
    public function storeComment(Request $req, $id)
    {
        $item = OrderItem::findOrFail($id);
        $item->cmt = $req->cmt;
        $item->save();

        return redirect()->route('chitietsanpham', $item->id_product);
    }


    public function getLogin(){
        return view('page.dangnhap');
    }

    public function getSignup(){
        return view('page.dangki');
    }

    public function postSignup(Request $req){
        $this->validate($req,
            [
                'email'=>'required|email|unique:user,email',//required bat buoc phai nhap, unique co trung email cua tai khoan khac ko
                'password'=>'required|min:6|max:20',
                'fullname'=>'required',
                're_password'=>'required|same:password'
            ],
            [
                'email.required'=>'vui long nhap email',
                'email.email'=>'email khong dung dinh dang',
                'email.unique'=>'email da co nguoi su dung',
                'password.required'=>'vui long nhap password',
                're_password.same'=>'password khong giong nhau',
                'password.min'=>'password phai co it nhat 6 ki tu',
                'fullname.required'=>'vui long nhap fullname'
            ]
        );
        $user = new User();
        $user->email = $req->email;
        $user->fullname = $req->fullname;
        $user->password = Hash::make($req->password);//sd hash de ma hoa password
        $user->phone = $req->phone;
        $user->address = $req->address;
        $user->save();

        return redirect()->back()->with('thanhcong','đã tạo tài khoản thành công');
        //return redirect()->back()->with(['flag'=>'success','message'=>'đã tạo tài khoản thành công']);
    }

    public function postLogin(Request $req){
        $this->validate($req,
            [
                'email'=>'required|email',//required bat buoc phai nhap
                'password'=>'required|min:6|max:20',
            ],
            [
                'email.required'=>'vui long nhap email',
                'email.email'=>'email khong dung dinh dang',
                'password.required'=>'vui long nhap password',
                'password.min'=>'password phai co it nhat 6 ki tu',
                'password.max'=>'password khong qua 20 ki tu'
            ]
        );
        //bien chung thuc nguoi dung la 1 mang
        $credentials = array('email'=>$req->email, 'password'=>$req->password);
        if(Auth::attempt($credentials)){
            return redirect()->back()->with(['flag'=>'success','message'=>'đăng nhập thành công']);
        }
        else{
            return redirect()->back()->with(['flag'=>'danger','message'=>'đăng nhập thất bại']);
        }
    }

    public function getLogout(){
        Auth::logout();
        return redirect()->route('trang-chu');
    }

    public function getProfile(){
    
        return view('page.profile');
    }

}