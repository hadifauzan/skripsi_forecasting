// SweetAlert Utilities untuk maintainability
// File: public/js/swal-utils.js

const SwalConfig = {
    brand: {
        confirmButtonColor: "#528b89",
        cancelButtonColor: "#6b7280",
    },
    danger: {
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
    },
    success: {
        confirmButtonColor: "#22c55e",
        cancelButtonColor: "#6b7280",
    },
};

// Utility functions untuk SweetAlert
window.SwalUtils = {
    // Delete confirmation
    confirmDelete: function (
        title = "Apakah Anda yakin?",
        text = "Data yang dihapus tidak dapat dikembalikan!"
    ) {
        return Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            ...SwalConfig.danger,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
        });
    },

    // Success message
    success: function (
        title = "Berhasil!",
        text = "Operasi berhasil dilakukan."
    ) {
        return Swal.fire({
            title: title,
            text: text,
            icon: "success",
            ...SwalConfig.success,
            confirmButtonText: "OK",
        });
    },

    // Brand themed confirmation
    confirmBrand: function (
        title,
        text,
        confirmText = "Ya",
        cancelText = "Batal"
    ) {
        return Swal.fire({
            title: title,
            text: text,
            icon: "question",
            showCancelButton: true,
            ...SwalConfig.brand,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
        });
    },

    // Error message
    error: function (title = "Error!", text = "Terjadi kesalahan.") {
        return Swal.fire({
            title: title,
            text: text,
            icon: "error",
            ...SwalConfig.danger,
            confirmButtonText: "OK",
        });
    },
};
