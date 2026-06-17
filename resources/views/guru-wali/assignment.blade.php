@extends('layouts.app')

@section('title', 'Kelola Siswa Bimbingan')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Kelola Siswa Bimbingan</h1>
        <p class="text-slate-500 mt-1 text-sm">Pilih siswa yang akan menjadi anak bimbingan Anda.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="border-b border-slate-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-slate-500" id="tabs" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-blue-600 text-blue-600 rounded-t-lg active" id="mode1-tab" data-tabs-target="#mode1" type="button" role="tab" aria-controls="mode1" aria-selected="true">Checklist Individu</button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-slate-600 hover:border-slate-300" id="mode2-tab" data-tabs-target="#mode2" type="button" role="tab" aria-controls="mode2" aria-selected="false">Batch per Kelas</button>
            </li>
        </ul>
    </div>
    
    <div id="tabContent">
        <form action="{{ route('guru_wali.assignment.save') }}" method="POST" id="assignmentForm">
            @csrf
            
            {{-- MODE 1: INDIVIDUAL --}}
            <div class="p-4 bg-white" id="mode1" role="tabpanel" aria-labelledby="mode1-tab">
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Cari nama atau NISN...">
                    </div>
                </div>

                <div class="overflow-x-auto max-h-[500px] overflow-y-auto border border-slate-200 rounded-lg">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th scope="col" class="p-4 w-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500">
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3">NISN</th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTableBody" class="divide-y divide-slate-200">
                            @foreach($allSiswa as $siswa)
                                @php
                                    $isMine = in_array($siswa->id_siswa, $myAssignedIds);
                                    $isAssignedToOther = $siswa->guruWali && $siswa->guruWali->id_user != $userId;
                                    $otherName = $isAssignedToOther ? $siswa->guruWali->user->nama_lengkap : '';
                                @endphp
                                <tr class="bg-white hover:bg-slate-50 siswa-row" data-kelas="{{ $siswa->id_kelas }}" data-assigned-other="{{ $isAssignedToOther ? 'true' : 'false' }}">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="id_siswa[]" value="{{ $siswa->id_siswa }}" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 siswa-checkbox" 
                                                {{ $isMine ? 'checked' : '' }} 
                                                {{ $isAssignedToOther ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap searchable-name">
                                        {{ $siswa->nama_siswa }}
                                    </td>
                                    <td class="px-6 py-4 searchable-nisn">
                                        {{ $siswa->nisn }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $siswa->kelas->nama_kelas }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($isMine)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Bimbingan Saya</span>
                                        @elseif($isAssignedToOther)
                                            <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-0.5 rounded" title="{{ $otherName }}">Sudah Diambil ({{ $otherName }})</span>
                                        @else
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded">Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MODE 2: BATCH --}}
            <div class="p-4 bg-white hidden" id="mode2" role="tabpanel" aria-labelledby="mode2-tab">
                <div class="mb-4 max-w-md">
                    <label for="kelasSelect" class="block mb-2 text-sm font-medium text-slate-900">Pilih Kelas</label>
                    <select id="kelasSelect" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <button type="button" id="btnBatchSelect" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none">Pilih Semua di Kelas Ini</button>
                    <button type="button" id="btnBatchDeselect" class="text-slate-900 bg-white border border-slate-300 focus:outline-none hover:bg-slate-100 focus:ring-4 focus:ring-slate-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">Batal Pilih Kelas Ini</button>
                </div>
                <div id="batchWarning" class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 hidden" role="alert">
                    <span class="font-medium">Perhatian!</span> <span id="batchWarningText"></span>
                </div>
                <p class="text-sm text-slate-500">Gunakan fitur ini untuk memilih banyak siswa sekaligus dalam satu kelas. Setelah menekan tombol "Pilih Semua", Anda bisa pindah ke tab Checklist Individu untuk melihat hasilnya atau langsung klik Simpan.</p>
            </div>
            
            <div class="px-5 py-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Penugasan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Logic
        const tabBtns = document.querySelectorAll('[role="tab"]');
        const tabPanels = document.querySelectorAll('[role="tabpanel"]');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Deactivate all
                tabBtns.forEach(b => {
                    b.classList.remove('border-blue-600', 'text-blue-600');
                    b.classList.add('border-transparent', 'hover:text-slate-600', 'hover:border-slate-300');
                    b.setAttribute('aria-selected', 'false');
                });
                tabPanels.forEach(p => p.classList.add('hidden'));

                // Activate clicked
                btn.classList.add('border-blue-600', 'text-blue-600');
                btn.classList.remove('border-transparent', 'hover:text-slate-600', 'hover:border-slate-300');
                btn.setAttribute('aria-selected', 'true');
                
                const target = document.querySelector(btn.getAttribute('data-tabs-target'));
                target.classList.remove('hidden');
            });
        });

        // Track checked state globally so it persists across searches
        const checkedSiswa = new Set(@json($myAssignedIds));
        
        // Update set when checkbox is clicked
        document.getElementById('siswaTableBody').addEventListener('change', function(e) {
            if(e.target.classList.contains('siswa-checkbox')) {
                const id = parseInt(e.target.value);
                if(e.target.checked) {
                    checkedSiswa.add(id);
                } else {
                    checkedSiswa.delete(id);
                }
            }
        });

        // Search Logic (Vanilla JS + Fetch)
        const searchInput = document.getElementById('searchInput');
        const tbody = document.getElementById('siswaTableBody');
        let searchTimeout;

        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value;
            
            searchTimeout = setTimeout(() => {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-slate-500">Memuat...</td></tr>';
                
                fetch(`{{ route('guru_wali.assignment.search') }}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if(data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-slate-500">Tidak ditemukan</td></tr>';
                        return;
                    }
                    
                    data.forEach(siswa => {
                        // Check if it's mine currently in the Set
                        const isMine = checkedSiswa.has(siswa.id_siswa);
                        const isAssignedToOther = siswa.is_taken && !siswa.is_assigned_to_me;
                        
                        let statusBadge = '';
                        if(isMine || siswa.is_assigned_to_me) {
                            statusBadge = `<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Bimbingan Saya</span>`;
                        } else if(isAssignedToOther) {
                            statusBadge = `<span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-0.5 rounded" title="${siswa.taken_by}">Sudah Diambil (${siswa.taken_by})</span>`;
                        } else {
                            statusBadge = `<span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded">Tersedia</span>`;
                        }

                        const tr = document.createElement('tr');
                        tr.className = 'bg-white hover:bg-slate-50 siswa-row';
                        tr.setAttribute('data-kelas', siswa.id_kelas);
                        tr.setAttribute('data-assigned-other', isAssignedToOther ? 'true' : 'false');
                        
                        tr.innerHTML = `
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="id_siswa_display[]" value="${siswa.id_siswa}" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 siswa-checkbox" 
                                        ${isMine ? 'checked' : ''} 
                                        ${isAssignedToOther ? 'disabled' : ''}>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap searchable-name">${siswa.nama_siswa}</td>
                            <td class="px-6 py-4 searchable-nisn">${siswa.nisn}</td>
                            <td class="px-6 py-4">${siswa.nama_kelas}</td>
                            <td class="px-6 py-4">${statusBadge}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Gagal memuat data</td></tr>';
                    console.error(err);
                });
            }, 300);
        });

        // Check All Logic
        const checkAll = document.getElementById('checkbox-all');
        checkAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.siswa-checkbox:not([disabled])');
            checkboxes.forEach(cb => {
                cb.checked = checkAll.checked;
                const id = parseInt(cb.value);
                if(cb.checked) {
                    checkedSiswa.add(id);
                } else {
                    checkedSiswa.delete(id);
                }
            });
        });

        // Batch Logic
        const btnBatchSelect = document.getElementById('btnBatchSelect');
        const btnBatchDeselect = document.getElementById('btnBatchDeselect');
        const kelasSelect = document.getElementById('kelasSelect');
        const batchWarning = document.getElementById('batchWarning');
        const batchWarningText = document.getElementById('batchWarningText');

        btnBatchSelect.addEventListener('click', function() {
            const selectedKelas = kelasSelect.value;
            if(!selectedKelas) {
                alert('Pilih kelas terlebih dahulu.');
                return;
            }

            let unavailableCount = 0;
            let selectedCount = 0;
            
            const siswaRows = document.querySelectorAll('.siswa-row');
            siswaRows.forEach(row => {
                if(row.getAttribute('data-kelas') === selectedKelas) {
                    const cb = row.querySelector('.siswa-checkbox');
                    if(row.getAttribute('data-assigned-other') === 'true') {
                        unavailableCount++;
                    } else {
                        cb.checked = true;
                        checkedSiswa.add(parseInt(cb.value));
                        selectedCount++;
                    }
                }
            });

            if(unavailableCount > 0) {
                batchWarningText.textContent = `${unavailableCount} siswa di kelas ini sudah dipegang guru wali lain dan dilewati. Berhasil mencentang ${selectedCount} siswa.`;
                batchWarning.classList.remove('hidden');
            } else {
                batchWarning.classList.add('hidden');
                alert(`Berhasil mencentang ${selectedCount} siswa dari kelas ini.`);
            }
        });

        btnBatchDeselect.addEventListener('click', function() {
            const selectedKelas = kelasSelect.value;
            if(!selectedKelas) {
                alert('Pilih kelas terlebih dahulu.');
                return;
            }

            const siswaRows = document.querySelectorAll('.siswa-row');
            siswaRows.forEach(row => {
                if(row.getAttribute('data-kelas') === selectedKelas) {
                    const cb = row.querySelector('.siswa-checkbox');
                    if(!cb.disabled) {
                        cb.checked = false;
                        checkedSiswa.delete(parseInt(cb.value));
                    }
                }
            });
            batchWarning.classList.add('hidden');
            alert('Semua checklist siswa di kelas ini (kecuali yang milik guru lain) telah dibatalkan.');
        });

        // Handle Form Submit to include all checked items from the Set
        const form = document.getElementById('assignmentForm');
        form.addEventListener('submit', function(e) {
            // Remove any existing hidden inputs we might have added, and disable the physical checkboxes so they don't submit
            document.querySelectorAll('.siswa-checkbox').forEach(cb => cb.disabled = true);
            
            // Append hidden inputs for each item in the Set
            checkedSiswa.forEach(id => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'id_siswa[]';
                hiddenInput.value = id;
                form.appendChild(hiddenInput);
            });
        });
    });
</script>
@endpush
@endsection
