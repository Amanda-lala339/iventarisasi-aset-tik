{{-- resources/views/partials/doc-preview.blade.php --}}
{{-- Dipakai bersama oleh assets/show dan assets/index. Letakkan di DALAM elemen yang ber-x-data="docPreview()" --}}

<script>
function docPreview() {
    return {
        showModal: false,
        previewUrl: '',
        previewName: '',
        fileExtension: '',
        zoomScale: 1, panX: 0, panY: 0, isDragging: false, startX: 0, startY: 0,
        loading: false,
        failed: false,
        officeHtml: '',
        sheets: [],
        activeSheet: 0,

        /* ---------- Jenis file ---------- */
        kind() {
            const u = this.previewUrl || '';
            if (/\.(jpg|jpeg|png|gif|webp|svg)($|\?)/i.test(u)) return 'image';
            if (/\.pdf($|\?)/i.test(u)) return 'pdf';
            if (/\.docx($|\?)/i.test(u)) return 'docx';
            if (/\.(xlsx|xls|csv)($|\?)/i.test(u)) return 'sheet';
            return 'other';
        },
        getFileExtension(url) {
            const match = url.match(/\.([a-zA-Z0-9]+)($|\?)/i);
            return match ? match[1].toLowerCase() : '';
        },

        /* ---------- Buka / tutup ---------- */
        async openPreview(url, name) {
            this.previewUrl = url;
            this.previewName = name;
            this.fileExtension = this.getFileExtension(url);
            this.resetZoom();
            this.officeHtml = '';
            this.sheets = [];
            this.activeSheet = 0;
            this.failed = false;
            this.loading = false;
            this.showModal = true;

            const k = this.kind();
            if (k === 'docx') await this.loadDocx(url);
            else if (k === 'sheet') await this.loadSheet(url);
        },
        closePreview() {
            this.showModal = false;
            this.previewUrl = '';
            this.previewName = '';
            this.officeHtml = '';
            this.sheets = [];
            this.loading = false;
            this.failed = false;
            this.resetZoom();
        },

        /* ---------- Muat library hanya saat dibutuhkan ---------- */
        loadScript(src) {
            window._scriptLoaders = window._scriptLoaders || {};
            if (!window._scriptLoaders[src]) {
                window._scriptLoaders[src] = new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = resolve;
                    s.onerror = reject;
                    document.head.appendChild(s);
                });
            }
            return window._scriptLoaders[src];
        },
        wrapHtml(body, wide) {
            const width = wide ? '' : 'max-width:900px;margin:0 auto;';
            return '<!DOCTYPE html><html><head><meta charset="utf-8"><style>'
                + 'body{font-family:Calibri,Arial,sans-serif;font-size:14px;line-height:1.6;color:#1f2937;padding:24px 32px;' + width + '}'
                + 'table{border-collapse:collapse;margin:12px 0}'
                + 'td,th{border:1px solid #cbd5e1;padding:4px 10px;font-size:13px;vertical-align:top}'
                + 'tr:first-child td{background:#eff6ff;font-weight:600}'
                + 'img{max-width:100%}'
                + '</style></head><body>' + body + '</body></html>';
        },

        /* ---------- Word (.docx) ---------- */
        async loadDocx(url) {
            this.loading = true;
            try {
                await this.loadScript('https://cdn.jsdelivr.net/npm/mammoth@1.8.0/mammoth.browser.min.js');
                const res = await fetch(url);
                if (!res.ok) throw new Error('fetch gagal');
                const buf = await res.arrayBuffer();
                const result = await mammoth.convertToHtml({ arrayBuffer: buf });
                if (this.previewUrl !== url) return;
                this.officeHtml = this.wrapHtml(result.value || '<p>Dokumen kosong.</p>', false);
            } catch (e) {
                if (this.previewUrl === url) this.failed = true;
            }
            if (this.previewUrl === url) this.loading = false;
        },

        /* ---------- Excel (.xlsx / .xls / .csv) ---------- */
        async loadSheet(url) {
            this.loading = true;
            try {
                await this.loadScript('https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js');
                const res = await fetch(url);
                if (!res.ok) throw new Error('fetch gagal');
                const buf = await res.arrayBuffer();
                const wb = XLSX.read(buf, { type: 'array' });
                const list = [];
                wb.SheetNames.forEach((sheetName) => {
                    const ws = wb.Sheets[sheetName];
                    let note = '';
                    if (ws['!ref']) {
                        const rg = XLSX.utils.decode_range(ws['!ref']);
                        if (rg.e.r > 999) {
                            rg.e.r = 999;
                            ws['!ref'] = XLSX.utils.encode_range(rg);
                            note = '<p style="color:#b45309;font-size:12px">Hanya 1000 baris pertama yang ditampilkan. Unduh file untuk melihat semuanya.</p>';
                        }
                    }
                    const full = XLSX.utils.sheet_to_html(ws);
                    const start = full.indexOf('<table');
                    const end = full.lastIndexOf('</table>');
                    const table = (start >= 0 && end >= 0) ? full.substring(start, end + 8) : '<p>Sheet ini kosong.</p>';
                    list.push({ name: sheetName, html: this.wrapHtml(note + table, true) });
                });
                if (this.previewUrl !== url) return;
                this.sheets = list;
            } catch (e) {
                if (this.previewUrl === url) this.failed = true;
            }
            if (this.previewUrl === url) this.loading = false;
        },

        /* ---------- Zoom & geser gambar ---------- */
        resetZoom() { this.zoomScale = 1; this.panX = 0; this.panY = 0; this.isDragging = false; },
        zoomIn() { if (this.zoomScale < 5) this.zoomScale = Math.round((this.zoomScale + 0.15) * 100) / 100; },
        zoomOut() {
            if (this.zoomScale > 1) {
                this.zoomScale = Math.max(1, Math.round((this.zoomScale - 0.15) * 100) / 100);
                if (this.zoomScale === 1) { this.panX = 0; this.panY = 0; }
            }
        },
        handleWheel(e) {
            if (this.kind() !== 'image') return;
            const step = 0.10;
            if (e.deltaY < 0) {
                if (this.zoomScale < 5) this.zoomScale = Math.round((this.zoomScale + step) * 100) / 100;
            } else if (this.zoomScale > 1) {
                this.zoomScale = Math.max(1, Math.round((this.zoomScale - step) * 100) / 100);
                if (this.zoomScale === 1) { this.panX = 0; this.panY = 0; }
            }
        },
        startDrag(e) { if (this.zoomScale <= 1) return; this.isDragging = true; this.startX = e.clientX - this.panX; this.startY = e.clientY - this.panY; },
        drag(e) { if (!this.isDragging) return; this.panX = e.clientX - this.startX; this.panY = e.clientY - this.startY; },
        endDrag() { this.isDragging = false; }
    };
}
</script>

{{-- ============ MODAL PREVIEW DOKUMEN ============ --}}
<div x-show="showModal"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     @keydown.escape.window="closePreview()"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-3">

    {{-- Tinggi dibuat PASTI (h-[90vh]) supaya PDF / Word / Excel ikut besar --}}
    <div class="bg-white rounded-2xl w-full max-w-7xl h-[90vh] flex flex-col shadow-2xl border border-blue-200"
         @click.outside="closePreview()">

        {{-- Header --}}
        <div class="shrink-0 flex flex-wrap justify-between items-center p-4 border-b border-blue-100 gap-2 bg-gradient-to-r from-blue-50 to-white rounded-t-2xl">
            <div class="flex items-center gap-3 min-w-0 pr-2 flex-1">
                <div class="p-2 rounded-xl bg-blue-100 text-blue-700 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-bold text-gray-800 text-base truncate" x-text="previewName || 'Preview Dokumen'"></h3>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 font-medium border border-blue-200" x-text="fileExtension.toUpperCase()"></span>
                        <p class="text-[11px] text-gray-500" x-show="kind() === 'image'">Scroll untuk zoom • Klik &amp; tahan untuk menggeser</p>
                        <p class="text-[11px] text-gray-500" x-show="kind() === 'pdf'">Gunakan kontrol PDF di dalam viewer</p>
                        <p class="text-[11px] text-gray-500" x-show="kind() === 'docx'">Pratinjau Word (tampilan disederhanakan)</p>
                        <p class="text-[11px] text-gray-500" x-show="kind() === 'sheet'">Pratinjau Excel (maks. 1000 baris per sheet)</p>
                        <p class="text-[11px] text-amber-600 font-medium" x-show="kind() === 'other'">Format ini tidak bisa dipratinjau, silakan unduh</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <template x-if="kind() === 'image'">
                    <div class="flex items-center bg-white border border-blue-200 rounded-lg p-0.5 shadow-sm">
                        <button type="button" @click="zoomOut()" title="Zoom Out" class="p-1.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" :disabled="zoomScale <= 1" :class="{'opacity-40 cursor-not-allowed': zoomScale <= 1}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <button type="button" @click="resetZoom()" title="Reset Zoom" class="px-2.5 py-0.5 text-xs font-mono font-semibold text-gray-700 hover:bg-blue-50 rounded transition-colors" x-text="Math.round(zoomScale * 100) + '%'"></button>
                        <button type="button" @click="zoomIn()" title="Zoom In" class="p-1.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </template>

                <a :href="previewUrl" download class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-md shadow-blue-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/></svg>
                    <span class="hidden sm:inline">Unduh</span>
                </a>

                <button type="button" @click="closePreview()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Area preview: mengisi seluruh sisa tinggi. Semua isi memakai absolute inset-3 --}}
        <div class="relative flex-1 min-h-0 bg-gray-100 rounded-b-2xl">

            {{-- Gambar --}}
            <template x-if="kind() === 'image'">
                <div class="absolute inset-3 flex items-center justify-center overflow-hidden rounded-lg bg-white shadow-inner select-none"
                     @wheel.prevent="handleWheel($event)"
                     @mousedown="startDrag($event)"
                     @mousemove="drag($event)"
                     @mouseup="endDrag()"
                     @mouseleave="endDrag()">
                    <img :src="previewUrl"
                         :style="'transform: translate3d(' + panX + 'px, ' + panY + 'px, 0px) scale(' + zoomScale + '); transition: ' + (isDragging ? 'none' : 'transform 0.1s ease-out') + '; cursor: ' + (zoomScale > 1 ? (isDragging ? 'grabbing' : 'grab') : 'default')"
                         class="max-h-full max-w-full object-contain rounded shadow-lg">
                </div>
            </template>

            {{-- PDF --}}
            <template x-if="kind() === 'pdf'">
                <div class="absolute inset-3 bg-white rounded-lg shadow-lg overflow-hidden">
                    <iframe :src="previewUrl + '#toolbar=1&navpanes=0'" class="w-full h-full border-0" frameborder="0"></iframe>
                </div>
            </template>

            {{-- Loading Word / Excel --}}
            <template x-if="loading">
                <div class="absolute inset-3 flex flex-col items-center justify-center gap-3 bg-white rounded-lg shadow-lg">
                    <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Menyiapkan pratinjau dokumen...</p>
                </div>
            </template>

            {{-- Word (.docx) --}}
            <template x-if="kind() === 'docx' && !loading && !failed">
                <div class="absolute inset-3 bg-white rounded-lg shadow-lg overflow-hidden">
                    <iframe sandbox="" :srcdoc="officeHtml" class="w-full h-full border-0"></iframe>
                </div>
            </template>

            {{-- Excel --}}
            <template x-if="kind() === 'sheet' && !loading && !failed">
                <div class="absolute inset-3 bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                    <div class="shrink-0 flex items-center gap-1 overflow-x-auto px-2 pt-2 border-b border-blue-100 bg-blue-50/60">
                        <template x-for="(s, i) in sheets" :key="i">
                            <button type="button" @click="activeSheet = i"
                                    class="shrink-0 px-3 py-1.5 text-xs font-medium rounded-t-md border border-b-0 transition-colors"
                                    :class="activeSheet === i ? 'bg-white text-blue-700 border-blue-200' : 'bg-transparent text-gray-500 border-transparent hover:text-blue-600'"
                                    x-text="s.name"></button>
                        </template>
                    </div>
                    <iframe sandbox="" :srcdoc="sheets[activeSheet] ? sheets[activeSheet].html : ''" class="flex-1 min-h-0 w-full border-0"></iframe>
                </div>
            </template>

            {{-- Format tidak didukung / gagal dimuat --}}
            <template x-if="(kind() === 'other' || failed) && !loading">
                <div class="absolute inset-3 flex items-center justify-center bg-white rounded-lg shadow-lg">
                    <div class="max-w-md text-center p-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 mb-2">Pratinjau tidak tersedia</h4>
                        <p class="text-sm text-gray-600 mb-4">File ini tidak bisa ditampilkan langsung di browser. Silakan unduh untuk melihat isinya.</p>
                        <a :href="previewUrl" download class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md shadow-blue-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3"/></svg>
                            Unduh File
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
