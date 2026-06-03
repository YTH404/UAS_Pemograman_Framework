import Swal from "sweetalert2";

const defaultSweetAlertMessages = {
	buttons: {
		cancel: "Cancel",
	},
	logout: {
		title: "Logout now?",
		text: "You will be signed out of your account.",
		confirm: "Yes, logout",
	},
	delete: {
		default: {
			title: "Delete this item?",
			text: "This action cannot be undone.",
			confirm: "Yes, delete",
		},
	},
};

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

const isPlainObject = (value) =>
	value !== null && typeof value === "object" && !Array.isArray(value);

const mergeMessages = (baseMessages, customMessages = {}) => {
	const messages = { ...baseMessages };

	Object.entries(customMessages).forEach(([key, customValue]) => {
		const baseValue = messages[key];

		if (isPlainObject(baseValue) && isPlainObject(customValue)) {
			messages[key] = mergeMessages(baseValue, customValue);
			return;
		}

		if (customValue !== undefined) {
			messages[key] = customValue;
		}
	});

	return messages;
};

const getSweetAlertMessages = () => {
	const messagesElement = document.querySelector("[data-swal-messages]");

	if (!messagesElement) {
		return defaultSweetAlertMessages;
	}

	try {
		return mergeMessages(
			defaultSweetAlertMessages,
			JSON.parse(messagesElement.dataset.swalMessages),
		);
	} catch {
		return defaultSweetAlertMessages;
	}
};

onReady(() => {
	const messages = getSweetAlertMessages();

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
				title: messages.logout.title,
				text: messages.logout.text,
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: messages.logout.confirm,
				cancelButtonText: messages.buttons.cancel,
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

			const title = form.dataset.swalTitle || messages.delete.default.title;
			const text = form.dataset.swalText || messages.delete.default.text;
			const confirmText =
				form.dataset.swalConfirm || messages.delete.default.confirm;

			Swal.fire({
				title,
				text,
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: confirmText,
				cancelButtonText: messages.buttons.cancel,
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
