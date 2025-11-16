<div id="bookingModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 15px; padding: 25px; width: 95%; max-width: 600px; box-shadow: 0 6px 15px rgba(0,0,0,0.2); position: relative; z-index: 10000;">
        <span class="modal-close" onclick="closeClassModal()" style="position: absolute; top: 15px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; color: #888;">&times;</span>
        <h1 id="modalTitle" style="margin-top: 0; color: #222; text-align: center;"></h1>
        <div class="options" style="display: flex; justify-content: center; gap: 25px; flex-wrap: wrap; margin-bottom: 30px;">
            <div class="option-group" style="flex: 1; min-width: 250px;">
                <h3 style="margin-bottom: 15px; color: #333;">Pilih Jadwal</h3>
                <div id="modalSchedule"></div>
            </div>
        </div>
        <button id="modalConfirmBtn" class="confirm-btn" onclick="bookClass()" style="display: block; width: 100%; padding: 15px; color: white; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: background 0.3s; background: #5a4636;">Konfirmasi</button>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById("bookingModal");
    let currentClassId = '';

    function createRadioOptions(name, options, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        options.forEach((option, index) => {
            const label = document.createElement('label');
            label.className = 'radio-box';
            label.style.cssText = 'display: block; margin-bottom: 15px; background: #f2f2f2; padding: 10px 15px; border-radius: 12px; cursor: pointer; transition: 0.3s;';
            label.innerHTML = `
                <input type="radio" name="${name}" value="${option.id}" ${index === 0 ? 'checked' : ''} style="margin-right: 10px;">
                <span>${new Date(option.start_time).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} (${new Date(option.start_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} - ${new Date(option.end_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })})</span>
            `;
            label.addEventListener('mouseenter', () => label.style.background = '#e0e0e0');
            label.addEventListener('mouseleave', () => label.style.background = '#f2f2f2');
            container.appendChild(label);
        });
    }

    function openClassModal(classId, className, schedules) {
        currentClassId = classId;
        document.getElementById("modalTitle").innerText = className;
        
        createRadioOptions('schedule', schedules, 'modalSchedule');

        const confirmBtn = document.getElementById("modalConfirmBtn");
        confirmBtn.className = `confirm-btn`;

        modal.style.display = "flex";
    }

    function closeClassModal() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeClassModal();
        }
    }

    function bookClass() {
        const scheduleId = document.querySelector('input[name="schedule"]:checked').value;
        const token = localStorage.getItem('auth_token');

        if (!token) {
            alert('Silakan login dulu untuk mendaftar kelas.');
            window.location.href = '/login';
            return;
        }

        fetch('/api/enrollments', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ schedule_id: scheduleId })
        })
        .then(async (res) => {
            if (res.status === 201) {
                const data = await res.json();
                alert('Enrollment berhasil. Terima kasih!');
                closeClassModal();
                // Redirect to payment page with token in query string
                window.location.href = `/bayar-kelas/${scheduleId}?token=${encodeURIComponent(token)}`;
            } else if (res.status === 409) {
                alert('Anda sudah terdaftar untuk jadwal ini.');
            } else if (res.status === 401) {
                alert('Sesi Anda telah habis. Silakan login ulang.');
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            } else {
                const err = await res.json().catch(() => ({}));
                console.error('Enroll failed', err);
                alert('Gagal mendaftar kelas. Silakan coba lagi.');
            }
        })
        .catch((err) => {
            console.error('Network error enrolling:', err);
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
        });
    }

    function purchaseMembership(membershipId, membershipName) {
        const token = localStorage.getItem('auth_token');

        if (!token) {
            alert('Silakan login dulu untuk membeli membership.');
            window.location.href = '/login';
            return;
        }

        if (!confirm(`Beli membership ${membershipName}? Biaya akan dikenakan sekarang.`)) {
            return;
        }

        fetch(`/api/memberships/${membershipId}/purchase`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(async (res) => {
            if (res.status === 201) {
                const data = await res.json();
                alert(`Berhasil membeli membership ${membershipName}! Sekarang Anda memiliki akses ke semua kelas.`);
                window.location.reload();
            } else if (res.status === 401) {
                alert('Sesi Anda telah habis. Silakan login ulang.');
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            } else {
                const err = await res.json().catch(() => ({}));
                console.error('Purchase failed', err);
                alert('Gagal membeli membership. Silakan coba lagi.');
            }
        })
        .catch((err) => {
            console.error('Network error purchasing:', err);
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
        });
    }

</script>
@endpush
