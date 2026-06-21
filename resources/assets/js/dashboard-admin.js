document.addEventListener('DOMContentLoaded', () => {
    const initEcho = () => {
        if (window.Echo) {
            window.Echo.channel('dashboard')
            .listen('.dashboard.updated', (e) => {
                console.log('Dashboard updated event received:', e);
                const stats = e.stats;

                // Update simple stats
                if (document.getElementById('stat-total-pelatihan')) {
                    document.getElementById('stat-total-pelatihan').innerText = stats.totalPelatihan;
                }
                if (document.getElementById('stat-total-peserta')) {
                    document.getElementById('stat-total-peserta').innerText = stats.totalPeserta;
                }
                if (document.getElementById('stat-total-instruktur')) {
                    document.getElementById('stat-total-instruktur').innerText = stats.totalInstruktur;
                }
                if (document.getElementById('stat-total-koordinator')) {
                    document.getElementById('stat-total-koordinator').innerText = stats.totalKoordinator;
                }
                if (document.getElementById('stat-total-kecamatan')) {
                    document.getElementById('stat-total-kecamatan').innerText = stats.totalKecamatan;
                }
                if (document.getElementById('stat-wa-sent-today')) {
                    document.getElementById('stat-wa-sent-today').innerText = stats.waSentToday;
                }
                if (document.getElementById('stat-wa-failed')) {
                    document.getElementById('stat-wa-failed').innerText = stats.waFailed;
                }
                if (document.getElementById('stat-active-templates')) {
                    document.getElementById('stat-active-templates').innerText = stats.activeTemplates;
                }
                if (document.getElementById('stat-notif-pending')) {
                    document.getElementById('stat-notif-pending').innerText = stats.notifPending;
                }

                // Update badges
                if (document.getElementById('badge-pending-koordinator')) {
                    document.getElementById('badge-pending-koordinator').innerText = stats.pendingKoordinatorCount + ' Menunggu';
                }
                if (document.getElementById('badge-active-pelatihan')) {
                    document.getElementById('badge-active-pelatihan').innerText = stats.activePelatihanCount + ' Aktif';
                }
                if (document.getElementById('badge-peserta-count')) {
                    document.getElementById('badge-peserta-count').innerText = stats.totalPeserta + ' Peserta';
                }
                if (document.getElementById('badge-active-koors')) {
                    document.getElementById('badge-active-koors').innerText = stats.koorActiveCount + ' Aktif';
                }

                // Update Pending Koordinator List/Table
                const containerPendingKoor = document.getElementById('container-pending-koordinator');
                if (containerPendingKoor) {
                    if (stats.pendingKoordinators.length === 0) {
                        containerPendingKoor.innerHTML = `
                          <div class="text-center py-5">
                            <i class="icon-base ti tabler-discount-check fs-1 text-success mb-3"></i>
                            <h6 class="text-white">Semua pendaftaran bersih!</h6>
                            <p class="text-body-premium small mb-0">Tidak ada pengajuan koordinator yang tertunda.</p>
                          </div>
                        `;
                    } else {
                        let rows = '';
                        stats.pendingKoordinators.forEach(koor => {
                            rows += `
                              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                                <td class="px-0 py-3">
                                  <div class="fw-semibold text-white">${koor.name}</div>
                                  <small class="text-body-premium">${koor.nik}</small>
                                </td>
                                <td class="py-3">
                                  <span class="badge-premium badge-premium-info">${koor.kecamatan_name}</span>
                                </td>
                                <td class="py-3">
                                  <a href="https://wa.me/${koor.whatsapp}" target="_blank" class="text-warning text-decoration-none small">
                                    <i class="icon-base ti tabler-brand-whatsapp me-1"></i>${koor.whatsapp}
                                  </a>
                                </td>
                                <td class="text-end px-0 py-3">
                                  <div class="d-inline-flex gap-2">
                                    <form action="${koor.approve_route}" method="POST">
                                      <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                      <button type="submit" class="btn btn-success btn-sm px-3" style="border-radius: 5px;">
                                        Approve
                                      </button>
                                    </form>
                                    <form action="${koor.reject_route}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak koordinator ini?')">
                                      <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                      <button type="submit" class="btn btn-danger btn-sm px-3" style="border-radius: 5px;">
                                        Tolak
                                      </button>
                                    </form>
                                  </div>
                                </td>
                              </tr>
                            `;
                        });
                        containerPendingKoor.innerHTML = `
                          <div class="table-responsive">
                            <table class="table table-borderless text-white align-middle">
                              <thead>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                                  <th class="text-body-premium small fw-semibold px-0">Nama / NIK</th>
                                  <th class="text-body-premium small fw-semibold">Kecamatan</th>
                                  <th class="text-body-premium small fw-semibold">WhatsApp</th>
                                  <th class="text-body-premium small fw-semibold text-end px-0">Aksi</th>
                                </tr>
                              </thead>
                              <tbody>
                                ${rows}
                              </tbody>
                            </table>
                          </div>
                          <div class="text-center mt-3">
                            <a href="/admin/koordinator/pending" class="btn btn-glow-premium py-2 px-4 w-100">
                              <i class="icon-base ti tabler-list-check me-1"></i>Lihat Semua Pengajuan
                            </a>
                          </div>
                        `;
                    }
                }

                // Update Latest Pelatihan
                const containerLatestPelatihan = document.getElementById('container-latest-pelatihan');
                if (containerLatestPelatihan) {
                    if (stats.latestPelatihan.length === 0) {
                        containerLatestPelatihan.innerHTML = `
                          <div class="text-center py-5">
                            <i class="icon-base ti tabler-book-off fs-1 text-warning mb-3"></i>
                            <h6 class="text-white">Belum ada pelatihan</h6>
                            <p class="text-body-premium small mb-0">Silakan tambahkan pelatihan baru.</p>
                          </div>
                        `;
                    } else {
                        let items = '';
                        stats.latestPelatihan.forEach(pel => {
                            const badge = pel.is_active
                                ? '<span class="badge bg-success bg-opacity-20 text-success small px-2 py-1" style="border-radius: 4px; font-size: 10px;">Aktif</span>'
                                : '<span class="badge bg-secondary bg-opacity-20 text-white-50 small px-2 py-1" style="border-radius: 4px; font-size: 10px;">Nonaktif</span>';

                            items += `
                              <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon-box stat-icon-primary" style="width: 42px; height: 42px; font-size: 1.25rem;">
                                  <i class="icon-base ti tabler-chef-hat"></i>
                                </div>
                                <div class="flex-grow-1">
                                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">${pel.nama}</h6>
                                  <small class="text-body-premium">Batch ${pel.batch} • Kuota ${pel.kuota}</small>
                                </div>
                                <div>
                                  ${badge}
                                </div>
                              </div>
                            `;
                        });
                        containerLatestPelatihan.innerHTML = `
                          <div class="d-flex flex-column gap-3 mb-4">
                            ${items}
                          </div>
                          <div class="text-center mt-auto">
                            <a href="/admin/pelatihan" class="btn btn-glow-premium w-100 py-2">
                              <i class="icon-base ti tabler-settings me-1"></i>Kelola Pelatihan
                            </a>
                          </div>
                        `;
                    }
                }

                // Update Latest Peserta
                const containerLatestPeserta = document.getElementById('container-latest-peserta');
                if (containerLatestPeserta) {
                    if (stats.latestPeserta.length === 0) {
                        containerLatestPeserta.innerHTML = `
                          <div class="text-center py-4">
                            <i class="icon-base ti tabler-user-off fs-2 text-muted mb-2"></i>
                            <p class="text-body-premium small mb-0">Belum ada peserta yang mendaftar.</p>
                          </div>
                        `;
                    } else {
                        let items = '';
                        stats.latestPeserta.forEach(p => {
                            const initials = p.name.substring(0, 2).toUpperCase();
                            items += `
                              <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                  <div class="instructor-avatar text-white" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                                    ${initials}
                                  </div>
                                  <div>
                                    <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">${p.name}</h6>
                                    <small class="text-body-premium">${p.nik}</small>
                                  </div>
                                </div>
                                <span class="text-body-premium small">${p.diff_time}</span>
                              </div>
                            `;
                        });
                        containerLatestPeserta.innerHTML = `
                          <div class="d-flex flex-column gap-3">
                            ${items}
                          </div>
                        `;
                    }
                }

                // Update Active Koors
                const containerActiveKoors = document.getElementById('container-active-koors');
                if (containerActiveKoors) {
                    if (stats.activeKoors.length === 0) {
                        containerActiveKoors.innerHTML = `
                          <div class="text-center py-4">
                            <i class="icon-base ti tabler-user-x fs-2 text-muted mb-2"></i>
                            <p class="text-body-premium small mb-0">Belum ada koordinator aktif.</p>
                          </div>
                        `;
                    } else {
                        let items = '';
                        stats.activeKoors.forEach(k => {
                            const initials = k.name.substring(0, 2).toUpperCase();
                            const chatBtn = k.whatsapp
                                ? `<a href="https://wa.me/${k.whatsapp}" target="_blank" class="btn btn-sm btn-outline-success px-2 py-1" style="border-radius: 4px; font-size: 11px;">
                                     <i class="icon-base ti tabler-brand-whatsapp"></i> Chat
                                   </a>`
                                : '';

                            items += `
                              <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                  <div class="instructor-avatar text-white" style="background: rgba(6, 182, 212, 0.15); color: #22d3ee;">
                                    ${initials}
                                  </div>
                                  <div>
                                    <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">${k.name}</h6>
                                    <small class="text-body-premium">Kecamatan: ${k.kecamatan_name}</small>
                                  </div>
                                </div>
                                ${chatBtn}
                              </div>
                            `;
                        });
                        containerActiveKoors.innerHTML = `
                          <div class="d-flex flex-column gap-3">
                            ${items}
                          </div>
                        `;
                    }
                }
            });
        }
    };

    if (window.Echo) {
        initEcho();
    } else {
        document.addEventListener('echo:ready', initEcho);
    }
});
