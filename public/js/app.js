/**
 * Phone Shop - Vanilla JS
 * Xử lý: Toast thông báo & Loading State cho Form
 */

document.addEventListener("DOMContentLoaded", () => {
	// 1. HỆ THỐNG TOAST (Thông báo nổi)
	window.showToast = (message, type = "success") => {
		// Tạo phần tử
		const toast = document.createElement("div");
		toast.className = `toast toast-${type}`;
		toast.textContent = message;
		document.body.appendChild(toast);

		// Kích hoạt animation (cần 1 frame delay)
		requestAnimationFrame(() => {
			toast.classList.add("show");
		});

		// Tự động ẩn sau 3 giây
		setTimeout(() => {
			toast.classList.remove("show");
			// Xóa khỏi DOM sau khi animation trượt xuống xong
			setTimeout(() => toast.remove(), 400);
		}, 3000);
	};

	// 2. XỬ LÝ LOADING KHI SUBMIT FORM
	// Chỉ áp dụng cho các form có thuộc tính data-loading="true"
	// hoặc các form thanh toán/giỏ hàng cụ thể để tránh xung đột
	const forms = document.querySelectorAll("form");

	forms.forEach((form) => {
		form.addEventListener("submit", function (e) {
			// Tìm nút submit trong form hiện tại
			const submitBtn = this.querySelector('button[type="submit"]');

			if (submitBtn) {
				// Thêm class loading để hiện spinner và ẩn text
				submitBtn.classList.add("loading");

				// (Tuỳ chọn) Nếu dùng AJAX thì e.preventDefault() ở đây.
				// Với PHP thuần (reload trang), ta để mặc định để form gửi đi.
			}
		});
	});

	// 3. XỬ LÝ ẢNH LỖI (Fallback)
	const images = document.querySelectorAll("img");
	images.forEach((img) => {
		img.onerror = function () {
			// Nếu ảnh lỗi, thay bằng placeholder hoặc ẩn đi
			this.src = "https://via.placeholder.com/300x220?text=No+Image";
			this.onerror = null; // Tránh vòng lặp vô tận
		};
	});
});
