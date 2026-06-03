import Swal from "sweetalert2";

const onReady = (callback) => {
	if (document.readyState !== "loading") {
		callback();
		return;
	}

	document.addEventListener("DOMContentLoaded", callback);
};

const bindOnce = (element, key) => {
	if (element.dataset[key] === "true") {
		return false;
	}

	element.dataset[key] = "true";
	return true;
};

onReady(() => {
	const toast = Swal.mixin({
		toast: true,
		position: "top-end",
		showConfirmButton: false,
		timer: 2500,
		timerProgressBar: true,
	});

	const successNotice = document.querySelector("[data-swal-success]");
	if (successNotice) {
		toast.fire({
			icon: "success",
			title: successNotice.dataset.swalSuccess,
		});
	}

	document.querySelectorAll("[data-swal-logout]").forEach((form) => {
		if (!bindOnce(form, "swalLogoutBound")) {
			return;
		}

		form.addEventListener("submit", (event) => {
			event.preventDefault();

			Swal.fire({
				title: "Logout now?",
				text: "You will be signed out of your account.",
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: "Yes, logout",
				cancelButtonText: "Cancel",
				confirmButtonColor: "#e11d48",
				cancelButtonColor: "#94a3b8",
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});
	});

	document.querySelectorAll("[data-swal-delete]").forEach((form) => {
		if (!bindOnce(form, "swalDeleteBound")) {
			return;
		}

		form.addEventListener("submit", (event) => {
			event.preventDefault();

			const title = form.dataset.swalTitle || "Delete this item?";
			const text = form.dataset.swalText || "This action cannot be undone.";
			const confirmText = form.dataset.swalConfirm || "Yes, delete";

			Swal.fire({
				title,
				text,
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: confirmText,
				cancelButtonText: "Cancel",
				confirmButtonColor: "#dc2626",
				cancelButtonColor: "#94a3b8",
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});
	});
});
