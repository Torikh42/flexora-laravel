<div id="bookingModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; overflow: hidden;">
    <div class="modal-content" style="background: white; border-radius: 15px; padding: 25px; width: 95%; max-width: 600px; max-height: 90vh; box-shadow: 0 6px 15px rgba(0,0,0,0.2); position: relative; z-index: 10000; display: flex; flex-direction: column; overflow: hidden;">
        <span class="modal-close" onclick="closeClassModal()" style="position: absolute; top: 15px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; color: #888; z-index: 10001;">&times;</span>
        <h1 id="modalTitle" style="margin-top: 0; margin-bottom: 20px; color: #222; text-align: center; flex-shrink: 0;"></h1>
        
        <!-- Scrollable content area -->
        <div class="modal-body" style="flex: 1; overflow-y: auto; padding-right: 10px; margin-right: -10px;">
            <div class="options" style="display: flex; justify-content: center; gap: 25px; flex-wrap: wrap; margin-bottom: 20px;">
                <div class="option-group" style="flex: 1; min-width: 250px;">
                    <h3 style="margin-bottom: 15px; color: #333;">Pilih Tanggal Kelas</h3>
                    <input type="date" id="classDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; margin-bottom: 20px;">
                    
                    <h3 style="margin-bottom: 15px; color: #333;">Jadwal Tersedia</h3>
                    <div id="modalSchedule"></div>
                </div>
            </div>
            <div id="membershipWarning" style="display: none; background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;"></div>
        </div>
        
        <!-- Fixed footer with button -->
        <button id="modalConfirmBtn" class="confirm-btn" onclick="bookClass()" style="display: block; width: 100%; padding: 15px; color: white; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: background 0.3s; background: #5a4636; margin-top: 15px; flex-shrink: 0;">Konfirmasi</button>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById("bookingModal");
    const classDateInput = document.getElementById("classDate");
    let currentClassId = '';
    let allSchedules = [];

    function openClassModal(classId, className, schedules) {
        currentClassId = classId;
        allSchedules = schedules;
        document.getElementById("modalTitle").innerText = className;
        
        // Set min date to today
        const today = new Date().toISOString().split('T')[0];
        classDateInput.min = today;
        classDateInput.value = today;
        
        // Load schedules for today
        loadSchedulesForDate(today);
        
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

    // When date changes, fetch schedules for that date
    classDateInput.addEventListener('change', function() {
        loadSchedulesForDate(this.value);
    });

    function loadSchedulesForDate(date) {
        const token = localStorage.getItem('auth_token');

        fetch(`/api/classes/available-by-date?date=${date}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            const schedules = data.schedules || [];
            createRadioOptions('schedule', schedules, 'modalSchedule', date);
            
            // Check membership validity
            checkMembershipForDate(date);
        })
        .catch(err => {
            console.error('Error loading schedules:', err);
            document.getElementById('modalSchedule').innerHTML = '<p style="color: #d32f2f;">Gagal memuat jadwal kelas.</p>';
        });
    }

    function checkMembershipForDate(date) {
        const token = localStorage.getItem('auth_token');

        fetch(`/api/auth/user-profile`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(res => res.json())
        .then(user => {
            const warningDiv = document.getElementById('membershipWarning');
            const selectedDate = new Date(date);
            selectedDate.setHours(0, 0, 0, 0); // Reset time for proper date comparison
            
            // Check if user has active membership
            if (user.user_memberships && user.user_memberships.length > 0) {
                // Find membership that covers the selected date
                let matchingMembership = null;
                
                for (let membership of user.user_memberships) {
                    const membershipStart = new Date(membership.start_date);
                    const membershipEnd = new Date(membership.end_date);
                    membershipStart.setHours(0, 0, 0, 0);
                    membershipEnd.setHours(0, 0, 0, 0);
                    
                    // Check if selected date falls within membership period
                    if (selectedDate >= membershipStart && selectedDate <= membershipEnd) {
                        matchingMembership = membership;
                        break;
                    }
                }
                
                if (matchingMembership) {
                    // User has membership for this date - show success message
                    warningDiv.style.display = 'block';
                    warningDiv.style.background = '#d4edda';
                    warningDiv.style.border = '1px solid #c3e6cb';
                    warningDiv.style.color = '#155724';
                    warningDiv.innerHTML = `✅ Anda memiliki membership yang aktif hingga ${new Date(matchingMembership.end_date).toLocaleDateString('id-ID')}. Kelas ini gratis!`;
                } else {
                    // User has membership but not for this date
                    const activeMembership = user.user_memberships.find(m => new Date(m.end_date) >= new Date());
                    if (activeMembership) {
                        warningDiv.style.display = 'block';
                        warningDiv.style.background = '#fff3cd';
                        warningDiv.style.border = '1px solid #ffc107';
                        warningDiv.style.color = '#856404';
                        warningDiv.innerHTML = `⚠️ Membership Anda berakhir pada ${new Date(activeMembership.end_date).toLocaleDateString('id-ID')}. Untuk tanggal ini Anda harus membayar per kelas.`;
                    } else {
                        warningDiv.style.display = 'block';
                        warningDiv.style.background = '#f8d7da';
                        warningDiv.style.border = '1px solid #f5c6cb';
                        warningDiv.style.color = '#721c24';
                        warningDiv.innerHTML = '⚠️ Anda tidak memiliki membership aktif. Silakan beli membership atau bayar per kelas.';
                    }
                }
            } else {
                // No membership at all
                warningDiv.style.display = 'block';
                warningDiv.style.background = '#f8d7da';
                warningDiv.style.border = '1px solid #f5c6cb';
                warningDiv.style.color = '#721c24';
                warningDiv.innerHTML = '⚠️ Anda tidak memiliki membership. Silakan beli membership atau bayar per kelas.';
            }
        })
        .catch(err => console.error('Error checking membership:', err));
    }

    function createRadioOptions(name, options, containerId, date) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        const token = localStorage.getItem('auth_token');
        
        if (options.length === 0) {
            container.innerHTML = '<p style="color: #666; text-align: center;">Tidak ada jadwal kelas untuk tanggal ini.</p>';
            return;
        }
        
        // Fetch user profile to check existing enrollments
        fetch(`/api/auth/user-profile`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(user => {
            const userEnrollments = user.enrollments || [];
            const enrolledScheduleIds = userEnrollments
                .filter(e => e.status === 'confirmed')
                .map(e => e.schedule_id);
            
            options.forEach((option, index) => {
                const isEnrolled = enrolledScheduleIds.includes(option.id);
                
                const label = document.createElement('label');
                label.className = 'radio-box';
                label.style.cssText = `display: block; margin-bottom: 15px; background: ${isEnrolled ? '#f0f0f0' : '#f2f2f2'}; padding: 10px 15px; border-radius: 12px; cursor: ${isEnrolled ? 'not-allowed' : 'pointer'}; transition: 0.3s; opacity: ${isEnrolled ? '0.6' : '1'};`;
                
                const radioInput = document.createElement('input');
                radioInput.type = 'radio';
                radioInput.name = name;
                radioInput.value = option.id;
                radioInput.disabled = isEnrolled;
                if (index === 0 && !isEnrolled) radioInput.checked = true;
                radioInput.style.marginRight = '10px';
                
                const spanText = `
                    <strong>${option.studio_class?.name || 'Class'}</strong> - 
                    ${new Date(option.start_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} - 
                    ${new Date(option.end_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                    <br>
                    <small style="color: #666;">Coach: ${option.instructor} | Harga: Rp ${option.price?.toLocaleString('id-ID')}${isEnrolled ? ' | ✅ Sudah terdaftar' : ''}</small>
                `;
                
                label.appendChild(radioInput);
                const span = document.createElement('span');
                span.innerHTML = spanText;
                label.appendChild(span);
                
                if (!isEnrolled) {
                    label.addEventListener('mouseenter', () => label.style.background = '#e0e0e0');
                    label.addEventListener('mouseleave', () => label.style.background = '#f2f2f2');
                }
                
                container.appendChild(label);
            });
        })
        .catch(err => {
            console.error('Error fetching user profile:', err);
            // Fallback: render without enrollment check
            options.forEach((option, index) => {
                const label = document.createElement('label');
                label.className = 'radio-box';
                label.style.cssText = 'display: block; margin-bottom: 15px; background: #f2f2f2; padding: 10px 15px; border-radius: 12px; cursor: pointer; transition: 0.3s;';
                label.innerHTML = `
                    <input type="radio" name="${name}" value="${option.id}" ${index === 0 ? 'checked' : ''} style="margin-right: 10px;">
                    <span>
                        <strong>${option.studio_class?.name || 'Class'}</strong> - 
                        ${new Date(option.start_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} - 
                        ${new Date(option.end_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                        <br>
                        <small style="color: #666;">Coach: ${option.instructor} | Harga: Rp ${option.price?.toLocaleString('id-ID')}</small>
                    </span>
                `;
                label.addEventListener('mouseenter', () => label.style.background = '#e0e0e0');
                label.addEventListener('mouseleave', () => label.style.background = '#f2f2f2');
                container.appendChild(label);
            });
        });
    }

    function bookClass() {
        const scheduleId = document.querySelector('input[name="schedule"]:checked')?.value;
        const token = localStorage.getItem('auth_token');

        if (!scheduleId) {
            alert('Silakan pilih jadwal kelas terlebih dahulu.');
            return;
        }

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
                if (data.enrollment_type === 'free_membership') {
                    alert('✅ Enrollment berhasil! Anda sudah terdaftar di kelas ini (via membership).');
                } else {
                    alert('✅ Enrollment berhasil. Anda telah terdaftar di kelas ini.');
                }
                closeClassModal();
                window.location.reload();
            } else if (res.status === 402) {
                const data = await res.json();
                window.location.href = `/bayar-kelas/${scheduleId}?token=${encodeURIComponent(token)}`;
            } else if (res.status === 409) {
                const data = await res.json();
                alert(`ℹ️ ${data.message}`);
            } else if (res.status === 401) {
                alert('Sesi Anda telah habis. Silakan login ulang.');
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            } else {
                const err = await res.json().catch(() => ({}));
                console.error('Enroll failed', err);
                alert(`❌ ${err.message || 'Gagal mendaftar kelas. Silakan coba lagi.'}`);
            }
        })
        .catch((err) => {
            console.error('Network error enrolling:', err);
            alert('❌ Terjadi kesalahan jaringan. Silakan coba lagi.');
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
                alert(`✅ Berhasil membeli membership ${membershipName}! Sekarang Anda memiliki akses ke semua kelas.`);
                window.location.reload();
            } else if (res.status === 422) {
                const err = await res.json().catch(() => ({}));
                alert(`⚠️ ${err.message || 'Anda sudah memiliki membership aktif. Silakan tunggu hingga berakhir.'}`);
            } else if (res.status === 401) {
                alert('Sesi Anda telah habis. Silakan login ulang.');
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            } else {
                const err = await res.json().catch(() => ({}));
                console.error('Purchase failed', err);
                alert('❌ Gagal membeli membership. Silakan coba lagi.');
            }
        })
        .catch((err) => {
            console.error('Network error purchasing:', err);
            alert('❌ Terjadi kesalahan jaringan. Silakan coba lagi.');
        });
    }

</script>
@endpush
