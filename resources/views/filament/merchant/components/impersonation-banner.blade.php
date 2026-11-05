@if(session()->has('impersonated_by_admin'))
<div style="display: inline-flex; align-items: center; gap: 0.5rem; margin-right: 0.65rem; background: #fff7ed; border: 1px solid #fdba74; border-radius: 9999px; padding: 0.25rem 0.5rem 0.25rem 0.65rem; box-shadow: 0 1px 2px rgba(234, 88, 12, 0.08); transition: all 0.15s ease;" 
     class="dark:bg-amber-950/40 dark:border-amber-700/60"
     title="Super Admin Impersonation: Actions reflect on {{ session('impersonated_store_name', 'this store') }}">
    
    <div style="display: flex; align-items: center; gap: 0.4rem;">
        <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #ea580c; box-shadow: 0 0 0 2px rgba(234, 88, 12, 0.25);"></span>
        <span style="font-size: 0.75rem; font-weight: 700; color: #9a3412; white-space: nowrap; line-height: 1;" class="dark:text-amber-300">
            Impersonating
        </span>
    </div>

    <a href="{{ route('impersonate.leave') }}" 
       style="display: inline-flex; align-items: center; gap: 0.25rem; background: #ea580c; color: #ffffff; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-decoration: none; line-height: 1; transition: background 0.15s ease;"
       onmouseover="this.style.background='#c2410c'" 
       onmouseout="this.style.background='#ea580c'"
       title="Exit impersonation and return to Super Admin">
        <span>Exit</span>
        <span style="font-size: 0.75rem; font-weight: 800;">&rarr;</span>
    </a>
</div>
@endif
