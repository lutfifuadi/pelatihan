@props([
    'containerClass' => '',
    'buttonClass' => '',
])

<div
    x-data="pushSubscription"
    x-init="init"
    x-cloak
    class="{{ $containerClass }}"
>
    {{-- ============================================================
        1. LOADING STATE — Skeleton glass card + spinner
        ============================================================ --}}
    <template x-if="!dismissed && isSupported && permission === 'loading'">
        <div class="w-full max-w-[420px] mx-auto">
            <div class="text-center p-6 sm:p-8"
                 style="background: rgba(15, 23, 42, 0.35); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 5px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 40px rgba(99, 102, 241, 0.1); ">
                <div class="flex items-center justify-center">
                    <svg class="w-10 h-10 animate-spin" style="color: #6366f1;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </template>

    {{-- ============================================================
        2. UNSUPPORTED — Sembunyikan total
        ============================================================ --}}
    <template x-if="!dismissed && !isSupported">
        <div class="hidden"></div>
    </template>

    {{-- ============================================================
        3. DENIED STATE — Izin notifikasi diblokir
        ============================================================ --}}
    <template x-if="!dismissed && isSupported && permission === 'denied'">
        <div class="w-full max-w-[420px] mx-auto">
            <div class="text-center p-6 sm:p-8"
                 style="background: rgba(15, 23, 42, 0.35); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 5px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 40px rgba(99, 102, 241, 0.1); ">
                {{-- Amber warning icon --}}
                <div class="flex justify-center mb-5">
                    <div class="flex items-center justify-center"
                         style="width: 56px; height: 56px; border-radius: 5px; background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.25); box-shadow: 0 0 20px rgba(245, 158, 11, 0.15);">
                        <svg class="w-7 h-7" style="color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>

                <h3 style="font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; color: #ffffff; margin-bottom: 0.75rem;">
                    Izin Notifikasi Diblokir
                </h3>
                <p style="font-family: 'Outfit', sans-serif; font-size: 0.95rem; line-height: 1.6; color: rgba(255, 255, 255, 0.7); margin-bottom: 1.5rem;">
                    Silakan buka pengaturan browser dan izinkan notifikasi dari website ini.
                </p>

                <button
                    type="button"
                    @click="dismiss"
                    class="w-full transition-all duration-300"
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; border-radius: 5px; font-family: 'Sora', sans-serif; font-weight: 600; padding: 12px 28px;"
                    onmouseover="this.style.background='linear-gradient(135deg, #6366f1, #d946ef)'; this.style.borderColor='transparent'; this.style.boxShadow='0 5px 15px rgba(99, 102, 241, 0.4)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.borderColor='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='none'; this.style.transform='translateY(0)';"
                >
                    Mengerti
                </button>
            </div>
        </div>
    </template>

    {{-- ============================================================
        4. DEFAULT STATE — Ajak user aktifkan notifikasi
        ============================================================ --}}
    <template x-if="!dismissed && isSupported && permission === 'default' && !isSubscribed">
        <div class="w-full max-w-[420px] mx-auto">
            <div class="text-center p-6 sm:p-8"
                 style="background: rgba(15, 23, 42, 0.35); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 5px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 40px rgba(99, 102, 241, 0.1); ">
                {{-- Icon bell with gradient --}}
                <div class="flex justify-center mb-5">
                    <div class="flex items-center justify-center"
                         style="width: 56px; height: 56px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </div>
                </div>

                {{-- Small label --}}
                <p style="font-family: 'Sora', sans-serif; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); margin-bottom: 0.5rem;">
                    NOTIFIKASI
                </p>

                {{-- Title --}}
                <h3 style="font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; color: #ffffff; margin-bottom: 0.75rem;">
                    Dapatkan Info Pelatihan
                </h3>

                {{-- Description --}}
                <p style="font-family: 'Outfit', sans-serif; font-size: 0.95rem; line-height: 1.6; color: rgba(255, 255, 255, 0.7); margin-bottom: 1.75rem;">
                    Jangan lewatkan pengumuman penting, jadwal pelatihan, dan info terbaru lainnya.
                </p>

                {{-- CTA Button --}}
                <button
                    type="button"
                    @click="requestPermission"
                    class="w-full transition-all duration-300"
                    style="background: linear-gradient(135deg, #6366f1, #d946ef); border: none; color: #ffffff; border-radius: 5px; font-family: 'Sora', sans-serif; font-size: 0.95rem; font-weight: 600; padding: 14px 28px; box-shadow: 0 5px 20px rgba(99, 102, 241, 0.35);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 30px rgba(99, 102, 241, 0.5)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(99, 102, 241, 0.35)';"
                >
                    Aktifkan Notifikasi
                </button>

                {{-- Dismiss link --}}
                <button
                    type="button"
                    @click="dismiss"
                    class="mt-4 transition-colors duration-200 focus:outline-none"
                    style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; color: rgba(255, 255, 255, 0.5); background: transparent; border: none;"
                    onmouseover="this.style.color='#ffffff';"
                    onmouseout="this.style.color='rgba(255, 255, 255, 0.5)';"
                >
                    Nanti saja
                </button>
            </div>
        </div>
    </template>

    {{-- ============================================================
        5. GRANTED STATE — Permission diberikan, belum subscribe
        ============================================================ --}}
    <template x-if="!dismissed && isSupported && permission === 'granted' && !isSubscribed">
        <div class="w-full max-w-[420px] mx-auto">
            <div class="text-center p-6 sm:p-8"
                 style="background: rgba(15, 23, 42, 0.35); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 5px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 40px rgba(99, 102, 241, 0.1); ">
                {{-- Gradient check icon --}}
                <div class="flex justify-center mb-5">
                    <div class="flex items-center justify-center"
                         style="width: 56px; height: 56px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <p style="font-family: 'Sora', sans-serif; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); margin-bottom: 0.5rem;">
                    IZIN DIBERIKAN
                </p>

                <h3 style="font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; color: #ffffff; margin-bottom: 0.75rem;">
                    Izin Diberikan!
                </h3>

                <p style="font-family: 'Outfit', sans-serif; font-size: 0.95rem; line-height: 1.6; color: rgba(255, 255, 255, 0.7); margin-bottom: 1.75rem;">
                    Klik tombol di bawah untuk mulai menerima notifikasi dari Pelatihanku.
                </p>

                <button
                    type="button"
                    @click="requestPermission"
                    class="w-full transition-all duration-300"
                    style="background: linear-gradient(135deg, #6366f1, #d946ef); border: none; color: #ffffff; border-radius: 5px; font-family: 'Sora', sans-serif; font-size: 0.95rem; font-weight: 600; padding: 14px 28px; box-shadow: 0 5px 20px rgba(99, 102, 241, 0.35);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 30px rgba(99, 102, 241, 0.5)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(99, 102, 241, 0.35)';"
                >
                    Mulai Berlangganan
                </button>

                <button
                    type="button"
                    @click="dismiss"
                    class="mt-4 transition-colors duration-200 focus:outline-none"
                    style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; color: rgba(255, 255, 255, 0.5); background: transparent; border: none;"
                    onmouseover="this.style.color='#ffffff';"
                    onmouseout="this.style.color='rgba(255, 255, 255, 0.5)';"
                >
                    Nanti saja
                </button>
            </div>
        </div>
    </template>

    {{-- ============================================================
        6. SUBSCRIBED STATE — Notifikasi sudah aktif
        ============================================================ --}}
    <template x-if="!dismissed && isSupported && isSubscribed">
        <div class="w-full max-w-[420px] mx-auto">
            <div class="text-center p-6 sm:p-8"
                 style="background: rgba(15, 23, 42, 0.35); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 5px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 40px rgba(99, 102, 241, 0.1); ">
                {{-- Emerald check icon --}}
                <div class="flex justify-center mb-5">
                    <div class="flex items-center justify-center"
                         style="width: 56px; height: 56px; border-radius: 5px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.25); box-shadow: 0 0 20px rgba(16, 185, 129, 0.15);">
                        <svg class="w-7 h-7" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h3 style="font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; color: #ffffff; margin-bottom: 0.75rem;">
                    Notifikasi Aktif
                </h3>
                <p style="font-family: 'Outfit', sans-serif; font-size: 0.95rem; line-height: 1.6; color: rgba(255, 255, 255, 0.7); margin-bottom: 1.5rem;">
                    Anda akan menerima notifikasi info terbaru dari Pelatihanku.
                </p>

                <button
                    type="button"
                    @click="unsubscribe"
                    class="w-full transition-all duration-300 inline-flex items-center justify-center gap-2"
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; border-radius: 5px; font-family: 'Sora', sans-serif; font-weight: 600; padding: 12px 28px;"
                    onmouseover="this.style.background='linear-gradient(135deg, #6366f1, #d946ef)'; this.style.borderColor='transparent'; this.style.boxShadow='0 5px 15px rgba(99, 102, 241, 0.4)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.borderColor='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='none'; this.style.transform='translateY(0)';"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                    </svg>
                    Nonaktifkan
                </button>

                <button
                    type="button"
                    @click="dismiss"
                    class="mt-4 transition-colors duration-200 focus:outline-none"
                    style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; color: rgba(255, 255, 255, 0.5); background: transparent; border: none;"
                    onmouseover="this.style.color='#ffffff';"
                    onmouseout="this.style.color='rgba(255, 255, 255, 0.5)';"
                >
                    Tutup
                </button>
            </div>
        </div>
    </template>
</div>
