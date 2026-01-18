<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // Hiển thị form liên hệ
    public function index()
    {
        return view('customer.contact.index');
    }

    // Xử lý gửi email
    public function send(Request $request)
    {
        // 1. Validate dữ liệu
        $details = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // 2. Gửi email
        // Lưu ý: Mail::to() là địa chỉ email bạn muốn nhận thư
        Mail::to('admin@example.com')->send(new ContactFormMail($details));

        // 3. Quay lại với thông báo thành công
        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}