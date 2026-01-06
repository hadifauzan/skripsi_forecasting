/**
 * Admin Panel JavaScript
 * Gentle Living Affiliate Management System
 */

// View Details Function
function viewDetails(id) {
    fetch(`/admin/affiliate/${id}/details`)
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const affiliate = data.data;
                const info = affiliate.affiliate_info || {};

                const modalContent = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Personal</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-sm text-gray-900">${
                                    affiliate.email
                                }</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <p class="text-sm text-gray-900">${
                                    affiliate.nama_lengkap
                                }</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Kontak WhatsApp</label>
                                <p class="text-sm text-gray-900">${
                                    affiliate.kontak_whatsapp
                                }</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Kota Domisili</label>
                                <p class="text-sm text-gray-900">${
                                    affiliate.kota_domisili
                                }</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Media Sosial</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Instagram</label>
                                <p class="text-sm text-gray-900">${
                                    info.akun_instagram || "-"
                                }</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">TikTok</label>
                                <p class="text-sm text-gray-900">${
                                    info.akun_tiktok || "-"
                                }</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Profesi/Kesibukan</label>
                                <p class="text-sm text-gray-900">${
                                    affiliate.profesi_kesibukan
                                }</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Tau Info Dari Mana</label>
                                <p class="text-sm text-gray-900">${
                                    affiliate.info_darimana
                                }</p>
                            </div>
                            ${
                                affiliate.yang_lain_text
                                    ? `
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Keterangan Lainnya</label>
                                    <p class="text-sm text-gray-900">${affiliate.yang_lain_text}</p>
                                </div>
                                `
                                    : ""
                            }
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Tanggal Daftar</label>
                                <p class="text-sm text-gray-900">${new Date(
                                    affiliate.created_at
                                ).toLocaleDateString("id-ID", {
                                    weekday: "long",
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                    hour: "2-digit",
                                    minute: "2-digit",
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                    affiliate.status === "Aktif"
                                        ? "bg-green-100 text-green-800"
                                        : affiliate.status === "Nonaktif"
                                        ? "bg-red-100 text-red-800"
                                        : "bg-gray-100 text-gray-800"
                                }">${affiliate.status}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                document.getElementById("modalContent").innerHTML =
                    modalContent;
                document
                    .getElementById("detailModal")
                    .classList.remove("hidden");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                title: "Error!",
                text: "Terjadi kesalahan saat memuat data",
                icon: "error",
                confirmButtonColor: "#dc2626",
            });
        });
}

// Close Modal Function
function closeModal() {
    document.getElementById("detailModal").classList.add("hidden");
}

// Close Edit Modal Function
function closeEditModal() {
    document.getElementById("editModal").classList.add("hidden");
}

// Edit Affiliate Function
function editAffiliate(id) {
    fetch(`/admin/affiliate/${id}/details`)
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const affiliate = data.data;
                const info = affiliate.affiliate_info || {};

                const editForm = `
                <form id="editForm" onsubmit="submitEdit(event, ${id})">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="edit_email" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="edit_email" 
                                   name="email" 
                                   value="${affiliate.email}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-300"
                                   required>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="edit_nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="edit_nama_lengkap" 
                                   name="nama_lengkap" 
                                   value="${affiliate.nama_lengkap}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-300"
                                   required>
                        </div>

                        <!-- Kontak WhatsApp -->
                        <div>
                            <label for="edit_kontak_whatsapp" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.130-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                Kontak WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="edit_kontak_whatsapp" 
                                   name="kontak_whatsapp" 
                                   value="${affiliate.kontak_whatsapp}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-300"
                                   required>
                        </div>

                        <!-- Kota Domisili -->
                        <div>
                            <label for="edit_kota_domisili" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Kota Domisili <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="edit_kota_domisili" 
                                   name="kota_domisili" 
                                   value="${affiliate.kota_domisili}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    <!-- Social Media Section -->
                    <div class="mt-8">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border-2 border-purple-100">
                            <h3 class="text-lg font-bold text-gray-700 mb-4">Media Sosial</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Instagram -->
                                <div>
                                    <label for="edit_akun_instagram" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                        Instagram
                                    </label>
                                    <input type="text" 
                                           id="edit_akun_instagram" 
                                           name="akun_instagram" 
                                           value="${info.akun_instagram || ""}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-300"
                                           placeholder="@username_instagram">
                                </div>

                                <!-- TikTok -->
                                <div>
                                    <label for="edit_akun_tiktok" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-black" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                        </svg>
                                        TikTok
                                    </label>
                                    <input type="text" 
                                           id="edit_akun_tiktok" 
                                           name="akun_tiktok" 
                                           value="${info.akun_tiktok || ""}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition-all duration-300"
                                           placeholder="@username_tiktok">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profesi -->
                    <div class="mt-8">
                        <label for="edit_profesi_kesibukan" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V4.5a2 2 0 00-2-2h-4a2 2 0 00-2 2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
                            </svg>
                            Profesi/Kesibukan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="edit_profesi_kesibukan" 
                                  name="profesi_kesibukan" 
                                  rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-300 resize-none"
                                  required>${
                                      affiliate.profesi_kesibukan
                                  }</textarea>
                    </div>

                    <!-- Status Section -->
                    <div class="mt-8">
                        <label for="edit_status" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-[#528B89]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Affiliator <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_status" 
                                name="status" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-300"
                                required>
                            <option value="Aktif" ${
                                affiliate.status === "Aktif" ? "selected" : ""
                            }>Aktif</option>
                            <option value="Nonaktif" ${
                                affiliate.status === "Nonaktif"
                                    ? "selected"
                                    : ""
                            }>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="sticky bottom-0 bg-white flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 -mx-6 px-6 pb-4">
                        <button type="button" 
                                onclick="closeEditModal()"
                                class="px-6 py-3 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-colors duration-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-[#528B89] to-[#446b6a] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <span class="submit-text">Simpan Perubahan</span>
                            <span class="loading-text hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            `;

                document.getElementById("editModalContent").innerHTML =
                    editForm;
                document.getElementById("editModal").classList.remove("hidden");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                title: "Error!",
                text: "Terjadi kesalahan saat memuat data untuk edit",
                icon: "error",
                confirmButtonColor: "#dc2626",
            });
        });
}

// Submit Edit Function
function submitEdit(event, id) {
    event.preventDefault();
    event.stopPropagation();

    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');

    if (!submitButton) {
        console.error("Submit button not found!");
        return;
    }

    const submitText = submitButton.querySelector(".submit-text");
    const loadingText = submitButton.querySelector(".loading-text");

    // Show loading state
    submitButton.disabled = true;
    if (submitText) submitText.classList.add("hidden");
    if (loadingText) loadingText.classList.remove("hidden");

    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    fetch(`/admin/affiliate/${id}/update`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken ? csrfToken.getAttribute("content") : "",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) =>
            response.text().then((text) => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Server returned invalid JSON: " + text);
                }
            })
        )
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data berhasil diperbarui!",
                    icon: "success",
                    confirmButtonColor: "#528B89",
                    timer: 2000,
                    timerProgressBar: true,
                }).then(() => {
                    closeEditModal();
                    location.reload();
                });
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors)
                        .flat()
                        .join(", ");
                    throw new Error(errorMessages);
                } else {
                    throw new Error(
                        data.message || "Terjadi kesalahan saat menyimpan data"
                    );
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                title: "Error!",
                text: error.message || "Terjadi kesalahan saat menyimpan data",
                icon: "error",
                confirmButtonColor: "#dc2626",
            });
        })
        .finally(() => {
            // Reset loading state
            submitButton.disabled = false;
            if (submitText) submitText.classList.remove("hidden");
            if (loadingText) loadingText.classList.add("hidden");
        });
}

// Delete Affiliate Function
function deleteAffiliate(id, name) {
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
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            fetch(`/admin/affiliate/${id}/delete`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken
                        ? csrfToken.getAttribute("content")
                        : "",
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((response) =>
                    response.text().then((text) => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error(
                                "Server returned invalid JSON: " + text
                            );
                        }
                    })
                )
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Data berhasil dihapus",
                            icon: "success",
                            confirmButtonColor: "#528B89",
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || "Gagal menghapus data");
                    }
                })
                .catch((error) => {
                    console.error("Delete error:", error);
                    Swal.fire({
                        title: "Error!",
                        text:
                            error.message ||
                            "Terjadi kesalahan saat menghapus data",
                        icon: "error",
                        confirmButtonColor: "#dc2626",
                    });
                });
        }
    });
}

// Export to Excel Function
function exportToExcel() {
    const exportButton = event.target;
    const originalText = exportButton.innerHTML;

    // Show loading state
    exportButton.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mengunduh Excel...
    `;
    exportButton.disabled = true;

    // Create download link
    const downloadLink = document.createElement("a");
    downloadLink.href = "/admin/export-excel";
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);

    // Show success message
    setTimeout(() => {
        Swal.fire({
            title: "Berhasil!",
            text: "File Excel berhasil diunduh",
            icon: "success",
            confirmButtonColor: "#528B89",
            showConfirmButton: true,
            confirmButtonText: "OK",
        }).then(() => {
            exportButton.innerHTML = originalText;
            exportButton.disabled = false;
        });
    }, 1000);
}

// Search and Filter functionality
function initializeFilters() {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const affiliateRows = document.querySelectorAll(".affiliate-row");

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        affiliateRows.forEach((row) => {
            const name = row.dataset.name || "";
            const email = row.dataset.email || "";
            const status = row.dataset.status || "";

            const matchesSearch =
                name.includes(searchTerm) || email.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener("input", filterTable);
    }

    if (statusFilter) {
        statusFilter.addEventListener("change", filterTable);
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    initializeFilters();

    // Close modal when clicking outside
    const detailModal = document.getElementById("detailModal");
    const editModal = document.getElementById("editModal");

    if (detailModal) {
        detailModal.addEventListener("click", function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }

    if (editModal) {
        editModal.addEventListener("click", function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
});
