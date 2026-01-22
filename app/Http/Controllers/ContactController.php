<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Hàm xử lý khi người dùng nhấn "GỬI TIN NHẮN"
    public function addContact(Request $request)
    {
        // 1. Kiểm tra dữ liệu
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'required',
            'title'   => 'required',
            'content' => 'required',
        ]);

        // 2. Lưu vào Database
        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'title'   => $request->title,
            'content' => $request->content,
            'status'  => 0, // 0 là chưa đọc
        ]);

        // 3. QUAY VỀ TRANG CHỦ kèm thông báo
        return redirect('/')->with('success', 'Gửi liên hệ thành công! Cảm ơn bạn.');
    }

    // Hàm hiển thị danh sách cho Admin
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.contact', compact('contacts'));
    }

    public function markAsRead($id)
    {
        // 1. Tìm liên hệ theo ID, nếu không thấy sẽ báo lỗi 404
        $contact = \App\Models\Contact::findOrFail($id);

        // 2. Cập nhật status sang 1 (Đã xem)
        $contact->update(['status' => 1]);

        // 3. Quay lại trang trước đó với thông báo thành công
        return back()->with('success', 'Đã đánh dấu tin nhắn là đã xem!');
    }

    public function toggleRead($id)
    {
        $contact = \App\Models\Contact::findOrFail($id);

        // Nếu đang là 0 thì đổi thành 1, ngược lại thì đổi thành 0
        $contact->status = ($contact->status == 0) ? 1 : 0;
        $contact->save();

        return back()->with('success', 'Đã cập nhật trạng thái tin nhắn!');
    }
}
