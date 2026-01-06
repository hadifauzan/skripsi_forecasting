class ShippingCalculator {
    constructor() {
        this.apiBaseUrl = '/api/shipping';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    }

    async getProvinces() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/provinces`);
            const data = await response.json();
            return data.success ? data.data : [];
        } catch (error) {
            console.error('Error fetching provinces:', error);
            return [];
        }
    }

    async getCities(provinceId = null) {
        try {
            const url = provinceId 
                ? `${this.apiBaseUrl}/cities?province_id=${provinceId}`
                : `${this.apiBaseUrl}/cities`;
            const response = await fetch(url);
            const data = await response.json();
            return data.success ? data.data : [];
        } catch (error) {
            console.error('Error fetching cities:', error);
            return [];
        }
    }

    async getCouriers() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/couriers`);
            const data = await response.json();
            return data.success ? data.data : {};
        } catch (error) {
            console.error('Error fetching couriers:', error);
            return {};
        }
    }

    async calculateShippingCost(origin, destination, weight, courier = null) {
        try {
            const endpoint = courier ? '/cost' : '/cost/all';
            const body = {
                origin: origin,
                destination: destination,
                weight: weight
            };

            if (courier) {
                body.courier = courier;
            }

            const response = await fetch(`${this.apiBaseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(body)
            });

            const data = await response.json();
            return data.success ? data.data : [];
        } catch (error) {
            console.error('Error calculating shipping cost:', error);
            return [];
        }
    }

    async trackDelivery(waybill, courier) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/track`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    waybill: waybill,
                    courier: courier
                })
            });

            const data = await response.json();
            return data.success ? data.data : null;
        } catch (error) {
            console.error('Error tracking delivery:', error);
            return null;
        }
    }

    async updateTrackingNumber(transactionId, trackingNumber) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/update-tracking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    transaction_id: transactionId,
                    tracking_number: trackingNumber
                })
            });

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error updating tracking number:', error);
            return { success: false, message: 'Terjadi kesalahan' };
        }
    }

    async getTransactionTracking(transactionId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/transaction-tracking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    transaction_id: transactionId
                })
            });

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error getting transaction tracking:', error);
            return { success: false, message: 'Terjadi kesalahan' };
        }
    }

    populateSelect(selectElement, data, valueKey, textKey, placeholder = 'Pilih...') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            selectElement.appendChild(option);
        });
    }

    populateCourierSelect(selectElement, couriers, placeholder = 'Pilih Kurir...') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        
        Object.entries(couriers).forEach(([key, value]) => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = value;
            selectElement.appendChild(option);
        });
    }

    displayShippingOptions(container, shippingOptions) {
        container.innerHTML = '';
        
        if (shippingOptions.length === 0) {
            container.innerHTML = '<p class="text-gray-500">Tidak ada opsi pengiriman tersedia</p>';
            return;
        }

        shippingOptions.forEach(option => {
            const optionElement = document.createElement('div');
            optionElement.className = 'border rounded-lg p-4 mb-3 cursor-pointer hover:bg-gray-50';
            optionElement.innerHTML = `
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold">${option.courier} - ${option.service}</h4>
                        <p class="text-sm text-gray-600">${option.description}</p>
                        <p class="text-sm text-gray-500">Estimasi: ${option.etd} hari</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">${option.formatted_cost}</p>
                        <input type="radio" name="shipping_option" value="${option.courier.toLowerCase()}|${option.service}|${option.cost}" class="mt-2">
                    </div>
                </div>
            `;
            container.appendChild(optionElement);
        });
    }
}

// Global instance
window.shippingCalculator = new ShippingCalculator();

// Auto-populate provinces on page load
document.addEventListener('DOMContentLoaded', async function() {
    const provinceSelect = document.getElementById('shipping_province');
    const citySelect = document.getElementById('shipping_city');
    
    if (provinceSelect) {
        const provinces = await window.shippingCalculator.getProvinces();
        window.shippingCalculator.populateSelect(provinceSelect, provinces, 'province_id', 'province', 'Pilih Provinsi...');
        
        provinceSelect.addEventListener('change', async function() {
            if (this.value) {
                const cities = await window.shippingCalculator.getCities(this.value);
                window.shippingCalculator.populateSelect(citySelect, cities, 'city_id', 'city_name', 'Pilih Kota...');
            } else {
                citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
            }
        });
    }
});

// Calculate shipping cost function
async function calculateShippingCost() {
    const origin = document.getElementById('origin_city')?.value;
    const destination = document.getElementById('shipping_city')?.value;
    const weight = document.getElementById('package_weight')?.value;
    const container = document.getElementById('shipping_options');
    
    if (!origin || !destination || !weight) {
        alert('Mohon lengkapi semua field yang diperlukan');
        return;
    }
    
    const shippingOptions = await window.shippingCalculator.calculateShippingCost(origin, destination, weight);
    window.shippingCalculator.displayShippingOptions(container, shippingOptions);
}
