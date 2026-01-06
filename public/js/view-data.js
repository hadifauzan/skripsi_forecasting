/* ========================================= */
/* ADMIN VIEW DATA FUNCTIONALITY            */
/* ========================================= */

class ViewDataManager {
    constructor() {
        this.initializeComponents();
        this.bindEvents();
        this.initializeRowNumbers();
    }

    initializeComponents() {
        this.searchInput = document.getElementById("searchInput");
        this.statusFilter = document.getElementById("statusFilter");
        this.paginationSection = document.getElementById("paginationSection");
        this.detailModal = document.getElementById("detailModal");
        this.editModal = document.getElementById("editModal");

        // Get pagination data from server-side variables
        this.paginationData = {
            currentPage: window.currentPage || 1,
            perPage: window.perPage || 10,
            total: window.totalAffiliates || 0,
        };
    }

    bindEvents() {
        if (this.searchInput) {
            this.searchInput.addEventListener(
                "input",
                this.handleFilterChange.bind(this)
            );
        }

        if (this.statusFilter) {
            this.statusFilter.addEventListener(
                "change",
                this.handleFilterChange.bind(this)
            );
        }

        // Close modals when clicking outside
        document.addEventListener("click", this.handleModalClose.bind(this));

        // Handle escape key for modals
        document.addEventListener("keydown", this.handleEscapeKey.bind(this));
    }

    initializeRowNumbers() {
        const rows = document.querySelectorAll(".affiliate-row");
        const { currentPage, perPage } = this.paginationData;

        rows.forEach((row, index) => {
            const numberCell = row.querySelector(".row-number");
            if (numberCell) {
                const originalNumber = (currentPage - 1) * perPage + index + 1;
                numberCell.textContent = originalNumber;
            }
        });
    }

    // ========================================= //
    // SEARCH AND FILTER FUNCTIONALITY          //
    // ========================================= //

    handleFilterChange() {
        this.filterAndRenumberRows();
    }

    filterAndRenumberRows() {
        const searchTerm = this.searchInput?.value.toLowerCase() || "";
        const statusValue = this.statusFilter?.value || "";
        const rows = document.querySelectorAll(".affiliate-row");
        let visibleRowCount = 0;

        rows.forEach((row) => {
            const name = row.dataset.name || "";
            const email = row.dataset.email || "";
            const status = row.dataset.status || "";

            const matchesSearch =
                name.includes(searchTerm) || email.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesStatus) {
                row.style.display = "";
                visibleRowCount++;
                this.updateRowNumber(row, visibleRowCount);
            } else {
                row.style.display = "none";
            }
        });

        this.updatePaginationInfo(searchTerm, statusValue, visibleRowCount);
        this.handleEmptyState(visibleRowCount);
    }

    updateRowNumber(row, number) {
        const numberCell = row.querySelector(".row-number");
        if (numberCell) {
            numberCell.textContent = number;
        }
    }

    updatePaginationInfo(searchTerm, statusValue, visibleRowCount) {
        const infoSpan = this.paginationSection?.querySelector(
            ".text-sm.text-gray-700 span"
        );
        const navButtons = this.paginationSection?.querySelector(
            ".flex.items-center.space-x-2"
        );

        if (!infoSpan) return;

        if (searchTerm || statusValue) {
            // Filtered results
            infoSpan.innerHTML = `Menampilkan <span class="font-semibold">1</span> - <span class="font-semibold">${visibleRowCount}</span> dari <span class="font-semibold">${visibleRowCount}</span> data (difilter)`;
            if (navButtons) navButtons.style.display = "none";
        } else {
            // Original pagination
            const { currentPage, perPage, total } = this.paginationData;
            const from = (currentPage - 1) * perPage + 1;
            const to = Math.min(from + perPage - 1, total);
            infoSpan.innerHTML = `Menampilkan <span class="font-semibold">${from}</span> - <span class="font-semibold">${to}</span> dari <span class="font-semibold">${total}</span> data`;
            if (navButtons) navButtons.style.display = "flex";
        }
    }

    handleEmptyState(visibleRowCount) {
        const tableBody = document.querySelector("tbody");
        let emptyMessage = document.getElementById("emptyMessage");

        if (visibleRowCount === 0) {
            if (!emptyMessage && tableBody) {
                emptyMessage = this.createEmptyStateMessage();
                tableBody.appendChild(emptyMessage);
            }
        } else {
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }

    createEmptyStateMessage() {
        const emptyMessage = document.createElement("tr");
        emptyMessage.id = "emptyMessage";
        emptyMessage.innerHTML = `
            <td colspan="7" class="px-8 py-12 text-center empty-state">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                              d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data yang ditemukan</h3>
                    <p class="text-gray-500 mb-4">Coba ubah kata kunci pencarian atau filter status.</p>
                    <button onclick="viewDataManager.resetFilters()" 
                            class="px-4 py-2 bg-[#528B89] text-white rounded-lg hover:bg-[#446b6a] transition-colors duration-200">
                        Reset Filter
                    </button>
                </div>
            </td>
        `;
        return emptyMessage;
    }

    resetFilters() {
        if (this.searchInput) this.searchInput.value = "";
        if (this.statusFilter) this.statusFilter.value = "";

        const rows = document.querySelectorAll(".affiliate-row");
        rows.forEach((row, index) => {
            row.style.display = "";
            const numberCell = row.querySelector(".row-number");
            if (numberCell) {
                const { currentPage, perPage } = this.paginationData;
                const originalNumber = (currentPage - 1) * perPage + index + 1;
                numberCell.textContent = originalNumber;
            }
        });

        this.updatePaginationInfo("", "", rows.length);

        const emptyMessage = document.getElementById("emptyMessage");
        if (emptyMessage) emptyMessage.remove();
    }

    // ========================================= //
    // EXPORT FUNCTIONALITY                     //
    // ========================================= //

    exportToExcel() {
        const button = document.querySelector(
            'button[onclick*="exportToExcel"]'
        );
        if (!button) return;

        const originalText = button.innerHTML;

        // Show loading state
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengunduh...
        `;
        button.disabled = true;

        // Redirect to export URL
        window.location.href = "/admin/export-excel";

        // Reset button after delay
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 3000);
    }

    // ========================================= //
    // MODAL FUNCTIONALITY                      //
    // ========================================= //

    handleModalClose(event) {
        if (event.target.classList.contains("modal-overlay")) {
            this.closeModal();
            this.closeEditModal();
        }
    }

    handleEscapeKey(event) {
        if (event.key === "Escape") {
            this.closeModal();
            this.closeEditModal();
        }
    }

    closeModal() {
        if (this.detailModal) {
            this.detailModal.classList.add("hidden");
        }
    }

    closeEditModal() {
        if (this.editModal) {
            this.editModal.classList.add("hidden");
        }
    }

    // ========================================= //
    // VIEW DETAILS FUNCTIONALITY               //
    // ========================================= //

    async viewDetails(id) {
        try {
            const response = await fetch(`/admin/affiliate/${id}/details`);
            const data = await response.json();

            if (data.success) {
                const modalContent = document.getElementById("modalContent");
                if (modalContent) {
                    modalContent.innerHTML = data.html;
                    this.detailModal.classList.remove("hidden");
                }
            } else {
                this.showError("Gagal memuat detail data");
            }
        } catch (error) {
            console.error("Error:", error);
            this.showError("Terjadi kesalahan saat memuat data");
        }
    }

    // ========================================= //
    // EDIT FUNCTIONALITY                       //
    // ========================================= //

    async editAffiliate(id) {
        try {
            const response = await fetch(`/admin/affiliate/${id}/details`);
            const data = await response.json();

            if (data.success) {
                const editForm = this.generateEditForm(data.data, id);
                const editModalContent =
                    document.getElementById("editModalContent");
                if (editModalContent) {
                    editModalContent.innerHTML = editForm;
                    this.editModal.classList.remove("hidden");
                }
            } else {
                this.showError("Gagal memuat data untuk edit");
            }
        } catch (error) {
            console.error("Error:", error);
            this.showError("Terjadi kesalahan saat memuat data");
        }
    }

    generateEditForm(affiliate, id) {
        const info = affiliate.affiliate_info || {};

        return `
            <form onsubmit="viewDataManager.submitEdit(event, ${id})" class="space-y-8">
                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Dasar
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit_email" class="block text-sm font-semibold text-gray-700 mb-3">Email</label>
                            <input type="email" id="edit_email" name="email" value="${
                                affiliate.email
                            }" required
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="email@example.com">
                        </div>
                        <div>
                            <label for="edit_nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-3">Nama Lengkap</label>
                            <input type="text" id="edit_nama_lengkap" name="nama_lengkap" value="${
                                affiliate.nama_lengkap
                            }" required
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="Masukkan nama lengkap">
                        </div>
                        <div>
                            <label for="edit_kontak_whatsapp" class="block text-sm font-semibold text-gray-700 mb-3">Kontak WhatsApp</label>
                            <input type="text" id="edit_kontak_whatsapp" name="kontak_whatsapp" value="${
                                affiliate.kontak_whatsapp
                            }" required
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="08xxxxxxxxx">
                        </div>
                        <div>
                            <label for="edit_kota_domisili" class="block text-sm font-semibold text-gray-700 mb-3">Kota Domisili</label>
                            <input type="text" id="edit_kota_domisili" name="kota_domisili" value="${
                                affiliate.kota_domisili
                            }" required
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="Masukkan kota domisili">
                        </div>
                        <div>
                            <label for="edit_status" class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                            <select id="edit_status" name="status" required class="form-input w-full px-4 py-3 rounded-xl">
                                <option value="Aktif" ${
                                    affiliate.status === "Aktif"
                                        ? "selected"
                                        : ""
                                }>Aktif</option>
                                <option value="Nonaktif" ${
                                    affiliate.status === "Nonaktif"
                                        ? "selected"
                                        : ""
                                }>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Social Media Information -->
                <div class="bg-blue-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v8a1 1 0 01-1 1h-2a1 1 0 01-1-1V4m0 0H7m10 0v9a1 1 0 01-1 1H8a1 1 0 01-1-1V4"></path>
                        </svg>
                        Media Sosial
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit_akun_instagram" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-pink-500 social-icon" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                Instagram
                            </label>
                            <input type="text" id="edit_akun_instagram" name="akun_instagram" value="${
                                info.akun_instagram || ""
                            }"
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="@username_instagram">
                        </div>
                        <div>
                            <label for="edit_akun_tiktok" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-black social-icon" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                </svg>
                                TikTok
                            </label>
                            <input type="text" id="edit_akun_tiktok" name="akun_tiktok" value="${
                                info.akun_tiktok || ""
                            }"
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="@username_tiktok">
                        </div>
                    </div>
                </div>

                <!-- Profesi -->
                <div class="mt-8">
                    <label for="edit_profesi_kesibukan" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V4.5a2 2 0 00-2-2h-4a2 2 0 00-2 2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
                        </svg>
                        Profesi/Kesibukan
                    </label>
                    <textarea id="edit_profesi_kesibukan" name="profesi_kesibukan" rows="4" required
                              class="form-textarea w-full px-4 py-3 rounded-xl"
                              placeholder="Jelaskan profesi atau kesibukan Anda saat ini">${
                                  affiliate.profesi_kesibukan
                              }</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <button type="button" onclick="viewDataManager.closeEditModal()"
                            class="px-6 py-3 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-colors duration-300">
                        Batal
                    </button>
                    <button type="submit" class="submit-btn px-6 py-3 text-white font-bold rounded-xl shadow-lg">
                        <span class="submit-text">Simpan Perubahan</span>
                        <span class="loading-text hidden">
                            <svg class="loading-spinner -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        `;
    }

    async submitEdit(event, id) {
        event.preventDefault();
        event.stopPropagation();

        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const submitText = submitButton.querySelector(".submit-text");
        const loadingText = submitButton.querySelector(".loading-text");

        // Show loading state
        submitButton.disabled = true;
        if (submitText) submitText.classList.add("hidden");
        if (loadingText) loadingText.classList.remove("hidden");

        try {
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            const response = await fetch(`/admin/affiliate/${id}/update`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                        ? csrfToken.getAttribute("content")
                        : "",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const text = await response.text();
            let data;

            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error("Server returned invalid JSON: " + text);
            }

            if (data.success) {
                this.showSuccess("Data berhasil diperbarui!", () => {
                    this.closeEditModal();
                    location.reload();
                });
            } else {
                const errorMessages = data.errors
                    ? Object.values(data.errors).flat().join(", ")
                    : data.message || "Terjadi kesalahan saat menyimpan data";
                throw new Error(errorMessages);
            }
        } catch (error) {
            console.error("Error:", error);
            this.showError(
                error.message || "Terjadi kesalahan saat menyimpan data"
            );
        } finally {
            // Reset loading state
            submitButton.disabled = false;
            if (submitText) submitText.classList.remove("hidden");
            if (loadingText) loadingText.classList.add("hidden");
        }
    }

    // ========================================= //
    // DELETE FUNCTIONALITY                     //
    // ========================================= //

    deleteAffiliate(id, name) {
        Swal.fire({
            title: "Konfirmasi Hapus",
            text: `Apakah Anda yakin ingin menghapus data ${name}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            customClass: {
                popup: "swal2-popup-custom",
            },
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const csrfToken = document.querySelector(
                        'meta[name="csrf-token"]'
                    );

                    const response = await fetch(
                        `/admin/affiliate/${id}/delete`,
                        {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-CSRF-TOKEN": csrfToken
                                    ? csrfToken.getAttribute("content")
                                    : "",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                        }
                    );

                    const text = await response.text();
                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        throw new Error(
                            "Server returned invalid JSON: " + text
                        );
                    }

                    if (data.success) {
                        this.showSuccess("Data berhasil dihapus", () => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || "Gagal menghapus data");
                    }
                } catch (error) {
                    console.error("Delete error:", error);
                    this.showError(
                        error.message || "Terjadi kesalahan saat menghapus data"
                    );
                }
            }
        });
    }

    // ========================================= //
    // UTILITY FUNCTIONS                        //
    // ========================================= //

    showSuccess(message, callback = null) {
        Swal.fire({
            title: "Berhasil!",
            text: message,
            icon: "success",
            confirmButtonColor: "#528B89",
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
        }).then(() => {
            if (callback) callback();
        });
    }

    showError(message) {
        Swal.fire({
            title: "Error!",
            text: message,
            icon: "error",
            confirmButtonColor: "#dc2626",
        });
    }
}

// ========================================= //
// INITIALIZE APPLICATION                    //
// ========================================= //

let viewDataManager;

document.addEventListener("DOMContentLoaded", function () {
    viewDataManager = new ViewDataManager();
});

// ========================================= //
// GLOBAL FUNCTIONS (for backward compatibility) //
// ========================================= //

function exportToExcel() {
    if (viewDataManager) {
        viewDataManager.exportToExcel();
    }
}

function viewDetails(id) {
    if (viewDataManager) {
        viewDataManager.viewDetails(id);
    }
}

function editAffiliate(id) {
    if (viewDataManager) {
        viewDataManager.editAffiliate(id);
    }
}

function deleteAffiliate(id, name) {
    if (viewDataManager) {
        viewDataManager.deleteAffiliate(id, name);
    }
}

function closeModal() {
    if (viewDataManager) {
        viewDataManager.closeModal();
    }
}

function closeEditModal() {
    if (viewDataManager) {
        viewDataManager.closeEditModal();
    }
}

function resetFilters() {
    if (viewDataManager) {
        viewDataManager.resetFilters();
    }
}
