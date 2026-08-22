@props([
    'name',
    'title' => 'تأكيد الحذف',
    'message' => 'هل أنت متأكد من رغبتك في الحذف؟ لا يمكن التراجع عن هذا الإجراء.',
    'action' => '#'
])

<x-modal :name="$name" maxWidth="sm">
    <div class="p-6 text-center" dir="rtl">
        <!-- Centered Icon -->
        <div class="w-14 h-14 rounded-2xl bg-danger-50 text-danger-600 flex items-center justify-center mx-auto mb-4 border border-danger-100 shadow-sm">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>

        <!-- Centered Title & Message -->
        <h3 class="text-base sm:text-lg font-black text-slate-800 mb-1.5 text-center">{{ $title }}</h3>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 text-center px-1">{{ $message }}</p>

        <!-- Centered Action Buttons -->
        <form method="POST" :action="{{ $action }}" class="grid grid-cols-2 gap-2.5">
            @csrf
            @method('DELETE')
            <button type="button" x-on:click="$dispatch('close-modal', '{{ $name }}')" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-colors">
                إلغاء
            </button>
            <button type="submit" class="w-full px-4 py-2.5 bg-danger-600 hover:bg-danger-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-danger-500/20">
                تأكيد الحذف
            </button>
        </form>
    </div>
</x-modal>
