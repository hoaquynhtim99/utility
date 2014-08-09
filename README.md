utility
=======

Module cung cấp môi trường quản lý cho các ứng dụng mini không cần dùng đến CSDL, các ứng dụng được phân thành hai loại: admin và ngoài site. Các ứng dụng admin là các ứng dụng bí mật mà chỉ có quản trị hoặc người điều hành module mới thấy và sử dụng được còn các ứng dụng site là các ứng dụng mà tất cả mọi người có thể nhìn thấy vào sử dụng.

## Danh sách các ứng dụng admin hiện có:
- update-modules-list: Cập nhật thông tin các module.
- upavatars: Cập nhật Avatar của thành viên từ diễn đàn.
- set-module-project-info: Cập nhật mô tả dự án của các file php.
- movenews: Di chuyển bài viết.
- del-unuse-user-photo: Xóa ảnh đại diện thừa.
- add-modfuncs: Thêm function của Module.

## Danh sách các ứng dụng site hiện có:
- ckeditor-code-format: Định dạng code cho trình soạn thảo

## Change Logs:
### 4.0.01:
- Chạy trên NukeViet 4.0.x.
- Thêm ứng dụng site ckeditor-code-format.
### 3.4.01:
- Quản lý tiện ích admin.
- Quản lý tiện ích site.
- Quản lý báo lỗi tiện ích site.
- Cung cấp 6 tiện ích admin.
