@if(session()->has('impersonated_by_admin'))
<div style="background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border: 1.5px solid #f59e0b; border-radius: 14px; padding: 0.875rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.12); position: relative; z-index: 40; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background: #f59e0b; color: white; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            🛡️
        </span>
        <div>
            <div style="font-size: 0.875rem; font-weight: 800; color: #78350f; line-height: 1.2;">
                Super Admin Impersonation Mode Active
            </div>
            <div style="font-size: 0.75rem; color: #92400e; font-weight: 500; margin-top: 0.15rem;">
                Logged in as <strong>{{ session('impersonated_store_name', 'Store Owner') }}</strong>. Actions taken will reflect on this tenant store.
            </div>
        </div>
    </div>

    <div>
        <a href="{{ route('impersonate.leave') }}" style="display: inline-flex; align-items: center; gap: 0.375rem; background: #b45309; color: white; padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800; text-decoration: none; box-shadow: 0 2px 4px rgba(180, 83, 9, 0.2); transition: all 0.15s ease;">
            <span>Leave Impersonation</span>
            <span style="font-size: 0.875rem;">&rarr;</span>
        </a>
    </div>
</div>
@endif
