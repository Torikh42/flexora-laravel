@extends('layouts.app')

@section('title', 'Dashboard - Flexora')

<div class="pt-5 px-4 md:px-8">
  <div class="bg-linear-to-b from-gray-300 to-gray-200 rounded-[30px] p-6 md:p-8 shadow-lg min-h-[500px] my-5">
    
    <!-- Loading State -->
    <div id="loadingState" class="text-center py-10">
      <div class="w-10 h-10 border-4 border-gray-200 border-t-amber-900 rounded-full animate-spin mx-auto mb-5"></div>
      <p class="text-gray-600">Memuat data Anda...</p>
    </div>

    <!-- Membership Section -->
    <div id="membershipSection" class="hidden mb-10">
      <h2 class="text-lg font-bold mb-4 text-gray-800">🎟️ Keanggotaan Aktif</h2>
      <div id="membershipCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5"></div>
    </div>

    <!-- Filter Section -->
    <div id="filterSection" class="flex flex-col md:flex-row justify-between items-stretch md:items-center mb-8 gap-5">
      <div class="flex flex-wrap gap-2">
        <button class="filter-btn px-6 py-2.5 rounded-full bg-white hover:bg-gray-100 transition-all duration-300 text-sm font-medium border-none cursor-pointer" data-filter="all" onclick="filterEnrollments('all')">
          Semua (<span id="totalCount">0</span>)
        </button>
        <button class="filter-btn px-6 py-2.5 rounded-full bg-white hover:bg-gray-100 transition-all duration-300 text-sm font-medium border-none cursor-pointer" data-filter="confirmed" onclick="filterEnrollments('confirmed')">
          Confirmed (<span id="confirmedCount">0</span>)
        </button>
        <button class="filter-btn px-6 py-2.5 rounded-full bg-white hover:bg-gray-100 transition-all duration-300 text-sm font-medium border-none cursor-pointer" data-filter="pending" onclick="filterEnrollments('pending')">
          Pending (<span id="pendingCount">0</span>)
        </button>
      </div>
      <div class="flex items-center bg-white px-5 py-2.5 rounded-full shadow-sm min-w-[250px] max-w-full md:max-w-[350px]">
        <input
          type="text"
          id="searchInput"
          placeholder="Cari kelas..."
          class="flex-1 border-none outline-none text-sm"
          onkeyup="searchEnrollments()"
        />
        <button class="border-none bg-transparent cursor-pointer text-base">🔍</button>
      </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden text-center py-20 px-5 text-gray-600">
      <div class="text-8xl mb-5">📦</div>
      <h2 class="text-2xl font-semibold mb-2.5 text-gray-700">Belum Ada Pemesanan</h2>
      <p class="text-base mb-2.5">Anda belum melakukan pemesanan kelas apapun.</p>
      <p class="text-base mb-5">Mulai jelajahi kelas-kelas kami dan lakukan booking pertama Anda!</p>
      <a href="{{ route('studio_classes.index') }}" class="no-underline">
        <button class="px-8 py-3 bg-amber-900 text-white border-none rounded-full cursor-pointer text-base font-semibold hover:bg-amber-800 transition-colors duration-300">
          Lihat Kelas Tersedia
        </button>
      </a>
    </div>

    <!-- Class Grid -->
    <div id="classGrid" class="hidden grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-10"></div>
  </div>
</div>

<!-- Modal untuk Detail Order -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 animate-[fadeIn_0.3s]">
  <div class="bg-white my-[5%] mx-auto p-8 rounded-2xl w-[90%] max-w-[600px] shadow-2xl animate-[slideUp_0.3s]">
    <span class="close text-gray-400 float-right text-3xl font-bold cursor-pointer hover:text-black transition-colors duration-300" onclick="closeModal()">&times;</span>
    <h2 class="mb-5 text-gray-800 text-xl font-semibold">Detail Kelas</h2>
    <div class="modal-details my-4" id="modalDetails">
      <!-- Detail akan diisi oleh JavaScript -->
    </div>
  </div>
</div>

@push('scripts')
<script>
  let allEnrollments = [];
  let currentFilter = 'all';

  // Check auth and load dashboard data
  document.addEventListener('DOMContentLoaded', async function() {
    const token = localStorage.getItem('auth_token');

    if (!token) {
      window.location.href = '{{ route("login") }}';
      return;
    }

    // Set active filter button
    document.querySelector('.filter-btn[data-filter="all"]').classList.add('bg-amber-900', 'text-white', 'hover:bg-amber-800');
    document.querySelector('.filter-btn[data-filter="all"]').classList.remove('bg-white', 'hover:bg-gray-100');

    await loadDashboardData(token);
  });

  async function loadDashboardData(token) {
    try {
      const response = await fetch('/api/enrollments', {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        }
      });

      if (!response.ok) {
        if (response.status === 401) {
          localStorage.removeItem('auth_token');
          window.location.href = '{{ route("login") }}';
          return;
        }
        throw new Error('Failed to load data');
      }

      const data = await response.json();
      allEnrollments = data.enrollments;

      // Hide loading state
      document.getElementById('loadingState').classList.add('hidden');

      // Update counts
      document.getElementById('totalCount').textContent = data.total_enrollments;
      document.getElementById('confirmedCount').textContent = data.confirmed_count;
      document.getElementById('pendingCount').textContent = data.pending_count;

      // Show filter section if there are enrollments
      if (data.total_enrollments > 0) {
        document.getElementById('filterSection').classList.remove('hidden');
        document.getElementById('filterSection').classList.add('flex');
      }

      // Show memberships
      if (data.active_memberships.length > 0) {
        displayMemberships(data.active_memberships);
      }

      // Show enrollments or empty state
      if (data.total_enrollments === 0) {
        document.getElementById('emptyState').classList.remove('hidden');
      } else {
        document.getElementById('classGrid').classList.remove('hidden');
        document.getElementById('classGrid').classList.add('grid');
        displayEnrollments(allEnrollments);
      }
    } catch (error) {
      console.error('Error loading dashboard:', error);
      document.getElementById('loadingState').innerHTML = `
        <p class="text-red-600">❌ Error loading data: ${error.message}</p>
      `;
    }
  }

  function displayMemberships(memberships) {
    const membershipSection = document.getElementById('membershipSection');
    const membershipCards = document.getElementById('membershipCards');

    membershipCards.innerHTML = '';
    memberships.forEach(membership => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded-2xl p-5 shadow-md border-l-4 border-green-500 hover:-translate-y-1 transition-transform duration-300';
      card.innerHTML = `
        <h3 class="text-base font-bold text-gray-800 mb-2.5">${membership.membership_name}</h3>
        <div class="text-sm text-gray-600 mb-2">
          <strong class="text-gray-800">Mulai:</strong> ${membership.start_date}
        </div>
        <div class="text-sm text-gray-600 mb-2">
          <strong class="text-gray-800">Berakhir:</strong> ${membership.end_date}
        </div>
        <div class="text-sm font-bold text-green-500 mt-2.5">
          ⏳ ${membership.days_remaining} hari tersisa
        </div>
      `;
      membershipCards.appendChild(card);
    });

    membershipSection.classList.remove('hidden');
  }

  function displayEnrollments(enrollments) {
    const classGrid = document.getElementById('classGrid');
    classGrid.innerHTML = '';

    enrollments.forEach(enrollment => {
      const card = document.createElement('div');
      const borderColor = enrollment.status === 'pending' ? 'border-l-red-500' : 'border-l-blue-400';
      card.className = `bg-white rounded-2xl overflow-hidden shadow-md hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border-l-4 ${borderColor}`;
      card.dataset.status = enrollment.status;
      card.dataset.class = enrollment.class_name.toLowerCase();

      const emoji = getClassEmoji(enrollment.class_name);
      const statusDisplay = enrollment.enrollment_type === 'free_membership' 
        ? '<span class="flex-1 px-2 py-2 border-none rounded-md text-white text-xs font-semibold text-center bg-green-500">✅ Membership</span>'
        : `<span class="flex-1 px-2 py-2 border-none rounded-md text-white text-xs font-semibold text-center ${enrollment.status === 'confirmed' ? 'bg-blue-400' : 'bg-red-500'}">${enrollment.status === 'confirmed' ? '✅ Confirmed' : '⏳ Pending'}</span>`;

      card.innerHTML = `
        <div class="w-full h-[150px] bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-5xl text-white">
          ${emoji}
        </div>
        <div class="p-4">
          <div class="font-bold text-sm mb-2 text-gray-800">${enrollment.class_name}</div>
          <div class="flex items-center gap-1.5 text-xs text-gray-600 mb-1.5">👤 ${enrollment.instructor}</div>
          <div class="flex items-center gap-1.5 text-xs text-gray-600 mb-1.5">📅 ${enrollment.schedule_date}</div>
          <div class="flex items-center gap-1.5 text-xs text-gray-600 mb-1.5">🕐 ${enrollment.schedule_time}</div>
          <div class="flex items-center gap-1.5 text-xs text-gray-600 mb-1.5">📍 ${enrollment.created_at}</div>
          <div class="flex gap-2 mt-3">
            ${statusDisplay}
            <button class="flex-1 px-2 py-2 border-none rounded-md bg-gray-800 text-white cursor-pointer text-xs font-semibold hover:bg-gray-700 transition-colors duration-300" onclick='viewDetails(\`${enrollment.class_name}\`, \`${enrollment.instructor}\`, \`${enrollment.schedule_date}\`, \`${enrollment.schedule_time}\`, \`${enrollment.status}\`)'>
              Details
            </button>
          </div>
        </div>
      `;
      classGrid.appendChild(card);
    });
  }

  function getClassEmoji(className) {
    const lower = className.toLowerCase();
    if (lower.includes('pole')) return '💃';
    if (lower.includes('yoga')) return '🧘';
    if (lower.includes('pilates')) return '🤸';
    return '🏋️';
  }

  function filterEnrollments(status) {
    currentFilter = status;
    const cards = document.querySelectorAll('.class-card, [data-status]');
    const buttons = document.querySelectorAll('.filter-btn');

    buttons.forEach(btn => {
      btn.classList.remove('bg-amber-900', 'text-white', 'hover:bg-amber-800');
      btn.classList.add('bg-white', 'hover:bg-gray-100');
    });
    
    event.target.closest('.filter-btn').classList.remove('bg-white', 'hover:bg-gray-100');
    event.target.closest('.filter-btn').classList.add('bg-amber-900', 'text-white', 'hover:bg-amber-800');

    cards.forEach(card => {
      if (status === 'all' || card.dataset.status === status) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function searchEnrollments() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('[data-status]');

    cards.forEach(card => {
      const className = card.querySelector('.font-bold').textContent.toLowerCase();
      const currentStatus = card.dataset.status;
      
      const matchesSearch = className.includes(searchValue);
      const matchesFilter = currentFilter === 'all' || currentStatus === currentFilter;

      if (matchesSearch && matchesFilter) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function viewDetails(className, instructor, date, time, status) {
    const modalDetails = document.getElementById('modalDetails');
    const statusBadge = status === 'confirmed' 
      ? '<span class="inline-block px-3 py-1 rounded-md text-white bg-blue-400">✅ Confirmed</span>'
      : '<span class="inline-block px-3 py-1 rounded-md text-white bg-red-500">⏳ Pending</span>';

    modalDetails.innerHTML = `
      <p class="my-2.5 text-sm text-gray-700"><strong class="text-gray-800">Kelas:</strong> ${className}</p>
      <p class="my-2.5 text-sm text-gray-700"><strong class="text-gray-800">Instruktur:</strong> ${instructor}</p>
      <p class="my-2.5 text-sm text-gray-700"><strong class="text-gray-800">Tanggal:</strong> ${date}</p>
      <p class="my-2.5 text-sm text-gray-700"><strong class="text-gray-800">Waktu:</strong> ${time}</p>
      <p class="my-2.5 text-sm text-gray-700"><strong class="text-gray-800">Status:</strong> ${statusBadge}</p>
    `;

    document.getElementById('detailModal').classList.remove('hidden');
  }

  function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
  }

  window.onclick = function (event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
      modal.classList.add('hidden');
    }
  };
</script>

<style>
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideUp {
    from {
      transform: translateY(30px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
</style>
@endpush