document.addEventListener("DOMContentLoaded", () => {
	// 1. Toast Helper (Giữ nguyên)
	window.showToast = (message, type = "success") => {
		const toast = document.createElement("div");
		toast.className = `toast toast-${type}`;
		toast.textContent = message;
		document.body.appendChild(toast);
		requestAnimationFrame(() => toast.classList.add("show"));
		setTimeout(() => {
			toast.classList.remove("show");
			setTimeout(() => toast.remove(), 400);
		}, 3000);
	};
	// 2. Xử lý AJAX cho Form Thêm Giỏ Hàng
	const ajaxForms = document.querySelectorAll(".ajax-cart-form");

	ajaxForms.forEach((form) => {
		form.addEventListener("submit", async (e) => {
			e.preventDefault();

			const btn = form.querySelector('button[type="submit"]');
			const originalText = btn.innerHTML;

			// Bắt buộc hiện loading
			btn.classList.add("loading");
			btn.disabled = true;
			btn.innerHTML = ""; // Xóa text để hiện spinner rõ hơn

			try {
				const formData = new FormData(form);

				const response = await fetch(form.action, {
					method: "POST",
					body: formData,
				});

				const result = await response.json();

				if (result.status) {
					window.showToast(result.message, "success");
				} else {
					window.showToast(result.message, "error");
				}
			} catch (error) {
				window.showToast("Đã xảy ra lỗi kết nối", "error");
			} finally {
				// ✅ ĐỘ TRỄ MƯỢT: Giữ loading 300ms để mắt kịp nhận diện
				setTimeout(() => {
					btn.classList.remove("loading");
					btn.disabled = false;
					btn.innerHTML = originalText; // Khôi phục icon/text gốc
				}, 300);
			}
		});
	});
	// 3. Xử lý Loading cho các Form khác (Login, Checkout...)
	// Chỉ áp dụng cho form KHÔNG có class ajax-cart-form
	document.querySelectorAll("form:not(.ajax-cart-form)").forEach((form) => {
		form.addEventListener("submit", function () {
			const btn = this.querySelector('button[type="submit"]');
			if (btn) btn.classList.add("loading");
		});
	});
});
